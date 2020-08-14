<?php
session_start();
include "../class/connect.php";
include "../class/date.php";
include "../class/audit_trail_class.php";

// if success
if (isset($_POST["position"]) && isset($_POST["department"])){
	$position = $_POST["position"];
	$department = $_POST["department"]; // the value of this is the department id
	include "../class/department.php";
	include "../class/position_class.php";

	// department first to insert before the position because we will get the department id in position	
	$department_class = new Department;
	$position_class = new Position;

	

	// check ko muna if exist na ung position na un at may dept id na naganun din

	// if did not fill out
	if ($position == "" || $department == ""){
		$_SESSION["position_dept_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Info did not save, you did not fill up the required fields</center>";
	}
	// check if exist
	else if ($position_class->checkExist($department,$position) != 0){
		$department_value = $department_class->getDepartmentValue($department)->Department;
		$_SESSION["position_dept_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>$position position</b> in the <b>$department_value department</b> already exist</center>";
	}

	// if success
	else {

		$date_class = new date;
		$position_class->insertPosition($department,$position,$date_class->getDate());
		$_SESSION["position_dept_success"] = "<center><span class='glyphicon glyphicon-ok' style='color:#196F3D'></span> <b>$position position</b> successfully save</center>";


		$dateTime = $date_class->getDateTime();
		$audit_trail_class = new AuditTrail;
		$module = "Position";
		$audit_trail_class->insertAuditTrail(0,0,$_SESSION["id"],$module,"Add position <b>".$position."</b>",$dateTime);
	}
	header("Location:../position_list.php");
}
// if failed
else {
	header("Location:../MainForm.php");
}
?>