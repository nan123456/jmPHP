<?php
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	require ("../../conn.php");
	$arr = array();
	$heating = array();//热处理查询数组
	$maching = array();//机械加工查询数组
	$welding = array();//焊接查询数组
	$craft = array();//机械制造查询数组
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	// $flag="Undelivered";
	$sql10="select partsName,productDrawingNumber from heattreatment";
	$res10 = $conn -> query($sql10);
	$j=0;
	while ($row10 = $res10 -> fetch_assoc()){
		$heating[$j]=$row10['partsName'].'_'.$row10['productDrawingNumber'];
		$j++;
	}
	
	$sql11="SELECT pnumber,partdrawnumber FROM machiningtable";
	$res11 = $conn -> query($sql11);
	$k=0;
	while ($row11 = $res11 -> fetch_assoc()){
		$maching[$k]=$row11['pnumber'].'_'.$row11['partdrawnumber'];
		$k++;
	}
	
	$sql12="select workordernumber,partdrawingnumber from weldingtable";
	$res12 = $conn -> query($sql12);
	$l=0;
	while ($row12 = $res12 -> fetch_assoc()){
		$welding[$l]=$row12['workordernumber'].'_'.$row12['partdrawingnumber'];
		$l++;
	}
	
	$sql13="SELECT pnumber,partdrawnumber FROM craftsmanshiptable";
	$res13 = $conn -> query($sql13);
	$m=0;
	while ($row13 = $res13 -> fetch_assoc()){
		$craft[$m]=$row13['pnumber'].'_'.$row13['partdrawnumber'];
		$m++;
	}
	
	
	if ($flag == 'Undelivered') {
		// 获取列表数据
		$sql = "select modid,fid,id,isexterior,figure_number,name,standard,route,count,child_material,number,product_name,remark,routeid,backMark,reason,pNumber from productionplan WHERE isfinish='0' and isexterior='0' and Pisfinish='0' ORDER BY backMark DESC,routeid";
		
		$res = $conn -> query($sql);
		if ($res -> num_rows > 0) {
			$i = 0;
			while ($row = $res -> fetch_assoc()) {
				$arr[$i]['modid'] = $row['modid'];
				$arr[$i]['external']=$row['isexterior'];//外协标志
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
				// $number = explode("#", $row['number']);
				// $arr[$i]['number'] = $number[0] . "#"; //工单
				$arr[$i]['number']=$row['number']; //工单
				$arr[$i]['product_name'] =$row['product_name']; //产品名称
				$arr[$i]['remark'] = $row['remark'];
				$arr[$i]['routeid'] = $row['routeid'];
//				if ($row['backMark'] == "1") {
//					$arr[$i]['backMark'] = "是";
//				} else {
//					$arr[$i]['backMark'] = "否";
//				}
	
				$arr[$i]['reason'] = $row['reason'];
				$arr[$i]['pNumber'] = $row['pNumber'];
				$p_figure_number=$row['pNumber'].'_'.$row['figure_number'];
				//后台返回值1不可勾选，0为无工单号可勾选，2为有工单号有工艺卡可勾选，3为该工艺路线不需要工艺卡可勾选
				if($row['figure_number']==''){
					//无工单号
					$forbidden=0;
				}else if($row['route']=='IA'||$row['route']=='IA1'||$row['route']=='IG'||$row['route']=='L焊'){
					//需要焊接或机械制造工艺卡
					if(in_array($p_figure_number,$welding)||in_array($p_figure_number,$craft)){
						//存在焊接或机械制造工艺卡
						$forbidden=2;
					}else{
						//不存在焊接或机械制造工艺卡
						$forbidden=1;
					}
				}else if($row['route']=='T粗'||$row['route']=='T淬'||$row['route']=='T调'||$row['route']=='T发黑'||$row['route']=='T焊'||$row['route']=='T划线'||$row['route']=='T坡'||$row['route']=='T退'||$row['route']=='T线'||$row['route']=='T正火'||$row['route']=='T装'){
					//需要热处理或机械加工工艺卡
					if(in_array($p_figure_number,$heating)||in_array($p_figure_number,$maching)){
						//存在热处理或机械加工工艺卡
						$forbidden=2;
					}else{
						//不存在热处理或机械加工工艺卡
						$forbidden=1;
					}					
				}else{
					//该工艺路线不需要工艺卡
					$forbidden=3;
				}
				$arr[$i]['forbidden'] = $forbidden;
				$i++;
			}
		}
		// 过滤重复作为下拉checkbox数据
		$sql2 = "SELECT DISTINCT standard from productionplan";
		//DISTINCT通过关键字standard来过滤掉多余的重复记录只保留一条
		$res2 = $conn -> query($sql2);
		if ($res2 -> num_rows > 0) {
			$i = 0;
			while ($row2 = $res2 -> fetch_assoc()) {
				// 开料尺寸
				$arr2[$i]['f6'] = $row2['standard'];
				$i++;
			}
		}
	
		$sql3 = "SELECT DISTINCT child_material from productionplan";
		$res3 = $conn -> query($sql3);
		if ($res3 -> num_rows > 0) {
			$i = 0;
			while ($row3 = $res3 -> fetch_assoc()) {
				// 规格
				$arr3[$i]['f5'] = $row3['child_material'];
				$i++;
			}
		}
	
		// 未排产
		$list_data = json_encode($arr);
		$fStandard = json_encode($arr2);
		$fChild_material = json_encode($arr3);
		$json = '{"success":true,"rows":' . $list_data . ',"fStandard":' . $fStandard . ',"fChild_material":' . $fChild_material . '}';
	}else if($flag =="Delivered") {
		// 已就工数据列表
	  $sql4 = "select modid,fid,id,figure_number,name,standard,route,count,child_material,number,product_name,remark,routeid,backMark,reason from productionplan WHERE isfinish='2' ORDER BY backMark DESC,routeid";
	  $res4 = $conn->query($sql4);
	  if($res4->num_rows > 0 ){
	    $i = 0;
	    while($row4 = $res4->fetch_assoc()){
	      $arr4[$i]['partid'] = $row4['id'];
	      $arr4[$i]['fid'] = $row4['fid'];  
		  $arr4[$i]['modid'] = $row4['modid']; 
		  $arr4[$i]['routeid'] = $row4['routeid']; 
	      $arr4[$i]['figure_number'] = $row4['figure_number']; 
	      $arr4[$i]['name'] = $row4['name'];
	      $arr4[$i]['standard'] = $row4['standard'];
	      $arr4[$i]['count'] = $row4['count'];
		  $arr4[$i]['route'] = $row4['route'];
	      $arr4[$i]['child_material'] = $row4['child_material'];
	      // $number4 = explode("#",$row4['number']);
	      // $arr4[$i]['number'] = $number4[0] . "#";
		  // $arr4[$i]['product_name'] = $number4[0] . $row4['product_name'];
		  $arr4[$i]['number']=$row4['number']; //工单
		  $arr4[$i]['product_name'] = $row4['product_name']; //产品名称
	      $arr4[$i]['remark'] = $row4['remark'];
//	      $arr4[$i]['station'] = $row4['station'];
//	      $arr4[$i]['schedule_date'] = $row4['schedule_date'];
	      $i++;
	    }
	
	    // 规格下拉筛选数据
	    $sql5 = "SELECT DISTINCT child_material FROM part A,route B,project C,workshop_k D WHERE B.id = D.routeid AND A.fid = C.id AND A.modid = D.modid";
	    $res5 = $conn->query($sql5);
	    if($res5->num_rows > 0) {
	      $i = 0;
	      while($row5 = $res5->fetch_assoc()) {
	        $arr5[$i]['F5'] = $row5['child_material'];
	        $i++;
	      }
	    }
	
	    // 开料尺寸下拉筛选数据
	    $sql6 = "SELECT DISTINCT standard FROM part A,route B,project C,workshop_k D WHERE B.id = D.routeid AND A.fid = C.id AND A.modid = D.modid";
	    $res6 = $conn->query($sql6);
	    if($res6->num_rows > 0) {
	      $i = 0;
	      while($row6 = $res6->fetch_assoc()) {
	        $arr6[$i]['F6'] = $row6['standard'];
	        $i++;
	      }
	    }
	
	    // 已就工
	    $list_data2 = json_encode($arr4);
	    $FChild_material = json_encode($arr5);
	    $FStandard = json_encode($arr6);
	    $json = '{"success":true,"rows2":'.$list_data2.',"FStandard":'.$FStandard.',"FChild_material":'.$FChild_material.'}';
	  }
	}else if($flag=='Production'){
		// 已完工数据列表
	  $sql4 = "select modid,fid,id,figure_number,name,standard,route,count,child_material,number,product_name,remark,routeid,backMark,reason from productionplan WHERE isfinish='1' ORDER BY backMark DESC,routeid";
	  $res4 = $conn->query($sql4);
	  if($res4->num_rows > 0 ){
	    $i = 0;
	    while($row4 = $res4->fetch_assoc()){
	      $arr4[$i]['partid'] = $row4['id'];
	      $arr4[$i]['fid'] = $row4['fid'];  
		  $arr4[$i]['modid'] = $row4['modid']; 
		  $arr4[$i]['routeid'] = $row4['routeid']; 
	      $arr4[$i]['figure_number'] = $row4['figure_number']; 
	      $arr4[$i]['name'] = $row4['name'];
	      $arr4[$i]['standard'] = $row4['standard'];
	      $arr4[$i]['count'] = $row4['count'];
		  $arr4[$i]['route'] = $row4['route'];
	      $arr4[$i]['child_material'] = $row4['child_material'];
	      // $number4 = explode("#",$row4['number']);
	      // $arr4[$i]['number'] = $number4[0] . "#";
		  // $arr4[$i]['product_name'] = $number4[0] . $row4['product_name'];
		  $arr4[$i]['number']=$row4['number']; //工单
		  $arr4[$i]['product_name'] = $row4['product_name']; //产品名称
	      $arr4[$i]['remark'] = $row4['remark'];
//	      $arr4[$i]['station'] = $row4['station'];
//	      $arr4[$i]['schedule_date'] = $row4['schedule_date'];
	      $i++;
	    }
	
	    // 规格下拉筛选数据
	    $sql5 = "SELECT DISTINCT child_material FROM part A,route B,project C,workshop_k D WHERE B.id = D.routeid AND A.fid = C.id AND A.modid = D.modid";
	    $res5 = $conn->query($sql5);
	    if($res5->num_rows > 0) {
	      $i = 0;
	      while($row5 = $res5->fetch_assoc()) {
	        $arr5[$i]['F5'] = $row5['child_material'];
	        $i++;
	      }
	    }
	
	    // 开料尺寸下拉筛选数据
	    $sql6 = "SELECT DISTINCT standard FROM part A,route B,project C,workshop_k D WHERE B.id = D.routeid AND A.fid = C.id AND A.modid = D.modid";
	    $res6 = $conn->query($sql6);
	    if($res6->num_rows > 0) {
	      $i = 0;
	      while($row6 = $res6->fetch_assoc()) {
	        $arr6[$i]['F6'] = $row6['standard'];
	        $i++;
	      }
	    }
	
	    // 已完工
	    $list_data2 = json_encode($arr4);
	    $FChild_material = json_encode($arr5);
	    $FStandard = json_encode($arr6);
	    $json = '{"success":true,"rows3":'.$list_data2.',"FStandard":'.$FStandard.',"FChild_material":'.$FChild_material.'}';
	  }
	
			//已完工
//	  $sql7 = "Select * from projectIng";
//	  $res7 = $conn->query($sql7);
//	  if($res7->num_rows > 0 ){
//	    $i = 0;
//	    while($row7 = $res7->fetch_assoc()){
//	      $arr7[$i]['partid'] = $row7['id'];
//	      $arr7[$i]['fid'] = $row7['fid'];  
//	      $arr7[$i]['modid'] = $row7['modid']; 
//	      $arr7[$i]['figure_number'] = $row7['figure_number']; 
//	      $arr7[$i]['name'] = $row7['name'];
//	      $arr7[$i]['standard'] = $row7['standard'];
//	      $arr7[$i]['count'] = $row7['count'];
//	      $arr7[$i]['child_material'] = $row7['child_material'];
//	      // $number7 = explode("#",$row7['number']);
//	      // $arr7[$i]['number'] = $number7[0] . "#";
//	      // $arr7[$i]['product_name'] = $number7[0] . $row7['product_name'];
//		  $arr7[$i]['number']=$row7['number']. "#"; //工单
//		  $arr7[$i]['product_name'] = $row7['number'] . $row7['product_name']; //产品名称
//	      $arr7[$i]['remark'] = $row7['remark'];
//	      $arr7[$i]['station'] = $row7['station'];
//	      $arr7[$i]['schedule_date'] = $row7['schedule_date'];
//	      $i++;
//	    }
//		   // 规格下拉筛选数据
//	    $sql8 = "SELECT DISTINCT child_material FROM part A,route B,project C,workshop_k D WHERE B.id = D.routeid AND A.fid = C.id AND A.modid = D.modid";
//	    $res8 = $conn->query($sql8);
//	    if($res8->num_rows > 0) {
//	      $i = 0;
//	      while($row8 = $res8->fetch_assoc()) {
//	        $arr8[$i]['F5'] = $row8['child_material'];
//	        $i++;
//	      }
//	    }
//	
//	    // 开料尺寸下拉筛选数据
//	    $sql9 = "SELECT DISTINCT standard FROM part A,route B,project C,workshop_k D WHERE B.id = D.routeid AND A.fid = C.id AND A.modid = D.modid";
//	    $res9 = $conn->query($sql9);
//	    if($res9->num_rows > 0) {
//	      $i = 0;
//	      while($row9 = $res9->fetch_assoc()) {
//	        $arr9[$i]['F6'] = $row9['standard'];
//	        $i++;
//	      }
//	    }
//	
//	    // 生产中
//	    $list_data3 = json_encode($arr7);
//	    $FChild_material3 = json_encode($arr8);
//	    $FStandard3 = json_encode($arr9);
//	    $json = '{"success":true,"rows3":'.$list_data3.'}';
//	  }
	}else if ($flag =="Scrap"){
		$sql = "select id,fid,Wid,modid,Pname,pNumber,routeid,unqualified,figure_number,name,child_material,route from fail WHERE unqualified !='0' ORDER BY routeid ASC";
		$res = $conn->query($sql);
		$i = 0;
		while($row = $res->fetch_assoc()){
			$arr[$i]['partid'] = $row['id'];
			$arr[$i]['Wid'] = $row['Wid'];
	      	$arr[$i]['fid'] = $row['fid'];  
     	 	$arr[$i]['modid'] = $row['modid']; 
	      	$arr[$i]['figure_number'] = $row['figure_number']; 
	      	$arr[$i]['name'] = $row['name'];
	      	$arr[$i]['routeid'] = $row['routeid'];
	      	$arr[$i]['count'] = $row['unqualified'];
	      	$arr[$i]['child_material'] = $row['child_material'];
	      	$arr[$i]['pNumber'] =$row['pNumber'];
	      	$arr[$i]['product_name'] = $row['Pname'];
			$arr[$i]['route'] = $row['route'];
	      	$i++;
		}
		$list_data = json_encode($arr);
		$json = '{"success":true,"rows4":' . $list_data . '}';
	}else if($flag=='exterior'){
		// 获取列表数据
		$sql = "select a.modid,a.fid,a.id,a.isexterior,a.figure_number,a.name,a.standard,a.count,a.child_material,a.remark,b.name as product_name,a.isfinish,b.number as pnumber from part a,project b WHERE (a.isexterior='1' or a.isexterior='2' or a.isexterior='3') and (a.fid=b.id) ORDER BY id";
		
		$res = $conn -> query($sql);
		if ($res -> num_rows > 0) {
			$i = 0;
			while ($row = $res -> fetch_assoc()) {
				$arr[$i]['modid'] = $row['modid'];
				$arr[$i]['external']=$row['isexterior'];//外协标志
				$arr[$i]['fid'] = $row['fid'];
				$arr[$i]['partid'] = $row['id'];
				$arr[$i]['figure_number'] = $row['figure_number'];
				//零件图号
				$arr[$i]['name'] = $row['name'];
				//名称
				$arr[$i]['standard'] = $row['standard'];
				//开料尺寸
//				$arr[$i]['route'] = $row['route'];
				//加工工艺路线
				$arr[$i]['count'] = $row['count'];
				//数量
				$arr[$i]['child_material'] = $row['child_material'];
				//规格
				// $number = explode("#", $row['number']);
				// $arr[$i]['number'] = $number[0] . "#"; //工单
				$arr[$i]['number']=$row['pnumber']; //工单
				$arr[$i]['product_name'] = $row['product_name']; //产品名称
				if($row['isfinish']=='1'){
					$arr[$i]['finish'] = '已完成';
				}else{
					$arr[$i]['finish'] = '未完成';
				}
//				$arr[$i]['remark'] = $row['remark'];
//				$arr[$i]['routeid'] = $row['routeid'];
//				if ($row['backMark'] == "1") {
//					$arr[$i]['backMark'] = "是";
//				} else {
//					$arr[$i]['backMark'] = "否";
//				}
	
//				$arr[$i]['reason'] = $row['reason'];
				$i++;
			}
		}
		// 过滤重复作为下拉checkbox数据
		$sql2 = "SELECT DISTINCT standard from productionplan";
		//DISTINCT通过关键字standard来过滤掉多余的重复记录只保留一条
		$res2 = $conn -> query($sql2);
		if ($res2 -> num_rows > 0) {
			$i = 0;
			while ($row2 = $res2 -> fetch_assoc()) {
				// 开料尺寸
				$arr2[$i]['f6'] = $row2['standard'];
				$i++;
			}
		}
	
		$sql3 = "SELECT DISTINCT child_material from productionplan";
		$res3 = $conn -> query($sql3);
		if ($res3 -> num_rows > 0) {
			$i = 0;
			while ($row3 = $res3 -> fetch_assoc()) {
				// 规格
				$arr3[$i]['f5'] = $row3['child_material'];
				$i++;
			}
		}
	
		// 未排产
		$list_data = json_encode($arr);
		$fStandard = json_encode($arr2);
		$fChild_material = json_encode($arr3);
		$json = '{"success":true,"rows4":' . $list_data . ',"fStandard":' . $fStandard . ',"fChild_material":' . $fChild_material . '}';
	
		
	}
	echo $json;
	$conn -> close();
?>