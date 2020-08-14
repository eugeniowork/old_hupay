<?php
session_start();
include "../class/connect.php";
include "../class/log_in.php";
include "../class/emp_information.php";
include "../class/company_class.php";

// for security purpose
// if set success 
if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["company"])){
	
	$username = $_POST["username"];
	$password = $_POST["password"];
	$company_id = $_POST["company"];

	//$password = password_hash($password, PASSWORD_DEFAULT);

	// class for log in

	$log_in = new Login;
	$exist = $log_in->setLogin($username,$password);

	$company_class = new Company;

		
	// failed
	if ($exist == 0){
		$_SESSION["failed_log_in"] = "<b>Invalid Username or Password</b>";
		header("Location:../index.php");
	}

	// success
	else {
		$user_id = $log_in->LoginDetails($username,$password);

		$emp_info_class = new EmployeeInformation;
		$row = $emp_info_class->getEmpInfoByRow($user_id);

		if ($row->ActiveStatus == 0) {
			$gender = $row->Gender;
			$pronoun = "she";
			if ($gender == "Male"){
				$pronoun = "he";
			}
			$_SESSION["failed_log_in"] = "<b>This user is set to inactive status. You are no longer permitted to access this site.</b>";
			header("Location:../index.php");
		}

		// if d exist ung company id
		else if ($company_class->checkExistCompanyId($company_id) == 0){
			$_SESSION["failed_log_in"] = "<b>There is an error during getting of data.</b>";
			header("Location:../index.php");
		}

		else if ($row->company_id != $company_id){
			$row_company = $company_class->getInfoByCompanyId($company_id);

			$_SESSION["failed_log_in"] = "<b>Your are not <b>".$row_company->company."</b> employee.</b>";
			header("Location:../index.php");
		}

		else {
			$_SESSION["id"] = $user_id;
			$_SESSION["role"] = $log_in->getRole($user_id);
			//echo $_SESSION["role"];
			header("Location:../MainForm.php");
		}
	}

}
// if not set failed
else {
	//$_SESSION["failed_log_in"] = "Invalid Username or Password";
	header("Location:../index.php");
}


?>