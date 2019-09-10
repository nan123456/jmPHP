<?php
//	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	require ("../../conn.php");
	$arr = array();
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	if ($flag == "Select") {
		$isfinish = isset($_POST["isfinish"]) ? $_POST["isfinish"] : '';
		$list = isset($_POST["list"]) ? $_POST["list"] : '';
		if ($isfinish == '0') {
			$sql = "select modid,figure_number,name,standard,route,count,child_material,number,product_name,remark,routeid,backMark,reason from productionplan WHERE isfinish='0' and Pisfinish='0' and route in $list  ORDER BY backMark DESC,routeid";
			$res = $conn -> query($sql);
			if ($res -> num_rows == TRUE) {
				$i = 0;
				while ($row = $res -> fetch_assoc()) {
					$arr[$i]['modid'] = $row['modid'];
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
					// $arr[$i]['number'] = $number[0] . "#";
					//工单
					// $arr[$i]['product_name'] = $number[0] . $row['product_name'];
					$arr[$i]['number']=$row['number']. "#"; //工单
					$arr[$i]['product_name'] = $row['number'] . $row['product_name']; //产品名称
					//产品名称
					$arr[$i]['remark'] = $row['remark'];
					$arr[$i]['routeid'] = $row['routeid'];
					if ($row['backMark'] == "1") {
						$arr[$i]['backMark'] = "是";
					} else {
						$arr[$i]['backMark'] = "否";
					}
	
					$arr[$i]['reason'] = $row['reason'];
					$i++;
				}
			}
			// 未排产
			if($arr!=''){
				$list_data = json_encode($arr);
				$json = '{"success":"true","rows":' . $list_data . '}';
			}else{
				$list_data = json_encode($arr);
				$json = '{"success":"error","rows":' . $list_data . '}';
			}
		} else if ($isfinish == '2') {
		// 已就工数据列表
	  $sql4 = "select modid,fid,id,figure_number,name,standard,route,count,child_material,number,product_name,remark,routeid,backMark,reason from productionplan WHERE isfinish='2' and route in $list ORDER BY backMark DESC,routeid";
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
		  $arr4[$i]['number']=$row4['number']. "#"; //工单
		  $arr4[$i]['product_name'] = $row4['number'] . $row4['product_name']; //产品名称
	      $arr4[$i]['remark'] = $row4['remark'];
	      $arr4[$i]['station'] = $row4['station'];
	      $arr4[$i]['schedule_date'] = $row4['schedule_date'];
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
//	    $list_data2 = json_encode($arr4);
//	    $FChild_material = json_encode($arr5);
//	    $FStandard = json_encode($arr6);
//	    $json = '{"success":true,"rows2":'.$list_data2.',"FStandard":'.$FStandard.',"FChild_material":'.$FChild_material.'}';
	    	if($arr4!=''){
		    $list_data2 = json_encode($arr4);
		    $FChild_material = json_encode($arr5);
		    $FStandard = json_encode($arr6);
		    $json = '{"success":true,"rows2":'.$list_data2.',"FStandard":'.$FStandard.',"FChild_material":'.$FChild_material.'}';
	    	}else{
	    		$json = '{"success":error}';
	    	}

	    
	  }
	  
	  
	} else if($isfinish == '1'){
			// 已完工数据列表
		  $sql4 = "select modid,fid,id,figure_number,name,standard,route,count,child_material,number,product_name,remark,routeid,backMark,reason from productionplan WHERE isfinish='1' and route in $list ORDER BY backMark DESC,routeid";
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
			  $arr4[$i]['number']=$row4['number']. "#"; //工单
			  $arr4[$i]['product_name'] = $row4['number'] . $row4['product_name']; //产品名称
		      $arr4[$i]['remark'] = $row4['remark'];
		      $arr4[$i]['station'] = $row4['station'];
		      $arr4[$i]['schedule_date'] = $row4['schedule_date'];
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
		
		    	if($arr4!=''){
			    $list_data2 = json_encode($arr4);
			    $FChild_material = json_encode($arr5);
			    $FStandard = json_encode($arr6);
			    $json = '{"success":true,"rows3":'.$list_data2.',"FStandard":'.$FStandard.',"FChild_material":'.$FChild_material.'}';
		    	}else{
		    		$json = '{"success":error}';
		    	}
	
		    
		  }
		  
		  
		}
	}else{
		$isfinish = isset($_POST["isfinish"]) ? $_POST["isfinish"] : '';
		$searchValue = isset($_POST["searchValue"]) ? $_POST["searchValue"] : '';
		$searchCondition = isset($_POST["searchCondition"]) ? $_POST["searchCondition"] : '';
		if ($isfinish == '0') {
			$sql = "select modid,figure_number,name,standard,route,count,child_material,number,product_name,remark,routeid,backMark,reason from productionplan WHERE isfinish='0' and $searchCondition LIKE '%$searchValue%' ORDER BY backMark DESC,routeid";
			$res = $conn -> query($sql);
			if ($res -> num_rows == TRUE) {
				$i = 0;
				while ($row = $res -> fetch_assoc()) {
					$arr[$i]['modid'] = $row['modid'];
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
					$arr[$i]['number']=$row['number']. "#"; //工单
					$arr[$i]['product_name'] = $row['number'] . $row['product_name']; //产品名称
					$arr[$i]['remark'] = $row['remark'];
					$arr[$i]['routeid'] = $row['routeid'];
					if ($row['backMark'] == "1") {
						$arr[$i]['backMark'] = "是";
					} else {
						$arr[$i]['backMark'] = "否";
					}
	
					$arr[$i]['reason'] = $row['reason'];
					$i++;
				}
			}
			// 未排产
			if($arr!=''){
				$list_data = json_encode($arr);
				$json = '{"success":"true","rows":' . $list_data . '}';
			}else{
				$list_data = json_encode($arr);
				$json = '{"success":"error","rows":' . $list_data . '}';
			}
		} else if ($isfinish == '2') {
		// 已就工数据列表
	  $sql4 = "select modid,fid,id,figure_number,name,standard,route,count,child_material,number,product_name,remark,routeid,backMark,reason from productionplan WHERE isfinish='2' and $searchCondition LIKE '%$searchValue%' ORDER BY backMark DESC,routeid";
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
		  $arr4[$i]['number']=$row4['number']. "#"; //工单
		  $arr4[$i]['product_name'] = $row4['number'] . $row4['product_name']; //产品名称
	      $arr4[$i]['remark'] = $row4['remark'];
	      $arr4[$i]['station'] = $row4['station'];
	      $arr4[$i]['schedule_date'] = $row4['schedule_date'];
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
//	    $list_data2 = json_encode($arr4);
//	    $FChild_material = json_encode($arr5);
//	    $FStandard = json_encode($arr6);
//	    $json = '{"success":true,"rows2":'.$list_data2.',"FStandard":'.$FStandard.',"FChild_material":'.$FChild_material.'}';
	    	if($arr4!=''){
		    $list_data2 = json_encode($arr4);
		    $FChild_material = json_encode($arr5);
		    $FStandard = json_encode($arr6);
		    $json = '{"success":true,"rows2":'.$list_data2.',"FStandard":'.$FStandard.',"FChild_material":'.$FChild_material.'}';
	    	}else{
	    		$json = '{"success":error}';
	    	}

	    
	  }
	  
	  
	} else if($isfinish == '1') {
			// 已完工数据列表
		  $sql4 = "select modid,fid,id,figure_number,name,standard,route,count,child_material,number,product_name,remark,routeid,backMark,reason from productionplan WHERE isfinish='1' and and $searchCondition LIKE '%$searchValue%' ORDER BY backMark DESC,routeid";
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
			  $arr4[$i]['number']=$row4['number']. "#"; //工单
			  $arr4[$i]['product_name'] = $row4['number'] . $row4['product_name']; //产品名称
		      $arr4[$i]['remark'] = $row4['remark'];
		      $arr4[$i]['station'] = $row4['station'];
		      $arr4[$i]['schedule_date'] = $row4['schedule_date'];
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
		
		    	if($arr4!=''){
			    $list_data2 = json_encode($arr4);
			    $FChild_material = json_encode($arr5);
			    $FStandard = json_encode($arr6);
			    $json = '{"success":true,"rows3":'.$list_data2.',"FStandard":'.$FStandard.',"FChild_material":'.$FChild_material.'}';
		    	}else{
		    		$json = '{"success":error}';
		    	}
	
		    
		  }
		  
		  
		}
	}
//	echo $sql;
	echo $json;
	$conn -> close();
?>