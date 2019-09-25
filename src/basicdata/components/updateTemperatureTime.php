<?php
	require('../../../conn.php');
	$flag = isset($_REQUEST["flag"]) ? $_REQUEST["flag"] : "";
//	$flag='time';
	date_default_timezone_set("PRC");//设置时区为中国时区
	switch($flag){
		//获取最新温度
		case 'temperature' :
			$sql='select `value1`,`value2`,`ctime` from `hoting` order by `id` desc limit 1';
			$result = $conn->query($sql);
			$row =$result->fetch_assoc();
			$temperature_min=$row['value1'];
			$temperature_max=$row['value2'];
			$sjc=$row['ctime'];
			$nowtime=time();//当前秒级时间戳
			$interval=$nowtime-$sjc;//时间间隔
			if($interval>120){
				//间隔时间大于2分钟
				echo	 '硬件获取时间已超两分钟，请检查硬件';
			}else{
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
			}

			break;
		//获取最新时间
		case 'time' :
			$sql='select `ctime` from `hoting` order by `id` desc limit 1';
			$result = $conn->query($sql);
			$row =$result->fetch_assoc();
			$sjc=$row['ctime'];
			$nowtime=time();//当前秒级时间戳
			$interval=$nowtime-$sjc;//时间间隔
			if($interval>120){
				//间隔时间大于2分钟
				echo	 '硬件获取时间已超两分钟，请检查硬件';
			}else{
				$time=date( "H:i:s",$sjc);	
				echo $time;	
			}	
			break;
	}
?>