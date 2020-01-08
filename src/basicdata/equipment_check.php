<?php
	require("../../conn.php");
	$ret_data = array();
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	$id = isset($_POST["id"])?$_POST["id"]:'';
	if($flag=='getCheckList'){
		$sql = "select `id`,`e_number`,`e_name`,`e_type`,`year_month`,`workshop`,`group` from `equipment_check_list` where `e_id`='$id'";
		$res = $conn->query($sql);
		if($res->num_rows>0){
			$i = 0;
			$ret_data["success"]= 'success';
			while($row = $res->fetch_assoc()){
				$ret_data["data"][$i]["id"] = $row["id"];
				$ret_data["data"][$i]["e_number"] = $row["e_number"];
				$ret_data["data"][$i]["e_name"] = $row["e_name"];
				$ret_data["data"][$i]["e_type"] = $row["e_type"];
				$ret_data["data"][$i]["year_month"] = $row["year_month"];
				$ret_data["data"][$i]["workshop"] = $row["workshop"];
				$ret_data["data"][$i]["group"] = $row["group"];
				$i++;
			}
		}
		$conn->close();			
	}
	$json = json_encode($ret_data);
	echo $json;
?>