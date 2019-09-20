<?php
require ("../conn.php");
$flag = $_POST["flag"];
switch ($flag) {
    case 'mes':
        $partid = $_POST["partid"];
        $pid = $_POST["pid"];
        $modid = $_POST["modid"];
        $routeid = $_POST["routeid"];
        $sql = "select name,figure_number,product_name,count,route from productionplan where id='" . $partid . "' and routeid='".$routeid."'";
        $res = $conn->query($sql);
        if ($res->num_rows > 0) {
            $i = 0;
            while ($row = $res->fetch_assoc()) {
                $arr[$i]['name'] = $row['name'];
                $arr[$i]['figure_number'] = $row['figure_number'];
                $arr[$i]['product_name'] = $row['product_name'];
                $arr[$i]['count'] = $row['count'];
                $arr[$i]['route'] = $row['route'];
                $i++;
            }
        }
        $json = json_encode($arr);
        echo $json;
        break;

    case 'schedule':
        // 获取isfinish状态
        $modid = $_POST["modid"];
        $pid = $_POST["pid"];
        $route=$_POST['route'];
        $routeid=$_POST['routeid'];
        $name=$_POST['name'];
        $figure_number=$_POST['figure_number'];
        $count=$_POST['count'];
        $cuser=$_POST['writtenBy'];
        $schedule_date = date("Y-m-d");
        $time = date("Y-m-d h:i:s");
        $sql_oldupdate= "UPDATE route SET isfinish='0' where modid='$modid' and id='$routeid'";
		$conn->query($sql_oldupdate);
        $sql =  "INSERT INTO workshop_k (modid, routeid, schedule_date, isfinish,ctime,cuser) VALUES ('$modid', '$routeid', '$schedule_date', '0','$time','$cuser')";
        $res = $conn->query($sql);
        break;
    }
    $conn->close();
?>
