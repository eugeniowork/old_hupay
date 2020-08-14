<?php
session_start();
include "../class/connect.php";
include "../class/emp_information.php";
include "../class/date.php";
include "../class/message_class.php";

if (isset($_POST["message_to"]) && isset($_POST["message_subject"]) && isset($_POST["message"])) {
	$message_to = $_POST["message_to"];
	$message_subject = $_POST["message_subject"];
	$message = $_POST["message"];
	$role = $_SESSION["role"];
	$emp_id = $_SESSION["id"];

	$emp_info_class = new EmployeeInformation;
	$date_class = new date;
	$message_class = new Message;

	$dateCreated = $date_class->getDateTime();


	// for retrieval purpose
	$_SESSION["message_message_to"] = $message_to;
	$_SESSION["message_message_subject"] = $message_to;
	$_SESSION["message_message"] = $message;


	// check if exist ung name
	if ($emp_info_class->checkExistEmployeeName($message_to) == 1) {
		$_SESSION["error_send_message"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>Employee Name $message_to</b> is not exist in the employee list";
	}

	// for checkinf if minessage nya ung sarili nya
	else if ($emp_info_class->checkMessageSelf($emp_id,$message_to) == 1) {
		$_SESSION["error_send_message"] =  "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> You are not authorized to message <b>yourself</b>";
	}

	// check if exist but not authorized to message
	//else if ($emp_info_class->checkExistEmployeeNameByRole($message_to,$role) == 1) {
	//	$_SESSION["error_send_message"] =  "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> You are not authorized to message <b>$message_to</b>";
	//}

	// success for inserting
	else {
		$to_emp_id = $emp_info_class->getEmpIdByEmployeeName($message_to);

		$message_class->insertMessage($emp_id,$to_emp_id,$message_subject,$message,$dateCreated);

		// for resetting values
		// for retrieval purpose
		$_SESSION["message_message_to"] = null;
		$_SESSION["message_message_subject"] = null;
		$_SESSION["message_message"] = null;
	

		$_SESSION["success_send_message"] =  "<center><span class='glyphicon glyphicon-ok'  style='color:#196F3D'></span> Your message to <b>$message_to</b> is successfully sent";
	}

	header("Location:../create_message.php");


}

else {
	header("Location:../MainForm.php");
}


?>