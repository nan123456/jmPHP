<?php
	require("../../conn.php");
	$ret_data = array();
	$flag = isset($_REQUEST["flag"]) ? $_REQUEST["flag"] : "";
	$relateId = isset($_REQUEST["relateId"]) ? $_REQUEST["relateId"] : "";
	switch($flag){
		case 'welding':
			$sql1="SELECT pnumber,proname,procode FROM weldingtree WHERE id='$relateId'";
			$result1 = $conn->query($sql1);
			$row1 =$result1->fetch_assoc();
			$pnumber=$row1['pnumber'];
			$proname=$row1['proname'];
			$procode=$row1['procode'];
			$ret_data['pnumber']=$pnumber;
			$ret_data['proname']=$proname;
			$ret_data['procode']=$procode;
			echo json_encode($ret_data);
			break;
		case 'heating':
			$sql1="SELECT pnumber,proname,procode FROM weldingtree WHERE id='$relateId'";
			$result1 = $conn->query($sql1);
			$row1 =$result1->fetch_assoc();
			$pnumber=$row1['pnumber'];
			$proname=$row1['proname'];
			$procode=$row1['procode'];
			$ret_data['pnumber']=$pnumber;
			$ret_data['proname']=$proname;
			$ret_data['procode']=$procode;
			echo json_encode($ret_data);
			break;
		case 'maching':
			$sql1="SELECT pnumber,proname,procode FROM weldingtree WHERE id='$relateId'";
			$result1 = $conn->query($sql1);
			$row1 =$result1->fetch_assoc();
			$pnumber=$row1['pnumber'];
			$proname=$row1['proname'];
			$procode=$row1['procode'];
			$ret_data['pnumber']=$pnumber;
			$ret_data['proname']=$proname;
			$ret_data['procode']=$procode;
			echo json_encode($ret_data);
			break;
		case 'craft':
			$sql1="SELECT pnumber,proname,procode FROM craftsmanshiptree WHERE id='$relateId'";
			$result1 = $conn->query($sql1);
			$row1 =$result1->fetch_assoc();
			$pnumber=$row1['pnumber'];
			$proname=$row1['proname'];
			$procode=$row1['procode'];
			$ret_data['pnumber']=$pnumber;
			$ret_data['proname']=$proname;
			$ret_data['procode']=$procode;
			echo json_encode($ret_data);
			break;			
	}
?>