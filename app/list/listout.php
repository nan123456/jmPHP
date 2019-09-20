<?php
	require("../../conn.php");
	$flag = $_POST["flag"];
//	 $flag = '3';
	
	switch ($flag) {
		//0为查询数据
		case '0' : 
			$id = $_POST["id"];
			$pid = $_POST["pid"];
			$modid = $_POST["modid"];
			// $id = "10281";
			// $modid = $_POST["modid"];
			$sql = "SELECT name,figure_number,child_material,count FROM part WHERE id='".$id."' or modid='".$modid."'";
			$res = $conn->query($sql);
			$data= array();
			$data["status"]="error";
		   if($res->num_rows > 0)
			{
				while($row = $res->fetch_assoc())
				{
					$data['name'] = $row['name'];
					$data['count'] = $row['count'];
					$data['figure_number'] = $row['figure_number'];
					$data['child_material'] = $row['child_material'];
				}
				$data["status"]="success";
			 }
			$json = json_encode($data);
			echo $json;
			break;
			//1为装配出仓
		case '1' : 
			$pid = $_POST["pid"];
			$modid = $_POST["modid"];
			$operator = $_POST["operator"];
			$listname=$_POST["listname"];
			$listid=$_POST["listid"];
			// $pid = '11';
			// $modid = '1000616933';
			// $operator = '12';
			// $listname='456';
			$sql_sea="select * from listout where modid='".$modid."' and listid='".$listid."'";
			$result_sea=$conn->query($sql_sea);
			$data='error';
			if($result_sea->num_rows>0){
				$data='error';
			}
			else{
				$sql = "INSERT INTO listout (pid,modid,operator,time,listname,listid) VALUES ('".$pid."','".$modid."','".$operator."','".date("Y-m-d")."','".$listname."' ,'".$listid."' )";
				$result=$conn->query($sql);
				if($result){
				$data='success';
				}
			}
			$json = json_encode($data);
			echo $json;
			break;
		//3为新建清单
		case '3':
			$listname=$_POST['listname'];
			$description=$_POST['description'];
			$creattime=date("Y/m/d");
			$creatuser=$_POST['creatuser'];
//			$listname='1222';
//			$description='4564';
//			$creattime='';
			$data["state"] = 'error';
			$sql_have="SELECT * FROM list WHERE listname='".$listname."'";
			$res_have=$conn->query($sql_have);
			if($res_have->num_rows>0){
				$data["state"] = 'exist';
			}
			else{
				$sql = "INSERT INTO list(listname,description,creattime,creatuser) VALUES ('".$listname."','".$description."','".$creattime."','".$creatuser."')";
				$res = $conn->query($sql);
				if($res){
					$data["state"] = 'success';
				}
			}
			$json=json_encode($data);
			echo $json;
			break;
			//装配清单完成
		case '4':
			$listid=$_POST['listid'];
			$time=date("Y/m/d H：i：s");
			$finishman=$_POST['finishman'];
//			$listname='1222';
//			$description='4564';
//			$creattime='';
			$data["state"] = 'error';
			$sql_update="update list set isfinish='1',finishtime='".$time."',finishman='".$finishman."' where id='".$listid."'";
			$res_update=$conn->query($sql_update);
			if($res_update){
				$data["state"] = 'success';
			}
			$json=json_encode($data);
			echo $json;
			
			break;
	}
	$conn->close();
?>