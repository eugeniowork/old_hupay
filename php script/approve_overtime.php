<?php
session_start();
include "../class/connect.php";
include "../class/attendance_overtime.php";
include "../class/date.php";
//include "../class/time_in_time_out.php";
include "../class/attendance_notifications.php";
include "../class/audit_trail_class.php";


if (isset($_POST["password"]) && isset($_GET["approve"])) {


	$password =  $_POST["password"];
	$attendance_ot_class = new Attendance_Overtime;
	

	$approve = $_GET["approve"];


	$attendance_ot_id = $_GET["attendance_ot_id"];
	
	//echo $password . "<br/>";
	//echo $approve . "<br/>";
	//echo $attendance_ot_id . "<br/>";
	$row = $attendance_ot_class->getInfoByAttendanceOtId($attendance_ot_id);
	
	if ($approve == "Approve"){
		

		// for updating notif status in attendance notif table

		$approve_stat = 0;
		if ($row->head_emp_id == 0 || $row->approve_stat == 0){
			$approve_stat = 1;
		}

		
		$attendance_ot_class->updateApproveRequest($attendance_ot_id,$approve_stat);

		$_SESSION["success_approve_ot"] = "success";

		$task_description = "Approve File OT";

	}

	else {
		// 
		$attendance_ot_class->updateDisapproveRequest($attendance_ot_id);
		$_SESSION["success_disapprove_ot"] = "success";

		$task_description = "Disapprove File OT";
	}

	

	$emp_id = $row->emp_id;
	$approver_id = $_SESSION["id"];
	$time_from = $row->time_from;
	$time_out = $row->time_out;
	$ot_date = $row->date;



	$date_class = new date;

	$final_attendance_date = $date_class->dateFormat($ot_date);

	$date_create = date_create($time_from);
	$final_time_in = date_format($date_create, 'g:i A');

	$date_create = date_create($time_out);
	$final_time_out = date_format($date_create, 'g:i A');

	
	$notifType = "File Overtime on $final_attendance_date from $final_time_in to time out $final_time_out";
	$status = $approve;
	$dateTime = $date_class->getDateTime();



	// for giving a notifications for approval
	$attendance_notifications_class = new Attendance_Notifications; // for notifications totally
	$attendance_notifications_class->insertNotifications($emp_id,$approver_id,'0',$attendance_ot_id,'0',$notifType,$approve." OT",$status,$dateTime); //$emp_id,$approve_emp_id,$status,$dateTime,$dateCreated)
	
	// for inserting to audit trail
	$audit_trail_class = new AuditTrail;
	$module = "File Overtime List";
	$audit_trail_class->insertAuditTrail($emp_id,$approver_id,'0',$module,$task_description,$dateTime);


	header("Location:../file_ot_list.php");

}

else {


	header("Location:../MainForm.php");
}




?>