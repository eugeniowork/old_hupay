<?php
session_start();
include "../class/connect.php";
include "../class/attendance_overtime.php";

if (isset($_POST["attendance_ot_id"])){
	$attendance_ot_id = $_POST["attendance_ot_id"];

	$attendance_ot_class = new Attendance_Overtime;


	//echo $attendance_notif_id;
	// for cancelling attenadnce notifications
	$attendance_ot_class->cancelFileOT($attendance_ot_id);

	$_SESSION["success_crud_ot"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> Your File attendance is successfully cancelled.</center>";

	header("Location:../view_attendance.php");
}
else {
	header("Location:../MainForm.php");
}
?>