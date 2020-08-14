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

include "class/money.php";


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
$_SESSION["active_sub_salary_loan"] = "active-sub-menu";
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

	include "class/emp_information.php";
	$emp_info = new EmployeeInformation;
	$position_class = new Position;
	$date_class = new date;
	$cashbond_class = new Cashbond;
	$salary_loan_class  = new SalaryLoan;
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
		<title>Salary Loan</title>
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
		<script src="js/hover.js"></script>
		<script src="js/string_uppercase.js"></script>
		<script src="js/numbers_only.js"></script>
		<script src="js/maxlength.js"></script>
		<script src="js/format.js"></script>
		<script src="js/readmore.js"></script>
		<script src="js/notifications.js"></script>
		<script src="js/custom.js"></script>
		<script src="js/jquery-ui.js"></script> 
		<script src="js/modal.js"></script>
		<script>
			$(document).ready(function(){
				$('#emp_list_with_salary_loan').dataTable( {
				     "order": [],
				    "columnDefs": [ {
				      "targets"  : 'no-sort',
				      "orderable": false,
				    }]
				});

				$('#attendance_list').dataTable( {
				     "order": [],
				    "columnDefs": [ {
				      "targets"  : 'no-sort',
				      "orderable": false,
				    }]
				});

				// 
				$('#salary_loan_history').dataTable( {
				     "order": [],
				    "columnDefs": [ {
				      "targets"  : 'no-sort',
				      "orderable": false,
				    }]
				});


				// filed_salary_loan_list
				$('#filed_salary_loan_list').dataTable( {
				     "order": [],
				    "columnDefs": [ {
				      "targets"  : 'no-sort',
				      "orderable": false,
				    }]
				});


				// 
				$('#finish_salary_loan_history_list').dataTable( {
				     "order": [],
				    "columnDefs": [ {
				      "targets"  : 'no-sort',
				      "orderable": false,
				    }]
				});


				$("input[name='dateFrom']").dcalendarpicker(); //
				$("input[name='dateTo']").dcalendarpicker(); //


				<?php


					// for adding success
					if (isset($_SESSION["add_salaryloan_success"])){
						echo '$(document).ready(function() {
							$("#success_modal_body_add").html("'.$_SESSION["add_salaryloan_success"].'");
							$("#add_successModal").modal("show");
						});';
						$_SESSION["add_salaryloan_success"] = null;
					}


					// for adding error
					if (isset($_SESSION["add_salaryloan_error"])){
						echo '$(document).ready(function() {
							$("#error_modal_body").html("'.$_SESSION["add_salaryloan_error"].'");
							$("#errorModal").modal("show");
						});';
						$_SESSION["add_salaryloan_error"] = null;
					}


					// for updating error
					if (isset($_SESSION["update_salaryloan_error"])){
						echo '$(document).ready(function() {
							$("#error_modal_body").html("'.$_SESSION["update_salaryloan_error"].'");
							$("#errorModal").modal("show");
						});';
						$_SESSION["update_salaryloan_error"] = null;
					}


					// for success updating
					if (isset($_SESSION["update_salary_success"])){
						echo '$(document).ready(function() {
							$("#success_modal_body_add").html("'.$_SESSION["update_salary_success"].'");
							$("#add_successModal").modal("show");
						});';
						$_SESSION["update_salary_success"] = null;
					}


					// for success in deleting 
					if (isset($_SESSION["success_delete_salary_loan"])){
						echo '$(document).ready(function() {
							$("#success_modal_body_add").html("'.$_SESSION["success_delete_salary_loan"].'");
							$("#add_successModal").modal("show");
						});';
						$_SESSION["success_delete_salary_loan"] = null;
					}


					// for adjustment error 
						if (isset($_SESSION["adjustment_salaryLoan_error"])){
							echo '$(document).ready(function() {
								$("#error_modal_body").html("'.$_SESSION["adjustment_salaryLoan_error"].'");
								$("#errorModal").modal("show");
							});';
						$_SESSION["adjustment_salaryLoan_error"] = null;
					}

					// for success in adjustment 
						if (isset($_SESSION["adjustment_salaryLoan_success"])){
							echo '$(document).ready(function() {
								$("#success_modal_body_add").html("'.$_SESSION["adjustment_salaryLoan_success"].'");
								$("#add_successModal").modal("show");
							});';
							$_SESSION["adjustment_salaryLoan_success"] = null;
						}


						// for approving of file salary loan error 
						if (isset($_SESSION["approve_file_error_salary_loan"])){
							echo '$(document).ready(function() {
								$("#error_modal_body").html("'.$_SESSION["approve_file_error_salary_loan"].'");
								$("#errorModal").modal("show");
							});';
							$_SESSION["approve_file_error_salary_loan"] = null;
						}

						// for approving of file salary loan success
						if (isset($_SESSION["approve_file_success_salary_loan"])){
							echo '$(document).ready(function() {
								$("#success_modal_body_add").html("'.$_SESSION["approve_file_success_salary_loan"].'");
								$("#add_successModal").modal("show");
							});';
							$_SESSION["approve_file_success_salary_loan"] = null;
						}

						// for disapprove of file salary loan error 
						if (isset($_SESSION["disapprove_file_error_salary_loan"])){
							echo '$(document).ready(function() {
								$("#error_modal_body").html("'.$_SESSION["disapprove_file_error_salary_loan"].'");
								$("#errorModal").modal("show");
							});';
							$_SESSION["disapprove_file_error_salary_loan"] = null;
						}

						// for disapproving of file salary loan success
						if (isset($_SESSION["disapprove_file_success_salary_loan"])){
							echo '$(document).ready(function() {
								$("#success_modal_body_add").html("'.$_SESSION["disapprove_file_success_salary_loan"].'");
								$("#add_successModal").modal("show");
							});';
							$_SESSION["disapprove_file_success_salary_loan"] = null;
						}


						//accept_file_success_salary_loan
						if (isset($_SESSION["accept_file_success_salary_loan"])){
							echo '$(document).ready(function() {
								$("#success_modal_body_add").html("'.$_SESSION["accept_file_success_salary_loan"].'");
								$("#add_successModal").modal("show");
							});';
							$_SESSION["accept_file_success_salary_loan"] = null;
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
						<li class="active" id="home_id">Salary Loan</li> 
					</ol>
				</div>
			</div>


			<?php
	 			

	 		?>


			<!-- for body -->
			<div class="container-fluid">
				<div class="row">
					<?php
						if ($_SESSION["role"] == 1 || $_SESSION["role"] == 3 || $_SESSION["role"] == 2){

					?>
					<div class="col-md-12">
						<div class="panel panel-primary" style="margin-top:10px;"> <!-- content-element -->

							<div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-list-alt"></span> List of Employee With Existing Salary Loan / Employee Benifit Program</center></div> 


							 <div class="panel-body">
							 	<div class="col-sm-12">
							 		<b>
										<div class="col-sm-12" style="border-radius:10px;background-color: #e5e8e8;margin-bottom:10px;padding:10px;text-align:center;">
											<small>
												<!--<span style='color:#186a3b;'>Icon Legends: </span> -->
												<span class='glyphicon glyphicon-pencil' style='color:#b7950b;margin-left:5px;'></span> - Edit Salary Loan Info
												<span class='glyphicon glyphicon-adjust' style='color: #239b56 ;margin-left:5px;'></span> - <a data-toggle="popover" id="hover_info" title="Adjustment Information" data-content="When a salary information need to be adjust because the employee give an advance payment. There's a report created after adjusting." style="color:#337ab7;cursor:pointer;">Adjust Salary Loan Info</a>
												<span class='glyphicon glyphicon-trash' style='color:#515a5a;margin-left:5px;'></span> - Delete Salary Loan Info
												<span class='glyphicon glyphicon-eye-open' style='color:#3498db;margin-left:5px;'></span> - View Salary Loan Info
												<span class='glyphicon glyphicon-print' style='color: #515a5a ;margin-left:5px;'></span> - <a data-toggle="popover" id="hover_info" title="Print Information" data-content="You can print a salary loan info if the employee remaining balance and amount loan is equal if not no print facility." style="color:#337ab7;cursor:pointer;">Print Salary Loan Info</a>
											</small>

										</div>
									</b>

							 		<table id="emp_list_with_salary_loan" class="table table-bordered table-hover table-striped" style="border:1px solid #BDBDBD;">
										<thead>
											<tr> 	
												<th width="16%;"><small><span class="glyphicon glyphicon-user" style="color:#186a3b;"></span> Employee Name</small></th>
												<th width="15%;"><small><span class="glyphicon glyphicon-calendar" style="color:#186a3b"></span> Range of Payment</small></th>
												<th width="14%;"><small><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Amount Loan</small></th>
												<th width="14%;"><small><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Deduction</small></th>
												<th width="14%;"><small><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Outstanding Bal.</small></th>
												<th width="15%;"><small><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Deduction Type</small></th>
												<th width="15%;"><small><span class="glyphicon glyphicon-edit" style="color:#186a3b"></span> Info</small></th>
												<th width="12%;"><small><span class="glyphicon glyphicon-wrench" style="color:#186a3b"></span> Action</small></th>
											</tr>
										</thead>
										<tbody>	
										<?php
											//$emp_info->getEmpInfoToTable();
											$salary_loan_class->getSalaryLoanInfoToTable();

										?>
										</tbody>
									</table>
							 	</div>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="panel panel-primary" style="margin-top:10px;"> <!-- content-element -->

							<div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-list-alt"></span> Employee Salary Loan History</center></div> 


							 <div class="panel-body">
							 	<div class="col-sm-12">
							 		

							 		<table id="finish_salary_loan_history_list" class="table table-bordered table-hover table-striped" style="border:1px solid #BDBDBD;">
										<thead>
											<tr> 	
												<th width="16%;"><small><span class="glyphicon glyphicon-user" style="color:#186a3b;"></span> Employee Name</small></th>
												<th width="15%;"><small><span class="glyphicon glyphicon-calendar" style="color:#186a3b"></span> Range of Payment</small></th>
												<th width="15%;"><small><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Amount Loan</small></th>
												<th width="15%;"><small><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Deduction</small></th>
												<th width="15%;"><small><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Deduction Type</small></th>
											</tr>
										</thead>
										<tbody>	
										<?php
											//$emp_info->getEmpInfoToTable();
											$salary_loan_class->getAllSalaryLoanHistory();

										?>
										</tbody>
									</table>
							 	</div>
							</div>
						</div>
					</div>
					<?php
						}
					?>

					<div class="col-md-12">
						<div class="panel panel-primary" style="<?php if($_SESSION['role'] == 2 ||$_SESSION['role'] == 4) { echo 'margin-top:10px';} ?>"> <!-- content-element -->

							<div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-list-alt"></span> Salary Loan List History</center></div> 


							 <div class="panel-body">
							 	<!--<div class="col-sm-12">
								 	<div class="pull-right" style="cursor:pointer;color:#186a3b" id="file_salary_loan">
							 			<span class="glyphicon glyphicon-file"></span>File Salary Loan
							 		</div>
						 		</div> 
						 		<br/>
						 		<br/>-->
							 	<div class="col-sm-12">
							 		

									<table id="salary_loan_history" class="table table-bordered table-hover table-striped" style="border:1px solid #BDBDBD;">
										<thead>
											<tr> 	
												<th><span class="glyphicon glyphicon-calendar" style="color:#186a3b"></span> Range of Payment</th>
												<th><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Amount Loan</th>
												<th><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Deduction</th>
												<th><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Outstanding Balance</th>
												<th><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Remarks</th>
												<th><span class="glyphicon glyphicon-stats" style="color:#186a3b"></span> Status</th>
											</tr>
										</thead>
										<tbody>	
										<?php
											//$emp_info->getEmpInfoToTable();
											$salary_loan_class->getOwnSalaryLoanToTable($id);

										?>
										</tbody>
									</table>
									<!--<div style="cursor:pointer;color: #3498db " id="file_salary_loan"><span class="glyphicon glyphicon-file" style="color:"></span> File Salary Loan</div> -->
							 		
							 	</div>
							</div>
						</div>
					</div>


					<!--
					<?php
						if ($_SESSION["role"] == 1 || $_SESSION["role"] == 3){
					?>
					<div class="col-md-12">
						<div class="panel panel-primary" style="<?php if($_SESSION['role'] == 2 ||$_SESSION['role'] == 4) { echo 'margin-top:10px';} ?>"> 

							<div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-list-alt"></span> List of Filed Salary Loan</center></div> 


							 <div class="panel-body">
							 	
							 	<div class="col-sm-12">
							 		

									<table id="filed_salary_loan_list" class="table table-bordered table-hover table-striped" style="border:1px solid #BDBDBD;">
										<thead>
											<tr> 	
												<th><span class="glyphicon glyphicon-calendar" style="color:#186a3b"></span> Employee Name</th>
												<th><span class="glyphicon glyphicon-calendar" style="color:#186a3b"></span> Range of Payment</th>
												<th><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Amount Loan</th>
												<th><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Deduction</th>
												<th><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Interest</th>
												<th><span class="glyphicon glyphicon-edit" style="color:#186a3b"></span> Remarks</th>
												<th><span class="glyphicon glyphicon-wrench" style="color:#186a3b"></span> Action</th>
											</tr>
										</thead>
										<tbody>	
										<?php
											//$emp_info->getEmpInfoToTable();
											$salary_loan_class->getFiledSalaryLoanToApprove();

										?>
										</tbody>
									</table>
							
							 		
							 	</div>
							</div>
						</div>
					</div>

					<?php
						}
					?>

					-->

					


						

					

					<?php
						if ($_SESSION["role"] == 3 || $_SESSION["role"] == 1){

					?>
					<div class="col-md-8 col-md-offset-2">
						<div class="panel panel-primary" style="margin-top:-10px;"> 

							<div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-plus"></span> <a data-toggle="collapse" href="#collapse1">Add Employee with Salary Loan</a></center></div>
						

							<div id="collapse1" class="panel-collapse collapse">
								<div class="panel-body">
								 	<form class="form-horizontal" action="" id="form_addSalaryLoan" method="post">
								 		<div class="form-group">
						 					<label class="control-label col-sm-3 col-sm-offset-1"><b>Employee Name:</b></label>
						 					<div class="col-sm-6 txt-pagibig-loan" style="margin-right:-15px;">
						 						<input type="text" class="form-control" name="empName" placeholder="Employee Name ..." id="input_payroll" required="required"/>
					 						</div>
					 						<label class="col-sm-1 control-label"><a href="#" id="choose_employee_salary_loan">Choose</a></label>
							 			</div>
							 			<div class="form-group">
											
											<label class="control-label col-sm-3 col-sm-offset-1" style=""><b>Deduction Type:</b></label>
											<div class="col-sm-3 txt-pagibig-loan">
												<select class="form-control" required="required" name="deductionTypeExist">
													<option value=""></option>	
													<option value="Semi-monthly">Semi-monthly</option>	
													<option value="Monthly">Monthly</option>													
												</select>
											</div>

											
										</div>
										<div class="form-group">
											<label class="control-label col-sm-3 col-sm-offset-1" style=""><small><b>If monthly, Deduction day:</b></small></label>
											<div class="col-sm-8 txt-pagibig-loan">
												<label class="radio-inline"><input required="required" type="radio" value="15" name="opt_deductedPayrollDate" disabled="disabled">15</label>
												<label class="radio-inline"><input required="required" type="radio" value="30" name="opt_deductedPayrollDate" disabled="disabled">30</label>
											</div>
										</div>

										<div class="form-group">
											<label class="control-label col-sm-3 col-sm-offset-1" style=""><b>Total Months:</b></label>
											<div class="col-sm-2 txt-pagibig-loan">
												<select class="form-control" required="required" name="totalMonthsExist">
													<option value=""></option>
													<option value="1">1</option>
													<option value="2">2</option>
													<option value="3">3</option>
													<option value="4">4</option>
													<option value="5">5</option>
													<option value="6">6</option>
													<option value="7">7</option>
													<option value="8">8</option>
													<option value="9">9</option>
													<option value="10">10</option>
													<option value="11">11</option>
													<option value="12">12</option>
													<option value="13">13</option>											
													<option value="14">14</option>
													<option value="15">15</option>
													<option value="16">16</option>
													<option value="17">17</option>
													<option value="18">18</option>
													<option value="19">19</option>
													<option value="20">20</option>
													<option value="21">21</option>
													<option value="22">22</option>
													<option value="23">23</option>
													<option value="24">24</option>
												</select>
											</div>
											
										</div>
										

							 			<div class="form-group">
						 					<label class="control-label col-sm-3 col-sm-offset-1"><b>Date From:</b></label>
						 					<div class="col-sm-6 txt-pagibig-loan">
						 						<input type="text" class="form-control" placeholder="Date From ..." name="dateFrom" required="required"/>
					 						</div>
							 			</div>
							 			<div class="form-group">
						 					<label class="control-label col-sm-3 col-sm-offset-1"><b>Date To:</b></label>
						 					<div class="col-sm-6 txt-pagibig-loan">
						 						<input type="text" class="form-control" placeholder="Date To ..." name="dateTo" required="required"/>
					 						</div>
							 			</div>
							 			<div class="form-group">
						 					<label class="control-label col-sm-3 col-sm-offset-1"><b>Amount Loan:</b></label>
						 					<div class="col-sm-6 txt-pagibig-loan">
						 						<input type="text" class="form-control" placeholder="Amount Loan ..." id="float_only" name="amountLoan" required="required"/>
					 						</div>
							 			</div>

							 			<div class="form-group">
						 					<label class="control-label col-sm-3 col-sm-offset-1"><b>Total Payment:</b></label>
						 					<div class="col-sm-6 txt-pagibig-loan">
						 						<input type="text" class="form-control" placeholder="Total Payment ..." id="float_only" name="total_payment" required="required"/>
					 						</div>
							 			</div>

							 			<div class="form-group">
						 					<label class="control-label col-sm-3 col-sm-offset-1"><b>Deduction:</b></label>
						 					<div class="col-sm-6 txt-pagibig-loan">
						 						<input type="text" class="form-control" placeholder="Deduction ..." id="float_only" name="decution" required="required"/>
					 						</div>
							 			</div>


							 			<div class="form-group">
						 					<label class="control-label col-sm-3 col-sm-offset-1"><b>Remaining Balance:</b></label>
						 					<div class="col-sm-6 txt-pagibig-loan">
						 						<input type="text" class="form-control" placeholder="Remaining Balance ..." id="float_only" name="remainingBalance" required="required"/>
					 						</div>
							 			</div>

							 			<div class="form-group">
						 					<label class="control-label col-sm-3 col-sm-offset-1"><b>Remarks:</b></label>
						 					<div class="col-sm-6 txt-pagibig-loan">
						 						<textarea class="form-control" placeholder="Remarks ..." id="" name="remarks" required="required"></textarea>
					 						</div>
							 			</div>

							 			<div class="form-group">
							 				<div class="col-sm-offset-6">
							 					<input type="submit" value="Submit" id="submitSalaryLoan" class="btn btn-primary btn-sm"/>
						 					</div>
						 				</div>

						 				<div class="form-group">
						 					<div id="error_msg">
					 						</div>
					 					</div>
							 		</form>
							 	</div>
							</div> 
						</div>
					</div>
					<?php
						}
					?>

					


					<?php

						if ($salary_loan_class->checkExistFileSalaryLoan($id) == 1){

						$money_class = new Money;

						$row_fileSalaryLoan = $salary_loan_class->getFiledSalaryLoan($id);
						$dateFile = $date_class->dateFormat($row_fileSalaryLoan->dateCreated);

						$dateFrom = $date_class->dateFormat($row_fileSalaryLoan->dateFrom);
						$dateTo = $date_class->dateFormat($row_fileSalaryLoan->dateTo);


						$deductionType = $row_fileSalaryLoan->deductionType;

						$approveStat = "";
						if ($row_fileSalaryLoan->apporveStat == 0){
							$approveStat = "Pending";
						}

						if ($row_fileSalaryLoan->apporveStat == 1){
							$approveStat = "Approve";
						}

					?>


					<div class="col-md-5 col-md-offset-7">
						<div class="thumbnail" style="border:1px solid #BDBDBD;">
							<div class="caption">
								<div class="container-fluid">
									<div class="row">
										<center><h5 style="color:#ff4136;"><u>File Salary Status</u></h5></center>
										<form class="form-horizontal">	
											<div class="form-group">
												<div class="col-md-12">
													<label class="control-label col-md-5" style="color:#158cba">Date Filed:</label>
													<div class="col-md-7">
														<label class="control-label"><b><?php echo $dateFile; ?></b></label>
													</div>
												</div>
											</div>

											<div class="form-group">
												<div class="col-md-12">
													<label class="control-label col-md-5" style="color:#158cba">Deduction Type:</label>
													<div class="col-md-7">
														<label class="control-label"><b><?php echo $deductionType;  ?></b></label>
													</div>
												</div>
											</div>


											<?php
												if ($deductionType == "Monthly"){

											?>
											<div class="form-group">
												<div class="col-md-12">
													<label class="control-label col-md-5" style="color:#158cba">Deduction Payroll Day:</label>
													<div class="col-md-7">
														<label class="control-label"><b><?php echo $row_fileSalaryLoan->deductionDay;  ?></b></label>
													</div>
												</div>
											</div>
											<?php
												}
											?>

											<div class="form-group">
												<div class="col-md-12">
													<label class="control-label col-md-5" style="color:#158cba">Total Months:</label>
													<div class="col-md-7">
														<label class="control-label"><b><?php echo $row_fileSalaryLoan->totalMonths;  ?></b></label>
													</div>
												</div>
											</div>

											<div class="form-group">
												<div class="col-md-12">
													<label class="control-label col-md-5" style="color:#158cba">Date From:</label>
													<div class="col-md-7">
														<label class="control-label"><b><?php echo $dateFrom;  ?></b></label>
													</div>
												</div>
											</div>


											<div class="form-group">
												<div class="col-md-12">
													<label class="control-label col-md-5" style="color:#158cba">Date To:</label>
													<div class="col-md-7">
														<label class="control-label"><b><?php echo $dateTo;  ?></b></label>
													</div>
												</div>
											</div>

											<div class="form-group">
												<div class="col-md-12">
													<label class="control-label col-md-5" style="color:#158cba">Amount Loan:</label>
													<div class="col-md-7">
														<label class="control-label"><b>Php <?php echo $money_class->getMoney($row_fileSalaryLoan->amountLoan);  ?></b></label>
													</div>
												</div>
											</div>


											<div class="form-group">
												<div class="col-md-12">
													<label class="control-label col-md-5" style="color:#158cba">Total Payment:</label>
													<div class="col-md-7">
														<label class="control-label"><b>Php <?php echo $money_class->getMoney($row_fileSalaryLoan->totalPayment);  ?></b></label>
													</div>
												</div>
											</div>

											<div class="form-group">
												<div class="col-md-12">
													<label class="control-label col-md-5" style="color:#158cba">Deduction:</label>
													<div class="col-md-7">
														<label class="control-label"><b>Php <?php echo $money_class->getMoney($row_fileSalaryLoan->deduction);  ?></b></label>
													</div>
												</div>
											</div>

											<div class="form-group">
												<div class="col-md-12">
													<label class="control-label col-md-5" style="color:#158cba">Remarks:</label>
													<div class="col-md-7">
														<label class="control-label"><b><?php echo $row_fileSalaryLoan->remarks; ?></b></label>
													</div>
												</div>
											</div>

											<?php
												if ($row_fileSalaryLoan->apporveStat == 1){													

											?>
											<div class="form-group">
												<div class="col-md-12">
													<label class="control-label col-md-5" style="color:#158cba">Date Approve:</label>
													<div class="col-md-7">
														<label class="control-label"><b><?php echo $date_class->dateFormat($row_fileSalaryLoan->dateApprove);  ?></b></label>
													</div>
												</div>
											</div>
											<?php
												}
											?>

											<div class="form-group">
												<div class="col-md-12">
													<label class="control-label col-md-5" style="color:#158cba">Status:</label>
													<div class="col-md-7">
														<label class="control-label"><b><?php echo $approveStat;  ?></b></label>
													</div>
												</div>
											</div>

											<?php
												if ($row_fileSalaryLoan->apporveStat == 1){													

											?>
											<div class="form-group">
												<div class="col-md-12">
													<center>
														<button type="button" class="btn btn-success btn-sm" id="accept_file_salary_loan">Accept</button>
														<button type="button" class="btn btn-danger btn-sm" id="decline_file_salary_loan">Decline</button>
													</center>
												</div>
											</div>


											<?php
												}
											?>

										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php
						}
					?>
				</div> <!-- end of row -->
			</div> <!-- end of container-fluid -->	


			<!--<div class="hr-payroll-system-footer">
				<strong>All Right Reserves 2017 | V1.0</strong>
			</div> -->
			
		</div> <!-- end of content -->


		<?php

			include "layout/footer.php";

		?>



		<div id="emp_list_modal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#158cba;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-user' style='color:#fff'></span>&nbsp;Employee List</h5>
					</div> 
					<div class="modal-body" id="">
						<div class="container-fluid">
							<div class="col-sm-8 col-sm-offset-2">
								<table id="attendance_list" class="table table-bordered table-hover table-striped" style="border:1px solid #BDBDBD;">
									<thead>
										<tr>
											<th class="no-sort"><center><span class="glyphicon glyphicon-user" style="color:#186a3b"></span> Employee Name</center></th>
										</tr>
									</thead>
									<tbody>	
									<?php
										$emp_info->getAllEmployeesNameToTable();

									?>
									</tbody>
								</table>
							</div>
						</div>
					</div> 
				</div>

			</div>
		</div> <!-- end of modal -->

	

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
		<div id="add_successModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#1d8348;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-plus' style='color:#fff'></span>&nbsp;Success Notification</h5>
					</div> 
					<div class="modal-body" id="success_modal_body_add">
						
					</div> 
					<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->



		<!-- FOR SUCCESS MODAL -->
		<div id="add_successModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#1d8348;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-plus' style='color:#fff'></span>&nbsp;Success Notification</h5>
					</div> 
					<div class="modal-body" id="success_modal_body_add">
						
					</div> 
					<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->



		<!-- FOR SUCCESS MODAL -->
		<div id="updateFormModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#1d8348;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-pencil' style='color:#fff'></span>&nbsp;Update Notification</h5>
					</div> 
					<div class="modal-body" id="update_salaryForm_body">
						
					</div> 
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- FOR SUCCESS MODAL -->
		<div id="view_salary_loan_history" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#1d8348;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-eye-open' style='color:#fff'></span>&nbsp;View Salary Loan History</h5>
					</div> 
					<div class="modal-body" id="view_salaryLoan_body">
						
					</div> 
				</div>

			</div>
		</div> <!-- end of modal -->



		<!-- FOR DELETE CONFIRMATION MODAL -->
		<div id="deletePagibigConfirmationModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#21618c;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-trash' style='color:#fff'></span>&nbsp;Delete Notification</h5>
					</div> 
					<div class="modal-body" id="delete_modal_body">
						
					</div> 
					<div class="modal-footer" style="padding:5px;text-align:center;" id="delete_modal_footer">
						<a href="#" class="btn btn-default" id="delete_yes_salary">YES</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->

		<!-- FOR DELETE CONFIRMATION MODAL -->
		<div id="disapproveFileSalaryLoanConfirmationModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#21618c;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-trash' style='color:#fff'></span>&nbsp;Disapprove Notification</h5>
					</div> 
					<div class="modal-body" id="disapprove_modal_body">
						
					</div> 
					<div class="modal-footer" style="padding:5px;text-align:center;" id="delete_modal_footer">
						<a href="#" class="btn btn-default" id="disapprove_yes_fileSalaryLoan">YES</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- FOR DELETE CONFIRMATION MODAL -->
		<div id="acceptFileSalaryLoanConfirmationModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#21618c;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-trash' style='color:#fff'></span>&nbsp;Accept Notification</h5>
					</div> 
					<div class="modal-body" id="accept_modal_body">
						
					</div> 
					<div class="modal-footer" style="padding:5px;text-align:center;" id="delete_modal_footer">
						<a href="#" class="btn btn-default" id="accept_yes_fileSalaryLoan">YES</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- FOR DELETE CONFIRMATION MODAL -->
		<div id="declineFileSalaryLoanConfirmationModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#21618c;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-trash' style='color:#fff'></span>&nbsp;Decline Notification</h5>
					</div> 
					<div class="modal-body" id="decline_modal_body">
						
					</div> 
					<div class="modal-footer" style="padding:5px;text-align:center;" id="delete_modal_footer">
						<a href="#" class="btn btn-default" id="decline_yes_fileSalaryLoan">YES</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- FOR ADJUSTMENT MODAL -->
		<div id="adjustLoanModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#1d8348;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-pencil' style='color:#fff'></span>&nbsp;Adjustment</h5>
					</div> 
					<div class="modal-body" id="adjust_salaryForm_body">
						
					</div> 
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- for update bio id modal -->
		<div id="updateRequestUpdateAttendanceModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm" id="modal_dialog_update_info">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#158cba;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-user' style='color:#fff'></span>&nbsp;Request File Salary Loan</h5>
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