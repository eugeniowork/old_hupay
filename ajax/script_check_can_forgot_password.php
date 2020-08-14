<?php

include "../class/connect.php";
include "../class/emp_information.php";


if (isset($_POST["username"]) && isset($_POST["generated_code"])){

	$username = $_POST["username"];
	$generated_code = $_POST["generated_code"];


	$emp_info_class = new EmployeeInformation;


	if ($emp_info_class->checkExistUsername($username) == 0){
		echo "Username is not exist";
	}

	else {	


		$row = $emp_info_class->getEmpInfoByUsername($username);

		$db_generated_code = $row->generated_code;


		if ($db_generated_code == ""){
			echo "No set generated code for forgot password, contact administrator to set generated code.";
		}

		else {


			if ($db_generated_code != $generated_code){
				echo "Generated Code for username " . $username . " is not match";
			}

			else {

				echo "Success";

				//echo "READY FOR LOGIC!";
			}
		}

		
	}	

}

else {
	header("Location:../index.php");
}


?>