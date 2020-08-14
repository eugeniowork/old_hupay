<?php
session_start();
include "../class/connect.php";
include "../class/message_class.php";
include "../class/date.php";
include "../class/emp_information.php";


if (isset($_GET["message_id"]) && isset($_POST["message_reply"])) {
	
	$message_class = new Message;
	$date_class = new date;
	$emp_info_class = new EmployeeInformation;
	$message_id = $_GET["message_id"];
	$reply = $_POST["message_reply"];

	$row = $message_class->getInfoByMessageId($message_id);
	$from = $_SESSION["id"];


	// if sa kanya ung message
	$own_message = 0;
	if ($row->from_emp_id == $from){
		$own_message = 1;
	}

	$message_class->unreadMessage($message_id,$own_message);


	$to = $row->from_emp_id; // ung nagpadala ng message
	$dateCreated = $date_class->getDateTime();

	/*echo $message_id . "<br/>";
	echo $from . "<br/>";
	echo $to . "<br/>";
	echo $reply . "<br/>";
	echo $dateCreated;*/

	// for inserting to message reply
	$message_class->insertMessageReply($message_id,$from,$to,$reply,$dateCreated);

	$row_emp = $emp_info_class->getEmpInfoByRow($to);

	$message_from_name = $row_emp->Firstname . " " . $row_emp->Lastname;

	$_SESSION["success_reply_message"] = "<center><span class='glyphicon glyphicon-ok'  style='color:#196F3D'></span> Your reply to message </b> about <b>$row->subject</b> is successfully sent."; 

	header("Location:../message_inbox.php");

}
else {
	header("Location:../MainForm.php");
}

?>