<?php
session_start();
include "../class/connect.php";
include "../class/time_in_time_out.php";
include "../class/attendance_overtime.php";
include "../class/date.php";


if (isset($_POST["hour_time_out"]) && isset($_POST["min_time_out"]) && isset($_POST["sec_time_out"]) && isset($_POST["type_ot"]) && isset($_POST["remarks"])) {
	
	$attendance_class = new Attendance;
	$attendance_ot_class = new Attendance_Overtime;
	$date_class = new date;
	
	$emp_id = $_SESSION["id"];
	$attendance_id = $_SESSION["attendance_id_file_ot"];

	$date = $attendance_class->getInfoByAttendaceId($attendance_id)->date;


	$date_create = date_create($date);
	$day = date_format($date_create, 'l');


	$time_in = "18:30:00";

	if ($day == "Saturday" || $day == "Sunday") {
		$time_in = $attendance_class->getInfoByAttendaceId($attendance_id)->time_in;
	}


	$hour = $_POST["hour_time_out"];
	$min = $_POST["min_time_out"];
	$sec = $_POST["sec_time_out"];

	$time_out = $hour . ":" . $min . ":" . $sec;

	$type_ot = $_POST["type_ot"];
	$remarks = $_POST["remarks"];
	$dateCreated = $date_class->getDate();
	

	if (($day == "Saturday" || $day == "Sunday") && ($type_ot != "Restday" && $type_ot != "Restday / Holiday")){
		$_SESSION["update_error_overtime"] = "error";
	}

	else if (($day != "Saturday" && $day != "Sunday") && ($type_ot != "Regular" && $type_ot != "Holiday")){
		$_SESSION["update_error_overtime"] = "error";
	}

	// if wla insert lang
	else {

		// if meron update lang
		if ($attendance_ot_class->existAttendanceId($attendance_id) == 1) {
			$attendance_ot_class->updateAttendanceOT($attendance_id,$time_out,$type_ot,$remarks,$dateCreated);
		}
		else {
			$attendance_ot_class->insertOvertime($emp_id,$attendance_id,$date,$time_in,$time_out,$type_ot,$remarks,$dateCreated);
		}
		$_SESSION["success_file_ot"] = "success";

	}

	
	header("Location:../view_attendance.php");
	
}
else {
	header("Location:../Mainform.php");
}

?>