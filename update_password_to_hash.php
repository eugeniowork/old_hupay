<?php
	

	include "class/connect.php"; // fixed class
	include "class/emp_information.php";
	$emp_info = new EmployeeInformation;


	/*if (isset($_GET["admin_authencticate_code"]) && $_GET["admin_authencticate_code"] == "9df3b01c60df20d13843841ff0d4482c"){


	}

	else {
		echo "Unaccessible!";
	}*/
	ini_set('max_execution_time', -1);
	$emp_info->updatePasswordToHash();

?>