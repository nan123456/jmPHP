<?php
require('../conn.php');
//	header('Access-Control-Allow-Origin: *'); // 允许任意域名发起的跨域请求
	$arr = ["('K','K坡')","('TK')","('S安装补贴','S玻璃钢','S厂检','S电气','S调试','S钢结构','S国（省）检','S派人维修','S移交客户','S座舱')","('F成型','F翻模','F模具','F喷涂','F装配','M木工')","('GS','G接线','G装灯','G装箱')","('T粗','T淬','T调','T发黑','T焊','T划线','T坡','T退','T线','T正火','T装')","('IA','IA1','IB','ID','IG','IS','I钻')","('LK','L焊','L转','L装')","('J探')","('FW成型','FW成型底漆','FW底漆','FW面漆','FW模具','TW半精车','TW插','TW粗车','TW调质','TW高频','TW滚','TW精车','TW拉','TW磨','TW刨','TW镗','TW铣','TW线割','W彩锌','W冲压','W镀铬','W镀锌','W发黑','W发泡','W改制','W回火','W机','W浸','W卷','W喷塑','W漆','W渗氮','W折','W退火')"];
	$data = array();
	for($i=0;$i<10;$i++){
		$sql = "SELECT COUNT(isfinish) AS count from `workshop_k` where isfinish = '2' and route in $arr[$i] ORDER BY id DESC limit 10";
		$result = $conn->query($sql);
		while ($row = $result->fetch_assoc()) {
			$data[$i] = $row["count"];
		}
	}
	$conn->close();
	$json = json_encode($data);
	echo $json;
?>