<?php
 	require("../../conn.php");
//	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
 	$sqldata='';
	$arr = $_POST["arr"];
 	$sql="SELECT route,name,figure_number,schedule_date,otime,todocount from `workshop_k` where isfinish = '2' and route in ('K') ORDER BY id DESC limit 10";
 	$result = $conn->query($sql);
 	while ($row = $result->fetch_assoc()) {
 		$sqldata=$sqldata.'{
			"Serial":"'.$row["route"].'",
			"name":"'.$row["name"].'",
			"figure_number":"'.$row["figure_number"].'",
			"time":"'.$row["finallytime"].'",
			"finished":"'.$row["otime"].'",
			"todocount":"'.$row["todocount"].'"
		},';
	}
 	$jsonresult = 'true';
	$otherdate = '{"success":"'.$jsonresult.'"
		      }';
	$json = '['.$sqldata.$otherdate.']';
	echo $json;
	$conn->close();
?>