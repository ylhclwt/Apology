<table class="table table-striped table-bordered table-hover"><thead><tr><th>#</th><th>名字</th><th class="col-xs-12 col-md-8">内容</th><th>时间</th><th>上帝您想要</th></tr></thead><tbody> 
<?php error_reporting(0);

date_default_timezone_set("Asia/Shanghai");
$conn = new mysqli("localhost","root","nana1013","01");

switch($_GET['mode'])
{
case 1:$sql = "select * from `apology` where `fin`=1 order by `id` desc limit 0,100 ";break;
case 2:$sql = "select * from `apology` where `fin`=1 and `sent`=0 order by `id` desc";break;
case 3:$sql = "select * from `apology` where `fin`=1 and `sent`=1 order by `id` desc";break;
}
$result = $conn -> query($sql);



while($row = mysqli_fetch_array($result))
{
switch($row['sent'])
{
//待审核
case 0:echo '<tr class="warning" id="'.$row['id'].'"><td>'.$row['id'].'</td><td>'.$row['user'].'</td><td id="c">'.$row['content'].'</td><td>'.transTime($row['time']).'</td><td><div id="b'.$row['id'].'"><button type="button" class="btn btn-success" onclick="pass('.$row['id'].',true)">救赎</button><button type="button" class="btn btn-danger" onclick="pass('.$row['id'].',false)">放逐</button></div></td></tr>
';break;
//通过
case 1:echo '<tr class="success"><td>'.($row['id']<10?'0'.$row['id']:$row['id']).'</td><td>'.$row['user'].'</td><td id="c">'.$row['content'].'</td><td>'.transTime($row['time']).'</td><td></td></tr>
';break;
//不通过
case 2:echo '<tr class="danger"><td>'.($row['id']<10?'0'.$row['id']:$row['id']).'</td><td>'.$row['user'].'</td><td id="c">'.$row['content'].'</td><td>'.transTime($row['time']).'</td><td></td></tr>
';break;
}
}

function transTime($ustime) { 
$ytime = date("Y-m-d H:i",$ustime); 
$rtime = date("n月j日 H:i",$ustime);
$htime = date("H:i",$ustime); 
$time = time() - $ustime;
$todaytime = strtotime("today");
$time1 = time() - $todaytime; 
if($time < 60){
$str = '刚刚';
}else if($time < 60 * 60){
$min = floor($time/60);
$str = $min.'分钟前';
}else if($time < $time1){
$str = '今天 '.$htime; 
}else{
$str = $rtime;
}
return $str;
}
?></tbody></table>
