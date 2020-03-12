<?php
	require ("../../conn.php");
	$ProductID = isset($_POST["ProductID"]) ? $_POST["ProductID"] : 'null';
	//获取当前时间戳
	$newtime=time();
	//定义返回数组
	$result = array(
		'code'=>200,
		'mes'=>''
	);
	//获取传入body
	// $bodyData = @file_get_contents('php://input');
	$url='http://192.168.1.117/ITEMWeb.asmx/GetProductBom?strProductID='.$ProductID;
	$bodyData=send_post($url);
	function send_post($url) {
    // $postdata = http_build_query($post_data);
    $options = array(
    'http' => array(
        'method' => 'POST',
        'header' => 'Content-type:application/x-www-form-urlencoded',
        'content' => $postdata,
        'timeout' => 15 * 60 // 超时时间（单位:s）
    )
  );
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    return $result;
}
	//去除xml部分
	$count=strpos($bodyData,'<?xml version="1.0" encoding="utf-8"?>'); 
	$bodyData=substr_replace($bodyData,"",$count,39); 
	$count=strpos($bodyData,'<string xmlns="http://caxawebitem.org/">'); 
	$bodyData=substr_replace($bodyData,"",$count,40); 
	$count=strpos($bodyData,'</string>'); 
	$bodyData=substr_replace($bodyData,"",$count,9); 
	//json解码
    $bodyData = json_decode($bodyData,true);
	$productId = isset($bodyData[0]["product_id"]) ? $bodyData[0]["product_id"] : "";
	$proname = isset($bodyData[0]["label"]) ? $bodyData[0]["label"] : "";
	//递归获取完整数据保存数据库
	function recursion($arr,$Fnumber,$product){
		require ("../../conn.php");
		foreach($arr as $key => $value){
			$sql = "INSERT INTO plm ( product_id, label, figure_number, belong_part, hierarchy, material, count, remark) VALUES	( '".$product."', '".$value["label"]."', '".$value["figure_number"]."', '".$Fnumber."', '".$value["hierarchy"]."', '".$value["material"]."', '".$value["count"]."', '".$value["remark"]."')";
			$conn->query($sql);
			if(is_array($value["children"])){
				recursion($value["children"],$value["figure_number"],$product);
			}
		}
	}
	
	if($bodyData[0]["product_id"]==$ProductID){
		$sql="DELETE FROM `plm` WHERE `product_id`='$ProductID'";
		$conn->query($sql);
		recursion($bodyData[0]["children"],$bodyData[0]["product_id"],$bodyData[0]["product_id"]);
		echo 'success';
	}else{
		echo 'error';
	}
?>