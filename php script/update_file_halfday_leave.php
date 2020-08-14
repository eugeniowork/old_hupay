<?php
session_start();
include "../class/connect.php";
include "../class/leave_class.php";
include "../class/date.php";

if (isset($_POST["update_halfday_leave_type_leave"]) && isset($_POST["update_halfday_leave_period"])
	&& isset($_POST["update_halfday_leave_date"]) && isset($_POST["update_halfday_remarks_Leave"])
	&& isset($_POST["leave_id"])){

	$date_class = new date;
	$leave_id = $_POST["leave_id"];
	$leave_type = $_POST["update_halfday_leave_type_leave"];
	$file_leave_type = $_POST["update_halfday_leave_period"] . " Halfday Leave with pay";
	$date = $date_class->dateDefaultDb($_POST["update_halfday_leave_date"]);
	$remarks = $_POST["update_halfday_remarks_Leave"];

	$leave_class = new Leave;
	



	$row = $leave_class->getInfoByLeaveId($leave_id);
	
	$date1=date_create(date_format(date_create($_POST["update_halfday_leave_date"]),"Y-m-d"));
	$date2=date_create(date_format(date_create($row->DateCreated),"Y-m-d"));
	$diff =date_diff($date1,$date2);
	$wew =  $diff->format("%R%a");
	$days = str_replace("+","",$wew);

	if ($leave_type == "Vacation Leave" && $days >= -1){
		$_SESSION["update_error_overtime"] = "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> <b>Date</b> must be 2 days before hands";
	}
	else {


	//if ($dateFrom > $dateTo) {
	//	$_SESSION["file_leave_error"] = "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> The <b>Date From</b> must be below the date of the declared <b>Date To</b></center>";
	//}
	//else {

		$leave_class->updateFileHalfLeaveWithPay($leave_id,$leave_type,$file_leave_type,$date,$remarks);
		$_SESSION["success_crud_leave"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> Your File Leave is successfully updated.</center>";
	}

	//}
	//echo $leave_id;
	header("Location:../view_attendance.php");
}
else {
	header("Location:../MainForm.php");
}

?>