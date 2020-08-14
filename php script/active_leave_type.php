<?php
session_start();
include "../class/connect.php";
include "../class/leave_class.php";


if (isset($_POST["lt_id"])){

	$lt_id = $_POST["lt_id"];

	$leave_class = new Leave;

	$row = $leave_class->getLeaveTypeById($lt_id);

	$leave_type = $row->name;

	$status = 0;
	if ($row->status == 0){
		$status = 1;
	}

	//echo $lt_id;

	$leave_class->activeLeaveType($lt_id,$status);

	$_SESSION["leave_type"]= "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> Leave Type <b>$leave_type</b> is successfully change it's active status</center>";
		echo "Success";


	header("Location:../leave_maintenance.php");

}
else {
	header("Location:../MainForm.php");
}


?>