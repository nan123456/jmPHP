<?php
require("../conn.php");
$flag = isset($_POST["flag"])?$_POST["flag"]:'';
$account = isset($_POST["account"])?$_POST["account"]:'';
$ret=array();
if($flag=='getSeeModules'){
	$sql="SELECT seeModule FROM `user` WHERE account='$account'";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$ret["data"]=$row["seeModule"];
	$json = json_encode($ret);
	echo $json;
}
?>