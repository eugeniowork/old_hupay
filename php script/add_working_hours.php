<?php
session_start();
include "../class/connect.php";
include "../class/working_hours_class.php";
include "../class/date.php";
include "../class/audit_trail_class.php";

if (isset($_POST["hour_time_in"]) && isset($_POST["min_time_in"]) && isset($_POST["sec_time_in"])
	&& isset($_POST["hour_time_out"]) && isset($_POST["min_time_out"]) && isset($_POST["sec_time_out"])) {

	$working_hours_class = new WorkingHours;
	$date_class = new date;

	$hour_time_in = $_POST["hour_time_in"];
	$min_time_in = $_POST["min_time_in"];
	$sec_time_in = $_POST["sec_time_in"];

	$hour_time_out = $_POST["hour_time_out"];
	$min_time_out = $_POST["min_time_out"];
	$sec_time_out = $_POST["sec_time_out"];


	$timeFrom = date_create($hour_time_in . ":" . $min_time_in . ":" . $sec_time_in);
	$final_timeFrom = date_format($timeFrom, 'H:i:s');

	$timeTo = date_create($hour_time_out . ":" . $min_time_out . ":" . $sec_time_out);
	$final_timeTo = date_format($timeTo, 'H:i:s');


	$difference = strtotime($final_timeTo) - strtotime($final_timeFrom);

	//echo $difference;

	if ($timeFrom >= $timeTo){
		$_SESSION["error_adding_working_hours"] =  "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> <b>Time from</b> must be not greater than or equal to <b>Time to</b></center>";
	}

	// ibig sabihin , di siya 8 hours and above
	else if ($difference < 28800){
		$_SESSION["error_adding_working_hours"] = "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> Time range must be 8 hours and above</center>";
	}

	// for checking if exist
	else if ($working_hours_class->existWorkingHours($final_timeFrom,$final_timeTo) == 1){
		$_SESSION["error_adding_working_hours"] = "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> The working hours of <b>$final_timeFrom - $final_timeTo</b> is already exist.</center>";
	}


	// if sucess
	else {
		$dateCreated = $date_class->getDate();

		$working_hours_class->insertWorkingHours($final_timeFrom,$final_timeTo,$dateCreated);

		$_SESSION["success_adding_working_hours"] = "<center><span class='glyphicon glyphicon-ok'  style='color:#196F3D'></span> Working hours of <b>$final_timeFrom - $final_timeTo</b> is successfully saved.</center>";


		$dateTime = $date_class->getDateTime();
		$audit_trail_class = new AuditTrail;
		$module = "Working Hours";
		$audit_trail_class->insertAuditTrail(0,0,$_SESSION["id"],$module,"Add working hours of $final_timeFrom - $final_timeTo",$dateTime);
	}

	header("Location:../working_hours_days.php");

	
}

else {
	header("Location:../MainForm.php");
}

?>