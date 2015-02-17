<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>赎 罪 大 厅</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<link rel="stylesheet" href="http://apps.bdimg.com/libs/bootstrap/3.2.0/css/bootstrap.min.css">
<script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
<script src="http://apps.bdimg.com/libs/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<style type="text/css">
body{font:14px "Microsoft Yahei",Arial !important}.table th,.table td{text-align:center}#c{text-align:left}
</style>
</head>
<body>
<center>
<h1>赎 罪 大 厅</h1>	
<div class="btn-group">
  <button type="button" class="btn btn-info" id="t1" onclick="mode(1)">最近100条</button>
  <button type="button" class="btn btn-info" id="t2" onclick="mode(2)">待审判</button>
  <button type="button" class="btn btn-info" id="t3" onclick="mode(3)">被救赎</button>
</div>
<div id="t"></div>
<p>—— Copyright © Vkki All Rights Reserved ——</p></center>
<script type="text/javascript">
mode(1);function mode(m){document.getElementById("t"+m).innerHTML="loading";var xmlhttp;if(window.XMLHttpRequest){xmlhttp=new XMLHttpRequest()}else{xmlhttp=new ActiveXObject("Microsoft.XMLHTTP")}
xmlhttp.onreadystatechange=function(){if(xmlhttp.readyState==4&&xmlhttp.status==200){document.getElementById("t").innerHTML=xmlhttp.responseText;document.getElementById("t"+m).innerHTML=(m==1?"最近100条":m==2?"待审判":"被救赎")}}
xmlhttp.open("GET","a.php?mode="+m,true);xmlhttp.send()}
function onBridgeReady(){WeixinJSBridge.call('hideOptionMenu')}
if(typeof WeixinJSBridge=="undefined"){if(document.addEventListener){document.addEventListener('WeixinJSBridgeReady',onBridgeReady,false)}else if(document.attachEvent){document.attachEvent('WeixinJSBridgeReady',onBridgeReady);document.attachEvent('onWeixinJSBridgeReady',onBridgeReady)}}else{onBridgeReady()}
function pass(id,pass){var xmlhttp;if(window.XMLHttpRequest){xmlhttp=new XMLHttpRequest()}else{xmlhttp=new ActiveXObject("Microsoft.XMLHTTP")}
xmlhttp.open("GET",(pass?"pass.php?id=":"deny.php?id=")+id,true);xmlhttp.send();document.getElementById(id).className=(pass?"success":"danger");document.getElementById("b"+id).style.display="none"}</script>
</body>
</html>
