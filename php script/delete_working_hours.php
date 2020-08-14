<?php
session_start();
include "../class/connect.php";
include "../class/working_hours_class.php";
include "../class/date.php";
include "../class/audit_trail_class.php";

if (isset($_POST["working_hours_id"])){
	$working_hours_id = $_POST["working_hours_id"];

	$working_hours_class = new WorkingHours;

	$row = $working_hours_class->getWorkingHoursInfoById($working_hours_id);

	$working_hours = $row->timeFrom . "-" . $row->timeTo;

	// for deleting query
	$working_hours_class->deleteWorkingHours($working_hours_id);

	

	$_SESSION["success_update_working_hours"] = "<center><span class='glyphicon glyphicon-ok'  style='color:#196F3D'></span> <b>Working hours</b> of <b>$working_hours</b> is successfully deleted.</center>";


	$date_class = new date;
	$dateTime = $date_class->getDateTime();
	$audit_trail_class = new AuditTrail;
	$module = "Working Hours";
	$audit_trail_class->insertAuditTrail(0,0,$_SESSION["id"],$module,"Delete working hours of <b>$working_hours</b>",$dateTime);

	header("Location:../working_hours_days.php");

}
else {
	header("Location:../MainForm.php");
}


?>