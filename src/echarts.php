<?php
	require("../conn.php");
//	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	class Alteration{  
	    public $name;  
	    public $value;  
	}  
	
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	$data = array();
	if($flag == "selectbox"){
		$sql = "SELECT name,pNumber FROM project";
		$res=$conn->query($sql);
		if($res->num_rows>0){
			$data["success"] = 'success';
			$i = 0;
			while($row=$res->fetch_assoc()){
				$data["data"][$i]["lable"] = $row["name"];
				$data["data"][$i]["value"] = $row["pNumber"];
				$i++;
			}
		}else {
			$data["success"] = 'error';
		}
	}else{
		$pNumber = isset($_POST["pNumber"])?$_POST["pNumber"]:'';
		$sql = "select count(a.name) as count from workshop_k a,route b where notNum > 0  AND a.routeid = b.id AND b.pNumber = '".$pNumber."' UNION select count(a.name) as count from workshop_k a,route b where a.routeid = b.id AND b.pNumber = '".$pNumber."'";
		$res=$conn->query($sql);
		if($res->num_rows>0){
			$i=0;
			while($row=$res->fetch_assoc()){
				$alter = new Alteration();
				if($i==0){
					$alter->name = "不合格次数";
					$alter->value = intval($row['count']);
					$un = $row['count'];
					$i++;
				}else{
					$alter->name = "合格次数";
					$alter->value = intval($row['count']-$un);
				}  
				$data[] = $alter;
			}
		}
	}
	echo json_encode($data);
?>