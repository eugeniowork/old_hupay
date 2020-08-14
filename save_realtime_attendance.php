<?php
include "class/connect.php";
include "class/time_in_time_out.php";


$attendance_class = new Attendance;

date_default_timezone_set("Asia/Manila");

if (isset($_POST["deviceToken"]) && isset($_POST["passphrase"])){
	// Put your device token here (without spaces):
	$deviceToken = $_POST['deviceToken'];
	// Put your private key's passphrase here:
	$passphrase = $_POST['passphrase'];
	$bio_id = $_GET["bio_id"];

	$dateVal = date("Y-m-d");
	$timeVal = date("H:i:s");

	//$dateVal = date("Y-m-d H:i:s");
	//$dateVal = date_create($dateVal);
	//date_sub($date, date_interval_create_from_date_string('15 hours'));

	// $current_date_time = date_format($date, 'Y-m-d H:i:s');
	//$dateVal = date_format($dateVal, 'Y-m-d');

	//$timeVal = date("Y-m-d H:i:s");
	//$timeVal = date_create($timeVal);
	//date_sub($date, date_interval_create_from_date_string('15 hours'));

	// $current_date_time = date_format($date, 'Y-m-d H:i:s');
	//$timeVal = date_format($timeVal, 'Y-m-d');

	//insert
	if ($attendance_class->checkExistRealTimeAttendance($dateVal,$bio_id) == 0){
		//$attendance_class->insertRealTimeAttendance($bio_id);
		$attendance_class->insert_time_in_time_out($bio_id,$dateVal,$timeVal,"",$dateVal);
	}
	// update
	else {
		$attendance_class->updateTimeOut($dateVal,$bio_id,$timeVal);
	}


	
}

else {
	echo "NOT AUTHORIZE TO VIEW THIS PAGE!";
}
	



?>