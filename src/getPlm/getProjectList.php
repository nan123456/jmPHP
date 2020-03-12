<?php
	require ("../../conn.php");
	$newtime=time();
	//获取传入body
	// $bodyData = @file_get_contents('php://input');
	$url='http://192.168.1.117/ITEMWeb.asmx?op=GetAllProduct';
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
    //重复获取数据先删除原数据
	$sql="delete from plm_header";
	$conn->query($sql);
    //保存数据库
    foreach($bodyData as $key => $value){
    	$sql="insert into plm_header(product_id,label) values('".$value["product_id"]."','".$value["product_name"]."')";
		$conn->query($sql);
    }
    echo "success";
?>