<?php
session_start();
include "../class/connect.php";
include "../class/emp_information.php";
include "../class/role.php";
include "../class/date.php";
include "../class/audit_trail_class.php";

$emp_id = $_SESSION["update_emp_id"];

if (isset($_POST["update_role"])) {
	$role = $_POST["update_role"];

	$emp_info_class = new EmployeeInformation;
	$num_rows = $emp_info_class->sameRoleInfo($emp_id,$role);

	// for security issue
	if ($role == "") {
		echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove'></span> There's an error during updating employee info, Information did not update.</center>";
	}

	// if no change has made
	else if ($num_rows == 1){
		echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove'></span> No updates were taken, No changes was made.</center>";
	}

	// if success
	else {
		$emp_info_class->updateAccountInfo($emp_id,$role);
		echo "<center style='color:#196F3D'><span class='glyphicon glyphicon-ok'></span> Employee Account Information is Successfully Updated.</center>";

		$date_class = new date;
		$dateTime = $date_class->getDateTime();
		$audit_trail_class = new AuditTrail;
		$module = "Update Account Information";
		$audit_trail_class->insertAuditTrail($emp_id,0,$_SESSION["id"],$module,"Update Account Information",$dateTime);
	}
}

else {
	header("Location:../Mainform.php");
}


?>