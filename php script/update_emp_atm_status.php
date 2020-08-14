<?php
session_start();
include "../class/connect.php";
include "../class/emp_information.php";

if (isset($_SESSION["update_emp_atm_status_id"])){
	/*$emp_id = $_SESSION["update_emp_atm_status_id"];
	
	$emp_info_class = new EmployeeInformation;
	$emp_info_class->updateATMstatus($emp_id);
	
	$row = $emp_info_class->getEmpInfoByRow($emp_id);
	$emp_name = $row->Lastname . ", " . $row->Firstname . " " . $row->Middlename;

	$_SESSION["success_update_atm_status"] = "<center><span class='glyphicon glyphicon-ok' style='color:#196F3D'></span> <b>ATM Status</b> of <b>".$emp_name."</b> is successfully updated.</center>";

	header("Location:../employee_list.php");*/

	$emp_id = $_SESSION["update_emp_atm_status_id"];
	$emp_info_class = new EmployeeInformation;
	$atmAccountNo = 0;
	$row = $emp_info_class->getEmpInfoByRow($emp_id);
	if ($row->WithAtm == 0){
		if (isset($_POST["atmNo"])){
			$atmAccountNo = $_POST["atmNo"];
		}
		else {
			header("Location:../MainForm.php");
		}
	}

	$emp_info_class->updateATMstatus($emp_id,$atmAccountNo);
	$row = $emp_info_class->getEmpInfoByRow($emp_id);
	$emp_name = $row->Lastname . ", " . $row->Firstname . " " . $row->Middlename;

	$_SESSION["success_update_atm_status"] = "<center><span class='glyphicon glyphicon-ok' style='color:#196F3D'></span> <b>ATM Status</b> of <b>".$emp_name."</b> is successfully updated.</center>";

	header("Location:../employee_list.php");
}

else {
	header("Location:../MainForm.php");
}

?>