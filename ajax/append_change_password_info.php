<?php
session_start();
include "../class/connect.php";
include "../class/emp_information.php";
include "../class/log_in.php";


if (isset($_POST["currentPassword"]) && isset($_POST["newPassword"]) && isset($_POST["confirmPassword"])) {

	$emp_id = $_SESSION["id"];

	$currentPassword = $_POST["currentPassword"];
	$newPassword = $_POST["newPassword"];
	$confirmPassword = $_POST["confirmPassword"];

	$emp_info_class = new EmployeeInformation;
	$row = $emp_info_class->getEmpInfoByRow($emp_id); // for getting the values of information of the current log in user

	$username = $row->Username;

	$log_in = new Login;
	$exist = $log_in->setLogin($username,$currentPassword);

	// if the entered current password is not corrent
	if ($exist == 0){
		echo '<span class="glyphicon glyphicon-remove" style="color:#CB4335"></span> The current password is incorrect.';
	}

	// if the new password and confirm password is not match
	else if ($newPassword != $confirmPassword){
		echo '<span class="glyphicon glyphicon-remove" style="color:#CB4335"></span> The new password and confirm password does not match.';
	}

	else if (strlen($newPassword) < 8){
		echo '<span class="glyphicon glyphicon-remove" style="color:#CB4335"></span> The password length must be 8 and above character.';
	}

	else {

		// for changing password class
		$emp_info_class->changePassword($emp_id,password_hash($newPassword, PASSWORD_DEFAULT));

		echo '<span class="glyphicon glyphicon-check" style="color:#196F3D"></span> You change you password successfully.<br/><br/>';
		echo 'Do you want to log out and log in with your new password? click <a href="php script/log_out_script.php">here</a>';
	}

	// for corrent color #
	


}

else {
	header("Location:../MainForm.php");
}


?>