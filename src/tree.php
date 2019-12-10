<?php
	require("../conn.php");
//	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	$ret_data=array();
	//设置时区为东八区
	date_default_timezone_set("PRC");
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
				$ret_data["data"][$i]["id"] = 1;
				$i++;
			}
			$ret_data["success"] = 'success';
		}
	$json=json_encode($ret_data);
	echo $json;
	}else if($flag=='project'){
		$type = isset($_POST["type"])?$_POST["type"]:'';
//		$ret_data["type"] = $type;
		$sql = "SELECT id,name,number,pNumber FROM project WHERE isfinish='0' AND type = '$type'";
		$res=$conn->query($sql);
		if($res->num_rows>0){
			$i = 0;
			while($row=$res->fetch_assoc()){
				$ret_data["data"][$i]["id"] = $row["id"];
				$ret_data["data"][$i]["name"] = $row["pNumber"].$row["name"];
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
			$sql = "SELECT id,name,modid,figure_number,pNumber FROM part  WHERE fid = '$id' AND (belong_part='') AND (isexterior=0) and radio = '1'";
		}
		else if($key==3){
			//进行中
			$sql = "SELECT id,name,modid,figure_number,pNumber FROM part  WHERE fid = '$id'  AND (isexterior=0) and isfinish='2'";
		}else if($key==4){
			//已完成
			$sql = "SELECT id,name,modid,figure_number,pNumber FROM part  WHERE fid = '$id'  AND (isexterior=0) and isfinish='1'";
		}else if($key==5){
			//外协
			$sql = "SELECT id,name,modid,figure_number,pNumber FROM part  WHERE fid = '$id' AND (isexterior=1||isexterior=2||isexterior=3)";
		}else if($key==6){
			//所有部件
			$sql = "SELECT id,name,modid,figure_number,pNumber FROM part  WHERE fid = '$id' AND (belong_part='') AND (isexterior=0)";
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
				$ret_data["data"][$i]["pNumber"] = $row["pNumber"];
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
				$sql = "SELECT id,name,modid,figure_number,pNumber FROM part  WHERE fid = '$pid' AND belong_part='$name' and radio = '1' AND (isexterior=0)";
			}else if($key==6){
				$sql = "SELECT id,name,modid,figure_number,pNumber FROM part  WHERE fid = '$pid' AND belong_part='$name' AND (isexterior=0)";
			}
			
			$res=$conn->query($sql);
			if($res->num_rows>0){
				$i = 0;
				while($row=$res->fetch_assoc()){
					$ret_data["data"][$i]["id"] = $row["id"];
					$ret_data["data"][$i]["pid"] = $pid;  //项目id
					$ret_data["data"][$i]["lx"] = 'bj';
					$ret_data["data"][$i]["name"] = $row["name"];
					$ret_data["data"][$i]["pNumber"] = $row["pNumber"];
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
				$sql = "SELECT id,name,modid,figure_number,pNumber FROM part  WHERE fid = '$pid' AND belong_part='$name' and radio = '1' AND (isexterior=0)";
			}else if($key==6){
				$sql = "SELECT id,name,modid,figure_number,pNumber FROM part  WHERE fid = '$pid' AND belong_part='$name' AND (isexterior=0)";
			}

			$res=$conn->query($sql);
			if($res->num_rows>0){
				$i = 0;
				while($row=$res->fetch_assoc()){
					$ret_data["data"][$i]["id"] = $row["id"];
					$ret_data["data"][$i]["pid"] = $pid;  //项目id
					$ret_data["data"][$i]["lx"] = 'bj';
					$ret_data["data"][$i]["name"] = $row["name"];
					$ret_data["data"][$i]["pNumber"] = $row["pNumber"];
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
		$name = isset($_POST["name"])?$_POST["name"]:'';
		$pnumber = isset($_POST["pnumber"])?$_POST["pnumber"]:'';
//		$state = isset($_POST["state"])?$_POST["state"]:'';
		if($name) {
//			$sql = "SELECT id,name,number FROM project WHERE isfinish='$state' AND modid = '$modid'";
//			$sql = "SELECT id,name,number FROM project WHERE  modid = '$modid'";
//			$res=$conn->query($sql);
//			if($res->num_rows>0){
//				while($row=$res->fetch_assoc()){
//					$ret_data["data"][0]["id"] = $row["id"];
//					$ret_data["data"][0]["name"] = $row["number"].$row["name"];
//					$ret_data["data"][0]["number"] = $row["number"];
//					$ret_data["data"][0]["zhname"] = $row["name"];
//					$ret_data["data"][0]["lx"] = 'xm';
//					$ret_data["data"][0]["leaf"] = true;
//				}
//				$ret_data["success"] = 'success';
//			}else {
				$sql="SELECT modid FROM part WHERE name='$name' and pNumber='$pnumber'";
				$res=$conn->query($sql);
				$row=$res->fetch_assoc();
				$modid=$row["modid"];
				$i=0;
				$arr=array();
				function recursion($modid,$pnumber,$i,$arr,$conn){
//					require("../conn.php");
					$asql = "SELECT id,fid,name,modid,figure_number,belong_part,pNumber FROM part  WHERE  modid='$modid' AND pNumber='$pnumber' ";
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
						$arr["data"][$i]["pNumber"] = $arow["pNumber"];
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
	}else if($flag == 'plm_type'){
//		$sql = "SELECT type from project where number in (SELECT DISTINCT product_id FROM plm) GROUP BY type";
		$sql="SELECT DISTINCT product_id FROM plm";
		$res=$conn->query($sql);
		if($res->num_rows>0){
			$i = 0;
			while($row=$res->fetch_assoc()){
				$ret_data["data"][$i]["name"] = $row["product_id"];
				$ret_data["data"][$i]["product_id"] = $row["product_id"];
				$ret_data["data"][$i]["lx"] = 'plm_tree';
//				$ret_data["data"][$i]["leaf"] = false;
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
		$sql = "SELECT id,product_id,label,figure_number,belong_part,hierarchy,material,count,remark FROM plm WHERE belong_part='$number'";
		$res=$conn->query($sql);
		if($res->num_rows>0){
			$i = 0;
			while($row=$res->fetch_assoc()){
				$ret_data["data"][$i]["id"] = $row["id"];
				$ret_data["data"][$i]["product_id"] = $row["product_id"];
				$ret_data["data"][$i]["name"] = $row["figure_number"].$row["label"];
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
		$sql = "SELECT id,product_id,label,figure_number,belong_part,hierarchy,material,count,remark FROM plm WHERE belong_part='$figure_number'";
		$res=$conn->query($sql);
		if($res->num_rows>0){
			$i = 0;
			while($row=$res->fetch_assoc()){
				$ret_data["data"][$i]["id"] = $row["id"];
				$ret_data["data"][$i]["product_id"] = $row["product_id"];
				$ret_data["data"][$i]["name"] = $row["figure_number"].$row["label"];
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
	}else if($flag=='data_project'){
		$type = isset($_POST["type"])?$_POST["type"]:'';
//		$ret_data["type"] = $type;
		$sql = "SELECT id,name,number,pNumber FROM project WHERE  type = '$type'";
		$res=$conn->query($sql);
		if($res->num_rows>0){
			$i = 0;
			while($row=$res->fetch_assoc()){
				$ret_data["data"][$i]["id"] = $row["id"];
				$ret_data["data"][$i]["name"] = $row["pNumber"].$row["name"];
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
	}else if($flag == 'data_type'){
		$sql = "SELECT type from project  GROUP BY type";
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
	}else if($flag=='getPLMchangeTree'){
		$product_id = isset($_POST["product_id"])?$_POST["product_id"]:'';
		$sql="SELECT json from plm_json where product_id = '$product_id' ";
		$res=$conn->query($sql);
		$row=$res->fetch_assoc();
		echo $row['json'];
	}else if($flag=='savePlmJson'){
		$product_id = isset($_POST["product_id"])?$_POST["product_id"]:'';
		$tree_json = isset($_POST["tree_json"])?$_POST["tree_json"]:'';
		$tree_name = isset($_POST["tree_name"])?$_POST["tree_name"]:'';
		$create_user_account = isset($_POST["create_user_account"])?$_POST["create_user_account"]:'';
		json_encode($tree_json);
		$sql0="SELECT id FROM plm_tree_list WHERE product_id='$product_id' AND tree_name='$tree_name'";
		$res0=$conn->query($sql0);
		if($res0->num_rows>0){
			$ret_data["success"] = 'chongfu';
		}else{
			$time=date('Y-m-d H:i:s', time());
			$sql="INSERT INTO `plm_tree_list`(`product_id`,`tree_json`,`tree_name`,`create_user_account`,`create_time`)VALUES('$product_id','$tree_json','$tree_name','$create_user_account','$time');";
			$sql2="SELECT LAST_INSERT_ID() AS 'lastid'";
			$res=$conn->query($sql);
			$res2=$conn->query($sql2);
			$row=$res2->fetch_assoc();
			$ret_data["success"] = 'success';
			$ret_data["data"]["treeid"] =$row['lastid'];
			
		}
		$json=json_encode($ret_data);
		echo $json;
	}else if($flag=='getTreeList'){
		$product_id = isset($_POST["product_id"])?$_POST["product_id"]:'';
		$sql="SELECT id,product_id,tree_json,tree_name,create_user_account,create_time FROM plm_tree_list WHERE product_id='$product_id' ORDER BY id DESC";
		$res=$conn->query($sql);
		if($res->num_rows>0){
			$i=0;
			while($row=$res->fetch_assoc()){
				$sql1="SELECT `name` FROM `user` WHERE account='".$row["create_user_account"]."'";
				$res1=$conn->query($sql1);
				$row1=$res1->fetch_assoc();
				$ret_data["data"][$i]["list_id"] = $row["id"];
				$ret_data["data"][$i]["product_id"] = $row["product_id"];
				$ret_data["data"][$i]["tree_json"] = $row["tree_json"];
				$ret_data["data"][$i]["tree_name"] = $row["tree_name"];
				$ret_data["data"][$i]["user_name"] = $row1["name"];
				$ret_data["data"][$i]["create_time"] = $row["create_time"];
				$i++;
			}
			$ret_data["success"] = 'success';			
		}else{
			$ret_data["success"] = 'null';
		}
		$json=json_encode($ret_data);
		echo $json;
	}else if($flag=='getRechangeTreeData'){
		$list_id = isset($_POST["list_id"])?$_POST["list_id"]:'';
		$sql="SELECT tree_json from plm_tree_list where id = '$list_id' ";
		$res=$conn->query($sql);
		$row=$res->fetch_assoc();
		echo $row['tree_json'];
	}else if($flag=='changePLMjson'){
		$id = isset($_POST["id"])?$_POST["id"]:'';
		$tree_json = isset($_POST["tree_json"])?$_POST["tree_json"]:'';
		$sql="UPDATE plm_tree_list SET tree_json='$tree_json' WHERE id='$id'";
		$sql2="SELECT tree_name,product_id FROM plm_tree_list WHERE id='$id'";
		$res=$conn->query($sql);
		$res2=$conn->query($sql2);
		$row=$res2->fetch_assoc();
		$ret_data["success"] = 'success';
		$ret_data["data"]["tree_name"] =$row['tree_name'];
		$ret_data["data"]["product_id"] =$row['product_id'];
		$json=json_encode($ret_data);
		echo $json;
	}else if($flag=='deletePLMTree'){
		$id = isset($_POST["id"])?$_POST["id"]:'';
		$sql="DELETE FROM plm_tree_list WHERE id='$id'";
		$res=$conn->query($sql);
		$ret_data["success"] = 'success';
		$json=json_encode($ret_data);
		echo $json;
	}else if($flag=='savePlmOperatingData'){
		$treename = isset($_POST["treename"])?$_POST["treename"]:'';
		$treeid = isset($_POST["treeid"])?$_POST["treeid"]:'';
		$product_id = isset($_POST["product_id"])?$_POST["product_id"]:'';
		$username = isset($_POST["username"])?$_POST["username"]:'';
//		$content = isset($_POST["content"])?$_POST["content"]:'';
		$content = isset($_POST["content"]) ? json_decode($_POST["content"],TRUE) : array();
		$time=date('Y-m-d H:i:s', time());
		$arr_length=count($content);
		for($i=0;$i<$arr_length;$i++){
			$content_single=$content[$i];
			$sql="INSERT INTO `plm_operating_data`(`tree_id`,`product_id`,`tree_name`,`content`,`create_user`,`create_time`,`isdelete`)VALUES('$treeid','$product_id','$treename','$content_single','$username','$time','0');";
			$res=$conn->query($sql);
		}
		$ret_data["success"] = 'success';
		$json=json_encode($ret_data);
		echo $json;
	}else if($flag=='getOprateTreeList'){
		$product_id = isset($_POST["product_id"])?$_POST["product_id"]:'';
		$sql="SELECT tree_id,product_id,tree_name,create_time,create_user FROM plm_operating_data WHERE product_id='$product_id' AND isdelete='0' GROUP BY tree_id ORDER BY tree_id DESC";
		$res=$conn->query($sql);
		if($res->num_rows>0){
			$i=0;
			while($row=$res->fetch_assoc()){
				$ret_data["data"][$i]["tree_id"] = $row["tree_id"];
				$ret_data["data"][$i]["user_name"] = $row["create_user"];
				$ret_data["data"][$i]["create_time"] = $row["create_time"];
				$ret_data["data"][$i]["product_id"] = $row["product_id"];
				$ret_data["data"][$i]["tree_name"] = $row["tree_name"];
				$i++;
			}
			$ret_data["success"] = 'success';			
		}else{
			$ret_data["success"] = 'null';
		}
		$json=json_encode($ret_data);
		echo $json;
	}else if($flag=='getOprateListData'){
		$OprateListID = isset($_POST["OprateListID"])?$_POST["OprateListID"]:'';
		$sql="SELECT id,tree_id,product_id,tree_name,content,create_user,create_time FROM plm_operating_data WHERE tree_id='$OprateListID' AND isdelete='0' ORDER BY id";
		$res=$conn->query($sql);
		if($res->num_rows>0){
			$i=0;
			while($row=$res->fetch_assoc()){
				$ret_data["data"][$i]["id"] = $row["id"];
				$ret_data["data"][$i]["tree_id"] = $row["tree_id"];
				$ret_data["data"][$i]["product_id"] = $row["product_id"];
				$ret_data["data"][$i]["tree_name"] = $row["tree_name"];
				$ret_data["data"][$i]["content"] = $row["content"];
				$ret_data["data"][$i]["create_user"] = $row["create_user"];
				$ret_data["data"][$i]["create_time"] = $row["create_time"];
				$i++;
			}
			$ret_data["success"] = 'success';			
		}else{
			$ret_data["success"] = 'null';
		}
		$json=json_encode($ret_data);
		echo $json;		
	}else if($flag=='deletePLMOprateData'){
		$id = isset($_POST["id"])?$_POST["id"]:'';
		$sql="DELETE FROM plm_operating_data WHERE id='$id'";
		$res=$conn->query($sql);
		$ret_data["success"] = 'success';
		$json=json_encode($ret_data);
		echo $json;	
	}else if($flag=="creatPlmJsonFile"){
		$listid = isset($_POST["listid"])?$_POST["listid"]:'';
		$sql="select tree_json from plm_tree_list where id='$listid'";
		$res=$conn->query($sql);
		$row=$res->fetch_assoc();
		$json_char= $row["tree_json"];
		$json=json_decode($json_char);
		var_dump($json);
	}
	
	$conn->close();
//	$json=json_encode($ret_data);
//	echo $json;
?>