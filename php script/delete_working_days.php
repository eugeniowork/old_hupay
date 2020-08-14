<?php
session_start();
include "../class/connect.php";
include "../class/working_days_class.php";
include "../class/date.php";
include "../class/audit_trail_class.php";

include "../class/universal_class.php";

if (isset($_POST["working_days_id"])){
	$working_days_id = $_POST["working_days_id"];


	$universal_class = new Universal;

	$tb_name_array = array("tb_employee_info");
	$col_name = "working_days_id";
	$unique_id = $working_days_id;

	$cannot_deleted =  $universal_class->cannotBeDeleted($tb_name_array,$col_name,$unique_id);

	if ($cannot_deleted == 1){
		$_SESSION["error_deleting"] = "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> You cannot delete the information because it is already link/ used to another information.</center>";
	}
	else {

		$working_days_class = new WorkingDays;

		$row = $working_days_class->getWorkingDaysInfoById($working_days_id);

		$day_from = $row->day_from;
		$day_to = $row->day_to;

		$day_of_the_week = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
		$day_of_the_week_value = [0,1,2,3,4,5,6];

		$count = count($day_of_the_week);

		$counter = 0;

		$day_from_value = "";
		$day_to_value = "";
		do {

			if ($day_of_the_week_value[$counter] == $day_from){
				$day_from_value = $day_of_the_week[$counter];
			}

			if ($day_of_the_week_value[$counter] == $day_to){
				$day_to_value = $day_of_the_week[$counter];
			}


			$counter++;
		}while($count > $counter);

		// for deleting query
		$working_days_class->deleteWorkingDays($working_days_id);

		$_SESSION["success_working_days"] = "<center><span class='glyphicon glyphicon-ok'  style='color:#196F3D'></span> <b>Working Days</b> of <b>$day_from_value - $day_to_value</b> is successfully deleted.</center>";


		$date_class = new date;
		$dateTime = $date_class->getDateTime();
		$audit_trail_class = new AuditTrail;
		$module = "Working Days";
		$audit_trail_class->insertAuditTrail(0,0,$_SESSION["id"],$module,"Delete working days of <b>$day_from_value - $day_to_value</b>",$dateTime);

		
	}

	header("Location:../working_hours_days.php");

}
else {
	header("Location:../MainForm.php");
}


?>