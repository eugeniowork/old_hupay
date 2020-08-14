<?php
session_start();
include "../class/connect.php";
include "../class/leave_class.php";
include "../class/date.php";
include "../class/working_days_class.php";
include "../class/holiday_class.php";
include "../class/emp_information.php";

if (isset($_POST["leave_id"]) && isset($_POST["update_formal_leaveType"]) && isset($_POST["update_formal_dateFrom_Leave"]) 
	&& isset($_POST["update_formal_dateTo_Leave"]) && isset($_POST["update_formal_remarks_Leave"])){


	$date_class = new date;
	$leave_id = $_POST["leave_id"];
	$leave_type = $_POST["update_formal_leaveType"];
	$dateFrom = $date_class->dateDefaultDb($_POST["update_formal_dateFrom_Leave"]);
	$dateTo = $date_class->dateDefaultDb($_POST["update_formal_dateTo_Leave"]);
	$remarks = $_POST["update_formal_remarks_Leave"];

	$leave_class = new Leave;
	$emp_info_class = new EmployeeInformation;
	$working_days_class = new WorkingDays;
	$holiday_class = new Holiday;

	$row = $leave_class->getInfoByLeaveId($leave_id);
	$fileLeaveType = $row->FileLeaveType;

	$exist_leave_type = 0;
	$no_days_to_file = 0;
	$lv_id = 0;
	$name = "";
	
	if ($leave_class->checkExistLeaveType($leave_type) == 1){

		$row_leave_type = $leave_class->getLeaveTypeById($leave_type);

		$lv_id = $row_leave_type->lv_id;
		$no_days_to_file = $row_leave_type->no_days_to_file;
		$name = $row_leave_type->name;
		//$db_leave_count = $row_leave_type->count;

		$exist_leave_type = 1;
	}

	$row = $emp_info_class->getEmpInfoByRow($row->emp_id);
	$bio_id = $row->bio_id;
	$row_wd = $working_days_class->getWorkingDaysInfoById($row->working_days_id);

	$day_from = $row_wd->day_from;
	$day_to = $row_wd->day_to;
	


	// first check muna antin kung exist ung leave type
	if ($exist_leave_type == 0){
		$_SESSION["file_leave_error"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There's an error during filing of leave";
	}

	else if (!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$_POST["update_formal_dateFrom_Leave"])) {
    	$_SESSION["file_leave_error"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>Date From</b> not match to the current format mm/dd/yyyy";
	}

	else if (!preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/",$_POST["update_formal_dateTo_Leave"])) {
    	$_SESSION["file_leave_error"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>Date To</b> not match to the current format mm/dd/yyyy";
	}
	
	else if ($dateFrom > $dateTo) {
		$_SESSION["file_leave_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> The <b>Date From</b> must be below the date of the declared <b>Date To</b></center>";
	}
	else {
		$lt_id = $leave_type;

		$leave_class->updateFileLeaveWithPay($leave_id,$name,$lt_id,$dateFrom,$dateTo,$remarks);
		$_SESSION["success_crud_leave"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> Your File Leave is successfully updated.</center>";

	}
	//echo $leave_id;
	//header("Location:../view_attendance.php");
}
else {
	header("Location:../MainForm.php");
}

?>