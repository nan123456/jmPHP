<?php
	header("Access-Control-Allow-Origin: *");
	date_default_timezone_set("PRC");//设置时区为中国时区
	// 允许任意域名发起的跨域请求
	require ("../conn.php");
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	$data = array();
	if($flag == "part"){
		$belong_part = isset($_POST["belong_part"])?$_POST["belong_part"]:'';
		$pNumber = isset($_POST["pNumber"])?$_POST["pNumber"]:'';
		$sql = "SELECT isfinish,COUNT(*) AS count FROM part WHERE belong_part = '$belong_part' AND pNumber = '$pNumber'  GROUP BY isfinish";
		$result = $conn->query($sql);
		if($result->num_rows>0){
			$sql2 = "SELECT COUNT(*) AS count FROM part WHERE belong_part = '$belong_part' AND pNumber = '$pNumber' ";
			$result2 = $conn->query($sql2);
			$row2=$result2->fetch_assoc();
			$i=0;
			$isfinish = array();
			while($row=$result->fetch_assoc()){
				//判断三项中缺少哪项
				array_push($isfinish,$row['isfinish']);
				switch($row['isfinish']){
					case ("0"):
						$data[0]["isfinish"] = "未开始";
						$data[0]["count"] = number_format($row["count"]/$row2["count"],5)*100;
						break;
					case ("1"):
						$data[1]["isfinish"] = "已完成";
						$data[1]["count"] = number_format($row["count"]/$row2["count"],5)*100;
						break;
					case ("2"):
						$data[2]["isfinish"] = "在建中";
						$data[2]["count"] = number_format($row["count"]/$row2["count"],5)*100;
						break;
				}
				$i++;
			}
			$arr = array("0"=>"0","1"=>"1","2"=>"2");
			$diffres=array_diff($arr,$isfinish);
			foreach($diffres as $key=>$value){
				switch($value){
					case ("0"):
						$data[0]["isfinish"] = "未开始";
						$data[0]["count"] = 0;
						break;
					case ("1"):
						$data[1]["isfinish"] = "已完成";
						$data[1]["count"] = 0;
						break;
					case ("2"):
						$data[2]["isfinish"] = "在建中";
						$data[2]["count"] = 0;
						break;
				}
			}
			$data["success"] = "success";
		}else{
			$sql = "SELECT isfinish,COUNT(*) AS count FROM part WHERE name = '$belong_part' AND pNumber = '$pNumber'  GROUP BY isfinish";
			$result = $conn->query($sql);
			if($result->num_rows>0){
				$sql2 = "SELECT COUNT(*) AS count FROM part WHERE name = '$belong_part' AND pNumber = '$pNumber' ";
				$result2 = $conn->query($sql2);
				$row2=$result2->fetch_assoc();
				$i=0;
				$isfinish = array();
				while($row=$result->fetch_assoc()){
					//判断三项中缺少哪项
					array_push($isfinish,$row['isfinish']);
					switch($row['isfinish']){
						case ("0"):
							$data[0]["isfinish"] = "未开始";
							$data[0]["count"] = number_format($row["count"]/$row2["count"],5)*100;
							break;
						case ("1"):
							$data[1]["isfinish"] = "已完成";
							$data[1]["count"] = number_format($row["count"]/$row2["count"],5)*100;
							break;
						case ("2"):
							$data[2]["isfinish"] = "在建中";
							$data[2]["count"] = number_format($row["count"]/$row2["count"],5)*100;
							break;
					}
					$i++;
				}
				$arr = array("0"=>"0","1"=>"1","2"=>"2");
				$diffres=array_diff($arr,$isfinish);
				foreach($diffres as $key=>$value){
					switch($value){
						case ("0"):
							$data[0]["isfinish"] = "未开始";
							$data[0]["count"] = 0;
							break;
						case ("1"):
							$data[1]["isfinish"] = "已完成";
							$data[1]["count"] = 0;
							break;
						case ("2"):
							$data[2]["isfinish"] = "在建中";
							$data[2]["count"] = 0;
							break;
					}
				}
				$data["success"] = "success";
			}else{
				$data["success"] = "error";
			}
		}
	}else if($flag =="project"){
		$pNumber = isset($_POST["pNumber"])?$_POST["pNumber"]:'';
		$sql = "SELECT isfinish,COUNT(*) AS count FROM part WHERE pNumber = '$pNumber'  GROUP BY isfinish";
		$result = $conn->query($sql);
		if($result->num_rows>0){
			$sql2 = "SELECT COUNT(*) AS count FROM part WHERE pNumber = '$pNumber' ";
			$result2 = $conn->query($sql2);
			$row2=$result2->fetch_assoc();
			$i=0;
			$isfinish = array();
			while($row=$result->fetch_assoc()){
				//判断三项中缺少哪项
				array_push($isfinish,$row['isfinish']);
				switch($row['isfinish']){
					case ("0"):
						$data[0]["isfinish"] = "未开始";
						$data[0]["count"] = number_format($row["count"]/$row2["count"],5)*100;
						break;
					case ("1"):
						$data[1]["isfinish"] = "已完成";
						$data[1]["count"] = number_format($row["count"]/$row2["count"],5)*100;
						break;
					case ("2"):
						$data[2]["isfinish"] = "在建中";
						$data[2]["count"] = number_format($row["count"]/$row2["count"],5)*100;
						break;
				}
				$i++;
			}
			$arr = array("0"=>"0","1"=>"1","2"=>"2");
			$diffres=array_diff($arr,$isfinish);
			foreach($diffres as $key=>$value){
				switch($value){
					case ("0"):
						$data[0]["isfinish"] = "未开始";
						$data[0]["count"] = 0;
						break;
					case ("1"):
						$data[1]["isfinish"] = "已完成";
						$data[1]["count"] = 0;
						break;
					case ("2"):
						$data[2]["isfinish"] = "在建中";
						$data[2]["count"] = 0;
						break;
				}
			}
			$data["success"] = "success";
		}
	}
	$json = json_encode($data);
	echo $json;											
	
	$conn->close();
?>