<?php
require("../conn.php");

//$time = date("Y-m-d");
$id = $_POST["id"];
$year_month = $_POST["year_month"];
	$sql = "SELECT id,e_name,e_type,check_content FROM equipment_check_list WHERE e_id = $id and `year_month`=$year_month and is_delete=0";
			$res = $conn->query($sql);
			if($res -> num_rows > 0) {
				$i = 0;
				while($row = $res->fetch_assoc()) {
					$data[$i]['id'] = $row['e_id'];
					$data[$i]['e_name'] = $row['e_name'];
					$data[$i]['e_type'] = $row['e_type'];
                    $data[$i]['check_content'] = $row['check_content'];
					$i++;
				}
				$data['row'] = $i;
			} else{
//				die();
				$data['row'] = 0;

			}
			$json = json_encode($data);
			echo $json;
?>