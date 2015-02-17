<?php
if(isset($_GET['id']))
{
	$conn = new mysqli("localhost","root","nana1013","01");
	$sql = "UPDATE `01`.`apology` SET `sent`=1 WHERE `id` = ".$_GET['id'];
	$conn->query($sql);
/*	if($conn->affected_rows)
	{
	echo "success";
	}
	else{
	echo "fail";
}*/
}
?>
