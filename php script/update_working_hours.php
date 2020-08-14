<?php
session_start();
include "../class/connect.php";
include "../class/working_hours_class.php";
include "../class/date.php";
include "../class/audit_trail_class.php";


if (isset($_POST["update_hour_time_in"]) && isset($_POST["update_min_time_in"]) && isset($_POST["update_sec_time_in"])
	&& isset($_POST["update_hour_time_out"]) && isset($_POST["update_min_time_out"]) && isset($_POST["update_sec_time_out"])){

	$working_hours_id =  $_POST["working_hours_id"];

	$working_hours_class = new WorkingHours;

	$hour_time_in = $_POST["update_hour_time_in"];
	$min_time_in = $_POST["update_min_time_in"];
	$sec_time_in = $_POST["update_sec_time_in"];

	$hour_time_out = $_POST["update_hour_time_out"];
	$min_time_out = $_POST["update_min_time_out"];
	$sec_time_out = $_POST["update_sec_time_out"];

	$timeFrom = date_create($hour_time_in . ":" . $min_time_in . ":" . $sec_time_in);
	$final_timeFrom = date_format($timeFrom, 'H:i:s');

	$timeTo = date_create($hour_time_out . ":" . $min_time_out . ":" . $sec_time_out);
	$final_timeTo = date_format($timeTo, 'H:i:s');


	$difference = strtotime($final_timeTo) - strtotime($final_timeFrom);



	$row = $working_hours_class->getWorkingHoursInfoById($working_hours_id);
	$old_time_from = $row->timeFrom;
	$old_time_to = $row->timeTo;

	//echo $difference;

	if ($timeFrom >= $timeTo){
		$_SESSION["error_update_working_hours"] =  "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> <b>Time from</b> must be not greater than or equal to <b>Time to</b></center>";
	}

	// ibig sabihin , di siya 8 hours and above
	else if ($difference < 28800){
		$_SESSION["error_update_working_hours"] = "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> Time range must be 8 hours and above</center>";
	}

	// for checking if exist
	else if ($working_hours_class->updateExistWorkingHours($working_hours_id,$final_timeFrom,$final_timeTo) == 1){
		$_SESSION["error_update_working_hours"] = "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> The working hours of <b>$final_timeFrom - $final_timeTo</b> is already exist.</center>";
	}


	// if no changes was made
	else if ($working_hours_class->updateNoChangesWorkingHours($working_hours_id,$final_timeFrom,$final_timeTo) == 1){
		$_SESSION["error_update_working_hours"] = "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> No updates were taken, No changes was made.</center>";
	}

	// if success
	else {
		$working_hours_class->updateWorkingHours($working_hours_id,$final_timeFrom,$final_timeTo);
		$_SESSION["success_update_working_hours"] = "<center><span class='glyphicon glyphicon-ok'  style='color:#196F3D'></span> Working hours is successfully updated.</center>";
	}


	$date_class = new date;
	$dateTime = $date_class->getDateTime();
	$audit_trail_class = new AuditTrail;
	$module = "Working Hours";
	$audit_trail_class->insertAuditTrail(0,0,$_SESSION["id"],$module,"Update working hours from <b>$old_time_from - $old_time_to</b> to  <b>$final_timeFrom - $final_timeTo</b>",$dateTime);

	header("Location:../working_hours.php");


}

else {
	header("Location:../MainForm.php");
}

?>