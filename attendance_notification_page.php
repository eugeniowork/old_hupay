<?php
session_start();
if (!isset($_SESSION["id"])){
	header("Location:index.php");
}
include "class/connect.php"; // fixed class
include "class/position_class.php"; // fixed class
include "class/attendance_notifications.php"; // fixed class
include "class/versioning_class.php"; // fixed class
include "class/payroll_notif_class.php"; // fixed class
include "class/events_notifications.php"; // fixed class
include "class/message_class.php"; // fixed class
include "class/date.php";
include "class/cashbond_class.php"; // fixed class
include "class/salary_loan_class.php"; // fixed class
include "class/leave_class.php"; // fixed class
include "class/company_class.php"; // fixed class
include "class/memorandum_class.php"; // fixed class
include "class/attendance_overtime.php"; // fixed class

include "class/attendance_notif.php";
//include "class/attendance_overtime.php";

include "class/time_in_time_out.php";

// for universal variables
$id = $_SESSION["id"];


// for session
$_SESSION["active_dashboard"] = null;
$_SESSION["active_sub_registration"] = null;
$_SESSION["active_sub_employee_list"] = null;
$_SESSION["active_sub_user_authentication"] = null;
$_SESSION["active_sub_messaging_create"] = null;
$_SESSION["active_sub_messaging_inbox"] = null;
$_SESSION["active_atm_no"] = null;
$_SESSION["active_working_hours"] = null;
$_SESSION["active_memorandum"] = null;
$_SESSION["active_minimum_wage"] = null;
$_SESSION["active_biometrics"] = null;
$_SESSION["active_department"] = null;
$_SESSION["active_position"] = null;
$_SESSION["active_sub_sss"] = null;
$_SESSION["active_sub_bir"] = null;
$_SESSION["active_sub_pagibig"] = null;
$_SESSION["active_sub_philhealth"] = null;
$_SESSION["active_holiday"] = null;
$_SESSION["active_events"] = null;
$_SESSION["active_sub_upload_attendance"] = null;
$_SESSION["active_sub_view_attendance"] = null;
$_SESSION["active_sub_attendance_list"] = null;
$_SESSION["active_sub_sub_attendance_list"] = null;
$_SESSION["active_sub_file_overtime"] = null;
$_SESSION["active_sub_ot_list"] = null;
$_SESSION["active_sub_attendance_updates"] = null;
$_SESSION["active_sub_add_attendance"] = null;
$_SESSION["active_leaves"] = null;
$_SESSION["active_sub_pagibig_loan"] = null;
$_SESSION["active_sub_sss_loan"] = null;
$_SESSION["active_sub_salary_loan"] = null;
$_SESSION["active_sub_create_salary"] = null;
$_SESSION["active_sub_view_payroll_info"] = null;
$_SESSION["active_sub_my_payslip"] = null;
$_SESSION["active_my_payslip"] = null;
$_SESSION["active_simkimban"] = null;
$_SESSION["active_cashbond"] = null;
$_SESSION["active_year_total_deduction"] = null;
$_SESSION["active_salary_information"] = null;
$_SESSION["active_audit_trail"] = null;
$_SESSION["active_sub_loan_adjustment"] = null;
$_SESSION["active_sub_simkimban_adjustment"] = null;
$_SESSION["active_sub_payroll"] = null;
$_SESSION["active_sub_payroll_adjust"] = null;

// this area is for null of session
$_SESSION["view_emp_id"] = null; // sa view emp info
$_SESSION["update_emp_id"] = null; // sa update emp info

?>

<?php
	// for security browsing purpose
	//if ($_SESSION["role"] != 1 && $_SESSION["role"] != 2) {
	//	header("Location:Mainform.php");
	//}

	// for security purposes if edited in the browser
	$type = $_GET["type"];
	$attendance_notification_id = $_GET["id"];

	if (!isset($_GET["type"]) && !isset($_GET["id"])){
		header("Location:Mainform.php");
	}

	include "class/emp_information.php";
	$emp_info = new EmployeeInformation;
	$position_class = new Position;
	$date_class = new date;

	$cashbond_class = new Cashbond;
	$salary_loan_class = new SalaryLoan;
	$leave_class = new Leave;
	$memorandum_class = new Memorandum;
	$attendance_ot_class = new Attendance_Overtime;
	$attendance_notif_class = new AttendanceNotif;


	// for pending ot 
	$pending_file_ot_count = $attendance_ot_class->getOvertimePendingCount($_SESSION["role"],$_SESSION["id"]);
	$pending_file_attendance_request_count = $attendance_notif_class->attendanceNotifPendingCount($_SESSION["role"],$_SESSION["id"]);
	
	$row = $emp_info->getEmpInfoByRow($id);
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Attendance Notification Page</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> <!-- for symbols -->
		<?php
			$company_class = new Company;
			$row_company = $company_class->getInfoByCompanyId($row->company_id);
			$logo_source = $row_company->logo_source;
		?>
		<link rel="shortcut icon" href="<?php echo $logo_source; ?>"> <!-- for logo -->

		<!-- css -->
		<link rel="stylesheet" href="css/lumen.min.css">
		<link rel="stylesheet" href="css/plug ins/calendar/dcalendar.picker.css">
		<link rel="stylesheet" href="css/custom.css">

		<!-- js -->
		<script src="js/jquery-1.12.2.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/plug ins/calendar/dcalendar.picker.js"></script>
		<script src="js/chevron.js"></script>
		<script src="js/notifications.js"></script>
		<script src="js/custom.js"></script>
		<script src="js/jquery-ui.js"></script>
		<script src="js/modal.js"></script>
		<script>
			$(document).ready(function(){
				
			});
		</script>
	</head>
	<body style="background-color:#ecf0f5;">
		<?php

			include "layout/header.php";

		?>
		<div class="content">

			<!-- for menu directory at the top -->
			<div class="container-fluid">
				<div class="row" style="border-bottom:1px solid #BDBDBD;">
					<ol class="breadcrumb">
						<li><a href="MainForm.php">&nbsp;&nbsp;<span class="glyphicon glyphicon-home"></span></a></li>
						<li class="active" id="home_id">Attendance Notification</li> 
					</ol>
				</div>
			</div>

			<!-- for body -->
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-12 content-div">
						<div class="thumbnail" style="border:1px solid #BDBDBD;">
							<div class="caption">
								<fieldset>
									<legend style="color:#357ca5;border-bottom:1px solid #BDBDBD"><span class="glyphicon glyphicon-info-sign"></span> Attendance Notification
										
									</legend>
									<div class="col-sm-8 col-sm-offset-2">
											<?php

												if ($attendance_notifications_class->checkExistAttendanceNotificationId($attendance_notification_id) == 0){
													echo "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There is no information to display, go to <a href='MainForm.php'>Homepage</a>";
												}

												else if ($attendance_notifications_class->checkExistAttendanceType($type) == 0){
													echo "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There is no information to display, go to <a href='MainForm.php'>Homepage</a>";
												}

												// if success exist
												else {
													$row_attendance_notification = $attendance_notifications_class->getInformationById($attendance_notification_id);

													$attendace_notif_class = new AttendanceNotif;
													$attendance_ot_class = new Attendance_Overtime;
													
													$attendance_class = new Attendance;

												//if ($type == "Add Attendance" || $type == "Update Attendance"){
													

												//}

												//if ($type == "Attendance OT"){
													
												//}


												// for attendance update and add
													//echo $type;
												if ($type == "Add Attendance" || $type == "Update Attendance"){
												// kapag wla at inedit
													if ($attendance_notifications_class->checkExistAttendanceTypeAndId($row_attendance_notification->attendance_notif_id,$type)== 0 && $type="Upload Attendance"){ // kapag upload attendance produce error dapat direct un kapag kinlick ung attendance notif , dapat sa view_attendance.php
														echo "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There is no information to display, go to <a href='MainForm.php'>Homepage</a>";
													}

													// if success
													else {
														$attendance_notif_id = $row_attendance_notification->attendance_notif_id;
														$row_attendance_notif = $attendace_notif_class->getRequestAttendanceById($attendance_notif_id);

														$timeFrom = date_format(date_create($row_attendance_notif->time_in), 'g:i A');
														$timeTo = date_format(date_create($row_attendance_notif->time_out), 'g:i A');


														$row_attendance_emp = $emp_info->getEmpInfoByRow($row_attendance_notif->emp_id);
														$emp_name_file =  $row_attendance_emp->Firstname . " " . $row_attendance_emp->Middlename . " " . $row_attendance_emp->Lastname;
											?>
												<form class="form-horizontal">
													<div class="form-group">
														<label class="control-label col-sm-4"><span style="color: #2471a3 ">Employee Name:</label>														
														<div class="col-sm-8">
															<label class="control-label"><b><?php echo $emp_name_file; ?></b></label>
														</div>
													</div>

													<div class="form-group">
														<label class="control-label col-sm-4"><span style="color: #2471a3 ">Attendance Notification Type:</label>														
														<div class="col-sm-8">
															<label class="control-label"><b><?php echo $type; ?></b></label>
														</div>
													</div>

													<div class="form-group">
														<label class="control-label col-sm-4"><span style="color: #2471a3 ">Attendance Date:</label>
														<div class="col-sm-8">
															<label class="control-label"><b><?php echo $date_class->dateFormat($row_attendance_notif->date); ?></b></label>
														</div>
													</div>

													<?php
														if ($type != "Add Attendance" && $row_attendance_notif->notif_status == 0){
																$row_attendance = $attendance_class->getInfoByAttendaceId($row_attendance_notif->attendance_id);

																$orig_timeFrom = date_format(date_create($row_attendance->time_in), 'g:i A');
																$orig_timeTo = date_format(date_create($row_attendance->time_out), 'g:i A');

													?>
													<div class="form-group">
														<label class="control-label col-sm-4"><span style="color: #2471a3 ">Original Time:</label>
														<div class="col-sm-8">
															<label class="control-label"><b><?php echo $orig_timeFrom . " - " . $orig_timeTo; ?></b></label>
														</div>
													</div>
													<?php
														} 
													?>	

													<div class="form-group">
														<label class="control-label col-sm-4"><span style="color: #2471a3 ">Requested Time:</label>
														<div class="col-sm-8">
															<label class="control-label"><b><?php echo $timeFrom . " - ". $timeTo; ?></b></label>
														</div>
													</div>

													<div class="form-group">
														<label class="control-label col-sm-4"><span style="color: #2471a3 ">Remarks:</label>
														<div class="col-sm-8">
															<label class="control-label"><b><?php echo $row_attendance_notif->remarks; ?></b></label>
														</div>
													</div>


													<div class="form-group">
														<label class="control-label col-sm-4"><span style="color: #2471a3 ">Status:</label>
														<div class="col-sm-8">
															<label class="control-label">
																<b>
																	<?php 

																		if ($row_attendance_notif->notif_status == 1){
																			echo "Approve on " . $date_class->dateFormat($row_attendance_notif->DateApprove);
																		}
																		else if ($row_attendance_notif->notif_status == 2){
																			echo "Disapprove on ". $date_class->dateFormat($row_attendance_notif->DateApprove);
																		}
																		else {
																			echo "Pending";
																		}
																		//echo $row_attendance_notif->notif_status; 
																	?>
																</b>
															</label>
														</div>
													</div>

													<div class="form-group">
														<?php
															if ($row_attendance_notif->notif_status == 0){

														?>
														<div class="col-sm-offset-4" id="<?php echo $attendance_notif_id; ?>">
															<a href="#" id="approve_attendace_notification_page" class="btn btn-primary btn-sm" title="Approve">Approve</a>
															<a href="#" id="approve_attendace_notification_page" class="btn btn-danger btn-sm" title="Disapprove">Disapprove</a>
														</div>
														<?php
															}
														?>
													</div>

												</form>
											<?php
													}
												}

												else if ($type == "Approve OT" || $type == "Disapprove OT"){
													//echo $row_attendance_notification->attendance_notif_id;
													if ($attendance_notifications_class->checkExistAttendanceTypeAndIdOT($row_attendance_notification->attendance_ot_id,$type)== 0 && $type="Upload Attendance"){ // kapag upload attendance produce error dapat direct un kapag kinlick ung attendance notif , dapat sa view_attendance.php
														echo "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There is no information to display, go to <a href='MainForm.php'>Homepage</a>";
													}
													else {
														$attendance_ot_id = $row_attendance_notification->attendance_ot_id;
														$row_attendance_ot = $attendance_ot_class->getInfoByAttendanceOtId($attendance_ot_id);

														$row_attendance_emp = $emp_info->getEmpInfoByRow($row_attendance_ot->emp_id);
														$emp_name_file =  $row_attendance_emp->Firstname . " " . $row_attendance_emp->Middlename . " " . $row_attendance_emp->Lastname;
													
														$timeFrom = date_format(date_create($row_attendance_ot->time_from), 'g:i A');
														$timeTo = date_format(date_create($row_attendance_ot->time_out), 'g:i A');
											?>
													<form class="form-horizontal">

														<div class="form-group">
															<label class="control-label col-sm-4"><span style="color: #2471a3 ">Overtime Type:</label>														
															<div class="col-sm-8">
																<label class="control-label"><b><?php echo $row_attendance_ot->type_ot; ?></b></label>
															</div>
														</div>

														<div class="form-group">
															<label class="control-label col-sm-4"><span style="color: #2471a3 ">Overtime Date:</label>
															<div class="col-sm-8">
																<label class="control-label"><b><?php echo $date_class->dateFormat($row_attendance_ot->date); ?></b></label>
															</div>
														</div>

														<div class="form-group">
															<label class="control-label col-sm-4"><span style="color: #2471a3 ">Time From:</label>
															<div class="col-sm-8">
																<label class="control-label"><b><?php echo $timeFrom; ?></b></label>
															</div>
														</div>

														<div class="form-group">
															<label class="control-label col-sm-4"><span style="color: #2471a3 ">Time To:</label>
															<div class="col-sm-8">
																<label class="control-label"><b><?php echo $timeTo; ?></b></label>
															</div>
														</div>

														<div class="form-group">
															<label class="control-label col-sm-4"><span style="color: #2471a3 ">Remarks:</label>
															<div class="col-sm-8">
																<label class="control-label"><b><?php echo $row_attendance_ot->remarks; ?></b></label>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4"><span style="color: #2471a3 ">Status:</label>
															<div class="col-sm-8">
																<label class="control-label">
																	<b>
																		<?php 

																			if ($row_attendance_ot->approve_stat == 1){
																				echo "Approve on " . $date_class->dateFormat($row_attendance_ot->DateApprove);
																			}
																			else if ($row_attendance_ot->approve_stat == 2){
																				echo "Disapprove on ". $date_class->dateFormat($row_attendance_ot->DateApprove);
																			}
																			else {
																				echo "Pending";
																			}
																			//echo $row_attendance_notif->notif_status; 
																		?>
																	</b>
																</label>
															</div>
														</div>
														<div class="form-group">
															<?php
																if ($row_attendance_ot->approve_stat == 0 && $row->head_emp_id == 0){

															?>
															<div class="col-sm-offset-4" id="<?php echo $attendance_ot_id; ?>">
																<a href="#" id="approve_attendace_notification_page" class="btn btn-primary btn-sm" title="Approve">Approve</a>
																<a href="#" id="approve_attendace_notification_page" class="btn btn-danger btn-sm" title="Disapprove">Disapprove</a>
															</div>
															<?php
																}
															?>
														</div>
													</form>
											<?php
													}
												}

												else if ($type =="Approve Attendance" || $type =="Disapprove Attendance"){

													if ($attendance_notifications_class->checkExistAttendanceTypeAndId($row_attendance_notification->attendance_notif_id,$type) == 0 && $type="Upload Attendance"){ // kapag upload attendance produce error dapat direct un kapag kinlick ung attendance notif , dapat sa view_attendance.php
														echo "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There is no information to display, go to <a href='MainForm.php'>Homepage</a>";
													}

													else {
														$attendance_notif_id = $row_attendance_notification->attendance_notif_id;
														$row_attendance_notif = $attendace_notif_class->getRequestAttendanceById($attendance_notif_id);

														$timeFrom = date_format(date_create($row_attendance_notif->time_in), 'g:i A');
														$timeTo = date_format(date_create($row_attendance_notif->time_out), 'g:i A');


														$row_attendance_emp = $emp_info->getEmpInfoByRow($row_attendance_notif->emp_id);
														$emp_name_file =  $row_attendance_emp->Firstname . " " . $row_attendance_emp->Middlename . " " . $row_attendance_emp->Lastname;
											?>
													<form class="form-horizontal">
														<div class="form-group">
															<label class="control-label col-sm-4"><span style="color: #2471a3 ">Employee Name:</label>														
															<div class="col-sm-8">
																<label class="control-label"><b><?php echo $emp_name_file; ?></b></label>
															</div>
														</div>

														<div class="form-group">
															<label class="control-label col-sm-4"><span style="color: #2471a3 ">Attendance Notification Type:</label>														
															<div class="col-sm-8">
																<label class="control-label"><b><?php echo $type; ?></b></label>
															</div>
														</div>

														<div class="form-group">
															<label class="control-label col-sm-4"><span style="color: #2471a3 ">Attendance Date:</label>
															<div class="col-sm-8">
																<label class="control-label"><b><?php echo $date_class->dateFormat($row_attendance_notif->date); ?></b></label>
															</div>
														</div>

														

														<div class="form-group">
															<label class="control-label col-sm-4"><span style="color: #2471a3 ">Requested Time:</label>
															<div class="col-sm-8">
																<label class="control-label"><b><?php echo $timeFrom . " - ". $timeTo; ?></b></label>
															</div>
														</div>

														<div class="form-group">
															<label class="control-label col-sm-4"><span style="color: #2471a3 ">Remarks:</label>
															<div class="col-sm-8">
																<label class="control-label"><b><?php echo $row_attendance_notif->remarks; ?></b></label>
															</div>
														</div>


														<div class="form-group">
															<label class="control-label col-sm-4"><span style="color: #2471a3 ">Status:</label>
															<div class="col-sm-8">
																<label class="control-label">
																	<b>
																		<?php 

																			if ($row_attendance_notif->notif_status == 1){
																				echo "Approve on " . $date_class->dateFormat($row_attendance_notif->DateApprove);
																			}
																			else if ($row_attendance_notif->notif_status == 2){
																				echo "Disapprove on ". $date_class->dateFormat($row_attendance_notif->DateApprove);
																			}
																			else {
																				echo "Pending";
																			}
																			//echo $row_attendance_notif->notif_status; 
																		?>
																	</b>
																</label>
															</div>
														</div>

														<div class="form-group">
															<?php

																//echo  . "wew";
																if ($row_attendance_notif->notif_status == 0 && $row->head_emp_id == 0){

															?>
															<div class="col-sm-offset-4" id="<?php echo $attendance_notif_id; ?>">
																<a href="#" id="approve_attendace_notification_page" class="btn btn-primary btn-sm" title="Approve">Approve</a>
																<a href="#" id="approve_attendace_notification_page" class="btn btn-danger btn-sm" title="Disapprove">Disapprove</a>
															</div>
															<?php
																}
															?>
														</div>

													</form>
											<?php
													}


												}

												



												// for attendance OT
												else if ($type == "Attendance OT"){

													if ($attendance_notifications_class->checkExistAttendanceTypeAndIdOT($row_attendance_notification->attendance_ot_id,$type)== 0 && $type="Upload Attendance"){ // kapag upload attendance produce error dapat direct un kapag kinlick ung attendance notif , dapat sa view_attendance.php
														echo "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There is no information to display, go to <a href='MainForm.php'>Homepage</a>";
													}

													else {

														$attendance_ot_id = $row_attendance_notification->attendance_ot_id;
														$row_attendance_ot = $attendance_ot_class->getInfoByAttendanceOtId($attendance_ot_id);

														$row_attendance_emp = $emp_info->getEmpInfoByRow($row_attendance_ot->emp_id);
														$emp_name_file =  $row_attendance_emp->Firstname . " " . $row_attendance_emp->Middlename . " " . $row_attendance_emp->Lastname;
													
														$timeFrom = date_format(date_create($row_attendance_ot->time_from), 'g:i A');
														$timeTo = date_format(date_create($row_attendance_ot->time_out), 'g:i A');
											?>
												<form class="form-horizontal">
													<div class="form-group">
														<label class="control-label col-sm-4"><span style="color: #2471a3 ">Employee Name:</label>														
														<div class="col-sm-8">
															<label class="control-label"><b><?php echo $emp_name_file; ?></b></label>
														</div>
													</div>

													<div class="form-group">
														<label class="control-label col-sm-4"><span style="color: #2471a3 ">Overtime Type:</label>														
														<div class="col-sm-8">
															<label class="control-label"><b><?php echo $row_attendance_ot->type_ot; ?></b></label>
														</div>
													</div>

													<div class="form-group">
														<label class="control-label col-sm-4"><span style="color: #2471a3 ">Overtime Date:</label>
														<div class="col-sm-8">
															<label class="control-label"><b><?php echo $date_class->dateFormat($row_attendance_ot->date); ?></b></label>
														</div>
													</div>

													<div class="form-group">
														<label class="control-label col-sm-4"><span style="color: #2471a3 ">Time From:</label>
														<div class="col-sm-8">
															<label class="control-label"><b><?php echo $timeFrom; ?></b></label>
														</div>
													</div>

													<div class="form-group">
														<label class="control-label col-sm-4"><span style="color: #2471a3 ">Time To:</label>
														<div class="col-sm-8">
															<label class="control-label"><b><?php echo $timeTo; ?></b></label>
														</div>
													</div>

													<div class="form-group">
														<label class="control-label col-sm-4"><span style="color: #2471a3 ">Remarks:</label>
														<div class="col-sm-8">
															<label class="control-label"><b><?php echo $row_attendance_ot->remarks; ?></b></label>
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4"><span style="color: #2471a3 ">Status:</label>
														<div class="col-sm-8">
															<label class="control-label">
																<b>
																	<?php 

																		if ($row_attendance_ot->approve_stat == 1){
																			echo "Approve on " . $date_class->dateFormat($row_attendance_ot->DateApprove);
																		}
																		else if ($row_attendance_ot->approve_stat == 2){
																			echo "Disapprove on ". $date_class->dateFormat($row_attendance_ot->DateApprove);
																		}
																		else {
																			echo "Pending";
																		}
																		//echo $row_attendance_notif->notif_status; 
																	?>
																</b>
															</label>
														</div>
													</div>
													<div class="form-group">
														<?php
															if ($row_attendance_ot->approve_stat == 0){

														?>
														<div class="col-sm-offset-4" id="<?php echo $attendance_ot_id; ?>">
															<a href="#" id="approve_attendace_notification_page" class="btn btn-primary btn-sm" title="Approve">Approve</a>
															<a href="#" id="approve_attendace_notification_page" class="btn btn-danger btn-sm" title="Disapprove">Disapprove</a>
														</div>
														<?php
															}
														?>
													</div>
												</form>
											<?php
													}
												}

												// for file leave
												else if ($type == "File Leave" || $type == "File Formal Leave" || $type == "File Halfday Leave"){
													if ($attendance_notifications_class->checkExistAttendanceTypeAndIdOT($row_attendance_notification->attendance_ot_id,$type)== 0 && $type="Upload Attendance"){ // kapag upload attendance produce error dapat direct un kapag kinlick ung attendance notif , dapat sa view_attendance.php
														echo "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There is no information to display, go to <a href='MainForm.php'>Homepage</a>";
													}

													else {
														$leave_id = $row_attendance_notification->leave_id;
														$row_leave = $leave_class->getInfoByLeaveId($leave_id);

														$row_leave_emp = $emp_info->getEmpInfoByRow($row_leave->emp_id);
														$emp_name_file =  $row_leave_emp->Firstname . " " . $row_leave_emp->Middlename . " " . $row_leave_emp->Lastname;

														$dateFrom = date_format(date_create($row_leave->dateFrom), 'F d, Y');
														$dateTo = date_format(date_create($row_leave->dateTo), 'F d, Y');
											?>
												<form class="form-horizontal">
													<div class="form-group">
														<label class="control-label col-sm-4"><span style="color: #2471a3 ">Employee Name:</label>														
														<div class="col-sm-8">
															<label class="control-label"><b><?php echo $emp_name_file; ?></b></label>
														</div>
													</div>

													<div class="form-group">
														<label class="control-label col-sm-4"><span style="color: #2471a3 ">Leave Type:</label>														
														<div class="col-sm-8">
															<label class="control-label"><b><?php echo $row_leave->LeaveType; ?></b></label>
														</div>
													</div>

													<div class="form-group">
														<label class="control-label col-sm-4"><span style="color: #2471a3 ">File Leave Type:</label>														
														<div class="col-sm-8">
															<label class="control-label"><b><?php echo $row_leave->FileLeaveType; ?></b></label>
														</div>
													</div>

													<div class="form-group">
														<label class="control-label col-sm-4"><span style="color: #2471a3 ">Date Range:</label>
														<div class="col-sm-8">
															<label class="control-label"><b><?php echo $dateFrom . " - " . $dateTo; ?></b></label>
														</div>
													</div>

													<div class="form-group">
														<label class="control-label col-sm-4"><span style="color: #2471a3 ">Remarks:</label>
														<div class="col-sm-8">
															<label class="control-label"><b><?php echo $row_leave->Remarks; ?></b></label>
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4"><span style="color: #2471a3 ">Status:</label>
														<div class="col-sm-8">
															<label class="control-label">
																<b>
																	<?php 

																		if ($row_leave->approveStat == 1){
																			echo "Approve on " . $date_class->dateFormat($row_leave->dateApprove);
																		}
																		else if ($row_leave->approveStat == 2){
																			echo "Disapprove on ". $date_class->dateFormat($row_leave->dateApprove);
																		}
																		else {
																			echo "Pending";
																		}
																		//echo $row_attendance_notif->notif_status; 
																	?>
																</b>
															</label>
														</div>
													</div>
													<div class="form-group">
														<?php
															if ($row_leave->approveStat == 0){

														?>
														<div class="col-sm-offset-4" id="<?php echo $leave_id; ?>">
															<a href="#" id="approve_request_leave" class="btn btn-primary btn-sm" title="Approve">Approve</a>
															<a href="#" id="approve_request_leave" class="btn btn-danger btn-sm" title="Disapprove">Disapprove</a>
														</div>
														<?php
															}
														?>
													</div>
												</form>
											<?php
													}
												}

												else if ($type =="Approve Leave" || $type == "Disapprove Leave"){
													if ($attendance_notifications_class->checkExistAttendanceTypeAndIdOT($row_attendance_notification->attendance_ot_id,$type)== 0 && $type="Upload Attendance"){ // kapag upload attendance produce error dapat direct un kapag kinlick ung attendance notif , dapat sa view_attendance.php
														echo "<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There is no information to display, go to <a href='MainForm.php'>Homepage</a>";
													}
													else {
														$leave_id = $row_attendance_notification->leave_id;
														$row_leave = $leave_class->getInfoByLeaveId($leave_id);

														$row_leave_emp = $emp_info->getEmpInfoByRow($row_leave->emp_id);
														$emp_name_file =  $row_leave_emp->Firstname . " " . $row_leave_emp->Middlename . " " . $row_leave_emp->Lastname;

														$dateFrom = date_format(date_create($row_leave->dateFrom), 'F d, Y');
														$dateTo = date_format(date_create($row_leave->dateTo), 'F d, Y');
											?>
												<form class="form-horizontal">
													

													<div class="form-group">
														<label class="control-label col-sm-4"><span style="color: #2471a3 ">Leave Type:</label>														
														<div class="col-sm-8">
															<label class="control-label"><b><?php echo $row_leave->LeaveType; ?></b></label>
														</div>
													</div>

													<div class="form-group">
														<label class="control-label col-sm-4"><span style="color: #2471a3 ">File Leave Type:</label>														
														<div class="col-sm-8">
															<label class="control-label"><b><?php echo $row_leave->FileLeaveType; ?></b></label>
														</div>
													</div>

													<div class="form-group">
														<label class="control-label col-sm-4"><span style="color: #2471a3 ">Date Range:</label>
														<div class="col-sm-8">
															<label class="control-label"><b><?php echo $dateFrom . " - " . $dateTo; ?></b></label>
														</div>
													</div>

													<div class="form-group">
														<label class="control-label col-sm-4"><span style="color: #2471a3 ">Remarks:</label>
														<div class="col-sm-8">
															<label class="control-label"><b><?php echo $row_leave->Remarks; ?></b></label>
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4"><span style="color: #2471a3 ">Status:</label>
														<div class="col-sm-8">
															<label class="control-label">
																<b>
																	<?php 

																		if ($row_leave->approveStat == 1){
																			echo "Approve on " . $date_class->dateFormat($row_leave->dateApprove);
																		}
																		else if ($row_leave->approveStat == 2){
																			echo "Disapprove on ". $date_class->dateFormat($row_leave->dateApprove);
																		}
																		else {
																			echo "Pending";
																		}
																		//echo $row_attendance_notif->notif_status; 
																	?>
																</b>
															</label>
														</div>
													</div>
													<div class="form-group">
														<?php



															if ($row_leave->approveStat == 0 && $row->head_emp_id == 0){

														?>
														<div class="col-sm-offset-4" id="<?php echo $leave_id; ?>">
															<a href="#" id="approve_request_leave" class="btn btn-primary btn-sm" title="Approve">Approve</a>
															<a href="#" id="approve_request_leave" class="btn btn-danger btn-sm" title="Disapprove">Disapprove</a>
														</div>
														<?php
															}
														?>
													</div>
												</form>
											<?php
													}
												}
											}
											?>		
										
									</div>
								</fieldset>
							</div>
						</div> <!-- end of thumbnail -->
					</div>
				</div> <!-- end of row -->
			</div> <!-- end of container-fluid -->	
			
		</div> <!-- end of content -->

		
					
				

		<?php

			include "layout/footer.php";

		?>


		<!-- for update bio id modal -->
		<div id="updateRequestUpdateAttendanceModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm" id="modal_dialog_update_info">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#158cba;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-user' style='color:#fff'></span>&nbsp;Request Update Attendance</h5>
					</div> 
					<div class="modal-body" id="modal_body_update_request_attendance" >
						
					</div> 
			 		
				</div>

			</div>
		</div> <!-- end of modal -->




		
	<!-- <script>
		var global_notif_count = <?php echo $attendance_notifications_class->checkExistNotif($id) ?>;
		//alert(global_notif_count);
		  var interval = setInterval(function(){ 

		      // clearInterval(interval);

		      //alert("HELLO WORLD!");
		      var datastring = "get=1&count="+global_notif_count;
		       $.ajax({
		        type: "POST",
		        url: "ajax/append_get_attendanance_notif.php",
		        data: datastring,
		        cache: false,
		       // datatype: "php",
		        success: function (data) {
		          //alert(data);

		         	if (data != "There is no new notif!"){

		         		data = data.split("#");

		         		global_notif_count = data[0];

		         		// to be load notify
		         		notifyMe(data[1],data[2]);  	

		         		// for ajax
		         	}

		        }
		    });
		    
		  }, 3000); //END OF TIMER//


		  // request permission on page load
		document.addEventListener('DOMContentLoaded', function () {
		  if (!Notification) {
		    alert('Desktop notifications not available in your browser. Try Chromium.'); 
		    return;
		  }

		  if (Notification.permission !== "granted")
		    Notification.requestPermission();
		});

		function notifyMe(path,employee_name) {

			//var path = path;


		  if (Notification.permission !== "granted")
		    Notification.requestPermission();
		  else {
		    var notification = new Notification('Attendance Notification', {
		      icon: 'img/logo/lloyds logo.png',
		      body: "A notif from " + employee_name,
		    });

		    notification.onclick = function () {
		      window.open("attendance_notification_page.php?"+path); // type=File Leave&id=32393      
		    };

		  }

		}
	</script> -->

	</body>
</html>