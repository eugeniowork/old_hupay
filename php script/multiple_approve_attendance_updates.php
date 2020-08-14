<?php
session_start();


include "../class/connect.php";
include "../class/attendance_notif.php";
include "../class/time_in_time_out.php";
include "../class/attendance_notifications.php";
include "../class/date.php";
include "../class/emp_information.php";
include "../class/audit_trail_class.php";



if (isset($_GET["approve"])){
	$approve = $_GET["approve"];

	$date_class = new date;

	$emp_id = $_SESSION["id"];
	//echo $approve;
	$role = $_SESSION["role"];

	//echo $emp_id . "<br/>";
	//echo $role;
	$attendance_notif_class = new AttendanceNotif;
	$count = $attendance_notif_class->attendanceNotifToTableCount($emp_id,$role);
	$counter = 1;
	do {

		if (isset($_POST["attendance_request".$counter])){
			$attendance_notif_id = $_POST["attendance_request".$counter];
			$row = $attendance_notif_class->getRequestAttendanceById($attendance_notif_id);
			if ($approve == "approve"){
				
				// for updating notif status in attendance notif table

				$notif_status = 0;
				if ($row->head_emp_id == 0 || $row->notif_status == 0){
					$notif_status = 1;
				}

				
				$attendance_notif_class->updateApproveRequest($attendance_notif_id,$notif_status);


				$emp_id = $row->emp_id;
				
				$time_in = $row->time_in;
				$time_out = $row->time_out;
				$attendance_date = $row->date; 


				$attendance_id = $row->attendance_id;
				// for updating notif status
				//
				$attendance_class = new Attendance;

				$emp_info_class = new EmployeeInformation;
				$row_emp = $emp_info_class->getEmpInfoByRow($emp_id);
				$bio_id = $row_emp->bio_id;

				// first check if exist ung attendance id kapag exist update lang , kapag d exist insert
				if ($attendance_class->checkExistByAttendanceId($attendance_id) == 1){
					$attendance_class->updateRequestTimeInTimeOut($attendance_id,$time_in,$time_out);
					//echo "update";
				}
				// if did not exist insert
				else {
					//echo "insert";
					$attendance_class->insert_time_in_time_out($bio_id,$attendance_date,$time_in,$time_out,$date_class->getDate());
				}

				$_SESSION["success"] = "success";

				$task_description = "Approve Attendance Updates";
			}

			else {
				// 
				$attendance_notif_class->updateDisapproveRequest($attendance_notif_id);
				$_SESSION["success_disapprove"] = "success";

				$task_description = "Dispprove Attendance Updates";
			}


			

			$final_attendance_date = $date_class->dateFormat($attendance_date);

			$date_create = date_create($time_in);
			$final_time_in = date_format($date_create, 'g:i A');

			$date_create = date_create($time_out);
			$final_time_out = date_format($date_create, 'g:i A');


			$emp_id = $emp_id = $row->emp_id;
			$approver_id = $_SESSION["id"];
			$notifType = "Update Attendance on $final_attendance_date with time in $final_time_in and time out $final_time_out";
			$status = $approve;
			$dateTime = $date_class->getDateTime();





			// dito ako hihinto


			// for giving a notifications for approval
			$attendance_notifications_class = new Attendance_Notifications; // for notifications totally
			$attendance_notifications_class->insertNotifications($emp_id,$approver_id,$attendance_notif_id,'0','0',$notifType,$approve." Attendance",$status,$dateTime); //$emp_id,$approve_emp_id,$status,$dateTime,$dateCreated)


			// for audit trail
			$audit_trail_class = new AuditTrail;
			$module = "Attendance Updates List";
			$audit_trail_class->insertAuditTrail($emp_id,$approver_id,'0',$module,$task_description,$dateTime);
				}


		$counter++;
	}while($count >= $counter);

	header("Location:../attendance_notif_list.php");

}
else {
	header("Location:../MainForm.php");
}


?>