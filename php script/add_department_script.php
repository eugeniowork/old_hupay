<?php
session_start();
include "../class/connect.php";
include "../class/department.php";
include "../class/date.php";
include "../class/audit_trail_class.php";

$date_class = new date;
$date = $date_class->getDate();

if (isset($_POST["department"])){
	$department = $_POST["department"];


	// check if exist
	$department_class = new Department;
	$num_rows = $department_class->existDepartment($department);

	// check if department has value
	if ($department == ""){
		$_SESSION["department_error_msg"]= "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> You must fill up department value</center>";
	}

	// if exist
	else if ($num_rows != 0){
		$_SESSION["department_error_msg"]= "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> The <b>Department $department</b> is already exist</center>";
	}

	// success then insert
	else {
		$department_class->insertDepartment($department,$date);
		$_SESSION["department_success_msg"]= "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> The <b>Department $department</b> is  successfully added</center>";

		$dateTime = $date_class->getDateTime();
		$audit_trail_class = new AuditTrail;
		$module = "Department";
		$audit_trail_class->insertAuditTrail(0,0,$_SESSION["id"],$module,"Add department <b>".$department."</b>",$dateTime);
	}

	header("Location:../department_list.php");
}

else {
	header("Location:../Mainform.php");
} 

?>