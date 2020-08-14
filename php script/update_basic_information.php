<?php
session_start();
include "../class/connect.php";
include "../class/emp_information.php";
include "../class/date.php";
include "../class/email_validation_format.php";
include "../class/audit_trail_class.php";

$emp_id = $_SESSION["update_emp_id"]; 

if (isset($_POST["lastname"]) && isset($_POST["firstname"]) && isset($_POST["middlename"])
	 && isset($_POST["civilstatus"]) && isset($_POST["address"]) && isset($_POST["birthdate"])
	 && isset($_POST["gender"])&& isset($_POST["contactNo"]) && isset($_POST["emailAdd"])){


	$date_class = new date;

	// this variable is for error in regex
	$has_error_contactNo = 0;
	$count_contactNo = strlen($_POST["contactNo"]); // check the length

	// the contact number length must be 7 9 11
	// 11 - contact number
	// 9 - landline number with area code
	// 7 - landline number only

	if ($count_contactNo != 7 && $count_contactNo != 9 && $count_contactNo != 11) {
		$has_error_contactNo = 1;		
	}

		// if 11 cp number 09 cmula then follow by any 9 digits
	if ($count_contactNo == 11) {
		$regex = '/^[0]{1}[9]{1}[0-9]{9}$/i';

  		if (!preg_match($regex, $_POST["contactNo"])) {
  			$has_error_contactNo = 1;
  		}

	}

	

	$last_name = $_POST["lastname"];
	$first_name = $_POST["firstname"];
	$middle_name = $_POST["middlename"];
	$civil_status = $_POST["civilstatus"];
	$address = $_POST["address"];
	//$birthdate = $date_class->dateDefaultDb($_POST["birthdate"]);
	$gender = $_POST["gender"];
	$contactNo = $_POST["contactNo"];
	$emailAddress = $_POST["emailAdd"];

	$birthday_month = substr($_POST["birthdate"],0,2);
	$birthday_day = substr(substr($_POST["birthdate"], -7), 0,2);
	$birthday_year = substr($_POST["birthdate"], -4);


	$email_class = new Email;
	$valid_email = $email_class->validateEmail($emailAddress);


	$emp_info_class = new EmployeeInformation;
	// check if no changes made
	//$num_rows = ;

	// if required fields is empty
	if ($last_name == "" || $first_name == "" || $civil_status == "" || $address == "" || $_POST["birthdate"] == "" || $gender == "") {
		//$_SESSION["update_emp_info_error_msg"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There's an error during updating employee info, Information did not update.</center>";
		echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove'></span> There's an error during updating employee info, Information did not update.</center>";
	}

	// if contact number is not valid
	else if ($contactNo != "" && $has_error_contactNo == 1){
		echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove'></span> The Contact Number is not match to the format.</center>";
	}

	// if invalid email format
	else if ($emailAddress != "" && $valid_email == 0){
		echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove'></span> The Email Address is not valid. </center>";
	}

	else if ($civil_status != "Single" && $civil_status != "Married") {
		echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove'></span> The Civil Status must be only Single or Married.</center>";
	}

	else if ($gender != "Male" && $gender != "Female") {
		echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove'></span> The Gender must be only Male or Female.</center>";
	}


	else if (!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$_POST["birthdate"])) {
    	echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove' style=''></span> <b>Birthdate</b> not match to the current format mm/dd/yyyy</center>";
	}

	// for validating leap year
	else if ($birthday_year % 4 == 0 && $birthday_month == 2 && $birthday_day >= 30){
		echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove' style=''></span> Invalid <b>Birthdate</b> date</center>";
	}

	// for validating leap year also
	else if ($birthday_year % 4 != 0 && $birthday_month == 2 && $birthday_day >= 29){
		echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove' style=''></span> Invalid <b>Birthdate</b> date</center>";
	}

	// mga month na may 31
	else if (($birthday_month == 4 || $birthday_month == 6 || $birthday_month == 9 || $birthday_month == 11)
			&& $birthday_day  >= 31){
		echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove' style=''></span> Invalid <b>Birthdate</b> date</center>";
	}

	else if ($emp_info_class->sameBasicInfo($emp_id,$last_name,$first_name,$middle_name,
												$civil_status,$address,$date_class->dateDefaultDb($_POST["birthdate"]),$gender,
												$contactNo,$emailAddress)== 1){
		echo "<center style='color:#CB4335'><span class='glyphicon glyphicon-remove'></span> No updates were taken, No changes was made.</center>";
	}

	// success
	else {
		$birthdate = $date_class->dateDefaultDb($_POST["birthdate"]);
		$emp_info_class->updateBasicInfo($emp_id,$last_name,$first_name,$middle_name,
												$civil_status,$address,$birthdate,$gender,
												$contactNo,$emailAddress);
		//$_SESSION["success_msg_update_basic_info"] = "<center><span class='glyphicon glyphicon-ok' style='color:#196F3D'></span> Employee Information is Successfully Updated.</center>";
		echo "<center style='color:#196F3D'><span class='glyphicon glyphicon-ok'></span> Employee Basic Information is Successfully Updated.</center>";


		$dateTime = $date_class->getDateTime();
		$audit_trail_class = new AuditTrail;
		$module = "Update Basic Information";
		$audit_trail_class->insertAuditTrail($emp_id,0,$_SESSION["id"],$module,"Update Basic Information",$dateTime);
	}


}

else {
	header("Location:../MainForm.php");
}

//header("Location:../employee_list.php");



?>