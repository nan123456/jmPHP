<?php
require ("../conn.php");
//require ("../classes/UploadFileOss.php");
require ("../classes/UploadFile.php");
$flag = $_POST["flag"];
//$flag = "0";

switch ($flag) {
		//评审
	case 'review' :
		$nub = isset($_POST["nub"]) ? $_POST["nub"] : "";
		$modid = isset($_POST["modid"]) ? $_POST["modid"] : "";
		$pid = isset($_POST["pid"]) ? $_POST["pid"] : "";
		$rid = isset($_POST["rid"]) ? $_POST["rid"] : "";//评审的id
		$routeid = isset($_POST["routeid"]) ? $_POST["routeid"] : "";
		print_r($_FILES);

		$returndata = array("state" => "1", "msg" => "");

		for ($i = 0; $i < $nub; $i++) {
			$index = $i + 1;
			$upfile = $_FILES["upfile" . $index];
			$filepathsave = $upfile["name"];
			$uploadfile = new UploadFile($upfile);
			if ($uploadfile->uploadFile()) {
				$sql = "select photourl from review where id = '" . $rid . "' ";
				$result = $conn -> query($sql);
				$row = $result -> fetch_assoc();
				if (strlen($row["photourl"]) > 0) {
					$filepathsave1 = $row["photourl"] . "," . $filepathsave;
					//上传照片url
					$sql1 = "UPDATE review SET photourl = '" . $filepathsave1 . "' WHERE id = '" . $rid . "' ";
					$result = $conn -> query($sql1);
					
				} else {
					//上传照片url
					$sql = "UPDATE review SET photourl = '" . $filepathsave . "' WHERE id = '" . $rid . "' ";
					$result = $conn -> query($sql);
					
				}
				//同步part表
				$sql2 = "select part_url from part where fid = '" . $pid . "' AND modid = '" . $modid . "' ";
				$result = $conn -> query($sql2);
				$row = $result -> fetch_assoc();
				if (strlen($row["part_url"]) > 0) {
					$filepathsave2 = $row["part_url"] . "," . $filepathsave;
					$sql3 = "UPDATE part SET part_url = '" . $filepathsave2 . "' WHERE fid = '" . $pid . "' AND modid = '" . $modid . "' ";
					$conn -> query($sql3);
				} else {
				//上传照片url
					$sql4 = "UPDATE part SET part_url = '" . $filepathsave . "' WHERE fid = '" . $pid . "' AND modid = '" . $modid . "' ";
					$conn -> query($sql4);
				}
			} else {
				$returndata["msg"] .= $filepathsave . "上传失败！";
			}
		}
		break;
		case 'finish' :
			$pid = isset($_POST["pid"]) ? $_POST["pid"] : "";
			$nub = isset($_POST["nub"]) ? $_POST["nub"] : "";
			$modid = isset($_POST["modid"]) ? $_POST["modid"] : "";
			$routeid = isset($_POST["routeid"]) ? $_POST["routeid"] : "";
	//		print_r($_FILES);
			for ($i = 0; $i < $nub; $i++) {
				$index = $i + 1;
				$upfile = $_FILES["upfile" . $index];
				//上传文件名
				$filepathsave = $upfile["name"];
				$uploadfile = new UploadFile($upfile);
				if ($uploadfile->uploadFile()) {
					//完工照片
				$sql = "select finishurl from workshop_k where routeid = '" . $routeid . "'";
				$result = $conn -> query($sql);
				$row = $result -> fetch_assoc();
				if (strlen($row["finishurl"]) > 0) {
					$filepathsave1 = $row["finishurl"] . "," . $filepathsave;
					//上传照片url
					$sql1 = "UPDATE workshop_k SET finishurl = '" . $filepathsave1 . "' WHERE routeid = '" . $routeid . "'";
					$conn -> query($sql1);
				} else {
					//上传照片url
					$sql = "UPDATE workshop_k SET finishurl = '" . $filepathsave . "' WHERE routeid = '" . $routeid . "'";
					$result = $conn -> query($sql);
				}
				//同步part表
					$sql2 = "select part_url from part where fid = '" . $pid . "' AND modid = '" . $modid . "' ";
					$result = $conn -> query($sql2);
					$row = $result -> fetch_assoc();
					if (strlen($row["part_url"]) > 0) {
						$filepathsave2 = $row["part_url"] . "," . $filepathsave;
						$sql3 = "UPDATE part SET part_url = '" . $filepathsave2 . "' WHERE fid = '" . $pid . "' AND modid = '" . $modid . "' ";
						$conn -> query($sql3);
					} else {
					//上传照片url
						$sql4 = "UPDATE part SET part_url = '" . $filepathsave . "' WHERE fid = '" . $pid . "' AND modid = '" . $modid . "' ";
						$conn -> query($sql4);
					}
				
				} else {
					$returndata["msg"] .= $filepathsave . "上传失败！";
				}
			}

		break;
		case 'inspect' :
		$nub = isset($_POST["nub"]) ? $_POST["nub"] : "";
		$modid = isset($_POST["modid"]) ? $_POST["modid"] : "";
		$wid = isset($_POST["wid"]) ? $_POST["wid"] : "";
		$pid = isset($_POST["pid"]) ? $_POST["pid"] : "";
		$routeid = isset($_POST["routeid"]) ? $_POST["routeid"] : "";
//		print_r($_FILES);
		$returndata = array("state" => "1", "msg" => "");
		for ($i = 0; $i < $nub; $i++) {
			$index = $i + 1;
			$upfile = $_FILES["upfile" . $index];
			$filepathsave = $upfile["name"];
			$uploadfile = new UploadFile($upfile);
			if ($uploadfile->uploadFile()) {
				//检验照片
				$sql = "select inspecturl from workshop_k where id = '" . $wid . "' ";
				$result = $conn -> query($sql);
				$row = $result -> fetch_assoc();
				if (strlen($row["inspecturl"]) > 0) {
					$filepathsave1 = $row["inspecturl"] . "," . $filepathsave;
					//上传照片url
					$sql1 = "UPDATE workshop_k SET inspecturl = '" . $filepathsave1 . "' WHERE id = '" . $wid . "' ";
					$result = $conn -> query($sql1);
				} else {
					//上传照片url
					$sql = "UPDATE workshop_k SET inspecturl = '" . $filepathsave . "' WHERE id = '" . $wid . "' ";
					$result = $conn -> query($sql);
				}
				//同步part表
					$sql1 = "select part_url from part where fid = '" . $pid . "' AND modid = '" . $modid . "' ";
					$result = $conn -> query($sql1);
					$row = $result -> fetch_assoc();
					if (strlen($row["part_url"]) > 0) {
						$filepathsave2 = $row["part_url"] . "," . $filepathsave;
						$sql3 = "UPDATE part SET part_url = '" . $filepathsave2 . "' WHERE fid = '" . $pid . "' AND modid = '" . $modid . "' ";
						$result = $conn -> query($sql3);
					} else {
					//上传照片url
						$sql4 = "UPDATE part SET part_url = '" . $filepathsave . "' WHERE fid = '" . $pid . "' AND modid = '" . $modid . "' ";
						$result = $conn -> query($sql4);
					}
			} else {
				$returndata["msg"] .= $filepathsave . "上传失败！";
			}
		}
		break;
		//不合格照片
		case 'unqualified' :
		$nub = isset($_POST["nub"]) ? $_POST["nub"] : "";//图片的长度
		$modid = isset($_POST["modid"]) ? $_POST["modid"] : "";
		$pid = isset($_POST["pid"]) ? $_POST["pid"] : "";
		$routeid = isset($_POST["routeid"]) ? $_POST["routeid"] : "";
		print_r($_FILES);
		$returndata = array("state" => "1", "msg" => "");
		for ($i = 0; $i < $nub; $i++) {
			$index = $i + 1;
			$upfile = $_FILES["upfile" . $index];
			$filepathsave = $upfile["name"];
			$uploadfile = new UploadFile($upfile);
			if ($uploadfile->uploadFile()) {
				$sql = "select unqualifiedurl from workshop_k where routeid = '" . $routeid . "' and modid ='".$modid."'";
				$result = $conn -> query($sql);
				$row = $result -> fetch_assoc();
				if (strlen($row["unqualifiedurl"]) > 0) {
					$filepathsave1 = $row["unqualifiedurl"] . "," . $filepathsave;
					//上传照片url
					$sql1 = "UPDATE workshop_k SET unqualifiedurl = '" . $filepathsave1 . "' WHERE routeid = '" . $routeid . "' and modid ='".$modid."'";
					$result = $conn -> query($sql1);
				} else {
					//上传照片url
					$sql = "UPDATE workshop_k SET unqualifiedurl = '" . $filepathsave . "' WHERE routeid = '" . $routeid . "' and modid ='".$modid."'";
					$result = $conn -> query($sql);
				}
				//同步part表
					$sql1 = "select part_url from part where fid = '" . $pid . "' AND modid = '" . $modid . "' ";
					$result = $conn -> query($sql1);
					$row = $result -> fetch_assoc();
					if (strlen($row["part_url"]) > 0) {
						$filepathsave2 = $row["part_url"] . "," . $filepathsave;
						$sql3 = "UPDATE part SET part_url = '" . $filepathsave2 . "' WHERE fid = '" . $pid . "' AND modid = '" . $modid . "' ";
						$result = $conn -> query($sql3);
					} else {
					//上传照片url
						$sql4 = "UPDATE part SET part_url = '" . $filepathsave . "' WHERE fid = '" . $pid . "' AND modid = '" . $modid . "' ";
						$result = $conn -> query($sql4);
					}
			} else {
				$returndata["msg"] .= $filepathsave . "上传失败！";
			}
		}
		break;
}
$conn -> close();
?>
