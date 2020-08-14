<?php
session_start();
include "../class/connect.php";
include "../class/attendance_overtime.php";
include "../class/date.php";

if (isset($_POST["update_hour_time_out"]) && isset($_POST["update_min_time_out"]) && isset($_POST["update_remarks"])
	&& isset($_POST["attendance_ot_id"]) && isset($_POST["type_ot"])){

	$attendance_ot_id = $_POST["attendance_ot_id"];

	$attendance_ot_class = new Attendance_Overtime;
	$date_class = new date;


	if ($_POST["type_ot"] != "Regular") {
		$hour_time_in = $_POST["update_hour_time_in"];
		$min_time_in = $_POST["update_min_time_in"];
		$period_time_in = $_POST["update_period_time_in"];
		if ($period_time_in == "PM"){
			$hour_time_in = $hour_time_in + 12;
		}
		$time_from = date_format(date_create($hour_time_in . ":" . $min_time_in . ":00"),"H:i:s");
	}
	else {
		$row = $attendance_ot_class->getInfoByAttendanceOtId($attendance_ot_id);
		$time_from = $row->time_from;
	}

	$hour_time_out = $_POST["update_hour_time_out"];
	$min_time_out = $_POST["update_min_time_out"];
	$period_time_out = $_POST["update_period_time_out"];
	if ($period_time_out == "PM"){
		$hour_time_out = $hour_time_out + 12;
	}

	$remarks = $_POST["update_remarks"];

	$time_out = date_format(date_create($hour_time_out . ":" . $min_time_out . ":00"),"H:i:s");

	
	//$attendance_date = $date_class->dateDefaultDb($_POST["update_attendance_date"]);
	if ($attendance_ot_class->updatesOTInfoNoChanges($attendance_ot_id,$time_from,$time_out,$remarks) == 1){
		$_SESSION["update_error_overtime"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> No updates were taken, No changes was made.</center>";
	}
	else if ($time_out <= $time_from){
		$_SESSION["update_error_overtime"] = "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> <b>Time out</b> cannot greater than or equal to <b>Time in</b>.</center>";
	}

	else {
		$attendance_ot_class->updateFileOvertime($attendance_ot_id,$time_from,$time_out,$remarks);
		$_SESSION["success_crud_ot"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> Your File attendance is successfully updated.</center>";
	}

	header("Location:../view_attendance.php");
}
else {

}

?>