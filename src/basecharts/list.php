<?php
	header("Access-Control-Allow-Origin: *");
	date_default_timezone_set("PRC");//设置时区为中国时区
	// 允许任意域名发起的跨域请求
	require ("../../conn.php");
	$arr = array();
	$arr2 = array();
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
//	$flag = "Undelivered";
	if ($flag == 'Undelivered') {
		// 获取列表数据
		$sql = "SELECT b.modid,a.modid,b.fid,b.id,b.figure_number,b.name,b.standard,b.route,b.count,b.child_material,b.pNumber,b.number,b.product_name,b.remark,b.routeid,b.backMark,b.reason,a.isfinish,a.stime,a.ftime,b.isexterior FROM workshop_k a,productionplan b WHERE a.modid = b.modid AND a.route = b.route AND  a.isfinish in ('1','3') AND b.isexterior = 0 order by id desc";
		
		$res = $conn -> query($sql);
		if ($res -> num_rows > 0) {
			$i = 0;
			while ($row = $res -> fetch_assoc()) {
				$arr[$i]['modid'] = $row['modid'];
				$arr[$i]['fid'] = $row['fid'];
				$arr[$i]['partid'] = $row['id'];
				$arr[$i]['figure_number'] = $row['figure_number'];
				//零件图号
				$arr[$i]['name'] = $row['name'];
				//名称
				$arr[$i]['standard'] = $row['standard'];
				//开料尺寸
				$arr[$i]['route'] = $row['route'];
				//加工工艺路线
				$arr[$i]['count'] = $row['count'];
				//数量
				$arr[$i]['child_material'] = $row['child_material'];
				//规格
				$arr[$i]['number']=$row['pNumber']; //工单
				$arr[$i]['product_name'] = $row['number'] . $row['product_name']; //产品名称
				$arr[$i]['remark'] = $row['remark'];
				$arr[$i]['routeid'] = $row['routeid'];
				$arr[$i]['stime'] = $row['stime'];
				$arr[$i]['ftime'] = $row['ftime'];			
				$arr[$i]['reason'] = $row['reason'];
				//计算工时
				
				//就工日期
				$startdate = date( "Y-m-d",strtotime($row['stime']));
				$starttime = date("H:i",strtotime($row['stime']));
				$finishidate = date( "Y-m-d",strtotime($row['ftime']));
				$finishitime = date( "H:i",strtotime($row['ftime']));
				
//				$arr[$i]['startdate'] = $startdate;
//				$arr[$i]['starttime'] = $starttime;
//				$arr[$i]['finishidate'] = $finishidate;
//				$arr[$i]['finishitime'] = $finishitime;
//				
				
				
				if($startdate == $finishidate){
					$minute=floor((strtotime($finishitime)-strtotime($starttime))%86400/60);
//					$arr[$i]['minute'] = $minute;
					$workinghours = round($minute/$row['count'],2)."分钟/件";
					$arr[$i]['workinghours'] = $workinghours;
				}else{
					if($starttime>"17:00"){
						$startdate = date('Y-m-d', strtotime('+1 day', strtotime($startdate)));
						$Date_List_a1 = explode("-",$finishidate);
						$Date_List_a2=explode("-",$startdate);
						$d1=mktime(0,0,0,$Date_List_a1[1],$Date_List_a1[2],$Date_List_a1[0]);
						$d2=mktime(0,0,0,$Date_List_a2[1],$Date_List_a2[2],$Date_List_a2[0]);
						$Days=round(($d1-$d2)/3600/24);
						$min = floor((strtotime($finishitime)-strtotime("00:00"))%86400/60);
						$minute = $Days*60*9+$min;
						$workinghours = round($minute/$row['count'],2)."分钟/件";
						$arr[$i]['workinghours'] = $workinghours;
					}else{
						if($starttime<"08:00")
							$starttime = "08:00";
						$min1 = floor((strtotime("17:00")-strtotime($starttime))%86400/60);
						$startdate = date('Y-m-d', strtotime('+1 day', strtotime($startdate)));
						$Date_List_a1 = explode("-",$finishidate);
						$Date_List_a2=explode("-",$startdate);
						$d1=mktime(0,0,0,$Date_List_a1[1],$Date_List_a1[2],$Date_List_a1[0]);
						$d2=mktime(0,0,0,$Date_List_a2[1],$Date_List_a2[2],$Date_List_a2[0]);
						$Days=round(($d1-$d2)/3600/24);
						$min2 = floor((strtotime($finishitime)-strtotime("00:00"))%86400/60);
						$minute = $Days*60*9+$min1+$min2;
						$workinghours = round($minute/$row['count'],2)."分钟/件";
						$arr[$i]['workinghours'] = $workinghours;
					}
				}
				$i++;
			}
}
		//规格筛选
		$sql2 = "SELECT DISTINCT b.child_material,a.isfinish,a.modid,b.modid FROM workshop_k a,productionplan b WHERE a.modid = b.modid AND a.isfinish IN ('1','3') AND b.isexterior = 0 group by child_material";
		$res2 = $conn -> query($sql2);
		if ($res2 -> num_rows > 0) {
			$i = 0;
			while ($row2 = $res2 -> fetch_assoc()) {
				// 规格
				if($row2['child_material'] != ""&&$row2['child_material'] != Null){
					$arr2[$i]['f5'] = $row2['child_material'];
					$i++;					
				}
			}
		}
		//项目名称筛选
		$sql3 = "SELECT DISTINCT b.product_name,b.number FROM workshop_k a,productionplan b WHERE a.modid = b.modid AND a.isfinish IN ('1','3') AND b.isexterior = 0 group by child_material";
		$res3 = $conn -> query($sql3);
		if ($res3 -> num_rows > 0) {
			$i = 0;
			while ($row3 = $res3 -> fetch_assoc()) {
				// 规格
				if($row3['product_name'] != ""&&$row3['product_name'] != Null&&$row3['number'] != ""&&$row3['number'] != Null){
					$arr3[$i]['f6'] = $row3['number'] . $row3['product_name'];
					$i++;					
				}
			}
		}
		//工单号筛选
		$sql4 = "SELECT DISTINCT b.pNumber FROM workshop_k a,productionplan b WHERE a.modid = b.modid AND a.isfinish IN ('1','3') AND b.isexterior = 0 group by child_material";
		$res4 = $conn -> query($sql4);
		if ($res4 -> num_rows > 0) {
			$i = 0;
			while ($row4 = $res4 -> fetch_assoc()) {
				// 规格
				if($row4['pNumber'] != ""&&$row4['pNumber'] != Null){
					$arr4[$i]['f7'] = $row4['pNumber'];
					$i++;					
				}
			}
		}
	
		// 工时统计
		$list_data = json_encode($arr);
		$fChild_material = json_encode($arr2);
		$product_name =  json_encode($arr3);
		$pNumber = json_encode($arr4);
		$json = '{"success":true,"rows":' . $list_data . ',"fChild_material":' . $fChild_material . ',"product_name":' . $product_name . ',"pNumber":' . $pNumber . '}';
	}else if($flag == "selectData"){
		//获取前端数据
		$select = isset($_POST["select"]) ? $_POST["select"] : '';
		// 获取列表数据
		$sql = "SELECT b.modid,a.modid,b.fid,b.id,b.figure_number,b.name,b.standard,b.route,b.count,b.child_material,b.pNumber,b.number,b.product_name,b.remark,b.routeid,b.backMark,b.reason,a.isfinish,a.stime,a.ftime FROM workshop_k a,productionplan b WHERE a.modid = b.modid AND a.route = b.route AND a.isfinish in ('1','3') AND CONCAT(b.figure_number,b.`name`,b.standard,b.route,b.count,b.child_material,b.pNumber,b.number,b.product_name,a.stime,a.ftime)LIKE '%".$select."%' AND b.isexterior = 0 order by id desc";
		
		$res = $conn -> query($sql);
		if ($res -> num_rows > 0) {
			$i = 0;
			while ($row = $res -> fetch_assoc()) {
				$arr[$i]['modid'] = $row['modid'];
				$arr[$i]['fid'] = $row['fid'];
				$arr[$i]['partid'] = $row['id'];
				$arr[$i]['figure_number'] = $row['figure_number'];
				//零件图号
				$arr[$i]['name'] = $row['name'];
				//名称
				$arr[$i]['standard'] = $row['standard'];
				//开料尺寸
				$arr[$i]['route'] = $row['route'];
				//加工工艺路线
				$arr[$i]['count'] = $row['count'];
				//数量
				$arr[$i]['child_material'] = $row['child_material'];
				//规格
				$arr[$i]['number']=$row['pNumber']; //工单
				$arr[$i]['product_name'] = $row['number'] . $row['product_name']; //产品名称
				$arr[$i]['remark'] = $row['remark'];
				$arr[$i]['routeid'] = $row['routeid'];
				$arr[$i]['stime'] = $row['stime'];
				$arr[$i]['ftime'] = $row['ftime'];			
				$arr[$i]['reason'] = $row['reason'];
				//计算工时
				
				//就工日期
				$startdate = date( "Y-m-d",strtotime($row['stime']));
				$starttime = date("H:i",strtotime($row['stime']));
				$finishidate = date( "Y-m-d",strtotime($row['ftime']));
				$finishitime = date( "H:i",strtotime($row['ftime']));
				
//				$arr[$i]['startdate'] = $startdate;
//				$arr[$i]['starttime'] = $starttime;
//				$arr[$i]['finishidate'] = $finishidate;
//				$arr[$i]['finishitime'] = $finishitime;
//				
				
				
				if($startdate == $finishidate){
					$minute=floor((strtotime($finishitime)-strtotime($starttime))%86400/60);
//					$arr[$i]['minute'] = $minute;
					$workinghours = round($minute/$row['count'],2)."分钟/件";
					$arr[$i]['workinghours'] = $workinghours;
				}else{
					if($starttime>"17:00"){
						$startdate = date('Y-m-d', strtotime('+1 day', strtotime($startdate)));
						$Date_List_a1 = explode("-",$finishidate);
						$Date_List_a2=explode("-",$startdate);
						$d1=mktime(0,0,0,$Date_List_a1[1],$Date_List_a1[2],$Date_List_a1[0]);
						$d2=mktime(0,0,0,$Date_List_a2[1],$Date_List_a2[2],$Date_List_a2[0]);
						$Days=round(($d1-$d2)/3600/24);
						$min = floor((strtotime($finishitime)-strtotime("00:00"))%86400/60);
						$minute = $Days*60*9+$min;
						$workinghours = round($minute/$row['count'],2)."分钟/件";
						$arr[$i]['workinghours'] = $workinghours;
					}else{
						if($starttime<"08:00")
							$starttime = "08:00";
						$min1 = floor((strtotime("17:00")-strtotime($starttime))%86400/60);
						$startdate = date('Y-m-d', strtotime('+1 day', strtotime($startdate)));
						$Date_List_a1 = explode("-",$finishidate);
						$Date_List_a2=explode("-",$startdate);
						$d1=mktime(0,0,0,$Date_List_a1[1],$Date_List_a1[2],$Date_List_a1[0]);
						$d2=mktime(0,0,0,$Date_List_a2[1],$Date_List_a2[2],$Date_List_a2[0]);
						$Days=round(($d1-$d2)/3600/24);
						$min2 = floor((strtotime($finishitime)-strtotime("00:00"))%86400/60);
						$minute = $Days*60*9+$min1+$min2;
						$workinghours = round($minute/$row['count'],2)."分钟/件";
						$arr[$i]['workinghours'] = $workinghours;
					}
				}
				$i++;
			}
		}
		$sql2 = "SELECT DISTINCT b.modid,a.modid,b.fid,b.id,b.figure_number,b.name,b.standard,b.route,b.count,b.child_material,b.pNumber,b.number,b.product_name,b.remark,b.routeid,b.backMark,b.reason,a.isfinish,a.stime,a.ftime FROM workshop_k a,productionplan b WHERE a.modid = b.modid AND a.route = b.route AND a.isfinish IN ('1','3') AND CONCAT(b.figure_number,b.`name`,b.standard,b.route,b.count,b.child_material,b.number,b.pNumber,b.product_name,a.stime,a.ftime)LIKE '%".$select."%' AND b.isexterior = 0 group by child_material order by a.id desc";
		$res2 = $conn -> query($sql2);
		if ($res2 -> num_rows > 0) {
			$i = 0;
			while ($row2 = $res2 -> fetch_assoc()) {
				// 规格
				if($row2['child_material'] != ""&&$row2['child_material'] != Null){
					$arr2[$i]['f5'] = $row2['child_material'];
					$i++;					
				}
			}
		}
		//项目名称筛选
		$sql3 = "SELECT DISTINCT b.product_name,b.number FROM workshop_k a,productionplan b WHERE a.modid = b.modid AND a.route = b.route AND a.isfinish in ('1','3') AND CONCAT(b.figure_number,b.`name`,b.standard,b.route,b.count,b.child_material,b.pNumber,b.number,b.product_name,a.stime,a.ftime)LIKE '%".$select."%' AND b.isexterior = 0 order by a.id desc";
		$res3 = $conn -> query($sql3);
		if ($res3 -> num_rows > 0) {
			$i = 0;
			while ($row3 = $res3 -> fetch_assoc()) {
				// 规格
				if($row3['product_name'] != ""&&$row3['product_name'] != Null&&$row3['number'] != ""&&$row3['number'] != Null){
					$arr3[$i]['f6'] = $row3['number'] . $row3['product_name'];
					$i++;					
				}
			}
		}
		//工单号筛选
		$sql4 = "SELECT DISTINCT b.pNumber FROM workshop_k a,productionplan b WHERE a.modid = b.modid AND a.route = b.route AND a.isfinish in ('1','3') AND CONCAT(b.figure_number,b.`name`,b.standard,b.route,b.count,b.child_material,b.pNumber,b.number,b.product_name,a.stime,a.ftime)LIKE '%".$select."%' AND b.isexterior = 0 order by a.id desc";
		$res4 = $conn -> query($sql4);
		if ($res4 -> num_rows > 0) {
			$i = 0;
			while ($row4 = $res4 -> fetch_assoc()) {
				// 规格
				if($row4['pNumber'] != ""&&$row4['pNumber'] != Null){
					$arr4[$i]['f7'] = $row4['pNumber'];
					$i++;					
				}
			}
		}
	
		// 工时统计
		$list_data = json_encode($arr);
		$fChild_material = json_encode($arr2);
		$product_name =  json_encode($arr3);
		$pNumber = json_encode($arr4);
		$json = '{"success":true,"rows":' . $list_data . ',"fChild_material":' . $fChild_material . ',"product_name":' . $product_name . ',"pNumber":' . $pNumber . '}';					
	}
	echo $json;
	$conn -> close();		
?>