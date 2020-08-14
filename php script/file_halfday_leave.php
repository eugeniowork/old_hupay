<?php
session_start();
include "../class/connect.php";
include "../class/leave_class.php";
include "../class/date.php";
include "../class/emp_information.php";
include "../class/attendance_notifications.php";


if (isset($_POST["halfday_leave_type_leave"]) && isset($_POST["halfday_leave_period"]) && isset($_POST["halfday_leave_date"]) && isset($_POST["halfday_remarks_Leave"])) {

	$emp_id = $_SESSION["id"];
	$date_class = new date;

	$leaveType = $_POST["halfday_leave_type_leave"];
	$period_leave_type = $_POST["halfday_leave_period"];
	//$dateFrom = $date_class->dateDefaultDb($_POST["dateFrom_Leave"]);
	//$dateTo = $date_class->dateDefaultDb($_POST["dateTo_Leave"]);
	$remarks = $_POST["halfday_remarks_Leave"];

	$date_month = substr($_POST["halfday_leave_date"],0,2);
	$date_day = substr(substr($_POST["halfday_leave_date"], -7), 0,2);
	$date_year = substr($_POST["halfday_leave_date"], -4);

	

	/*echo $leaveType . "<br/>";
	echo $dateFrom . "<br/>";
	echo $dateTo . "<br/>";
	echo $remarks . "<br/>";


	*/

	$date1=date_create(date_format(date_create($_POST["halfday_leave_date"]),"Y-m-d"));
	$date2=date_create(date_format(date_create(date("Y-m-d")),"Y-m-d"));
	$diff =date_diff($date1,$date2);
	$wew =  $diff->format("%R%a");
	$days = str_replace("+","",$wew);

	$leave_class = new Leave;

	if ($leaveType == "Vacation Leave" && $days >= -1){
		$_SESSION["update_error_overtime"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>Date</b> must be 2 days before hands";
	}
	
	else if (!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$_POST["halfday_leave_date"])) {
    	$_SESSION["update_error_overtime"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>Date</b> not match to the current format mm/dd/yyyy";
	}

	// for validating leap year
	else if ($date_year % 4 == 0 && $date_month == 2 && $date_day >= 30){
		$_SESSION["update_error_overtime"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid <b>Date</b> date";
	}

	// for validating leap year also
	else if ($date_year % 4 != 0 && $date_month == 2 && $date_day >= 29){
		$_SESSION["update_error_overtime"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid <b>Date</b> date";
	}

	// mga month na may 31
	else if (($date_month == 4 || $date_month == 6 || $date_month == 9 || $date_month == 11)
			&& $date_day  >= 31){
		$_SESSION["update_error_overtime"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid <b>Date</b> date";
	}


	else {
		// success
		//$leave_class = new Leave;
		$emp_info_class = new EmployeeInformation;

		$date = $date_class->dateDefaultDb($_POST["halfday_leave_date"]);

		$dateFrom = $date_class->dateDefaultDb($_POST["halfday_leave_date"]);
		$dateTo = $date_class->dateDefaultDb($_POST["halfday_leave_date"]);

	
		$dateCreated = $date_class->getDate();

		$row_emp = $emp_info_class->getEmpInfoByRow($emp_id);
		$head_emp_id = $row_emp->head_emp_id;
		//echo $head_emp_id . "<br/>";
		$fileLeaveType = $period_leave_type . " " . $_GET["FileLeaveType"];

		$notifFileLeave = "File Halfday Leave";

		$leave_count = $row_emp->leave_count;
		//echo $leave_count . "<br/>";

		//$leave_range_count = ((strtotime($dateTo) - strtotime($dateFrom)) / 86400) + 1;

		$leave_range_count = .5;

	//	echo $leave_range_count;

		//echo $leave_class->existDateFromDateTo($emp_id,$date_class->dateDefaultDb($_POST["halfday_leave_date"]),$date_class->dateDefaultDb($_POST["halfday_leave_date"]),$fileLeaveType);

		if ($leave_range_count <= $leave_count){


			//if ($fileLeaveType == "Leave without pay") {
			//	$notifFileLeave = "File Formal Leave";
			//}

			
			if ($leave_class->existDateFromDateTo($emp_id,$date_class->dateDefaultDb($_POST["halfday_leave_date"]),$date_class->dateDefaultDb($_POST["halfday_leave_date"]),$fileLeaveType) != 0){
					
				// approveStat
				$approveStat = 0;
				// ibig sabihin staff xa
				if ($head_emp_id != 0){
					$approveStat = 4;
				}

				// ibig sabihin head xa
				else if ($head_emp_id == 0){
					$approveStat = 0;
				}

				$leave_class->updateLeave($emp_id,$head_emp_id,$dateFrom,$dateTo,$leaveType,$remarks,$fileLeaveType,$dateCreated);
			}
			else {
				// for inserting
				// approveStat
				$approveStat = 0;
				// ibig sabihin staff xa
				if ($head_emp_id != 0){
					$approveStat = 4;
				}

				// ibig sabihin head xa
				else if ($head_emp_id == 0){
					$approveStat = 0;
				}

				$leave_class->insertLeave($emp_id,$head_emp_id,$date,$date,$leaveType,$remarks,$fileLeaveType,$approveStat,$dateCreated); 
			}
			

			// mabigyan ng notifications admin,hr lang so ung role id is 2 and 1
			$emp_id_values = explode("#",$emp_info_class->getEmpIdByNotification($emp_id));
			//echo $emp_id_values[0];

			$count = $emp_info_class->getEmpIdByNotificationCount($emp_id) - 1;
			//echo $count;
			$final_date_from = $date_class->dateFormat($dateFrom);
			$final_date_to = $date_class->dateFormat($dateTo);

			

			$counter = 0;
			do {

				$emp_id = $emp_id_values[$counter];
				//echo $emp_id . "<br/>";
				
				$approver_id = $_SESSION["id"];
				$notifType = "File " .$period_leave_type. " Halfday ".$leaveType." from $final_date_from to $final_date_to";
				$status = "Pending";
				$dateTime = $date_class->getDateTime();

				// wla muna notification sa file leave
				$attendance_notifications_class = new Attendance_Notifications; // for notifications totally
				$attendance_notifications_class->insertNotifications($emp_id,$approver_id,'0','0',$leave_class->leaveLastId(),$notifType,$notifFileLeave,$status,$dateTime); //$emp_id,$approve_emp_id,$status,$dateTime,$dateCreated)
				

				$counter++;
			}while($counter <= $count);

			$date_leave = $date_class->dateFormat($date);
			$_SESSION["file_leave_success"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> File <b>$leaveType</b> with the date <b>$date_leave</b> is successfully filed</center>";
		
		} // end of if
		else {
			$_SESSION["file_leave_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> You cannot file leave with pay because your remaining leave count is less than the date range you've enter.</center>";
		}

		
	}


	header("Location:../view_attendance.php");

}

else {
	header("Location:../MainForm.php");
}



?>