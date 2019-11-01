<?php
	require("../conn.php");
//	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	set_time_limit(0); //使无响应时间限制
	$ret_data = array();	
//	$ret_data["ftype"] = isset($_POST["ftype"])?$_POST["ftype"] : '';
	$ret_data["fname"] = isset($_POST["fname"])?$_POST["fname"] : '';
	$ret_data["name"] = isset($_POST["name"])?$_POST["name"] : '';
	$ret_data["number"] = isset($_POST["number"])?$_POST["number"] : '';
	$ret_data["pnumber"] = isset($_POST["pnumber"])?$_POST["pnumber"] : '';
	$type=isset($_POST["type"])?$_POST["type"] : '';
	if($type){
		$typearr=explode('_',$type);
		$ret_data["typevalue"]=$typearr[0];
		$ret_data["type"]=$typearr[1];
	}
//	$ret_data["type"] = isset($_POST["type"])?$_POST["type"] : '';
	$ret_data["date"] = isset($_POST["date"])?$_POST["date"] : '';
	$ret_data["ftype"]=substr($ret_data["fname"],-3,3);
	
//	$ret_data["ftype"] =$_POST["ftype"];
//	$ret_data["name"] =$_POST["name"];
//	$ret_data["number"] =$_POST["number"];
//	$ret_data["pnumber"] =$_POST["pnumber"];
//	$ret_data["type"] = $_POST["type"];
//	$ret_data["date"] =$_POST["date"];

	
	$name = $ret_data["name"];
	$pnumber = $ret_data["pnumber"].$ret_data["number"];
	$number = $ret_data["number"];
	$type = $ret_data["type"];
	$date = $ret_data["date"];
	$typevalue = $ret_data["typevalue"];
	//查询数据库，检查是否已存在该项目
	$asql = "SELECT id FROM project WHERE pNumber = '$pnumber'";
	$ares = $conn->query($asql);
//	if($ret_data["ftype"] == 'vnd.openxmlformats-officedocument.spreadsheetml.sheet'&& $ares->num_rows==0){
	if($ret_data["ftype"] == 'xls'&& $ares->num_rows==0){

		//加载PHPExcel类库
		require_once("../PHPExcel.php");
		require_once("../PHPExcel/IOFactory.php");
		require_once("../PHPExcel/Reader/Excel5.php");
//		require_once("../PHPExcel/Reader/Excel2007.php");
	
		//xls格式为2003格式 即Excel5,此处为03格式
		$objReader = PHPExcel_IOFactory::createReader('Excel5'); 
		//xlsx格式为2007格式 即excel2007
//		$objReader = PHPExcel_IOFactory::createReader('excel2007'); 
		
//		$_FILES['file'] 获取前端上传文件信息
//		$_FILES['file']['tmp_name'] 缓存的文件的路径
		$Excel = $_FILES['file'];
		$filetmp = $Excel['tmp_name'];
		//		$ret_data['file'] = $Excel;
		//		$ret_data['fname'] = $filetmp;
		
		$objReader->setReadDataOnly(true);//当数据格式有特殊字符时，使用该方法读取相应的单元格的数据，忽略任何格式的信息。
		
		$objPHPExcel = $objReader->load($filetmp);
		$sheet = $objPHPExcel->getSheet(0);  // 读取第一个sheet表
		$highestRow = $sheet->getHighestRow(); // 取得总行数
		$highestRow=intval($highestRow);
		$ret_data["highestRow"]=$highestRow; 
   	 	$ctime = date('Y-m-d H:i:s');
   	 	$sjc = time();
   	 	$fsql = "INSERT INTO `weldingtree`(`proname`,`procode`,`category`,`ctime`,`pnumber`) VALUES('$name','$number','$typevalue','$sjc','$pnumber')";
   	 	$fres = $conn->query($fsql);
   	 	$gsql = "INSERT INTO `craftsmanshiptree`(`proname`,`procode`,`category`,`ctime`,`pnumber`) VALUES('$name','$number','$typevalue','$sjc','$pnumber')";
   	 	$gres = $conn->query($gsql);
	 	$bsql = "INSERT INTO project (name,type,number,pNumber,end_date,isfinish,ctime,typevalue)VALUES('$name','$type','$number','$pnumber','$date','0','$ctime','$typevalue')";
		$bres = $conn->query($bsql);
	  	$csql = "SELECT id FROM project WHERE number = '$number'";
	 	$cres = $conn->query($csql);
	  	if($cres->num_rows>0){
	 		while($crow = $cres->fetch_assoc()){
	 			$id = $crow["id"];
	  		}
	 	}
    	//拆拼接字符串，为下面比对插入数据库做准备
//  	$str = explode("#",$number);
    	//项目名
//  	$projectname = $name.$str[1];
//  	$ret_data["projectname"] =$projectname;
    	
		// $highestColumn = $sheet->getHighestColumn(); // 取得总列数
	    //循环读取excel表格,读取一条,插入一条
	    //rw表示从哪一行开始读取  从第二行开始读取，因为第一行是标题不保存
	    //$a表示列号
		for($rw=2;$rw<=$highestRow;$rw++)  
	    {
	    		$ret_data["max"]=$rw; 
	        $a = $objPHPExcel->getActiveSheet()->getCell("A".$rw)->getValue();//获取A(项目)列的值
	        $d = $objPHPExcel->getActiveSheet()->getCell("D".$rw)->getValue();//获取D(订单号)列的值
	        $i = $objPHPExcel->getActiveSheet()->getCell("I".$rw)->getValue();//获取I(物料名称)列的值
	        $j = $objPHPExcel->getActiveSheet()->getCell("J".$rw)->getValue();//获取J(物料属性)列的值
	        $k = $objPHPExcel->getActiveSheet()->getCell("K".$rw)->getValue();//获取K(所属部件)列的值
	        $l = $objPHPExcel->getActiveSheet()->getCell("L".$rw)->getValue();//获取L(开料尺寸)列的值
	        $r = $objPHPExcel->getActiveSheet()->getCell("R".$rw)->getValue();//获取R(工艺路线)列的值
	        $u = $objPHPExcel->getActiveSheet()->getCell("U".$rw)->getValue();//获取U(订单说明)列的值
	        $v = $objPHPExcel->getActiveSheet()->getCell("V".$rw)->getValue();//获取V(订单备注)列的值
	        $w = $objPHPExcel->getActiveSheet()->getCell("W".$rw)->getValue();//获取W(订单数量)列的值
	        $x = $objPHPExcel->getActiveSheet()->getCell("X".$rw)->getValue();//获取X(入库数量)列的值
	        $y = $objPHPExcel->getActiveSheet()->getCell("Y".$rw)->getValue();//获取Y(子件编码)列的值
	        $z = $objPHPExcel->getActiveSheet()->getCell("Z".$rw)->getValue();//获取Z(子件名称)列的值
	        $aa = $objPHPExcel->getActiveSheet()->getCell("AA".$rw)->getValue();//获取AA(单位)列的值
	        $ab = $objPHPExcel->getActiveSheet()->getCell("AB".$rw)->getValue();//获取AB(单阶用量)列的值
	        $ac = $objPHPExcel->getActiveSheet()->getCell("AC".$rw)->getValue();//获取AC(辅计量单位)列的值
	        	$ad = $objPHPExcel->getActiveSheet()->getCell("AD".$rw)->getValue();//获取AD(换算率)列的值
	        	$ae = $objPHPExcel->getActiveSheet()->getCell("AE".$rw)->getValue();//获取AE(应领数量)列的值
	        	$af = $objPHPExcel->getActiveSheet()->getCell("AF".$rw)->getValue();//获取AF(辅计算数量)列的值
	        	$ag = $objPHPExcel->getActiveSheet()->getCell("AG".$rw)->getValue();//获取AG(领料申请数量)列的值
	        	$ah = $objPHPExcel->getActiveSheet()->getCell("AH".$rw)->getValue();//获取AH(已领数量)列的值
	        	$ai = $objPHPExcel->getActiveSheet()->getCell("AI".$rw)->getValue();//获取AI(领料仓库)列的值
	        	$aj = $objPHPExcel->getActiveSheet()->getCell("AJ".$rw)->getValue();//获取AJ(科技仓现存)列的值
	        	$ak = $objPHPExcel->getActiveSheet()->getCell("AK".$rw)->getValue();//获取AK(子件备注)列的值
//	        	$al = $objPHPExcel->getActiveSheet()->getCell("AL".$rw)->getCalculatedValue();//获取AL(MoDId)列的值
			$al = $objPHPExcel->getActiveSheet()->getCell("AL".$rw)->getValue();//获取AL(MoDId)列的值
	        	$am = $objPHPExcel->getActiveSheet()->getCell("AM".$rw)->getValue();//获取AM(ABC质量分类)列的值     
	        	$an = $objPHPExcel->getActiveSheet()->getCell("AN".$rw)->getValue();//获取AN(图号)列的值
	        	$ao = $objPHPExcel->getActiveSheet()->getCell("AO".$rw)->getValue();//获取AO(所属部件图号)列的值  
	        //去除读取的数据头尾的空格
	        $a = trim(html_entity_decode($a),chr(0xc2).chr(0xa0));
	        $d = trim(html_entity_decode($d),chr(0xc2).chr(0xa0));
	        $i = trim(html_entity_decode($i),chr(0xc2).chr(0xa0));
	        $j = trim(html_entity_decode($j),chr(0xc2).chr(0xa0));
	        $k = trim(html_entity_decode($k),chr(0xc2).chr(0xa0));
	        $l = trim(html_entity_decode($l),chr(0xc2).chr(0xa0));
	        $r = trim(html_entity_decode($r),chr(0xc2).chr(0xa0));
	        $u = trim(html_entity_decode($u),chr(0xc2).chr(0xa0));
	        $v = trim(html_entity_decode($v),chr(0xc2).chr(0xa0));
	        $w = trim(html_entity_decode($w),chr(0xc2).chr(0xa0));
	        $x = trim(html_entity_decode($x),chr(0xc2).chr(0xa0));
	        $y = trim(html_entity_decode($y),chr(0xc2).chr(0xa0));
	        $z = trim(html_entity_decode($z),chr(0xc2).chr(0xa0));
	        $aa = trim(html_entity_decode($aa),chr(0xc2).chr(0xa0));
	        $ab = trim(html_entity_decode($ab),chr(0xc2).chr(0xa0));
	        $ac = trim(html_entity_decode($ac),chr(0xc2).chr(0xa0));
	        $ad = trim(html_entity_decode($ad),chr(0xc2).chr(0xa0));
	        $ae = trim(html_entity_decode($ae),chr(0xc2).chr(0xa0));
	        $af = trim(html_entity_decode($af),chr(0xc2).chr(0xa0));
	        $ag = trim(html_entity_decode($ag),chr(0xc2).chr(0xa0));
	        $ah = trim(html_entity_decode($ah),chr(0xc2).chr(0xa0));
	        $ai = trim(html_entity_decode($ai),chr(0xc2).chr(0xa0));
	        $aj = trim(html_entity_decode($aj),chr(0xc2).chr(0xa0));
	        $ak = trim(html_entity_decode($ak),chr(0xc2).chr(0xa0));
	        $al = trim(html_entity_decode($al),chr(0xc2).chr(0xa0));
	        $am = trim(html_entity_decode($am),chr(0xc2).chr(0xa0));
	        $an = trim(html_entity_decode($an),chr(0xc2).chr(0xa0));
	        $ao = trim(html_entity_decode($ao),chr(0xc2).chr(0xa0));
	        
//	        echo $e;
			
//			if($r) {
//				$ret_data["route"] = $r;
//				$route_arr = explode('→',$r);
//				$length = count($route_arr);
//				for($route_i=1;$route_i<$length;$route_i++){
//					$dsql = "INSERT INTO route (pid,modid,route,listid,route_line,isfinish,pNumber)VALUES('$id','$al','$route_arr[$route_i]','$route_i','$r','0','$pnumber')";
//					$dres = $conn->query($dsql);					
//				}
//			}
	        	if($d!=''){
	        		if($am == "A" || $am == "B"){
	        			$radio=1;  //关键零部件
	        		}else if($am != "A" && $am != "B"){
	        			$radio=2;  //非关键零部件
	        		}
	        		$isexterior=0; //是否外协，默认为0
	        		//获取工艺路线的值
		        	if($r) {
		        		//判断是否外协，如果在工艺路线中存在W，则有外协
		        		if(strpos($r,'W') !== false){ 
 						$isexterior=1; 
					}else{
 						$isexterior=0;
					}
					$ret_data["route"] = $r;
					$route_arr = explode('→',$r);
					$length = count($route_arr);
					for($route_i=1;$route_i<$length;$route_i++){
						$dsql = "INSERT INTO route (pid,modid,route,listid,route_line,isfinish,pNumber,isexterior)VALUES('$id','$al','$route_arr[$route_i]','$route_i','$r','0','$pnumber','$isexterior')";
						$dres = $conn->query($dsql);					
					}
				}
	        		$sql = "INSERT INTO part (fid,belong_part,pNumber,name,child_material,standard,radio,category,quantity,unit,count,modid,child_number,remark,isfinish,isexterior,ordernumber,figure_number,belong_figure_number) VALUES('$id','$k','$pnumber','$i','$z','$l','$radio','$am','$ab','$aa','$w','$al','$y','$v','0','$isexterior','$d','$an','$ao')"; //null 为主键id，自增可用null表示自动添加
	        		$res= $conn->query($sql);
	        	}
	        	
	        	if($d==''){
	        		$esql="INSERT INTO compare_plm(project,ordernumber,name,belong_part,property,size,route,orderamount)VALUES('$a','$d','$i','$k','$j','$l','$r','$w')";//无订单号与PLM进行比对
	        		$eres= $conn->query($esql);
	        	}
        	$sql = "SELECT modid FROM route WHERE pid='$number'AND route = 'S移交客户'";
        	$result = $conn->query($sql);
        	$row = $result->fetch_assoc();
        	$sql2 = "UPDATE project SET modid='".$row['modid']."' WHERE id='$number'";
	       	$result2= $conn->query($sql2);
//	        if($partname!=$projectname){
//	        	if($am == "A" || $am == "B"){
//	        		$radio = 1;
//	        	}else{
//	        		$radio = 2;
//	        	}
//		    	$sql = "INSERT INTO part (fid,belong_part,pNumber,figure_number,name,material,child_material,standard,radio,category,quantity,unit,count,modid,child_number,child_unit,remark,isfinish) VALUES('$id','$e','$pnumber','$i','$partname','$k','$l','$m','$radio','$n','$o','$p','$s','$t','$u','$y','$r','3')"; //null 为主键id，自增可用null表示自动添加
//		    	$res= $conn->query($sql);
//	        }else {
//	        	$sql = "UPDATE project SET modid='$t' WHERE id = '$id'"; //为项目添加modid
//		    	$res= $conn->query($sql);
//	        }
	        
      }
//		$conn->close();
		$ret_data["success"]="success";
	}else {
		$conn->close();
		$ret_data["success"] = "error";
	}
	
	
	$json = json_encode($ret_data);
	echo $json;
?>