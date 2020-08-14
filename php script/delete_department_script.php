<?php
session_start();
include "../class/connect.php";
include "../class/department.php";
include "../class/date.php";
include "../class/audit_trail_class.php";

$department_class = new Department;

if (isset($_SESSION["dept_del_id"])){
	$dept_id = $_SESSION["dept_del_id"];

	// for information purpose
	$department = $department_class->getDepartmentValue($dept_id)->Department;

	$department_class->deleteDepartment($dept_id); // deletion query

	$date_class = new date;
	$dateTime = $date_class->getDateTime();
	$audit_trail_class = new AuditTrail;
	$module = "Department";
	$audit_trail_class->insertAuditTrail(0,0,$_SESSION["id"],$module,"Delete department <b>".$department."</b>",$dateTime);


	$_SESSION["success_msg_del_dept"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> The <b>" . $department. " Department </b> is successfully deleted</center>";
	header("Location:../department_list.php");

}

else {
	header("Location:../MainForm.php");
}

?>