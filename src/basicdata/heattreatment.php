<?php
	header("Access-Control-Allow-Origin: *");
	require_once '../../conn.php';
	
	/*毫秒级的时间戳*/
	function getMillisecond() {
		list($t1, $t2) = explode(' ', microtime());
		return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
	}
	
	$flag = isset($_REQUEST["flag"]) ? $_REQUEST["flag"] : "";
	switch($flag){
		case "heattreatmentDataOne" ://热处理工艺技术要求及检验记录表信息保存
			$treeId = isset($_POST["treeId"]) ? $_POST["treeId"] :"";//表【craftsmanshiptree】的id
			$heattreatmentTableHeader = isset($_POST["heattreatmentTableHeader"]) ? json_decode($_POST["heattreatmentTableHeader"],TRUE) : array();
			$model = isset($_REQUEST["model"]) ? $_REQUEST["model"] : "";
			$temperature = isset($_POST["temperature"]) ? json_decode($_POST["temperature"],TRUE) : array();
			$time = isset($_POST["time"]) ? json_decode($_POST["time"],TRUE) : array();
//			$otherData = isset($_POST["otherData"]) ? json_decode($_POST["otherData"],TRUE) : array();
//			$selectvalue = isset($_POST["selectvalue"]) ? json_decode($_POST["selectvalue"],TRUE) : array();
			$otherData = isset($_POST["otherData"]) ? $_POST["otherData"] :"";
			$selectvalue = isset($_POST["selectvalue"]) ? $_POST["selectvalue"] :"";
			//返回数据
			$returnData = array(
				"state" => "success",
				"message" => "",
				"sql" => ""				
			);
			$temperature_string='';
			for($i=0;$i<count($temperature);$i++){
				$temperature_string=$temperature_string.$temperature[$i].'|';
			}
			$time_string='';
			for($j=0;$j<count($time);$j++){
				$time_string=$time_string.$time[$j].'|';
			}
			$otherData_string = $otherData;;
			$selectvalue_string = $selectvalue;						
			//保存单一信息
			if(count($heattreatmentTableHeader) > 0){
				$sql  = "INSERT INTO `heattreatment`(weldingtree_id,model,productName,ownPartName,partsName,productDrawingNumber,ownPartDrawingNumber";
				$sql .= ",partsDrawingNumber,ctime) VALUES('".$treeId."','".$model."','".$heattreatmentTableHeader["productName"]."','".$heattreatmentTableHeader["ownPartName"]."'";
				$sql .= ",'".$heattreatmentTableHeader["partsName"]."','".$heattreatmentTableHeader["productDrawingNumber"]."','".$heattreatmentTableHeader["ownPartDrawingNumber"]."'";
				$sql .= ",'".$heattreatmentTableHeader["partsDrawingNumber"]."','".time()."')";
				$conn->query($sql);
				
				$sql2="select id from heattreatment where productDrawingNumber='".$heattreatmentTableHeader["productDrawingNumber"]."' and partsName='".$heattreatmentTableHeader["partsName"]."' order by id desc limit 1";
				$res2=$conn->query($sql2);
				$row2 =$res2->fetch_assoc();
				
				$sql3="INSERT INTO heattreatbody(heattreatment_id,model,temperature,time,otherdata,selectvalue)VALUES('".$row2["id"]."','$model','$temperature_string','$time_string','".$otherData_string."','".$selectvalue_string."')";
				$res3=$conn->query($sql3);
			}else{
				$returnData["state"] = "failure";
				$returnData["message"] = "主要数据为空";
			}
			
			$json = json_encode($returnData);
			echo $json;
			break;
		//获取热处理信息	
		case "getHeattreatmentInfoData":
			//接收数据
			$contactId = isset($_GET["contactID"]) ? $_GET["contactID"] : "";

			//返回数据
			$returnData = array(
				"state" => "success",
				"message" => "",
				"data" => array(
					"craftsmanshipTableHeader" => array()
				)
			);

			//主表信息
			$sql = "SELECT * FROM `heattreatment` WHERE `id`='".$contactId."'";
			$result = $conn->query($sql);
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					//头部信息
					$returnData["data"]["craftsmanshipTableHeader"]["contactId"] = $row["id"];
					$returnData["data"]["craftsmanshipTableHeader"]["productName"] = $row["productName"];
					$returnData["data"]["craftsmanshipTableHeader"]["ownPartName"] = $row["ownPartName"];
					$returnData["data"]["craftsmanshipTableHeader"]["partsName"] = $row["partsName"];
					$returnData["data"]["craftsmanshipTableHeader"]["productDrawingNumber"] = $row["productDrawingNumber"];
					$returnData["data"]["craftsmanshipTableHeader"]["ownPartDrawingNumber"] = $row["ownPartDrawingNumber"];
					$returnData["data"]["craftsmanshipTableHeader"]["partsDrawingNumber"] = $row["partsDrawingNumber"];
					$returnData["data"]["value"] = $row["model"];

				}
			}
			$sql2="SELECT temperature,time,otherdata,selectvalue FROM `heattreatbody` WHERE `heattreatment_id`='".$contactId."'";
			$result2 = $conn->query($sql2);
			$row2 = $result2->fetch_assoc();
			$returnData["temperature"]=$row2["temperature"];
			$returnData["time"]=$row2["time"];
			$returnData["otherData"]=$row2["otherdata"];
			$returnData["selectvalue"]=$row2["selectvalue"];
			$json = json_encode($returnData);
			echo $json;
			break;
		//复制
		case "copyHeattreatment":
			//接收数据
			$relateId = isset($_GET["contactId"]) ? $_GET["contactId"] : "";//表【weldingtree】的id
			//返回给前端的数据
			$returnData = array(
				"state" => "success",
				"message" => ""
			);
			//获取旧时间，复制修改函数REPLACE需要定值修改
			$sql = "select `weldingtree_id`,`ctime` from `heattreatment` where id='".$relateId."'";
			$result = $conn->query($sql);
			$row = $result->fetch_assoc();
			$oldTime = $row["ctime"];
			//复制首表信息，返回自增id
			$sql = "INSERT INTO heattreatment ( weldingtree_id,model,productName,ownPartName,partsName,productDrawingNumber,ownPartDrawingNumber,partsDrawingNumber,ctime )";
			$sql .= "SELECT weldingtree_id,model,productName,ownPartName,partsName,productDrawingNumber,ownPartDrawingNumber,partsDrawingNumber,REPLACE(`ctime`,'".$oldTime."','".time()."') FROM heattreatment WHERE `id` = '".$relateId."'";
			$autoIncrementId = $conn->query($sql) ? $conn->insert_id : "";//获取成功插入后的id
			
			if(!empty($autoIncrementId)){
				$returnData["message"] = "保存成功";
			}else{
				$returnData["state"] = "failure";
				$returnData["message"] = "自增值ID为空";
			}
			$json = json_encode($returnData);
			echo $json;
			break;
		//删除
		case "deleteHeattreatment":
			//接收数据
			$contactId = isset($_GET["contactId"]) ? $_GET["contactId"] : "";
			
			//返回数据
			$returnData = array(
				"state" => "success",
				"message" => ""				
			);

			//删除表头表尾
			$sql = "DELETE FROM `heattreatment` WHERE `id`='".$contactId."'";
			if(!$conn->query($sql)){
				$returnData["state"] = "fail";
				$returnData["message"] .= "表头记录删除失败";
			}
			$json = json_encode($returnData);
			echo $json;
			break;
	}
?>