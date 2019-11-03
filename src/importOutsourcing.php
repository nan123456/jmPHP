<?php
	require("../conn.php");
//	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	set_time_limit(0); //使无响应时间限制
	$ret_data = array();
	$ret_data["filename"] = isset($_POST["filename"])?$_POST["filename"] : '';
	$ret_data["ftype"]=substr($ret_data["filename"],-3,3);
	if($ret_data["ftype"] == 'xls' || $ret_data["ftype"] == 'XLS'){
		//加载PHPExcel类库
		require_once("../PHPExcel.php");
		require_once("../PHPExcel/IOFactory.php");
		require_once("../PHPExcel/Reader/Excel5.php");
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$Excel = $_FILES['file'];
		$filetmp = $Excel['tmp_name'];
		$objReader->setReadDataOnly(true);//当数据格式有特殊字符时，使用该方法读取相应的单元格的数据，忽略任何格式的信息。
		$objPHPExcel = $objReader->load($filetmp);
		$sheet = $objPHPExcel->getSheet(0);  // 读取第一个sheet表
		$highestRow = $sheet->getHighestRow(); // 取得总行数
		$highestRow=intval($highestRow);
		$ret_data["highestRow"]=$highestRow;
		$i=0;
		for($rw=2;$rw<=$highestRow;$rw++){
			$ret_data["max"]=$rw;
			$l = $objPHPExcel->getActiveSheet()->getCell("L".$rw)->getValue();//获取L(工程项目-表头)列的值
			$q = $objPHPExcel->getActiveSheet()->getCell("Q".$rw)->getValue();//获取Q(物料名称)列的值
			//去除读取的数据头尾的空格
	        $l = trim(html_entity_decode($l),chr(0xc2).chr(0xa0));
	        $q = trim(html_entity_decode($q),chr(0xc2).chr(0xa0));
	        if($l!==''||$q!==''){
	        	$arr=explode("#",$l);
	        	$l=$arr[0]."#";
	        	$sql = "select a.modid,a.fid,a.id,a.isexterior,a.figure_number,a.name,a.standard,a.count,a.child_material,a.remark,b.name as product_name,a.isfinish,b.number as p_number,a.pNumber from part a,project b WHERE (a.isexterior='1' or a.isexterior='2' or a.isexterior='3') and (a.fid=b.id)  AND a.pNumber = '".$l."' AND a.`name`='".$q."' ORDER BY id DESC";
		
				$res = $conn -> query($sql);
				if ($res -> num_rows > 0) {
					while ($row = $res -> fetch_assoc()) {
						$ret_data['row'][$i]['modid'] = $row['modid'];
						$ret_data['row'][$i]['external']=$row['isexterior'];//外协标志
						$ret_data['row'][$i]['fid'] = $row['fid'];
						$ret_data['row'][$i]['partid'] = $row['id'];
						$ret_data['row'][$i]['figure_number'] = $row['figure_number'];
						//零件图号
						$ret_data['row'][$i]['name'] = $row['name'];
						//名称
						$ret_data['row'][$i]['standard'] = $row['standard'];
						//开料尺寸
		//				$arr[$i]['route'] = $row['route'];
						//加工工艺路线
						$ret_data['row'][$i]['count'] = $row['count'];
						//数量
						$ret_data['row'][$i]['child_material'] = $row['child_material'];
						//规格
						// $number = explode("#", $row['number']);
						// $arr[$i]['number'] = $number[0] . "#"; //工单
						$ret_data['row'][$i]['number']=$row['pNumber']; //工单
						$ret_data['row'][$i]['product_name'] = $row['product_name']; //产品名称
						if($row['isfinish']=='1'){
							$ret_data['row'][$i]['finish'] = '已检验';
						}else{
							$ret_data['row'][$i]['finish'] = '未检验';
						}
		//				$arr[$i]['remark'] = $row['remark'];
		//				$arr[$i]['routeid'] = $row['routeid'];
		//				if ($row['backMark'] == "1") {
		//					$arr[$i]['backMark'] = "是";
		//				} else {
		//					$arr[$i]['backMark'] = "否";
		//				}
			
		//				$arr[$i]['reason'] = $row['reason'];
						$i++;
					}
					$ret_data["success"] = "success";
				}

				
	        }else{
	        	$conn->close();
				$ret_data["success"] = "error";
	        }
		}
	}else{
		$conn->close();
		$ret_data["success"] = "文件格式错误";
	}
	$json = json_encode($ret_data);
	echo $json;
?>