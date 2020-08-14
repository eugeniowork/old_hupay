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
		<title>File Loan</title>
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


				// 
				$('#file_loan_list').dataTable( {
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

				 // file_simkimban_loan_list
				 $('#file_simkimban_loan_list').dataTable( {
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
					if (isset($_SESSION["file_success_emp_loan"])){
						echo '$(document).ready(function() {
							$("#success_modal_body_add").html("'.$_SESSION["file_success_emp_loan"].'");
							$("#add_successModal").modal("show");
						});';
						$_SESSION["file_success_emp_loan"] = null;
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
						<li class="active" id="home_id">File Loan</li> 
					</ol>
				</div>
			</div>


			<?php
	 			

	 		?>


			<!-- for body -->
			<div class="container-fluid">
				<div class="row">
					

					<div class="col-md-12">
						<div class="panel panel-primary" style="margin-top:10px"> <!-- content-element -->

							<div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-list-alt"></span>&nbsp;Loan List History</center></div> 


							 <div class="panel-body">
							 	<div class="col-sm-12">
								 	<div class="pull-right" style="cursor:pointer;color:#186a3b" id="">
							 			<a href="#addLoanModal" data-toggle="modal"><span class="glyphicon glyphicon-file"></span>File Loan</a>
							 		</div>
						 		</div> 
						 		<br/>
						 		<br/>
							 	<div class="col-sm-12">
							 		

									<table id="salary_loan_history" class="table table-bordered table-hover table-striped" style="border:1px solid #BDBDBD;">
										<thead>
											<tr> 	
												<th><span class="glyphicon glyphicon-qrcode" style="color:#186a3b"></span> Reference No.</th>
												<th><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Amount</th>
												<th class="no-sort"><span class="glyphicon glyphicon-pencil" style="color:#186a3b"></span> Purpose</th>
												<th><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Type</th>
												<th><span class="glyphicon glyphicon-stats" style="color:#186a3b"></span> Status</th>
												<th class="no-sort"><span class="glyphicon glyphicon-wrench" style="color:#186a3b"></span> Action</th>
											</tr>
										</thead>
										<tbody>	
										<?php
											//$emp_info->getEmpInfoToTable();
											$emp_loan_class = new EmployeeLoan;

											$emp_loan_class->getFileLoanList($_SESSION["id"]);

										?>
										</tbody>
									</table>
									<!--<div style="cursor:pointer;color: #3498db " id="file_salary_loan"><span class="glyphicon glyphicon-file" style="color:"></span> File Salary Loan</div> -->
							 		
							 	</div>
							</div>
						</div>
					</div>



					<?php

						if ($_SESSION["role"] == 1 || $_SESSION["role"] == 2){
					?>
					<div class="col-md-12">
						<div class="panel panel-primary" style="margin-top:10px"> <!-- content-element -->

							<div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-list-alt"></span>&nbsp;File Loan List History</center></div> 


							 <div class="panel-body">
							 	
							 	<div class="col-sm-12">
							 		

									<table id="file_loan_list" class="table table-bordered table-hover table-striped" style="border:1px solid #BDBDBD;">
										<thead>
											<tr> 	
												<th><span class="glyphicon glyphicon-qrcode" style="color:#186a3b"></span> Employee Name</th>
												<th><span class="glyphicon glyphicon-qrcode" style="color:#186a3b"></span> Reference No.</th>
												<th><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Amount</th>
												<th class="no-sort"><span class="glyphicon glyphicon-pencil" style="color:#186a3b"></span> Purpose</th>
												<th><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Type</th>
			
												<th class="no-sort"><span class="glyphicon glyphicon-wrench" style="color:#186a3b"></span> Action</th>
											</tr>
										</thead>
										<tbody>	
										<?php
											//$emp_info->getEmpInfoToTable();
											$emp_loan_class = new EmployeeLoan;

											
											$emp_loan_class->getAllFileLoanList();
										?>
										</tbody>
									</table>
									<!--<div style="cursor:pointer;color: #3498db " id="file_salary_loan"><span class="glyphicon glyphicon-file" style="color:"></span> File Salary Loan</div> -->
							 		
							 	</div>
							</div>
						</div>
					</div>


					<?php
						}
					?>




					

					<?php
						if ($_SESSION["role"] == 1 || $_SESSION["role"] == 3){
					?>
					<div class="col-md-12">
						<div class="panel panel-primary" style="<?php if($_SESSION['role'] == 2 ||$_SESSION['role'] == 4) { echo 'margin-top:10px';} ?>"> <!-- content-element -->

							<div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-list-alt"></span> List of Filed Salary Loan & Employment Benifit Program</center></div> 


							 <div class="panel-body">
							 	
							 	<div class="col-sm-12">
							 		

									<table id="filed_salary_loan_list" class="table table-bordered table-hover table-striped" style="border:1px solid #BDBDBD;">
										<thead>
											<tr> 	
												<th><span class="glyphicon glyphicon-calendar" style="color:#186a3b"></span> Employee Name</th>
												<th><span class="glyphicon glyphicon-calendar" style="color:#186a3b"></span> Range of Payment</th>
												<th><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Amount Loan</th>
												<th><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Deduction</th>
												<th><span class="glyphicon glyphicon-edit" style="color:#186a3b"></span> Info</th>
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
									<!--<div style="cursor:pointer;color: #3498db " id="file_salary_loan"><span class="glyphicon glyphicon-file" style="color:"></span> File Salary Loan</div> -->
							 		
							 	</div>
							</div>
						</div>
					</div>

					<?php
						}
					?>



					<?php
						if ($_SESSION["role"] == 1 || $_SESSION["role"] == 3){
					?>
					<div class="col-md-12">
						<div class="panel panel-primary" style="<?php if($_SESSION['role'] == 2 ||$_SESSION['role'] == 4) { echo 'margin-top:10px';} ?>"> <!-- content-element -->

							<div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-list-alt"></span> List of Filed SIMKIMBAN LOAN</center></div> 


							 <div class="panel-body">
							 	
							 	<div class="col-sm-12">
							 		

									<table id="file_simkimban_loan_list" class="table table-bordered table-hover table-striped" style="border:1px solid #BDBDBD;">
										<thead>
											<tr> 	
												<th><span class="glyphicon glyphicon-calendar" style="color:#186a3b"></span> Employee Name</th>
												<th><span class="glyphicon glyphicon-calendar" style="color:#186a3b"></span> Range of Payment</th>
												<th><span class="glyphicon glyphicon-shopping-cart" style="color:#186a3b"></span> Item</th>

												<th><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Amount Loan</th>

												<th><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Deduction</th>
												<th><span class="glyphicon glyphicon-edit" style="color:#186a3b"></span> Info</th>
												<!--<th><span class="glyphicon glyphicon-edit" style="color:#186a3b"></span> Remarks</th> -->
												<th><span class="glyphicon glyphicon-wrench" style="color:#186a3b"></span> Action</th>
											</tr>
										</thead>
										<tbody>	
										<?php
											//$emp_info->getEmpInfoToTable();
											include "class/simkimban_class.php";

											$simkimban_class = new Simkimban;
											$simkimban_class->getFileSimkimbanLoan();

										?>
										</tbody>
									</table>
									<!--<div style="cursor:pointer;color: #3498db " id="file_salary_loan"><span class="glyphicon glyphicon-file" style="color:"></span> File Salary Loan</div> -->
							 		
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


	<!-- FOR SUCCESS MODAL -->
		<div id="addLoanModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#1d8348;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-plus-sign' style='color:#fff'></span>&nbsp;Loan Form</h5>
					</div> 
					<div class="modal-body" id="update_salaryForm_body">
						<form class="form-horizontal" id="form_file_loan" method="post">
							<div class="form-group">
								<div class="col-md-6">
									<label class="control-label">Amount</label>
									<input type="text" class="form-control" name="amount" id="float_only" required="required"/>
								</div>

								<div class="col-md-6">
									<label class="control-label">Type</label>
									<select name="loan_type" class="form-control" required="required">
										<option value=""></option>
										<option value="1">Salary Loan</option>
										<option value="2">SIMKIMBAN</option>
										<option value="3">Employee Benifit Program Loan</option>
									</select>
								</div>
							</div>

							<div class="form-group" id="div_program" style="display: none">
								<div class="col-md-6 col-md-offset-6">
									<label class="control-label">Program</label>
									<select name="program" class="form-control" required="required">
										<option value=""></option>
										<option value="1">Service Rewards</option>
										<option value="2">Tulong Pangkabuhayan Program</option>
										<option value="3">Education Assistance Program</option>
										<option value="4">Housing Renovation Program</option>
										<option value="5">Emergency and Medical Assistance Program</option>
									</select>
									
								</div>

							</div>


							<div class="form-group">
								<div class="col-md-12">
									<label class="control-label">Purpose</label>
									<textarea name="purpose" class="form-control" required="required"></textarea>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<button type="submit" class="btn btn-primary btn-sm pull-right" id="file_loan">File</button>
								</div>
							</div>
						</form>
						
					</div> 
				</div>

			</div>
		</div> <!-- end of modal -->



		<div id="editLoanModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#1d8348;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-plus-sign' style='color:#fff'></span>&nbsp;Loan Form</h5>
					</div> 
					<div class="modal-body" id="update_file_loan_body">
						
						
					</div> 
				</div>

			</div>
		</div> <!-- end of modal -->



		<!-- FOR DELETE CONFIRMATION MODAL -->
		<div id="cancelFileLoanModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#21618c;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-trash' style='color:#fff'></span>&nbsp;Cancel Notification</h5>
					</div> 
					<div class="modal-body" id="cancel_file_loan_modal_body">
						
					</div> 
					<div class="modal-footer" style="padding:5px;text-align:center;" id="delete_modal_footer">
						<a href="#" class="btn btn-default" id="cancel_yes_fil_loan">YES</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->



		<!-- FOR DELETE CONFIRMATION MODAL -->
		<div id="disapproveFileLoanModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#21618c;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-trash' style='color:#fff'></span>&nbsp;Disapprove Notification</h5>
					</div> 
					<div class="modal-body" id="disapprove_file_loan_modal_body">
						
					</div> 
					<div class="modal-footer" style="padding:5px;text-align:center;" id="delete_modal_footer">
						<a href="#" class="btn btn-default" id="disapprove_yes_fil_loan">YES</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->



		<!-- FOR DELETE CONFIRMATION MODAL -->
		<div id="approveSimkimbanLoanModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#21618c;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-trash' style='color:#fff'></span>&nbsp;Approve Notification</h5>
					</div> 
					<div class="modal-body" id="approve_simkimban_loan_body">
						
					</div> 
					<div class="modal-footer" style="padding:5px;text-align:center;" id="delete_modal_footer">
						<a href="#" class="btn btn-default" id="approve_yes_file_simkimban_loan">YES</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- FOR DELETE CONFIRMATION MODAL -->
		<div id="disApproveSimkimbanLoanModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#21618c;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-trash' style='color:#fff'></span>&nbsp;Approve Notification</h5>
					</div> 
					<div class="modal-body" id="disapprove_simkimban_loan_body">
						
					</div> 
					<div class="modal-footer" style="padding:5px;text-align:center;" id="delete_modal_footer">
						<a href="#" class="btn btn-default" id="disapprove_yes_file_simkimban_loan">YES</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->


		<script>
			$(document).ready(function(){
				//alert("HELLO WORLD!");
				$("#form_file_loan").submit(function(event) {          
		              event.preventDefault();
		             
		            });
				$("#file_loan").on("click",function(){
				//	alert("HELLO WORLD!");
					var amount = $("input[name='amount']").val();
					var type = $("select[name='loan_type']").val();
					var program = $("select[name='program']").val();
					var purpose = $("textarea[name='purpose']").val();

					if (amount != "" && type != "" && purpose != ""){

						if (type == 3 && program == ""){
							//alert("Wew");
						}

						else {
							//alert("READY FOR SUBMITTION");

						//var datastring = "amount="+amount+"&type="+type+"&purpose="+purpose;
						//alert(datastring);
							$("#form_file_loan").attr("action","php script/emp_file_loan.php");
							$("#form_file_loan").unbind().submit();
						}
					}


				});


				$("select[name='loan_type']").on("change",function(){
					//alert($(this).val());
					if ($(this).val() == 3){
						$("#div_program").removeAttr("style");
						$("select[name='program']").attr("required","required");
					}

					else {
						$("select[name='program']").val("");
						$("#div_program").attr("style","display:none");
						$("select[name='program']").removeAttr("required");
					}
				});


				$("button[id='update_file_loan']").on("click",function(){
					var file_loan_id = $(this).closest("tr").attr("id");

					var datastring = "file_loan_id="+file_loan_id;

					$.ajax({
			            type: "POST",
			            url: "ajax/append_update_file_loan.php",
			            data: datastring,
			            cache: false,
			           // datatype: "php",
			            success: function (data) {
			            	//alert(data);
			              //$("select[name='position']").html(data);
			               	$('#update_file_loan_body').html(data);
			              	$("#editLoanModal").modal("show");
			            }
			        });
				});	


				$("button[id='cancel_file_loan']").on("click",function(){
					var file_loan_id = $(this).closest("tr").attr("id");

					var datastring = "file_loan_id="+file_loan_id;

					$.ajax({
			            type: "POST",
			            url: "ajax/append_cancel_file_loan.php",
			            data: datastring,
			            cache: false,
			           // datatype: "php",
			            success: function (data) {
			            	//alert(data);
			              //$("select[name='position']").html(data);
			               	$('#cancel_file_loan_modal_body').html(data);
			              	$("#cancelFileLoanModal").modal("show");
			            }
			        });
				});



				$("button[id='disapprove_file_loan']").on("click",function(){
					var file_loan_id = $(this).closest("tr").attr("id");

					var datastring = "file_loan_id="+file_loan_id;

					$.ajax({
			            type: "POST",
			            url: "ajax/append_disapprove_file_loan.php",
			            data: datastring,
			            cache: false,
			           // datatype: "php",
			            success: function (data) {
			            	//alert(data);
			              //$("select[name='position']").html(data);
			               	$('#disapprove_file_loan_modal_body').html(data);
			              	$("#disapproveFileLoanModal").modal("show");
			            }
			        });
				});



				$("button[id='create_loan_schedule']").on("click",function(){
					var file_loan_id = $(this).closest("tr").attr("id");

					var datastring = "file_loan_id="+file_loan_id;

					$.ajax({
			            type: "POST",
			            url: "ajax/append_create_loan_schedule.php",
			            data: datastring,
			            cache: false,
			           // datatype: "php",
			            success: function (data) {
			            	//alert(data);
			              //$("select[name='position']").html(data);
		               		$('#update_file_loan_body').html(data);
			              	$("#editLoanModal").modal("show");
			            }
			        });
				});


				$("button[id='disapprove_file_loan']").on("click",function(){
					var file_loan_id = $(this).closest("tr").attr("id");

					var datastring = "file_loan_id="+file_loan_id;

					$.ajax({
			            type: "POST",
			            url: "ajax/append_disapprove_file_loan.php",
			            data: datastring,
			            cache: false,
			           // datatype: "php",
			            success: function (data) {
			            	//alert(data);
			              //$("select[name='position']").html(data);
			               	$('#disapprove_file_loan_modal_body').html(data);
			              	$("#disapproveFileLoanModal").modal("show");
			            }
			        });
				});


				$("span[id='approve_simkimbanLoan']").on("click",function(){
					//alert("HELLO WORLD!");
					var simkimban_id = $(this).closest("tr").attr("id");

					var datastring = "simkimban_id="+simkimban_id;

					$.ajax({
			            type: "POST",
			            url: "ajax/append_approve_simkimban_loan.php",
			            data: datastring,
			            cache: false,
			           // datatype: "php",
			            success: function (data) {
			            	//alert(data);
			              //$("select[name='position']").html(data);
			               	$('#approve_simkimban_loan_body').html(data);
			              	$("#approveSimkimbanLoanModal").modal("show");
			            }
			        });
				});


				$("span[id='dis_approve_simkimbanLoan']").on("click",function(){
					//alert("HELLO WORLD!");
					var simkimban_id = $(this).closest("tr").attr("id");

					var datastring = "simkimban_id="+simkimban_id;

					$.ajax({
			            type: "POST",
			            url: "ajax/append_dis_approve_simkimban_loan.php",
			            data: datastring,
			            cache: false,
			           // datatype: "php",
			            success: function (data) {
			            	//alert(data);
			              //$("select[name='position']").html(data);
			               	$('#disapprove_simkimban_loan_body').html(data);
			              	$("#disApproveSimkimbanLoanModal").modal("show");
			            }
			        });
				});



			});
		</script>

	</body>
</html>