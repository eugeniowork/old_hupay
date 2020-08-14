<?php
session_start();
include "../class/connect.php";
include "../class/time_in_time_out.php";

if (isset($_POST["time_in"]) && isset($_POST["time_out"])) {
	$attendance_id = $_SESSION["attendance_id_update_request"];
	$time_in = $_POST["time_in"];
	$time_out = $_POST["time_out"];
	$attendance_class = new Attendance;
	if ($attendance_class->sameAttendance($attendance_id,$time_in,$time_out) == "1"){
		echo "same info";
	}
}
else {
	header("Location:../Mainform.php");
}

?>