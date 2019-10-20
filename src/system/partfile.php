<?php
	require("../../conn.php");
//	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	$ret_data=array();
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';

	if($flag == "partfile"){
		$id = isset($_POST["id"])?$_POST["id"]:'';
//		$sql = "select route,route_line,notNum,remark,backMark,reason,otime,stime,utime,ctime,photourl from onfile where id = '$id' order by Rid ";
		$sql = "select route,route_line,notNum,remark,backMark,reason,otime,stime,utime,ctime,part_url from onfile where id = '$id' order by Rid ";
		$res=$conn->query($sql);
		if($res->num_rows>0){
			$i = 0;
			while($row=$res->fetch_assoc()){
				$ret_data["data"][$i]["route"] = $row["route"];
				$ret_data["data"][$i]["route_line"] = $row["route_line"];
				$ret_data["data"][$i]["notNum"] = $row["notNum"];
				$ret_data["data"][$i]["remark"] = $row["remark"];
				if($row["backMark"]=="0"){
					$ret_data["data"][$i]["backMark"] = "否";
				}else{
					$ret_data["data"][$i]["backMark"] = "是";
				}
				$arr = array();
				if($row["part_url"]){
					$arr=explode(',',$row["part_url"]);
					$base = "http://47.106.161.130:80/jmmes/app/uploadfiles/";
					foreach($arr as $key => $url){
						$arr[$key] = $base .$url;
					}	
				}
				$ret_data["data"][$i]["photourl"] = $arr;
				$ret_data["data"][$i]["reason"] = $row["reason"];
				$ret_data["data"][$i]["otime"] = $row["otime"];
				$ret_data["data"][$i]["stime"] = $row["stime"];
				$ret_data["data"][$i]["utime"] = $row["utime"];
				$ret_data["data"][$i]["ctime"] = $row["ctime"];
				$i++;
			}
			$ret_data["success"] = 'success';
		}
	}else if($flag=="partdata"){
		$id = isset($_POST["id"])?$_POST["id"]:'';
		$sql = "SELECT figure_number,name,count,standard,radio,child_material,id,child_number,quantity,material,Pmodid,pNumber FROM onfile WHERE id  = '$id'";
		$res=$conn->query($sql);
		if($res->num_rows>0){
			$i = 0;
			while($row=$res->fetch_assoc()){
				$ret_data["data"]["id"] = $row["id"];
				$ret_data["data"]["figure_number"] = $row["figure_number"];
				$ret_data["data"]["name"] = $row["name"];
				$ret_data["data"]["count"] = $row["count"];
				$ret_data["data"]["standard"] = $row["standard"];
				if($row["radio"]=="1"){
					$ret_data["data"]["radio"] = "关键零部件";
				}else{
					$ret_data["data"]["radio"] = "非关键零部件";
				}
				
				$ret_data["data"]["child_material"] = $row["child_material"];
				$ret_data["data"]["child_number"] = $row["child_number"];
				$ret_data["data"]["quantity"] = $row["quantity"];
				$ret_data["data"]["material"] = $row["material"];
				$ret_data["data"]["Pmodid"] = $row["Pmodid"];
				$ret_data["data"]["pNumber"] = $row["pNumber"];
			}
			$ret_data["success"] = 'success';
		}else{
			$ret_data["success"] = 'error';
		}
	}else{
		
	}
		
	
	$conn->close();
	$json = json_encode($ret_data);
	echo $json;
?>