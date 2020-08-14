<?php
session_start();
include "../class/connect.php";
include "../class/working_days_class.php";
include "../class/audit_trail_class.php";
include "../class/date.php";


if (isset($_POST["day_from"]) && isset($_POST["day_to"])){
	$day_from = $_POST["day_from"];
	$day_to = $_POST["day_to"];

	//echo $day_from . " " . $day_to;

	$working_days_class = new WorkingDays;
	$date_class = new date;

	$day_of_the_week = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");


	$working_days = $day_of_the_week[$day_from] . "-" . $day_of_the_week[$day_to];

	if ($working_days_class->existWorkingDays($day_from,$day_to) == 1){
		echo "<span class='color-red'><b>".$working_days."</b> is already exist.</span>";
	}
	else {

		$working_days_class->insertWorkingDays($day_from,$day_to);
		echo "Success";

		$_SESSION["success_working_days"] = "<center><span class='glyphicon glyphicon-ok'  style='color:#196F3D'></span> Working Days of <b>$working_days</b> is successfully saved.</center>";


		$dateTime = $date_class->getDateTime();
		$audit_trail_class = new AuditTrail;
		$module = "Working Days";
		$audit_trail_class->insertAuditTrail(0,0,$_SESSION["id"],$module,"Add working days of <b>$working_days</b>",$dateTime);

	}

 }

else {
	header("Location:../MainForm.php");
}


?>