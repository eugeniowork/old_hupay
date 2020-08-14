<?php

include "../class/connect.php";
include "../class/emp_information.php";



if (isset($_POST["username"]) && isset($_POST["password"])){

	$username = $_POST["username"];
	$password = $_POST["password"];

	$emp_info_class = new EmployeeInformation;

	$row = $emp_info_class->getEmpInfoByUsername($username);

	$emp_id = $row->emp_id;


	$password = password_hash($password, PASSWORD_DEFAULT);

	$emp_info_class->changePassword($emp_id,$password);

	$emp_info_class->updateGeneratedCode($emp_id,"");

	echo "Success";

	// changePassword

}

else {
	header("Location:../index.php");
}


?>