<?php
	require('../../../conn.php');
	$flag = isset($_REQUEST["flag"]) ? $_REQUEST["flag"] : "";
//	$flag='time';
	switch($flag){
		//获取最新温度
		case 'temperature' :
			$sql='select `value1`,`value2` from `hoting` order by `id` desc limit 1';
			$result = $conn->query($sql);
			$row =$result->fetch_assoc();
			$temperature_min=$row['value1'];
			$temperature_max=$row['value2'];
			if($temperature_max=='0.0'){
				//未测量最高温，温度用最低温表示
				$temperature=$temperature_min;
			}else if($temperature_min=='0.0'){
				//未测量最低温，温度用最高温表示
				$temperature=$temperature_max;				
			}else{
				//温度用平均值表示
				$temperature=($temperature_max+$temperature_min)/2;
			}
			echo $temperature;
			break;
		//获取最新时间
		case 'time' :
			$sql='select `time` from `hoting` order by `id` desc limit 1';
			$result = $conn->query($sql);
			$row =$result->fetch_assoc();
			$sjc=$row['time'];
			$time=date( "H:i:s",$sjc);	
			echo $time;		
			break;
	}
?>