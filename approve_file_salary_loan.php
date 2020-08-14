<?php
session_start();
if (!isset($_SESSION["id"])){
	header("Location:index.php");
}
include "class/connect.php"; // fixed class
include "class/position_class.php"; // fixed class
include "class/attendance_notifications.php"; // fixed class
include "class/date.php"; // fixed class
include "class/versioning_class.php"; // fixed class
include "class/payroll_notif_class.php"; // fixed class
include "class/events_notifications.php"; // fixed class
include "class/message_class.php"; // fixed class
include "class/cashbond_class.php"; // fixed class
include "class/salary_loan_class.php"; // fixed class
include "class/leave_class.php"; // fixed class
include "class/company_class.php"; // fixed class
include "class/memorandum_class.php"; // fixed class
include "class/attendance_overtime.php"; // fixed class
include "class/attendance_notif.php"; // fixed class

// not fixed class
include "class/simkimban_class.php";

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
	/*if ($_SESSION["role"] == 2 || $_SESSION["role"] == 4) {
		header("Location:Mainform.php");
	}*/

	if (!isset($_GET["id"])){
		header("Location:MainForm.php");
	}

	$file_salary_loan_id = $_GET["id"];
	$salary_loan_class = new SalaryLoan;

	if ($salary_loan_class->checkExistFileSalaryLoanId($file_salary_loan_id) == 0){
		header("Location:MainForm.php");
	}

	// for checking if exist ung file salary loan id
	include "class/emp_information.php";
	$emp_info = new EmployeeInformation;
	$position_class = new Position;
	$date_class = new date;

	$cashbond_class = new Cashbond;
	$leave_class = new Leave;
	$simkimban_class = new Simkimban;
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
		<title>Approve Salary Loan</title>
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
		<link rel="stylesheet" href="css/plug ins/data_tables/dataTables.bootstrap.css">
		<link rel="stylesheet" href="css/custom.css">

		<!-- js -->
		<script src="js/jquery-1.12.2.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		
		<script src="js/plug ins/calendar/dcalendar.picker.js"></script>
		<script src="js/plug ins/data_tables/jquery.dataTables.js"></script>
		<script src="js/plug ins/data_tables/dataTables.bootstrap.js"></script>
		<script src="js/chevron.js"></script>
		<script src="js/string_uppercase.js"></script>
		<script src="js/numbers_only.js"></script>
		<script src="js/maxlength.js"></script>
		<script src="js/format.js"></script>
		<script src="js/readmore.js"></script>
		<script src="js/notifications.js"></script>
		<script src="js/date_validation.js"></script>
		<script src="js/custom.js"></script>
		<script src="js/jquery-ui.js"></script>
		<script src="js/modal.js"></script> 
		<script>
			$(document).ready(function(){
				$("input[name='dateFromFileSalaryLoan']").dcalendarpicker();

				<?php
					// for adding error
					if (isset($_SESSION["approve_file_error_salary_loan"])){
						echo '$(document).ready(function() {
							$("#error_modal_body").html("'.$_SESSION["approve_file_error_salary_loan"].'");
							$("#errorModal").modal("show");
						});';
						$_SESSION["approve_file_error_salary_loan"] = null;
					}


					// for adding success
					if (isset($_SESSION["file_success_salary_loan"])){
						echo '$(document).ready(function() {
							$("#success_modal_body_file").html("'.$_SESSION["file_success_salary_loan"].'");
							$("#file_successModal").modal("show");
						});';
						$_SESSION["file_success_salary_loan"] = null;
					}
				?>



			});
		</script>
	</head>
	<body>
		<?php

			include "layout/header.php";

		?>

		<div class="content">

			<!-- for menu directory at the top -->
			<div class="container-fluid">
				<div class="row" style="border-bottom:1px solid #BDBDBD;">
					<ol class="breadcrumb">
						<li><a href="MainForm.php">&nbsp;&nbsp;<span class="glyphicon glyphicon-home"></span></a></li>
						<li id="home_id"><a href="salary_loan.php">Salary Loan</a></li>
						<li class="active" id="home_id">Approve File Salary Loan</li> 					  
					</ol>
				</div>
			</div>

			<!-- for body -->
			<div class="container-fluid">
				<div class="row">
					<?php

						$row_salary_loan = $salary_loan_class->getFileSalaryLoanById($file_salary_loan_id);

						$row_emp_salary_loan = $emp_info->getEmpInfoByRow($row_salary_loan->emp_id);
						$file_emp_file_fullName = $row_emp_salary_loan->Lastname . ", " . $row_emp_salary_loan->Firstname . " " . $row_emp_salary_loan->Middlename;


						$disabled = "";
						if ($row_salary_loan->deductionType == "Semi-monthly"){
							$disabled = "disabled='disabled'";
						}

						
						$dateFrom = $date_class->dateDefault($row_salary_loan->dateFrom);
						$dateTo = $date_class->dateDefault($row_salary_loan->dateTo);



					?>
					<div class="col-sm-12 content-div">
						<div class="thumbnail" style="border:1px solid #BDBDBD;">
							<div class="caption">
								<form class="form-horizontal" id="form_fileSalaryLoan" method="post">

									<?php
										if ($row_salary_loan->apporveStat == 0) {
									?>
									<div class="form-group">
										<div class="col-sm-12">
											<center>
												<h4><u style="color:#ff4136;">APPROVE FILE SALARY LOAN FORM</u></h4>
											</center>
										</div>
									</div>

									<?php
										} // end of if

										else {

									?>
									<div class="form-group">
										<div class="col-sm-12">
											<center>
												<h4><u style="color:#ff4136;">APPROVE FILE INFORMATION</u></h4>
											</center>
										</div>
									</div>
									<?php
										} // end of else
									?>

									<?php echo "<input type='hidden' name='fileSalaryLoanId' value='".$file_salary_loan_id."' />" ?>


									<div class="form-group">
										<div class="col-sm-12">
											<label class="control-label col-md-4" style="color:#158cba">Employee Name:</label>
											
											<?php
												if ($row_salary_loan->apporveStat == 0) {
											?>
												<div class="col-md-4">
													<input type="text" class="form-control"  name="empName" value="<?php echo $file_emp_file_fullName; ?>"  placeholder="" id="readonly_txt" autocomplete="off"/>
												</div>
											<?php
												} // end of if
												else {
											?>
												<div class="col-md-4">
													<input type="text" class="form-control"  name="empName" value="<?php echo $file_emp_file_fullName; ?>"  placeholder="" id="input_payroll" autocomplete="off"/>
												</div>
											<?php
												} // end of else
											?>
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-sm-12">
											<label class="control-label col-md-4" style="color:#158cba">Deduction Type:</label>
											<?php
												if ($row_salary_loan->apporveStat == 0) {
											?>
											<div class="col-md-2">
												<select class="form-control" required="required" name="deductionType">
													<option value=""></option>	
													<option value="Semi-monthly" <?php if ($row_salary_loan->deductionType == "Semi-monthly"){ echo "selected='selected'" ;} ?> >Semi-monthly</option>	
													<option value="Monthly" <?php if ($row_salary_loan->deductionType == "Monthly"){ echo "selected='selected'"; } ?>>Monthly</option>													
												</select>
											</div>
											<?php
												}
												else {
											?>
												<div class="col-md-4">
													<input type="text" class="form-control"  name="empName" value="<?php echo $row_salary_loan->deductionType; ?>"  placeholder="" id="input_payroll" autocomplete="off"/>
												</div>
											<?php
												} //end of else
											?>

										</div>
									</div>

									<div class="form-group">
										<div class="col-sm-12">
											<?php
												if ($row_salary_loan->apporveStat == 0) {
											?>
											<label class="control-label col-md-4" style="color:#158cba">If monthly, specify the date payroll to be deducted:</label>
											<div class="col-md-8">
												<label class="radio-inline"><input type="radio" value="15" name="opt_deductedPayrollDate" <?php if ($row_salary_loan->deductionDay == 15) { echo "checked='checked'"; } ?> <?php echo $disabled; ?> >15</label>
												<label class="radio-inline"><input type="radio" value="30" name="opt_deductedPayrollDate" <?php if ($row_salary_loan->deductionDay == 30) { echo "checked='checked'"; } ?> <?php echo $disabled; ?> >30</label>
											</div>
											<?php
												}
												else {
													if ($row_salary_loan->deductionDay != 0){

											?>
											<label class="control-label col-md-4" style="color:#158cba">Deduction Day:</label>
											<div class="col-md-4">
												<input type="text" class="form-control"  name="empName" value="<?php echo $row_salary_loan->deductionDay; ?>"  placeholder="" id="input_payroll" autocomplete="off"/>
											</div>
											<?php
													} // end of if
												} // end of else
											?>


										</div>
									</div>

									<div class="form-group">
										<div class="col-sm-12">
											<label class="control-label col-md-4" style="color:#158cba">Date From :</label>
											<?php
												if ($row_salary_loan->apporveStat == 0) {
											?>
												<div class="col-md-2" style="margin-right:-5%;">
													<select class="form-control" style="width:70%;" required="required" name="dateFromMonth" data-toggle="tooltip" data-placement="top" title="Month">
														<option value="1" <?php if (date_format(date_create($row_salary_loan->dateFrom), 'm') == 1) { echo "selected='selected'"; } ?> >1</option>
														<option value="2" <?php if (date_format(date_create($row_salary_loan->dateFrom), 'm') == 2) { echo "selected='selected'"; } ?>>2</option>
														<option value="3" <?php if (date_format(date_create($row_salary_loan->dateFrom), 'm') == 3) { echo "selected='selected'"; } ?>>3</option>
														<option value="4" <?php if (date_format(date_create($row_salary_loan->dateFrom), 'm') == 4) { echo "selected='selected'"; } ?>>4</option>
														<option value="5" <?php if (date_format(date_create($row_salary_loan->dateFrom), 'm') == 5) { echo "selected='selected'"; } ?>>5</option>
														<option value="6" <?php if (date_format(date_create($row_salary_loan->dateFrom), 'm') == 6) { echo "selected='selected'"; } ?>>6</option>
														<option value="7" <?php if (date_format(date_create($row_salary_loan->dateFrom), 'm') == 7) { echo "selected='selected'"; } ?>>7</option>
														<option value="8" <?php if (date_format(date_create($row_salary_loan->dateFrom), 'm') == 8) { echo "selected='selected'"; } ?>>8</option>
														<option value="9" <?php if (date_format(date_create($row_salary_loan->dateFrom), 'm') == 9) { echo "selected='selected'"; } ?>>9</option>
														<option value="10" <?php if (date_format(date_create($row_salary_loan->dateFrom), 'm') == 10) { echo "selected='selected'"; } ?>>10</option>
														<option value="11" <?php if (date_format(date_create($row_salary_loan->dateFrom), 'm') == 11) { echo "selected='selected'"; } ?>>11</option>
														<option value="12" <?php if (date_format(date_create($row_salary_loan->dateFrom), 'm') == 12) { echo "selected='selected'"; } ?>>12</option>
													</select>
												</div>
												<div class="col-md-2" style="margin-right:-5%;">
													<select class="form-control" style="width:70%;" required="required" name="dateFromDay" data-toggle="tooltip" data-placement="top" title="Day">
														<option value="15" <?php if (date_format(date_create($row_salary_loan->dateFrom), 'd') == 15) { echo "selected='selected'"; } ?> >15</option>	
														<option value="30" <?php if (date_format(date_create($row_salary_loan->dateFrom), 'd') == 30) { echo "selected='selected'"; } ?>>30</option>												
													</select>
												</div>

												<div class="col-md-2">
													<select class="form-control" style="width:70%;" required="required" name="dateFromYear" data-toggle="tooltip" data-placement="top" title="Year">
														<?php
															$year = date('Y');
															$nextYear = $year + 1;

														?>
														<option value="<?php echo $year; ?>" <?php if (date_format(date_create($row_salary_loan->dateFrom), 'Y') == $year) { echo "selected='selected'"; } ?> ><?php echo $year; ?></option>	
														<option value="<?php echo $nextYear; ?>" <?php if (date_format(date_create($row_salary_loan->dateFrom), 'Y') == $nextYear) { echo "selected='selected'"; } ?> ><?php echo $nextYear; ?></option>													
													</select>
												</div>
											<?php
												}
												else {

											?>
											<div class="col-md-4">
												<input type="text" class="form-control"  name="empName" value="<?php echo $dateFrom; ?>"  placeholder="" id="input_payroll" autocomplete="off"/>
											</div>
											<?php
												} // end of else
											?>
										</div>
									</div>

									<div class="form-group">
										<div class="col-sm-12">
											<label class="control-label col-md-4" style="color:#158cba">Total Months:</label>
											<?php
												if ($row_salary_loan->apporveStat == 0) {
											?>
											<div class="col-md-2">
												<select class="form-control" required="required" name="totalMonths">
													<option value="1" <?php if ($row_salary_loan->totalMonths == 1) { echo "selected='selected'"; } ?> >1</option>	
													<option value="2" <?php if ($row_salary_loan->totalMonths == 2) { echo "selected='selected'"; } ?> >2</option>
													<option value="3" <?php if ($row_salary_loan->totalMonths == 3) { echo "selected='selected'"; } ?> >3</option>
													<option value="4" <?php if ($row_salary_loan->totalMonths == 4) { echo "selected='selected'"; } ?> >4</option>
													<option value="5" <?php if ($row_salary_loan->totalMonths == 5) { echo "selected='selected'"; } ?> >5</option>
													<option value="6" <?php if ($row_salary_loan->totalMonths == 6) { echo "selected='selected'"; } ?> >6</option>
													<option value="7" <?php if ($row_salary_loan->totalMonths == 7) { echo "selected='selected'"; } ?> >7</option>												
													<option value="8" <?php if ($row_salary_loan->totalMonths == 8) { echo "selected='selected'"; } ?> >8</option>
													<option value="9" <?php if ($row_salary_loan->totalMonths == 9) { echo "selected='selected'"; } ?> >9</option>
													<option value="10" <?php if ($row_salary_loan->totalMonths == 10) { echo "selected='selected'"; } ?> >10</option>
													<option value="11" <?php if ($row_salary_loan->totalMonths == 11) { echo "selected='selected'"; } ?> >11</option>
													<option value="12" <?php if ($row_salary_loan->totalMonths == 12) { echo "selected='selected'"; } ?> >12</option>
													<option value="13" <?php if ($row_salary_loan->totalMonths == 13) { echo "selected='selected'"; } ?> >13</option>
													<option value="14" <?php if ($row_salary_loan->totalMonths == 14) { echo "selected='selected'"; } ?> >14</option>
													<option value="15" <?php if ($row_salary_loan->totalMonths == 15) { echo "selected='selected'"; } ?> >15</option>
													<option value="16" <?php if ($row_salary_loan->totalMonths == 16) { echo "selected='selected'"; } ?> >16</option>
													<option value="17" <?php if ($row_salary_loan->totalMonths == 17) { echo "selected='selected'"; } ?> >17</option>
													<option value="18" <?php if ($row_salary_loan->totalMonths == 18) { echo "selected='selected'"; } ?> >18</option>
													<option value="19" <?php if ($row_salary_loan->totalMonths == 19) { echo "selected='selected'"; } ?> >19</option>
													<option value="20" <?php if ($row_salary_loan->totalMonths == 20) { echo "selected='selected'"; } ?> >20</option>
													<option value="21" <?php if ($row_salary_loan->totalMonths == 21) { echo "selected='selected'"; } ?> >21</option>
													<option value="22" <?php if ($row_salary_loan->totalMonths == 22) { echo "selected='selected'"; } ?> >22</option>
													<option value="23" <?php if ($row_salary_loan->totalMonths == 23) { echo "selected='selected'"; } ?> >23</option>
													<option value="24" <?php if ($row_salary_loan->totalMonths == 24) { echo "selected='selected'"; } ?> >24</option>
													
												</select>
											</div>
											<div style="color:#CB4335;margin-top:10px;" id="errorTotalMonths"></div>
											<?php
												}
												else {

											?>
											<div class="col-md-4">
												<input type="text" class="form-control"  name="empName" value="<?php echo $row_salary_loan->totalMonths; ?>"  placeholder="" id="input_payroll" autocomplete="off"/>
											</div>
											<?php
												} // end of else
											?>
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-sm-12">
											<label class="control-label col-md-4" style="color:#158cba">Date To :</label>
											<?php
												if ($row_salary_loan->apporveStat == 0) {
											?>
											<div class="col-md-4">
												<input type="text" class="form-control" readonly="readonly" name="dateTo" data-toggle="tooltip" data-placement="top" title="Format:mm/dd/yyyy ex. 01/01/1990" autocomplete="off" id="date_only" value="<?php echo $dateTo; ?>" placeholder=""/>
											</div>
											<?php
												}
												else {
											?>
											<div class="col-md-4">
												<input type="text" class="form-control"  name="empName" value="<?php echo $dateTo; ?>"  placeholder="" id="input_payroll" autocomplete="off"/>
											</div>
											<?php
												} // end of else
											?>
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-sm-12">
											<label class="control-label col-md-4" style="color:#158cba">Amount Loan:</label>
											<?php
												if ($row_salary_loan->apporveStat == 0) {
											?>
											<div class="col-md-4">
												<input type="text" class="form-control"  name="amountLoan" value="<?php echo $row_salary_loan->amountLoan; ?>" placeholder="" id="float_only"/>
											</div>
											<?php
												}
												else {
											?>
											<div class="col-md-4">
												<input type="text" class="form-control"  name="empName" value="<?php echo $row_salary_loan->amountLoan; ?>"  placeholder="" id="input_payroll" autocomplete="off"/>
											</div>
											<?php
												} // end of else
											?>
										</div>
									</div>


									<div class="form-group">
										<div class="col-sm-12">
											<label class="control-label col-md-4" style="color:#158cba">Total Payment:</label>
											<?php
												if ($row_salary_loan->apporveStat == 0) {
											?>
											<div class="col-md-4">
												<input type="text" class="form-control"  name="totalPayment" value="<?php echo $row_salary_loan->totalPayment; ?>" readonly="readonly" placeholder="" id="float_only"/>
											</div>
											<?php
												}
												else {
											?>
											<div class="col-md-4">
												<input type="text" class="form-control"  name="empName" value="<?php echo $row_salary_loan->totalPayment; ?>"  placeholder="" id="input_payroll" autocomplete="off"/>
											</div>
											<?php
												} // end of else
											?>

										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-12">
											<label class="control-label col-md-4" style="color:#158cba">Deduction:</label>
											<?php
												if ($row_salary_loan->apporveStat == 0) {
											?>
											<div class="col-md-4">
												<input type="text" class="form-control"  name="deduction" placeholder="" value="<?php echo $row_salary_loan->deduction; ?>" readonly="readonly"/>
											</div>
											<div style="color:#CB4335;margin-top:10px;" id="errorDeduction">&nbsp;</div>
											<?php
												}
												else {
											?>
											<div class="col-md-4">
												<input type="text" class="form-control"  name="empName" value="<?php echo $row_salary_loan->deduction; ?>"  placeholder="" id="input_payroll" autocomplete="off"/>
											</div>
											<?php
												} // end of else
											?>

										</div>
									</div>


									<div class="form-group">
										<div class="col-sm-12">
											<label class="control-label col-md-4" style="color:#158cba">Remarks:</label>
											<?php
												if ($row_salary_loan->apporveStat == 0) {
											?>
											<div class="col-md-4">
												<textarea class="form-control" name="remarks" readonly="readonly"><?php echo $row_salary_loan->remarks; ?></textarea>
											</div>
											
											<?php
												}
												else {
											?>
											<div class="col-md-4">
												<textarea class="form-control" name="remarks" readonly="readonly"><?php echo $row_salary_loan->remarks; ?></textarea>
											</div>
											
											<?php
												} // end of else
											?>

										</div>
									</div>

									<?php
										if ($row_salary_loan->apporveStat != 0) {

											$stats_approve = "Approve";
											if ($row_salary_loan->apporveStat == 2){
												$stats_approve = "Disapprove";
											}
										
											$dateApprove = $date_class->dateDefault($row_salary_loan->dateApprove);

									?>
									<div class="form-group">
										<div class="col-sm-12">
											<label class="control-label col-md-4" style="color:#158cba">Approve Status:</label>
											<div class="col-md-4">
												<input type="text" class="form-control"  name="empName" value="<?php echo $stats_approve; ?>"  placeholder="" id="input_payroll" autocomplete="off"/>
											</div>
										</div>
									</div>

									<div class="form-group">
										<div class="col-sm-12">
											<label class="control-label col-md-4" style="color:#158cba">Date Approve:</label>
											<div class="col-md-4">
												<input type="text" class="form-control"  name="empName" value="<?php echo $dateApprove; ?>"  placeholder="" id="input_payroll" autocomplete="off"/>
											</div>
										</div>
									</div>
									<?php
										} // end of if										
									?>


									<?php
										if ($row_salary_loan->apporveStat == 0) {
									?>
									<div class="form-group">
										<div class="col-sm-12">
											<center>
												<button type="button" class="btn btn-primary btn-sm" id="approve_file_salary_loan">Approve Salary Loan</button>
											</center>
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-12">
											<div class="col-md-offset-4 col-md-4" id="error_message">&nbsp;</div>
										</div>
									</div>
									<?php
										} // end of if
									?>
								</form>


								<!--<fieldset>
									<legend style="color: #1e8449 "><span class="glyphicon glyphicon-ruble" style="color:#2980b9;"></span>&nbsp;Existing Loan/ SIMKIMBAN</legend>
									<table class="table table-striped table-hover table-bordered">
										<thead>
											<tr>
												<th style="color:#fff;background-color:#616a6b"><span class="glyphicon glyphicon-tags"></span>&nbsp;&nbsp;Loan Type</th>
												<th style="color:#fff;background-color:#616a6b"><span class="glyphicon glyphicon-calendar"></span>&nbsp;Date Range</th>
												<th style="color:#fff;background-color:#616a6b"><span class="glyphicon glyphicon-ruble"></span>&nbsp;Amount Loan</th>
												<th style="color:#fff;background-color:#616a6b"><span class="glyphicon glyphicon-ruble"></span>&nbsp;Total Payment</th>
												<th style="color:#fff;background-color:#616a6b"><span class="glyphicon glyphicon-ruble"></span>&nbsp;Outstanding Balance</th>
											</tr>
										</thead>
										<tbody>
											<?php
												$salary_loan_class->getExistingLoanToTableToFileSalaryLoan($row_salary_loan->emp_id);
												$simkimban_class->getExistingSimkimbanToTableToFileSalaryLoan($row_salary_loan->emp_id);
											?>
										</tbody>
									</table>
								</fieldset> -->
							</div>
						</div>
					</div>
					
				</div> <!-- end of row -->
			</div> <!-- end of container-fluid -->	


			<!--<div class="hr-payroll-system-footer">
				<strong>All Right Reserves 2017 | V1.0</strong>
			</div> -->
			
		</div> <!-- end of content -->


		<?php

			include "layout/footer.php";

		?>

		<!-- FOR ERROR MODAL -->
		<div id="errorModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#b03a2e;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-alert' style='color:#fff'></span>&nbsp;Error Notification</h5>
					</div> 
					<div class="modal-body" id="error_modal_body">
						<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There's an error during getting of data, Please refresh the page</center>
					</div> 
			 		<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- FOR SUCCESS MODAL -->
		<div id="file_successModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#1d8348;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-plus' style='color:#fff'></span>&nbsp;Success Notification</h5>
					</div> 
					<div class="modal-body" id="success_modal_body_file">
						
					</div> 
					<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->



	<!--<script>
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