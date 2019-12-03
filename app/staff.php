<?php
	require("../conn.php");
	$flag=$_POST['flag'];
	switch($flag){
		case 'login':
			$id = $_POST["id"];
		//	$id = '7';
			$sql = "select name,department from user where id='".$id."' ";
			$res = $conn->query($sql);
			if($res -> num_rows > 0) {
				$i = 0;
				while($row = $res->fetch_assoc()) {
					$arr[$i]['name'] = $row['name'];
					$arr[$i]['department'] = $row['department'];
					$i++;
				}
			}
		break;
		case 'loginelse':
			$account=$_POST['account'];
			$password=$_POST['password'];
			$arr['state']='error';
			$sql = "SELECT department,name FROM user WHERE account = '".$account."' and password = '".$password."'";
			$res = $conn->query($sql);
			if($res -> num_rows > 0) {
				$i = 0;
				while($row = $res->fetch_assoc()) {
					$arr[$i]['name'] = $row['name'];
					$arr[$i]['department'] = $row['department'];
				}
				$arr['state']='success';
			}
		break;
	}
	$json = json_encode($arr);
	echo $json;
	$conn->close();
?>