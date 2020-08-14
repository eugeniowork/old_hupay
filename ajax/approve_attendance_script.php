<?php
session_start();
include "../class/connect.php";
include "../class/emp_information.php";

if (isset($_POST["password"])){
	$emp_id = $_SESSION["id"];

	//echo $emp_id;

	$emp_info_class = new EmployeeInformation;
	$row = $emp_info_class->getEmpInfoByRow($emp_id);
	$db_password = $row->Password;

	$password = $_POST["password"];

	//echo $password;

	if (!password_verify($password, $db_password)) {
		echo "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> You have entered an invalid password.</center>";
	}
}
else {
	header("Location:../Mainform.php");
}

?>