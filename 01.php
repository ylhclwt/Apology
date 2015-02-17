<?php
header("Content-type: text/html; charset=utf-8"); 
//define your token
define("TOKEN", "");
define("被关注消息","【对不起青春 * 川大零壹微信电台】收听 SCU01CLUB 的听众可以用匿名、昵称、真实姓名等方式，参与我们的“向青春道歉”活动。敞开心扉，诉说你想道歉的对象以及原因。道歉后会收到另一位听众的道歉。信息全保密，不经筛选匿名随机推送。");
define("常规回复",'回复「莫西莫西」即可参与「向青春道歉」活动~诉说你想道歉的对象以及原因。道歉后会收到另一位听众的道歉。<a href="http://mp.weixin.qq.com/s?__biz=MzA5NTg3MDgzNQ==&mid=202345092&idx=1&sn=90f6ea90395c19234f6603e4e77ef259#rd">点我查看活动详情</a>');
define("求名字","欧哈哟~我是零壹烧，请问怎么称呼你呢？");
define("求内容","你好~请用一条文字或语音告诉我你想要道歉的对象和内容吧，零壹烧会乖乖听哒(=^ω^=)");
$wechatObj = new wechatCallbackapiTest();
$wechatObj->valid();
class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	$this->responseMsg();
        	exit;
        }
    }
	
	public function primarytextmsg($fromUsername, $toUsername, $text)
	{
		$time = time();
		$textTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Content><![CDATA[%s]]></Content>
					<FuncFlag>0</FuncFlag>
					</xml>"; 
					$msgType = "text";
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $text);
					echo $resultStr;
	}

    public function responseMsg()
    {
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		if (!empty($postStr)){
                libxml_disable_entity_loader(true);
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
				$MsgType = $postObj->MsgType;
				
				$conn = new mysqli("localhost","root","nana1013","01");
				$vcontent = '';
				
				switch($MsgType)
				{
				case "event":
				{
					$this->primarytextmsg($fromUsername, $toUsername, 被关注消息);break;
				}
				case "voice":
				{
					$vcontent = trim($postObj->Recognition);
				}
				case "text":
				{
					$keyword = (empty($vcontent)? trim($postObj->Content) : "【语音】".$vcontent);
					$time = time();
					
					if(!empty( $keyword ))
					{
						//查看是否有属于这个用户的未完成的道歉记录
						$sql = "select * from `apology` where `openid` = '".$fromUsername."' and fin = 0";
						//插入一条道歉记录
						$sql1 = "INSERT INTO `apology` (`openid`) VALUES ('".$fromUsername."')";
						
						$result = $conn -> query($sql);
						$record = $result -> num_rows;

						if( $keyword == "莫西莫西" || $keyword == "么西么西"|| $keyword == "摩西摩西")
						{
							if($record == 0)
							{
								$this->primarytextmsg($fromUsername, $toUsername, 求名字);
								$conn -> query($sql1);
							}
							else if(mysqli_fetch_array($result)['user']=='')
							{
								$this->primarytextmsg($fromUsername, $toUsername, 求名字);
							}
							else
							{
								$this->primarytextmsg($fromUsername, $toUsername, mysqli_fetch_array($result)['user'].求内容);
							}
							
						}
						else if(substr_count($keyword, "我叫")||substr_count($keyword, "我是")||substr_count($keyword, "叫我"))
						{

							if($record > 0 && mysqli_fetch_array($result)['user']=='')
							{
								$name = mb_substr($keyword , 2+max(max(mb_strpos($keyword,"我叫",0,"utf-8"),mb_strpos($keyword,"我是",0,"utf-8")),mb_strpos($keyword,"我叫",0,"utf-8")), null, "utf-8");
								$this->primarytextmsg($fromUsername, $toUsername, $name.求内容);
							
								//写入昵称
								$sql2 = "UPDATE `apology` SET `user` = '".$name."' where `openid` = '".$fromUsername."' and fin = 0";
								$conn -> query($sql2);
							}

							else if($record > 0)
							{
								//写入道歉内容
								$sql3 = "UPDATE `apology` SET `content` = '".$keyword."' , `fin` = 1 ,`time` = ".time()." where `openid` = '".$fromUsername."' and fin = 0";
								$conn -> query($sql3);
								//随机选取返回信
								$sql4 = "SELECT count(*) FROM `apology` WHERE `sent` = 1";
								$random = rand(0 , mysqli_fetch_array($conn -> query($sql4))['count(*)']-1);

								$sql5 = "SELECT `user`,`content` FROM `apology` where `sent` = 1 limit ".$random.",1";
								$result = $conn -> query($sql5);
								$row = mysqli_fetch_array($result);
								$this->primarytextmsg($fromUsername, $toUsername, "你的道歉已经成功传送到赎罪大厅，相信被你道歉的TA一定会在冥冥之中原谅你的~\n\n下面是来自〔".$row['user']."〕的一条道歉：\n\n".$row['content']);
							}
							else
							{
								$conn -> query($sql1);
								$name = mb_substr($keyword , 2+max(max(mb_strpos($keyword,"我叫",0,"utf-8"),mb_strpos($keyword,"我是",0,"utf-8")),mb_strpos($keyword,"我叫",0,"utf-8")), null, "utf-8");
								$this->primarytextmsg($fromUsername, $toUsername, $name.求内容);
							
								//写入昵称
								$sql2 = "UPDATE `apology` SET `user` = '".$name."' where `openid` = '".$fromUsername."' and fin = 0";
								$conn -> query($sql2);
							}
						}
						else
						{
							
							if($record == 0)
							{
								$this->primarytextmsg($fromUsername, $toUsername, 常规回复);
							}
							else if(empty(mysqli_fetch_array($result)['user']))
							{
								$this->primarytextmsg($fromUsername, $toUsername, $keyword.求内容);
								//写入昵称
								$sql2 = "UPDATE `apology` SET `user` = '".$keyword."' where `openid` = '".$fromUsername."' and fin = 0";
								$conn -> query($sql2);
							}
							else
							{
								//写入道歉内容
								$sql3 = "UPDATE `apology` SET `content` = '".$keyword."' , `fin` = 1 ,`time` = ".time()." where `openid` = '".$fromUsername."' and fin = 0";
								$conn -> query($sql3);
								//随机选取返回信
								$sql4 = "SELECT count(*) FROM `apology` WHERE `sent` = 1";
								$random = rand(0 , mysqli_fetch_array($conn -> query($sql4))['count(*)']-1);

								$sql5 = "SELECT `user`,`content` FROM `apology` where `sent` = 1 limit ".$random.",1";
								$result = $conn -> query($sql5);
								$row = mysqli_fetch_array($result);
								$this->primarytextmsg($fromUsername, $toUsername, "你的道歉已经成功传送到赎罪大厅，相信被你道歉的TA一定会在冥冥之中原谅你的~\n\n下面是来自〔".$row['user']."〕的一条道歉：\n\n".$row['content']);
							}
						}
					}
					break;
                }
				}
        }else {echo "";exit;}
    }
	private function checkSignature()
	{
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}

?>
