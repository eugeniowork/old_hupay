<?php
session_start();
include "../class/connect.php";
include "../class/emp_information.php";
include "../class/date.php";
include "../class/audit_trail_class.php";


$emp_info_class = new EmployeeInformation;

if (isset($_SESSION["update_emp_id"]) && isset($_GET["resignation_date"])){
	$emp_id = $_SESSION["update_emp_id"];
	$resignation_date = $_GET["resignation_date"];
	$row = $emp_info_class->getEmpInfoByRow($emp_id);
	$fullName = $row->Firstname . " " . $row->Middlename . " " . $row->Lastname;

	$activeStatus = $row->ActiveStatus;

	$setActiveStatus = 0;
	$activeStatusValue = "Inactive";

	if ($activeStatus == 0) {
		$setActiveStatus = 1;
		$activeStatusValue = "Active";
	}

	if ($resignation_date != ""){
		$resignation_date = date_format(date_create($resignation_date),"Y-m-d");
	}


	$emp_info_class->updateActiveStatus($emp_id,$setActiveStatus,$resignation_date);

	$_SESSION["success_msg_update_basic_info"] = "<center><span class='glyphicon glyphicon-ok' style='color:#196F3D'></span> <b>" .$fullName . "</b>  is Successfully Updated to <b>".$activeStatusValue." Status</b>.</center";

	$date_class = new date;
	$dateTime = $date_class->getDateTime();
	$audit_trail_class = new AuditTrail;
	$module = "Update Allowance Information";
	$audit_trail_class->insertAuditTrail($emp_id,0,$_SESSION["id"],$module,"Change to " . $activeStatusValue . " status",$dateTime);
	
	header("Location:../employee_list.php");
}

else {
	header("Location:../Mainform.php");
}

?>