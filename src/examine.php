<?php
	require ("../conn.php");
//	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	$ret_data = array();
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
//	if($flag == "Select"){
////		$sql = "select Wmodid,station,name,route,count,figure_number,radio,photourl,inspectcount from test where inspectcount !='0' ORDER BY ftime desc ";
//		$sql="select Wmodid,station,name,utime,photourl,route,count,figure_number,radio,inspectcount,pNumber from test where isfinish = '1'";
//		$res=$conn->query($sql);
//		if($res->num_rows>0){
//			$i = 0;
//			while($row=$res->fetch_assoc()){
//				$ret_data["data"][$i]["number"] = $row["Wmodid"];
//				$ret_data["data"][$i]["partName"] = $row["name"];
//				$ret_data["data"][$i]["processName"] = $row["station"];
//				$ret_data["data"][$i]["route"] = $row["route"];
//				$ret_data["data"][$i]["count"] = $row["inspectcount"];
//				$ret_data["data"][$i]["figure_number"] = $row["figure_number"];
//				$partdrawnumber = $row["figure_number"];
//				$pnumber=$row["pNumber"];
//				//使用部件图号查询制造工艺卡信息
//				$sql1 = "select craftsmanshiptree_id,id from craftsmanshiptable where partdrawnumber = '$partdrawnumber' and pnumber='$pnumber'";//使用部件图号查询制造工艺卡信息
//				$res1 = $conn ->query($sql1);
//				$row1 = $res1 -> fetch_assoc();
//				$ret_data["data"][$i]["contactId"] = isset( $row1["id"]) ?  $row1["id"] : '';
//				$ret_data["data"][$i]["selectedTreeNode"] = $row1["craftsmanshiptree_id"];
//				if($ret_data["data"][$i]["contactId"]==''){
//					$ret_data["data"][$i]["show_btn"] = false;
//				}else{
//					$ret_data["data"][$i]["show_btn"] = true;
//				}
//				//使用部件图号查询焊接工艺卡信息
//				$sql2 = "select id from weldingtable where partdrawingnumber = '$partdrawnumber' and workordernumber='$pnumber'";//使用部件图号查询制造工艺卡信息
//				$res2 = $conn ->query($sql2);
//				$row2 = $res2 -> fetch_assoc();
//				$ret_data["data"][$i]["weldingcontactId"] = isset( $row2["id"]) ? $row2["id"] : '';
//				if($ret_data["data"][$i]["weldingcontactId"]==''){
//					$ret_data["data"][$i]["show_btn1"] = false;
//				}else{
//					$ret_data["data"][$i]["show_btn1"] = true;
//				}
//				
//				$ret_data["data"][$i]["photourl"] = $row["photourl"];
//				if($row["radio"]==2){
//					$ret_data["data"][$i]["radio"] = "非关键零部件";
//				}else{
//					$ret_data["data"][$i]["radio"] = "关键零部件";
//				}
//				$i++;
//			}
//			$ret_data["success"] = 'success';
//		}
//	}
	if($flag == "Test"){
		$result = $_POST["result"];
		$Number = $_POST["Number"];
		$person = $_POST["person"];
		$type   = $_POST["type"];
		$sql = "UPDATE workshop_k SET isfinish='".$result."',uuser = '".$person."',test_type = '".$type."' WHERE isfinish = '1' and modid='".$Number."'";
		$res=$conn->query($sql);
		if($res->num_rows>0){
			
			}
		$ret_data["success"] = 'success';
	}else if($flag=='State'){
		$state = $_POST["state"];
		$pnumber = $_POST["selectvalue"];
		$sql_p="select name from project where pNumber='$pnumber'";
		$res_p=$conn->query($sql_p);
		$row_p=$res_p->fetch_assoc();
		$project=$pnumber.$row_p["name"];
		if($state==1){
			//未检验
			$sql = "select Wmodid,station,name,utime,finishurl AS photourl,route,count,figure_number,radio,pNumber from test where pNumber='$pnumber' and isfinish = '1' order by ftime desc";
		}else if($state==4){
			//不合格
			$sql = "select Wmodid,station,name,utime,unqualifiedurl AS photourl,route,unqualified as count,figure_number,radio,pNumber from test where pNumber='$pnumber' and unqualified>'0' order by utime desc";
		}else if($state==3){
			//合格
			$sql = "select Wmodid,station,name,utime,inspecturl AS photourl,route,(count-unqualified-reviews-dumping) as count,figure_number,radio,pNumber from test where pNumber='$pnumber' and isfinish = '3' and (count-unqualified-reviews-dumping)>'0' order by utime desc";
		}
		$res=$conn->query($sql);
		if($res->num_rows>0){
			$i = 0;
			while($row=$res->fetch_assoc()){
				$ret_data["data"][$i]["number"] = $row["Wmodid"];
				$ret_data["data"][$i]["partName"] = $row["name"];
				$ret_data["data"][$i]["checkDate"] = $row["utime"];
				$ret_data["data"][$i]["route"] = $row["route"];
				$ret_data["data"][$i]["count"] = $row["count"];
				$ret_data["data"][$i]["figure_number"] = $row["figure_number"];
				$ret_data["data"][$i]["project"] = $project;
				$partdrawnumber = $row["figure_number"];
				$pnumber=$row["pNumber"];
			if($row["pNumber"]!=''){
				//使用部件图号查询制造工艺卡信息
				$sql1 = "select craftsmanshiptree_id,id from craftsmanshiptable where partdrawnumber = '$partdrawnumber' and pnumber='$pnumber'";//使用部件图号查询制造工艺卡信息
				$res1 = $conn ->query($sql1);
				$row1 = $res1 -> fetch_assoc();
				$ret_data["data"][$i]["contactId"] = isset( $row1["id"]) ?  $row1["id"] : '';
				$ret_data["data"][$i]["selectedTreeNode"] = $row1["craftsmanshiptree_id"];
				if($ret_data["data"][$i]["contactId"]==''){
					$ret_data["data"][$i]["show_btn"] = false;
				}else{
					$ret_data["data"][$i]["show_btn"] = true;
				}
				//使用部件图号查询焊接工艺卡信息
				$sql2 = "select id from weldingtable where partdrawingnumber = '$partdrawnumber' and workordernumber='$pnumber'";//使用部件图号查询焊接工艺卡信息
				$res2 = $conn ->query($sql2);
				$row2 = $res2 -> fetch_assoc();
				$ret_data["data"][$i]["weldingcontactId"] = isset( $row2["id"]) ? $row2["id"] : '';
				if($ret_data["data"][$i]["weldingcontactId"]==''){
					$ret_data["data"][$i]["show_btn1"] = false;
				}else{
					$ret_data["data"][$i]["show_btn1"] = true;
				}
				//使用部件图号查询热处理工艺卡信息
				$sql3 = "select weldingtree_id,id from heattreatment where productDrawingNumber = '$partdrawnumber' and partsName='$pnumber'";//使用部件图号查询热处理工艺卡信息
				$res3 = $conn ->query($sql3);
				$row3 = $res3 -> fetch_assoc();
				$ret_data["data"][$i]["heatId"] = isset( $row3["id"]) ?  $row3["id"] : '';
				$ret_data["data"][$i]["selectedTreeNode2"] = $row3["weldingtree_id"];
				if($ret_data["data"][$i]["heatId"]==''){
					$ret_data["data"][$i]["show_btn2"] = false;
				}else{
					$ret_data["data"][$i]["show_btn2"] = true;
				}
				//使用部件图号查询加工工艺卡信息
				$sql4 = "select craftsmanshiptree_id,id from machiningtable where partdrawnumber = '$partdrawnumber' and pnumber='$pnumber'";//使用部件图号查询加工工艺卡信息
				$res4 = $conn ->query($sql4);
				$row4 = $res4 -> fetch_assoc();
				$ret_data["data"][$i]["machingId"] = isset( $row4["id"]) ?  $row4["id"] : '';
				$ret_data["data"][$i]["selectedTreeNode3"] = $row4["craftsmanshiptree_id"];
				if($ret_data["data"][$i]["machingId"]==''){
					$ret_data["data"][$i]["show_btn3"] = false;
				}else{
					$ret_data["data"][$i]["show_btn3"] = true;
				}
			}
//				$img_arr=explode(',',$row["photourl"]);
				$ret_data["data"][$i]["photourl"] = $row["photourl"];
//				$ret_data["data"][$i]["show_img"] = isset( $row["photourl"]) ? true : false;
				if($row["radio"]==2){
					$ret_data["data"][$i]["radio"] = "非关键零部件";
				}else{
					$ret_data["data"][$i]["radio"] = "关键零部件";
				}
				$i++;
			}
			$ret_data["success"] = 'success';
		}else{
			$ret_data["success"] = 'error';
		}
	}else if($flag='getProject'){
		$i=0;
		$sql="select name,pNumber from project";
		$res=$conn->query($sql);
		while($row=$res->fetch_assoc()){
			$ret_data["data"][$i]["label"]=$row["pNumber"].$row["name"];
			$ret_data["data"][$i]["value"]=$row["pNumber"];
			$i++;
		}		
	}
	$conn->close();
	$json = json_encode($ret_data);
	echo $json;
?>