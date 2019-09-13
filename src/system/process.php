<?php
	require("../../conn.php");
	$ret_data = array();
//	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
//	$send_json = @file_get_contents('php://input');  // body 传值获取
//	$id = isset($_POST["id"]) ? $_POST["id"] : '11111';
//	$ret_data["id"] = $send_json;
//		echo $_POST["id"];
    $flag=$_POST['flag'];
	// $flag='shift';
	if($flag=='concession'){
		$sql = "select * from concession";
		$res = $conn->query($sql);
		if($res->num_rows>0) {
			$i=0;
			$ret_data["success"]= 'success';
			while($row = $res->fetch_assoc()){
				$ret_data["data"][$i]["number"] = $i+1;
				$ret_data["data"][$i]["name"] = $row["name"];
				$ret_data["data"][$i]["pNumber"] = $row["pNumber"];
				$ret_data["data"][$i]["id"] = $row["id"];
				$ret_data["data"][$i]["figure_number"] = $row["figure_number"];
				$ret_data["data"][$i]["backcount"] = $row["backcount"];
				$ret_data["data"][$i]["count"] = $row["count"];
				$i++;
			}
		}
		$conn->close();
		$json = json_encode($ret_data);
		echo $json;
	}	
	if($flag=="shift"){
		$sql = "select pNumber,shiftpNumber,SUM(shiftcount) as shiftcount,modid from shiftrecord group BY modid";
		$res = $conn->query($sql);
		if($res->num_rows>0) {
			$i=0;
			$ret_data["success"]= 'success';
			while($row = $res->fetch_assoc()){
				$ret_data["data"][$i]["number"] = $i+1;
				// $ret_data["data"][$i]["id"] = $row["id"];
				$ret_data["data"][$i]["pNumber"] = $row["pNumber"];
				$ret_data["data"][$i]["shiftpNumber"] = $row["shiftpNumber"];
				$ret_data["data"][$i]["shiftcount"] = $row["shiftcount"];
				$modid=$row["modid"];
				$sql_sea="select name,figure_number,count from part where modid='".$modid."'";
				$res_sea = $conn->query($sql_sea);
		        if($res->num_rows>0) {
					while($row1=$res_sea->fetch_assoc()){
						$ret_data["data"][$i]["name"] = $row1["name"];
						$ret_data["data"][$i]["figure_number"] = $row1["figure_number"];
						$ret_data["data"][$i]["count"] = $row1["count"];
					}
				}
				$i++;
			}
		}
		$conn->close();
		$json = json_encode($ret_data);
		echo $json;
	}
?>