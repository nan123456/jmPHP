<?php
	require("../../conn.php");
//	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	$ret_data=array();
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	
	
	$conn->close();
	$json = json_encode($ret_data);
	echo $json;
?>