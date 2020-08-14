<?php
session_start();
include "../class/connect.php";
include "../class/emp_information.php";
include "../class/date.php";
include "../class/audit_trail_class.php";

$emp_id = $_SESSION["update_emp_id"];

if (isset($_POST["update_sssNo"]) && isset($_POST["update_pagibigNo"]) && isset($_POST["update_tinNo"]) && isset($_POST["update_philhealthNo"])){
	$sssNo = $_POST["update_sssNo"];
	$pagibigNo = $_POST["update_pagibigNo"];
	$tinNo = $_POST["update_tinNo"];
	$philhealthNo = $_POST["update_philhealthNo"];

	$emp_info_class = new EmployeeInformation;
	$num_rows = $emp_info_class->sameGovtInfo($emp_id,$sssNo,$pagibigNo,$tinNo,$philhealthNo);

	// if no changes was made
	if ($num_rows == 1) {
		//$_SESSION["update_emp_info_error_msg"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> No updates were taken, No changes was made.</center>";	
		echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove'></span> No updates were taken, No changes was made.</center>";
	}

	// if sss no length is not equal to 10
	else if ($sssNo != "" &&strlen($sssNo) != 10){
		echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove'></span> SSS No. must composed of 10 digits.</center>";
	}

	// if sss no length is not equal to 10
	else if ($pagibigNo != "" &&strlen($pagibigNo) != 12){
		echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove'></span> Pag-ibig No. must composed of 12 digits.</center>";
	}

	else if ($tinNo != "" &&strlen($tinNo) != 9){
		echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove'></span> Tin No. must composed of 9 digits.</center>";
	}

	else if ($philhealthNo != "" &&strlen($philhealthNo) != 12){
		echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove'></span> Philhealth No. must composed of 12 digits.</center>";
	}


	else {
		$emp_info_class->updateGovtInfo($emp_id,$sssNo,$pagibigNo,$tinNo,$philhealthNo);
		echo "<center style='color:#196F3D'><span class='glyphicon glyphicon-ok'></span> Employee Gov't Information is Successfully Updated.</center";

		$date_class = new date;
		$dateTime = $date_class->getDateTime();
		$audit_trail_class = new AuditTrail;
		$module = "Update Government Information";
		$audit_trail_class->insertAuditTrail($emp_id,0,$_SESSION["id"],$module,"Update Government Information",$dateTime);
	}
}

else {
	header("Location:../MainForm.php");
}





?>