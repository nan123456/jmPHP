<?php
	require("../../conn.php");
//	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	$ret_data=array();
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	$figure_number = isset($_POST["figure_number"])?$_POST["figure_number"]:'';
	$pnumber = isset($_POST["pnumber"])?$_POST["pnumber"]:'';
	switch($flag){
		case 'welding':
		$sql = "SELECT id FROM weldingtable WHERE partdrawingnumber = '$figure_number' and workordernumber = '$pnumber'";
		$res=$conn->query($sql);
		if($res->num_rows>0){
			$ret_data["success"] = 'success';
			while($row=$res->fetch_assoc()){
				$ret_data["id"] = $row["id"];
			}
		}else {
			$ret_data["success"] = 'error';
		}
		break;
		case 'crafts':
		$sql = "SELECT id FROM craftsmanshiptable WHERE partdrawnumber = '$figure_number' and pnumber = '$pnumber'";
		$res=$conn->query($sql);
		if($res->num_rows>0){
			$ret_data["success"] = 'success';
			while($row=$res->fetch_assoc()){
				$ret_data["id"] = $row["id"];
			}
		}else {
			$ret_data["success"] = 'error';
		}
		break;
		case 'heating':
		$sql = "SELECT id FROM heattreatment WHERE productDrawingNumber = '$figure_number' and partsName = '$pnumber'";
		$res=$conn->query($sql);
		if($res->num_rows>0){
			$ret_data["success"] = 'success';
			while($row=$res->fetch_assoc()){
				$ret_data["id"] = $row["id"];
			}
		}else {
			$ret_data["success"] = 'error';
		}
		break;
		case 'maching':
		$sql = "SELECT id FROM machiningtable WHERE partdrawnumber = '$figure_number' and pnumber = '$pnumber'";
		$res=$conn->query($sql);
		if($res->num_rows>0){
			$ret_data["success"] = 'success';
			while($row=$res->fetch_assoc()){
				$ret_data["id"] = $row["id"];
			}
		}else {
			$ret_data["success"] = 'error';
		}
		break;
	}
		
	
	
	$conn->close();
	$json = json_encode($ret_data);
	echo $json;
?>