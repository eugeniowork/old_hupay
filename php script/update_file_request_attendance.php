<?php
session_start();
include "../class/connect.php";
include "../class/attendance_notif.php";
include "../class/date.php";


if (isset($_POST["update_attendance_date"]) && isset($_POST["update_hour_time_in"]) && isset($_POST["update_min_time_in"])
	&& isset($_POST["update_period_time_in"]) && isset($_POST["update_hour_time_out"]) && isset($_POST["update_min_time_out"])
	&& isset($_POST["update_period_time_out"]) && isset($_POST["update_remarks"])){

	$attendance_notif_id = $_POST["attendance_notif_id"];
	
	$hour_time_in = $_POST["update_hour_time_in"];
	$min_time_in = $_POST["update_min_time_in"];
	$period_time_in = $_POST["update_period_time_in"];
	if ($period_time_in == "PM"){
		$hour_time_in = $hour_time_in + 12;
	}

	$hour_time_out = $_POST["update_hour_time_out"];
	$min_time_out = $_POST["update_min_time_out"];
	$period_time_out = $_POST["update_period_time_out"];
	if ($period_time_out == "PM"){
		$hour_time_out = $hour_time_out + 12;
	}

	$remarks = $_POST["update_remarks"];


	$time_in = date_format(date_create($hour_time_in . ":" . $min_time_in . ":00"),"H:i:s");
	$time_out = date_format(date_create($hour_time_out . ":" . $min_time_out . ":00"),"H:i:s");

	$attendance_notif_class = new AttendanceNotif;
	$date_class = new date;

	$attendance_date = $date_class->dateDefaultDb($_POST["update_attendance_date"]);


	/*
	echo $attendance_notif_id . "<br/>";
	echo $attendance_date . "<br/>";
	echo $time_in . "<br/>";
	echo $time_out . "<br/>";
	echo $remarks . "<br/>";
	*/

	//echo $attendance_date;
	//echo $attendance_notif_class->updatesAttendanceInfoNoChanges($attendance_notif_id,$attendance_date,$time_in,$time_out,$remarks);

	//echo $time_in . " " . $time_out . "<br/>";
	if ($attendance_notif_class->updatesAttendanceInfoNoChanges($attendance_notif_id,$time_in,$time_out,$remarks) == 1){
		$_SESSION["add_error_attendance"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> No updates were taken, No changes was made.</center>";
		//echo "wew";
	}

	else if ($time_out <= $time_in){
		$_SESSION["add_error_attendance"] = "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> <b>Time out</b> cannot greater than or equal to <b>Time in</b>.</center>";
	}

	else {
		//echo "wews";
		$attendance_notif_class->updateFileAttendanceInfo($attendance_notif_id,$time_in,$time_out,$remarks);
		$_SESSION["success_crud_attendance_info"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> Your File attendance is successfully updated.</center>";
	}

	header("Location:../view_attendance.php");


}
else {
	header("Location:../MainForm");
}


?>