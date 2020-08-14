<?php
session_start();
include "../class/connect.php";
include "../class/memorandum_class.php";
include "../class/emp_information.php";
include "../class/department.php";
include "../class/date.php";
include "../class/audit_trail_class.php";


if (isset($_POST["delete_memoId"])){
	$memo_id = $_POST["delete_memoId"];
	
	$memo_class = new Memorandum;
	$emp_info_class = new EmployeeInformation;
	$dept_class = new Department;
	

	$row = $memo_class->getMemoInfoById($memo_id);
	
	$subject = $row->Subject;

	/*if ($row->recipient == "Specific Employee"){
		$row_emp = $emp_info_class->getEmpInfoByRow($row->emp_id);
		$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;
	}

	else if ($row->recipient == "Department"){
		$row_dept = $dept_class->getDepartmentValue($row->dept_id);
		$fullName = $row_dept->Department . " Department";		

	}

	else {
		$fullName = "All Employee";
	}
	*/

	$memo_class->deleteMemo($memo_id);

	$memo_class->deleteMemoNotif($memo_id);

	$date_class = new date;
	$dateTime = $date_class->getDateTime();
	$audit_trail_class = new AuditTrail;
	$module = "Memorandum";
	$audit_trail_class->insertAuditTrail(0,0,$_SESSION["id"],$module,"Delete memorandum about <b>".$subject."</b>",$dateTime);

	$_SESSION["success_delete_memo"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> <b>Memorandum</b> of <b>$subject</b> is successfully deleted.</center>";

	header("Location:../memorandum.php");


}

else {
	header("Location:../MainForm.php");
}

?>