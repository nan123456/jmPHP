<?php
	require("../../conn.php");
	$ret_data = array();
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	if($flag=='getCheckList'){
		$id = isset($_POST["id"])?$_POST["id"]:'';
		$sql = "select `id`,`e_number`,`e_name`,`e_type`,`year_month`,`workshop`,`group` from `equipment_check_list` where `e_id`='$id'";
		$res = $conn->query($sql);
		if($res->num_rows>0){
			$i = 0;
			$ret_data["success"]= 'success';
			while($row = $res->fetch_assoc()){
				$ret_data["data"][$i]["id"] = $row["id"];
				$ret_data["data"][$i]["e_number"] = $row["e_number"];
				$ret_data["data"][$i]["e_name"] = $row["e_name"];
				$ret_data["data"][$i]["e_type"] = $row["e_type"];
				$ret_data["data"][$i]["year_month"] = $row["year_month"];
				$ret_data["data"][$i]["workshop"] = $row["workshop"];
				$ret_data["data"][$i]["group"] = $row["group"];
				$i++;
			}
		}
		$conn->close();			
	}else if($flag=='saveNewCheckTable'){
		$e_id = isset($_POST["e_id"])?$_POST["e_id"]:'';
		$e_user = isset($_POST["e_user"])?$_POST["e_user"]:'';
		$group = isset($_POST["group"])?$_POST["group"]:'';
		$year_month = isset($_POST["year_month"])?$_POST["year_month"]:'';
		$sql1 = "select `id` from `equipment_check_list` where `e_id`='$e_id' and `year_month`='$year_month'";
		$res1 = $conn->query($sql1);
		if($res1->num_rows>0){
			$ret_data['res']='repetitive';
		}else{
			$sql2="SELECT type,typenumber,number,workcenter from equipment where id='$e_id'";
			$res2 = $conn->query($sql2);
			if($res2->num_rows>0){
				$row2 = $res2->fetch_assoc();
				$e_name=$row2["type"];//设备类型
				$e_type=$row2["typenumber"];//设备型号
				$e_number=$row2["number"];//设备编号
				$workshop=$row2["workcenter"];//车间
				$state_time='{"running":"","fault":"","other":"","mantain":"","plan":""}';
				$sqli="SELECT content FROM `equipment_table_ template` WHERE `name`='".$e_name."'";
				$resulti=$conn->query($sqli);
				$rowi=$resulti->fetch_assoc();
				$json=$rowi["content"];
				$content_json=(array) json_decode($json,true);
				$content_arr=array();
				$char="[";
				for($i=0;$i<11;$i++){
					$z=$i+1;
					$j="content".$z;
					$k="method".$z;
					$content_arr[$i]='{"'.$j.'":"'.$content_json[$j].'","'.$k.'":"'.$content_json[$k].'","1":"","2":"","3":"","4":"","5":"","6":"","7":"","8":"","9":"","10":"","11":"","12":"","13":"","14":"","15":"","16":"","17":"","18":"","19":"","20":"","21":"","22":"","23":"","24":"","25":"","26":"","27":"","28":"","29":"","30":"","31":""}';
					$char=$char.$content_arr[$i].',';
				}
				$char=substr($char, 0, -1);
				$check_content=$char.']';
				$sql3="insert into equipment_check_list(e_id,`year_month`,e_name,workshop,`group`,e_number,e_type,e_user,state_time,check_content)values('$e_id','$year_month','$e_name','$workshop','$group','$e_number','$e_type','$e_user','$state_time','$check_content')";
				$result3=$conn->query($sql3);
				$ret_data['res']='success';
			}else{
				$ret_data['res']='error';
			}
		}
		$conn->close();
	}
	$json = json_encode($ret_data);
	echo $json;
?>