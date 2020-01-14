<?php
	require("../conn.php");
	$data=array();
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	if($flag=='getCheckList'){
		$id = isset($_POST["id"])?$_POST["id"]:'';
		$year_month = isset($_POST["year_month"])?$_POST["year_month"]:'';
		$sql = "SELECT id,e_number,e_name,e_type,check_content FROM equipment_check_list WHERE e_id = '$id' and `year_month`='$year_month' and is_delete=0";
		$res = $conn->query($sql);
		if($res -> num_rows > 0){
			$row = $res->fetch_assoc();
			$data['data']['e_number'] = $row['e_number'];
			$data['data']['e_name'] = $row['e_name'];
			$data['data']['e_type'] = $row['e_type'];
			$data['data']['check_content'] = $row['check_content'];	
			$data['data']['id'] = $row['id'];	
			$data['result']='success';
		}else{
			$data['result']='null';
		}
		$conn->close();
	}else if($flag=='saveCheckList'){
		$id = isset($_POST["id"])?$_POST["id"]:'';
		$content = isset($_POST["content"])?$_POST["content"]:'';
		$sql="UPDATE `equipment_check_list` SET `check_content`='$content' WHERE `id`='$id'";
		$res = $conn->query($sql);
		$data['result']='success';
	}
	$json = json_encode($data);
	echo $json;
	
?>