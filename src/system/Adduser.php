<?php
	header("Access-Control-Allow-Origin: *");
	date_default_timezone_set("PRC");//设置时区为中国时区
	// 允许任意域名发起的跨域请求
	require ("../../conn.php");
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	$data = array();
	if($flag=='Searchacc'){
		$account = isset($_POST["acc"])?$_POST["acc"]:'';
		$sql="SELECT account FROM `user` WHERE account='$account'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			$data["success"]='error';
		}else{
			$data["success"]='success';
		}
	}else if($flag=='SearchGnum'){
		$gNum = isset($_POST["gNum"])?$_POST["gNum"]:'';
		$sql="SELECT `name` FROM `user` WHERE gNum='$gNum'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			$row=$result->fetch_assoc();
			$data["name"]=$row["name"];
			$data["success"]='success';
		}else{
			$data["success"]='error';
		}
	}else if($flag=="Register"){
		$account = isset($_POST["account"])?$_POST["account"]:'';
		$password = isset($_POST["password"])?$_POST["password"]:'';
		$gNum = isset($_POST["gNum"])?$_POST["gNum"]:'';
		$seeModule = isset($_POST["seeModule"])?$_POST["seeModule"]:'';
		$cuser = isset($_POST["cuser"])?$_POST["cuser"]:'';
		$password = sha1($password);
		$sql = "UPDATE `user` SET account='$account',`password`='$password',seeModule='$seeModule',cuser='$cuser' WHERE gNum = '$gNum'";
		$result = $conn->query($sql);
		$data["success"]='success';
	}
	$json = json_encode($data);
	echo $json;											
	
	$conn->close();
?>