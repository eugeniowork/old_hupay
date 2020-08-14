<?php
include "../class/connect.php";
include "../class/emp_information.php";



if (isset($_POST["emp_id"]) && isset($_POST["accountNo"])){
	$emp_id = $_POST["emp_id"];
	$accountNo = $_POST["accountNo"];

	$emp_info_class = new EmployeeInformation;

	// if no changes was made 
	if ($emp_info_class->checkNoChangesUpdateAtmAccountNo($emp_id,$accountNo) == 1){
		echo "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> No changes was made, No updates were taken.";
	}

	else {
		$emp_info_class->updateAtmAccountNo($emp_id,$accountNo);
		echo "<span class='glyphicon glyphicon-ok' style='color:#196F3D'></span> ATM Account No is successfully updated.";
	}

}

else {
	header("Location:../MainForm.php");
}

?>