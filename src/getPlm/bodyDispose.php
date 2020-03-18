<?php
	require ("../../conn.php");
	$ProductID = isset($_POST["ProductID"]) ? $_POST["ProductID"] : 'null';
	set_time_limit(0);
	//获取当前时间戳
	$newtime=time();
	//定义返回数组
	// $result = array(
	// 	'code'=>200,
	// 	'mes'=>''
	// );
	//获取传入body
	// $bodyData = @file_get_contents('php://input');
	$url='http://192.168.1.245/ITEMWeb.asmx/GetProductBom?strProductID='.$ProductID;
	//测试链接
	// $url='http://localhost/jmphp/src/getPlm/echoData.php';
	$bodyData=send_post($url);
	function send_post($url) {
    // $postdata = http_build_query($post_data);
	$postdata=null;
    $options = array(
    'http' => array(
        'method' => 'GET',
        'header' => 'Content-type:application/x-www-form-urlencoded',
        'content' => $postdata,
        'timeout' => 15 * 60 // 超时时间（单位:s）
    )
  );
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    return $result;
}
	// echo $bodyData;
	//去除xml部分
	$count=strpos($bodyData,'<?xml version="1.0" encoding="utf-8"?>'); 
	$bodyData=substr_replace($bodyData,"",$count,39); 
	$count=strpos($bodyData,'<string xmlns="http://caxawebitem.org/">'); 
	$bodyData=substr_replace($bodyData,"",$count,40); 
	$count=strpos($bodyData,'</string>'); 
	$bodyData=substr_replace($bodyData,"",$count,9);
	//替换所有空格
	$bodyData=preg_replace('/\s+/','',$bodyData);
	//替换所有换行
	$bodyData = str_replace(array("\r\n", "\r", "\n"), "", $bodyData);
	//保存json字符串变量
	$bodyData_str=$bodyData;
	// echo $bodyData;
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
		$sql1="DELETE FROM `plm` WHERE `product_id`='$ProductID'";
		$conn->query($sql1);
		$sql2="DELETE FROM `plm_json` WHERE `product_id`='$ProductID'";
		$conn->query($sql2);
		$sql3="INSERT INTO plm_json(`product_id`,`json`)VALUES('$ProductID','$bodyData_str')";
		$conn->query($sql3);
		recursion($bodyData[0]["children"],$bodyData[0]["product_id"],$bodyData[0]["product_id"]);
		echo 'success';
	}else{
		echo 'error';
	}
?>