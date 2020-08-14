<?php
session_start();
include "../class/connect.php";
include "../class/leave_class.php";
include "../class/emp_information.php";
include "../class/attendance_notifications.php";
include "../class/date.php";
include "../class/audit_trail_class.php";


if (isset($_GET["leave_id"]) && isset($_GET["approve"])) {
	$leave_id = $_GET["leave_id"];
	$approve = $_GET["approve"];

	$leave_class = new Leave;
	$emp_info_class = new EmployeeInformation;

	// for updating the leave
	//$leave_class->approveLeave($leave_id);

	
	$date_class = new date;
	$approveDate = $date_class->getDate();
	$row = $leave_class->getInfoByLeaveId($leave_id);

	// if approve
	if ($approve == "Approve"){

		$approveStat = 0;
		if ($row->head_emp_id == 0 || $row->approveStat == 0){
			$approveStat = 1;
		}

		$leave_class->approveLeave($leave_id,$approveStat,$approveDate);

		$_SESSION["success_approve_leave"] = "success";

	}

	// if disapprove
	else {
		// disapproveLeave
		$leave_class->disapproveLeave($leave_id,$approveDate);
		$_SESSION["success_disapprove_leave"] = "success";
	}

	
	$emp_id = $row->emp_id;
	$approver_id = $_SESSION["id"];
	$dateFrom = $row->dateFrom;
	$dateTo = $row->dateTo;
	$leaveType = $row->LeaveType;

	

	//$final_attendance_date = $date_class->dateFormat($ot_date);

	$date_create = date_create($dateFrom);
	$final_date_from = date_format($date_create, 'F d, Y');

	$date_create = date_create($dateTo);
	$final_date_to = date_format($date_create, 'F d, Y');

	
	$notifType = "File ".$leaveType." from $final_date_from to $final_date_to";
	$status = $approve;
	$dateTime = $date_class->getDateTime();



	// for giving a notifications for approval
	$attendance_notifications_class = new Attendance_Notifications; // for notifications totally
	$attendance_notifications_class->insertNotifications($emp_id,$approver_id,"0","0","$leave_id",$notifType,$approve . " Leave",$status,$dateTime); //$emp_id,$approve_emp_id,$status,$dateTime,$dateCreated)

	$audit_trail_class = new AuditTrail;
	$module = "Leave Request List";
	$task_description = $approve . " " . $leaveType . ", " . $row->FileLeaveType;
	$audit_trail_class->insertAuditTrail($emp_id,$approver_id,'0',$module,$task_description,$dateTime);


	header("Location:../leave.php");

}

else {
	header("Location:../MainForm.php");
}

?>