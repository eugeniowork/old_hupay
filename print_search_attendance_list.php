<?php
include "class/connect.php";
include "class/time_in_time_out.php";

if (isset($_POST["dateFrom"]) && isset($_POST["dateTo"])){
	$dateFrom = $_POST["dateFrom"];
	$dateTo = $_POST["dateTo"];

	$attendance_class = new Attendance;
	$attendance_class->attendanceListReports($dateFrom,$dateTo);


	
}
else {
	header("Location:MainForm.php");
}


?>