<?php
	require("../conn.php");
	$flag = $_POST["flag"];
//	 $flag = '1';
	switch ($flag) {
		case '1':
		    $modid=$_POST['modid'];
//		    $modid='1000604220';
		    $sql_name="SELECT * FROM part where modid='".$modid."'";
			$result = $conn->query($sql_name);
			if($result -> num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					$arr['partname'] = $row['name'];
					$name=$row['name'];
					$count=$row['count'];
					$figure_number=$row['figure_number'];
					$isexterior=$row['isexterior'];
					$Wisfinish=$row['isfinish'];//主要用于外协的判断
				}
			}
			if($isexterior=="1"){
				$sql_state="SELECT * FROM workshop_k where modid='".$modid."'";
				$res_state = $conn->query($sql_state);
				if($res_state -> num_rows > 0) {
					$i = 0;
					while($row_state = $res_state->fetch_assoc()) {
						$arr[$i]['route'] = '外协W';
//						$arr[$i]['state']=$row_state['isfinish'];
						$arr[$i]['name']=$row_state['name'];
						$arr[$i]['todocount']=$row_state['todocount'];//待完工数量
						$arr[$i]['inspectcount']=$row_state['inspectcount'];//待检验数量
						$inspectcount=$row_state['inspectcount'];
						$arr[$i]['count']=$count;//总数量
						$arr[$i]['notNum']=$row_state['notNum'];//返工次数
						$arr[$i]['unqualified']=$row_state['unqualified'];//不合格数量
						$unqualified=$row_state['unqualified'];
						$arr[$i]['finishcount']=$count-$row_state['todocount'];//完工数量
						$dumping=$row_state['dumping'];//报废数量
						$reviews=$row_state['reviews'];//报废数量
					}
				}
				if($Wisfinish=='0'){
					$arr[$i]['flag']="未进行";
					$arr[$i]['state']='no';
					
				}
				else if($Wisfinish=='1'){
					$arr[$i]['flag']="已完成";
					$arr[$i]['state']='0';
					$arr[$i]['qualified']=$count-$unqualified-$dumping;
				}
				else{
					$arr[$i]['flag']="进行中";
					$arr[$i]['state']='1';
					$arr[$i]['qualified']=$inspectcount;
					$arr[$i]['qualified']=$count-$inspectcount-$unqualified-$dumping-$reviews;
				}
			}
			else{
				$arr['state']="nodata";
				$sql_all="SELECT * FROM route where modid='".$modid."' ORDER BY id ";
				$res = $conn->query($sql_all);
				if($res -> num_rows > 0) {
	//				$arr['state']="data";
					$i = 0;
					while($row = $res->fetch_assoc()) {
						$arr[$i]['route'] = $row['route'];
						$arr[$i]['id'] = $row['id'];
						$sql_state="SELECT * FROM workshop_k where routeid='".$row['id']."'";
							$res_state = $conn->query($sql_state);
							if($res_state -> num_rows > 0) {
								while($row_state = $res_state->fetch_assoc()) {
									$arr[$i]['state']=$row_state['isfinish'];
									$arr[$i]['name']=$row_state['name'];
									$arr[$i]['todocount']=$row_state['todocount'];//待完工数量
									$arr[$i]['inspectcount']=$row_state['inspectcount'];//待检验数量
									$arr[$i]['count']=$count;//总数量
									$arr[$i]['notNum']=$row_state['notNum'];//返工次数
									$arr[$i]['unqualified']=$row_state['unqualified'];//不合格数量
									$arr[$i]['finishcount']=$count-$row_state['todocount'];//完工数量
									$arr[$i]['dumping']=$row_state['dumping'];//报废数量
								}
							}
						if($row['isfinish']=="0"){
							$arr[$i]['flag']="未进行";
							$arr[$i]['state']='no';
							$arr[$i]['count']=$count;//总数量
							$arr[$i]['name']=$name;
						}
						else if($row['isfinish']=="1")
						{
							$arr[$i]['flag']="已完成";
							$arr[$i]['state']='0';
							$arr[$i]['count']=$count;//总数量
							$arr[$i]['name']=$name;
							$arr[$i]['qualified']=$count-$row_state['unqualified']-$arr[$i]['dumping'];
						}
						else{
							$arr[$i]['flag']="进行中";
							$arr[$i]['state']='1';
							$arr[$i]['qualified']=$count-$row_state['todocount']-$arr[$i]['inspectcount']-$row_state['unqualified']-$row_state['dumping']-$row_state['reviews'];
	//				
							
						}
						$i++;
					}
				}
			}
//				
			$sql_url="SELECT part_url FROM part where modid='".$modid."'";
			$result_url = $conn->query($sql_url);
			if($result_url -> num_rows > 0) {
				while($row_url = $result_url->fetch_assoc()) {
					$arrphoto= $row_url['part_url'];
				}
				$arr['photo'] = explode(',',$arrphoto); 
			}
			else{
				$arr['photo'] ='';
			}
			$json = json_encode($arr);
//			$json_url = json_encode($photo);
			echo $json;
//			echo $json_url;
			break;
	}
	$conn->close();
?>