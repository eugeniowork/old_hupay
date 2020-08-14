<?php
session_start();
include "../class/connect.php";
include "../class/attendance_notif.php";

if (isset($_POST["attendance_notif_id"])){
	$attendance_notif_id = $_POST["attendance_notif_id"];

	$attendance_notifications_class= new AttendanceNotif;


	//echo $attendance_notif_id;
	// for cancelling attenadnce notifications
	$attendance_notifications_class->cancelFileAttendanceRequestUpdates($attendance_notif_id);

	$_SESSION["success_crud_attendance_info"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> Your File attendance is successfully cancelled.</center>";

	header("Location:../view_attendance.php");
}
else {
	header("Location:../MainForm.php");
}
?>