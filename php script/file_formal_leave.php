<?php
session_start();
include "../class/connect.php";
include "../class/leave_class.php";
include "../class/date.php";
include "../class/emp_information.php";
include "../class/attendance_notifications.php";

// leaveType
if (isset($_POST["formal_leaveType"]) && isset($_POST["formal_dateFrom_Leave"]) && isset($_POST["formal_dateTo_Leave"]) && isset($_POST["formal_remarks_Leave"])){

	$emp_id = $_SESSION["id"];
	$date_class = new date;

	$leaveType = $_POST["formal_leaveType"];
	//$dateFrom = $date_class->dateDefaultDb($_POST["dateFrom_Leave"]);
	//$dateTo = $date_class->dateDefaultDb($_POST["dateTo_Leave"]);
	$remarks = $_POST["formal_remarks_Leave"];

	$dateFrom_month = substr($_POST["formal_dateFrom_Leave"],0,2);
	$dateFrom_day = substr(substr($_POST["formal_dateFrom_Leave"], -7), 0,2);
	$dateFrom_year = substr($_POST["formal_dateFrom_Leave"], -4);

	$dateTo_month = substr($_POST["formal_dateTo_Leave"],0,2);
	$dateTo_day = substr(substr($_POST["formal_dateTo_Leave"], -7), 0,2);
	$dateTo_year = substr($_POST["formal_dateTo_Leave"], -4);

	/*echo $leaveType . "<br/>";
	echo $dateFrom . "<br/>";
	echo $dateTo . "<br/>";
	echo $remarks . "<br/>";
	*/

	if (!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$_POST["formal_dateFrom_Leave"])) {
    	$_SESSION["update_error_overtime"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>Date From</b> not match to the current format mm/dd/yyyy";
	}

	// for validating leap year
	else if ($dateFrom_year % 4 == 0 && $dateFrom_month == 2 && $dateFrom_day >= 30){
		$_SESSION["update_error_overtime"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid <b>Date From</b> date";
	}

	// for validating leap year also
	else if ($dateFrom_year % 4 != 0 && $dateFrom_month == 2 && $dateFrom_day >= 29){
		$_SESSION["update_error_overtime"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid <b>Date From</b> date";
	}

	// mga month na may 31
	else if (($dateFrom_month == 4 || $dateFrom_month == 6 || $dateFrom_month == 9 || $dateFrom_month == 11)
			&& $dateFrom_day  >= 31){
		$_SESSION["update_error_overtime"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid <b>Date From</b> date";
	}

	else if (!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$_POST["formal_dateTo_Leave"])) {
    	$_SESSION["update_error_overtime"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>Date To</b> not match to the current format mm/dd/yyyy";
	}

	// for validating leap year
	else if ($dateTo_year % 4 == 0 && $dateTo_month == 2 && $dateTo_day >= 30){
		$_SESSION["update_error_overtime"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid <b>Date To</b> date";
	}

	// for validating leap year also
	else if ($dateTo_year % 4 != 0 && $dateTo_month == 2 && $dateTo_day >= 29){
		$_SESSION["update_error_overtime"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid <b>Date To</b> date";
	}

	// mga month na may 31
	else if (($dateTo_month == 4 || $dateTo_month == 6 || $dateTo_month == 9 || $dateTo_month == 11)
			&& $dateTo_day  >= 31){
		$_SESSION["update_error_overtime"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid <b>Date To</b> date";
	}

	else if ($_POST["formal_dateFrom_Leave"] > $_POST["formal_dateTo_Leave"]) {
		$_SESSION["file_leave_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> The <b>Date From</b> must be below the date of the declared <b>Date To</b></center>";
	}

	else {
		// success
		$leave_class = new Leave;
		$emp_info_class = new EmployeeInformation;

		$dateFrom = $date_class->dateDefaultDb($_POST["formal_dateFrom_Leave"]);
		$dateTo = $date_class->dateDefaultDb($_POST["formal_dateTo_Leave"]);
	
		$dateCreated = $date_class->getDate();

		$row_emp = $emp_info_class->getEmpInfoByRow($emp_id);
		$head_emp_id = $row_emp->head_emp_id;
		//echo $head_emp_id . "<br/>";
		$fileLeaveType = $_GET["FileLeaveType"];

		$notifFileLeave = "File Formal Leave";
		//if ($fileLeaveType == "Leave without pay") {
		//	$notifFileLeave = "File Formal Leave";
		//}

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


		// for inserting
		$leave_class->insertLeave($emp_id,$head_emp_id,$dateFrom,$dateTo,$leaveType,$remarks,$fileLeaveType,$approveStat,$dateCreated); 

		

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
			$notifType = "File Formal ".$leaveType." from $final_date_from to $final_date_to";
			$status = "Pending";
			$dateTime = $date_class->getDateTime();

			// wla muna notification sa file leave
			$attendance_notifications_class = new Attendance_Notifications; // for notifications totally
			$attendance_notifications_class->insertNotifications($emp_id,$approver_id,'0','0',$leave_class->leaveLastId(),$notifType,$notifFileLeave,$status,$dateTime); //$emp_id,$approve_emp_id,$status,$dateTime,$dateCreated)
			

			$counter++;
		}while($counter <= $count);


		$_SESSION["file_leave_success"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> File <b$leaveType</b> From <b>$final_date_from</b> to <b>$final_date_to</b> is successfully filed</center>";

	}


	header("Location:../view_attendance.php");
}

else {
	header("Location:../MainForm.php");
}

?>