<?php
	require("../conn.php");
//	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	$ret_data=array();
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	
	//未审核部分
	if($flag == 'unreview_type'){
		$sql = "SELECT type from project where isfinish='2' GROUP BY type";
		$res=$conn->query($sql);
		if($res->num_rows>0){
			$i = 0;
			while($row=$res->fetch_assoc()){
				$ret_data["data"][$i]["name"] = $row["type"];
				$ret_data["data"][$i]["leaf"] = false;
				$i++;
			}
			$ret_data["success"] = 'success';
		}
	$json=json_encode($ret_data);
	echo $json;
	}else if($flag=='unreview_project'){
		$type = isset($_POST["type"])?$_POST["type"]:'';
//		$ret_data["type"] = $type;
		$sql = "SELECT id,name,number FROM project WHERE isfinish='2' AND type = '$type'";
		$res=$conn->query($sql);
		if($res->num_rows>0){
			$i = 0;
			while($row=$res->fetch_assoc()){
				$ret_data["data"][$i]["id"] = $row["id"];
				$ret_data["data"][$i]["name"] = $row["number"].$row["name"];
				$ret_data["data"][$i]["number"] = $row["number"];
				$ret_data["data"][$i]["zhname"] = $row["name"];
				$ret_data["data"][$i]["lx"] = 'xm';
				$ret_data["data"][$i]["leaf"] = false;
				$i++;
			}
			$ret_data["success"] = 'success';
		}
	$json=json_encode($ret_data);
	echo $json;
	}
	//未完成部分
	else if($flag == 'type'){
		$sql = "SELECT type from project where isfinish='0' GROUP BY type";
		$res=$conn->query($sql);
		if($res->num_rows>0){
			$i = 0;
			while($row=$res->fetch_assoc()){
				$ret_data["data"][$i]["name"] = $row["type"];
				$ret_data["data"][$i]["leaf"] = false;
				$i++;
			}
			$ret_data["success"] = 'success';
		}
	$json=json_encode($ret_data);
	echo $json;
	}else if($flag=='project'){
		$type = isset($_POST["type"])?$_POST["type"]:'';
//		$ret_data["type"] = $type;
		$sql = "SELECT id,name,number FROM project WHERE isfinish='0' AND type = '$type'";
		$res=$conn->query($sql);
		if($res->num_rows>0){
			$i = 0;
			while($row=$res->fetch_assoc()){
				$ret_data["data"][$i]["id"] = $row["id"];
				$ret_data["data"][$i]["name"] = $row["number"].$row["name"];
				$ret_data["data"][$i]["number"] = $row["number"];
				$ret_data["data"][$i]["zhname"] = $row["name"];
				$ret_data["data"][$i]["lx"] = 'xm';
				$ret_data["data"][$i]["leaf"] = false;
				$i++;
			}
			$ret_data["success"] = 'success';
		}
	$json=json_encode($ret_data);
	echo $json;
	}
	//已完成部分
	else if($flag == 'finished_type'){
		$sql = "SELECT type from project where isfinish='1' GROUP BY type";
		$res=$conn->query($sql);
		if($res->num_rows>0){
			$i = 0;
			while($row=$res->fetch_assoc()){
				$ret_data["data"][$i]["name"] = $row["type"];
				$ret_data["data"][$i]["leaf"] = false;
				$i++;
			}
			$ret_data["success"] = 'success';
		}
		$json=json_encode($ret_data);
		echo $json;
	}else if($flag=='finished_project'){
		$type = isset($_POST["type"])?$_POST["type"]:'';
//		$ret_data["type"] = $type;
		$sql = "SELECT id,name,number FROM project WHERE isfinish='1' AND type = '$type'";
		$res=$conn->query($sql);
		if($res->num_rows>0){
			$i = 0;
			while($row=$res->fetch_assoc()){
				$ret_data["data"][$i]["id"] = $row["id"];
				$ret_data["data"][$i]["name"] = $row["number"].$row["name"];
				$ret_data["data"][$i]["number"] = $row["number"];
				$ret_data["data"][$i]["zhname"] = $row["name"];
				$ret_data["data"][$i]["lx"] = 'xm';
				$ret_data["data"][$i]["leaf"] = false;
				$i++;
			}
			$ret_data["success"] = 'success';
		}
		$json=json_encode($ret_data);
		echo $json;
	}else if($flag=='mpart'){  //项目下一级部件
		$id = isset($_POST["id"])?$_POST["id"]:'';
		$name = isset($_POST["name"])?$_POST["name"]:'';
		$number = isset($_POST["number"])?$_POST["number"]:'';
//		$str = explode("#",$number);
//		$projectname = $name.$str[1];
		$key = isset($_POST["key"])?$_POST["key"]:'';
//		$ret_data["type"] = $type;
		if($key==1){
			//1关键部件
			$sql = "SELECT id,name,modid,figure_number FROM part  WHERE fid = '$id' AND (belong_part='') AND (isexterior=0) and radio = '1'";
		}
		else if($key==3){
			//进行中
			$sql = "SELECT id,name,modid,figure_number FROM part  WHERE fid = '$id'  AND (isexterior=0) and isfinish='2'";
		}else if($key==4){
			//已完成
			$sql = "SELECT id,name,modid,figure_number FROM part  WHERE fid = '$id'  AND (isexterior=0) and isfinish='1'";
		}else if($key==5){
			//外协
			$sql = "SELECT id,name,modid,figure_number FROM part  WHERE fid = '$id' AND (isexterior=1||isexterior=2||isexterior=3)";
		}else if($key==6){
			//所有部件
			$sql = "SELECT id,name,modid,figure_number FROM part  WHERE fid = '$id' AND (belong_part='') AND (isexterior=0)";
		}
		
		$res=$conn->query($sql);
		if($res->num_rows>0){
			$i = 0;
			while($row=$res->fetch_assoc()){
				$ret_data["data"][$i]["id"] = $row["id"];
				$ret_data["data"][$i]["pid"] = $id;  //项目id
				$ret_data["data"][$i]["lx"] = 'bj';
				$ret_data["data"][$i]["name"] = $row["name"];
				$ret_data["data"][$i]["figure_number"] = $row["figure_number"];
				$ret_data["data"][$i]["modid"] = $row["modid"];
				$ret_data["data"][$i]["leaf"] = false;
				$i++;
			}
			$ret_data["success"] = 'success';
		}
		$json=json_encode($ret_data);
		echo $json;
	}else if($flag=='part'){ // 部件
		$modid = isset($_POST["modid"])?$_POST["modid"]:'';
		$pid = isset($_POST["pid"])?$_POST["pid"]:'';
		$name = isset($_POST["name"])?$_POST["name"]:'';
		$figure_number = isset($_POST["figure_number"])?$_POST["figure_number"]:'';
		$level = isset($_POST["level"])?$_POST["level"]:'';
		$key = isset($_POST["key"])?$_POST["key"]:'';
		$ret_data["level"] = $figure_number.'&'.$name;
		if($level == 5) {
			if($key==1){
				$sql = "SELECT id,name,modid,figure_number FROM part  WHERE fid = '$pid' AND belong_part='$name' and radio = '1' AND (isexterior=0)";
			}else if($key==6){
				$sql = "SELECT id,name,modid,figure_number FROM part  WHERE fid = '$pid' AND belong_part='$name' AND (isexterior=0)";
			}
			
			$res=$conn->query($sql);
			if($res->num_rows>0){
				$i = 0;
				while($row=$res->fetch_assoc()){
					$ret_data["data"][$i]["id"] = $row["id"];
					$ret_data["data"][$i]["pid"] = $pid;  //项目id
					$ret_data["data"][$i]["lx"] = 'bj';
					$ret_data["data"][$i]["name"] = $row["name"];
//					$ret_data["data"][$i]["figure_number"] = $row["figure_number"];
					$ret_data["data"][$i]["modid"] = $row["modid"];
					$ret_data["data"][$i]["leaf"] = false;
					$i++;
				}
				$ret_data["success"] = 'success';
			}
//			else {
//				$bpart = $figure_number.'&'.$name;
//				$sql = "SELECT id,name,modid,figure_number FROM part  WHERE fid = '$pid' AND belong_part='$bpart' and radio = '$key'";
//				$res=$conn->query($sql);
//				if($res->num_rows>0){
//					$i = 0;
//					while($row=$res->fetch_assoc()){
//						$ret_data["data"][$i]["id"] = $row["id"];
//						$ret_data["data"][$i]["pid"] = $pid;  //项目id
//						$ret_data["data"][$i]["lx"] = 'bj';
//						$ret_data["data"][$i]["name"] = $row["name"];
//						$ret_data["data"][$i]["figure_number"] = $row["figure_number"];
//						$ret_data["data"][$i]["modid"] = $row["modid"];
//						$ret_data["data"][$i]["leaf"] = true;
//						$i++;
//					}
//					$ret_data["success"] = 'success';
//				}
//			}
		
		}else {
			if($key==1){
				$sql = "SELECT id,name,modid,figure_number FROM part  WHERE fid = '$pid' AND belong_part='$name' and radio = '1' AND (isexterior=0)";
			}else if($key==6){
				$sql = "SELECT id,name,modid,figure_number FROM part  WHERE fid = '$pid' AND belong_part='$name' AND (isexterior=0)";
			}

			$res=$conn->query($sql);
			if($res->num_rows>0){
				$i = 0;
				while($row=$res->fetch_assoc()){
					$ret_data["data"][$i]["id"] = $row["id"];
					$ret_data["data"][$i]["pid"] = $pid;  //项目id
					$ret_data["data"][$i]["lx"] = 'bj';
					$ret_data["data"][$i]["name"] = $row["name"];
//					$ret_data["data"][$i]["figure_number"] = $row["figure_number"];
					$ret_data["data"][$i]["modid"] = $row["modid"];
					$ret_data["data"][$i]["leaf"] = false;
					$i++;
				}
				$ret_data["success"] = 'success';
			}
//			else {
//				$bpart = $figure_number.'&'.$name;
//				$sql = "SELECT id,name,modid,figure_number FROM part  WHERE fid = '$pid' AND belong_part='$bpart'";
//				$res=$conn->query($sql);
//				if($res->num_rows>0){
//					$i = 0;
//					while($row=$res->fetch_assoc()){
//						$ret_data["data"][$i]["id"] = $row["id"];
//						$ret_data["data"][$i]["pid"] = $pid;  //项目id
//						$ret_data["data"][$i]["lx"] = 'bj';
//						$ret_data["data"][$i]["name"] = $row["name"];
//						$ret_data["data"][$i]["figure_number"] = $row["figure_number"];
//						$ret_data["data"][$i]["modid"] = $row["modid"];
//						$ret_data["data"][$i]["leaf"] = false;
//						$i++;
//					}
//					$ret_data["success"] = 'success';
//				}
//			}
		}
		$json=json_encode($ret_data);
		echo $json;
	} else if($flag == 'treefilter'){
		$modid = isset($_POST["modid"])?$_POST["modid"]:'';
		$state = isset($_POST["state"])?$_POST["state"]:'';
		if($modid) {
			$sql = "SELECT id,name,number FROM project WHERE isfinish='$state' AND modid = '$modid'";
			$res=$conn->query($sql);
			if($res->num_rows>0){
				while($row=$res->fetch_assoc()){
					$ret_data["data"][0]["id"] = $row["id"];
					$ret_data["data"][0]["name"] = $row["number"].$row["name"];
					$ret_data["data"][0]["number"] = $row["number"];
					$ret_data["data"][0]["zhname"] = $row["name"];
					$ret_data["data"][0]["lx"] = 'xm';
					$ret_data["data"][0]["leaf"] = true;
				}
				$ret_data["success"] = 'success';
			}else {
				$sql="SELECT pNumber FROM part WHERE modid='$modid'";
				$res=$conn->query($sql);
				$row=$res->fetch_assoc();
				$pnumber=$row["pNumber"];
				$i=0;
				$arr=array();
				function recursion($modid,$pnumber,$i,$arr,$conn){
//					require("../conn.php");
					$asql = "SELECT id,fid,name,modid,figure_number,belong_part FROM part  WHERE  modid='$modid' AND pNumber='$pnumber' ";
					$ares=$conn->query($asql);
					if($ares->num_rows>0){
						$arow=$ares->fetch_assoc();
						$arr["data"][$i]["id"] = $arow["id"];
						$arr["data"][$i]["pid"] = $arow["fid"];  //项目id
						$arr["data"][$i]["lx"] = 'bj';
						$arr["data"][$i]["name"] = $arow["name"];
						$arr["data"][$i]["figure_number"] = $arow["figure_number"];
						$arr["data"][$i]["modid"] = $arow["modid"];
						$arr["data"][$i]["belong_part"] = $arow["belong_part"];
						$arr["data"][$i]["leaf"] = true;
						if($arow["belong_part"]){
							$belong_part=$arow["belong_part"];
							$bsql="SELECT modid FROM part WHERE name='$belong_part' AND pNumber='$pnumber' ";
							$bres=$conn->query($bsql);
							$brow=$bres->fetch_assoc();
							$father_modid=$brow["modid"];
							$i++;
							recursion($father_modid,$pnumber,$i,$arr,$conn);
						}else{
							$arr["success"] = 'success';
//							return $arr;
							$json=json_encode($arr);
							echo $json;
						}						
					}else {
						$arr["success"] = 'error';
						$json=json_encode($arr);
						echo $json;						
					}
//					return $arr;
				}
				$ret_data=recursion($modid,$pnumber,$i,$arr,$conn);
				
//				$asql = "SELECT id,fid,name,modid,figure_number FROM part  WHERE  modid='$modid'";
//				$ares=$conn->query($asql);
//				if($ares->num_rows>0){
//					while($arow=$ares->fetch_assoc()){
//						$ret_data["data"][0]["id"] = $arow["id"];
//						$ret_data["data"][0]["pid"] = $arow["fid"];  //项目id
//						$ret_data["data"][0]["lx"] = 'bj';
//						$ret_data["data"][0]["name"] = $arow["name"];
//						$ret_data["data"][0]["figure_number"] = $arow["figure_number"];
//						$ret_data["data"][0]["modid"] = $arow["modid"];
//						$ret_data["data"][0]["leaf"] = true;
//					}
//					$ret_data["success"] = 'success';
//				}else {
//					$ret_data["success"] = 'error';
//				}
			
			}
		}
	}else if($flag == 'plm_type'){
		$sql = "SELECT type from project where number in (SELECT DISTINCT product_id FROM plm) GROUP BY type";
		$res=$conn->query($sql);
		if($res->num_rows>0){
			$i = 0;
			while($row=$res->fetch_assoc()){
				$ret_data["data"][$i]["name"] = $row["type"];
				$ret_data["data"][$i]["leaf"] = false;
				$i++;
			}
			$ret_data["success"] = 'success';
		}
		$json=json_encode($ret_data);
		echo $json;
	}else if($flag=='plm_project'){
		$type = isset($_POST["type"])?$_POST["type"]:'';
//		$ret_data["type"] = $type;
		$sql = "SELECT id,name,number FROM project WHERE number in (SELECT DISTINCT product_id FROM plm) AND type = '$type'";
		$res=$conn->query($sql);
		if($res->num_rows>0){
			$i = 0;
			while($row=$res->fetch_assoc()){
				$ret_data["data"][$i]["id"] = $row["id"];
				$ret_data["data"][$i]["name"] = $row["number"].$row["name"];
				$ret_data["data"][$i]["number"] = $row["number"];
				$ret_data["data"][$i]["zhname"] = $row["name"];
				$ret_data["data"][$i]["lx"] = 'xm';
				$ret_data["data"][$i]["leaf"] = false;
				$i++;
			}
			$ret_data["success"] = 'success';
		}
		$json=json_encode($ret_data);
		echo $json;
	}else if($flag=='plm_mpart'){
		$number = isset($_POST["number"])?$_POST["number"]:'';
		$sql = "SELECT id,product_id,label,figure_number,belong_part,hierarchy,material,count FROM plm WHERE belong_part='$number'";
		$res=$conn->query($sql);
		if($res->num_rows>0){
			$i = 0;
			while($row=$res->fetch_assoc()){
				$ret_data["data"][$i]["id"] = $row["id"];
				$ret_data["data"][$i]["product_id"] = $row["product_id"];
				$ret_data["data"][$i]["name"] = $row["label"];
				$ret_data["data"][$i]["figure_number"] = $row["figure_number"];
				$ret_data["data"][$i]["lx"] = 'plm_part';
				$ret_data["data"][$i]["leaf"] = false;
				$ret_data["data"][$i]["belong_part"] = $row["belong_part"];
				$ret_data["data"][$i]["hierarchy"] = $row["hierarchy"];
				$ret_data["data"][$i]["material"] = $row["material"];
				$ret_data["data"][$i]["count"] = $row["count"];
				$ret_data["data"][$i]["remark"] = $row["remark"];
				$i++;
			}
			$ret_data["success"] = 'success';
		}
		$json=json_encode($ret_data);
		echo $json;
	}else if($flag=='plm_part'){
		$figure_number = isset($_POST["figure_number"])?$_POST["figure_number"]:'';
		$sql = "SELECT id,product_id,label,figure_number,belong_part,hierarchy,material,count FROM plm WHERE belong_part='$figure_number'";
		$res=$conn->query($sql);
		if($res->num_rows>0){
			$i = 0;
			while($row=$res->fetch_assoc()){
				$ret_data["data"][$i]["id"] = $row["id"];
				$ret_data["data"][$i]["product_id"] = $row["product_id"];
				$ret_data["data"][$i]["name"] = $row["label"];
				$ret_data["data"][$i]["figure_number"] = $row["figure_number"];
				$ret_data["data"][$i]["lx"] = 'plm_part';
				$ret_data["data"][$i]["leaf"] = false;
				$ret_data["data"][$i]["belong_part"] = $row["belong_part"];
				$ret_data["data"][$i]["hierarchy"] = $row["hierarchy"];
				$ret_data["data"][$i]["material"] = $row["material"];
				$ret_data["data"][$i]["count"] = $row["count"];
				$ret_data["data"][$i]["remark"] = $row["remark"];
				$i++;
			}
			$ret_data["success"] = 'success';
		}
		$json=json_encode($ret_data);
		echo $json;
	}

	
	$conn->close();
//	$json=json_encode($ret_data);
//	echo $json;
?>