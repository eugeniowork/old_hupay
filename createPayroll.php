<?php
session_start();
if (!isset($_SESSION["id"])){
	header("Location:index.php");
}
include "class/connect.php"; // fixed class
include "class/position_class.php"; // fixed class
include "class/attendance_notifications.php"; // fixed class
include "class/date.php";
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

include "class/cut_off.php";
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
$_SESSION["active_sub_create_salary"] = "active-sub-menu";
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
	if ($_SESSION["role"] == 2 || $_SESSION["role"] == 4) {
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
		<title>Create Payroll</title>
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
		<style type="text/css">
			input[readonly="readonly"] { 
				/*color: #21618c ;*/
			}
		</style>


		<!-- js -->
		<script src="js/jquery-1.12.2.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/plug ins/calendar/dcalendar.picker.js"></script>
		<script src="js/plug ins/data_tables/jquery.dataTables.js"></script>
		<script src="js/plug ins/data_tables/dataTables.bootstrap.js"></script>
		<script src="js/chevron.js"></script>
		<script src="js/numbers_only.js"></script>
		<script src="js/maxlength.js"></script>
		<script src="js/notifications.js"></script>
		<script src="js/custom.js"></script>
		<script src="js/jquery-ui.js"></script> 
		<script src="js/modal.js"></script>
		<script>
			$(document).ready(function(){
				//$('#attendance_list').DataTable();
				$('#attendance_list').dataTable( {
				     "order": [],
				    "columnDefs": [ {
				      "targets"  : 'no-sort',
				      "orderable": false,
				    }]
				});


				<?php
					// error in adding
						if (isset($_SESSION["save_payroll_error_msg"])){
							echo '$(document).ready(function() {
								$("#add_error_modal_body").html("'.$_SESSION["save_payroll_error_msg"].'");
								$("#add_errorModal").modal("show");
							});';
							$_SESSION["save_payroll_error_msg"] = null;
						}



								// success in adding
						if (isset($_SESSION["save_payroll_success_msg"])){
							echo '$(document).ready(function() {
								$("#success_modal_body_add").html("'.$_SESSION["save_payroll_success_msg"].'");
								$("#add_successModal").modal("show");
							});';
							$_SESSION["save_payroll_success_msg"] = null;
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
						<li class="active" id="home_id">Create Payroll</li> 
					</ol>
				</div>
			</div>

			<!-- for body -->
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-12 content-div">
						<div class="thumbnail" style="border:1px solid #BDBDBD;background-color:none;">
							<div class="caption" id="payroll_form">
								
								<fieldset>
									<legend style="color:#357ca5;border-bottom:1px solid #BDBDBD"><span class="glyphicon glyphicon-list-alt"></span> cut off: 
									<?php
										$cut_off_class = new CutOff;
										$cut_off_class->getCutOffPeriod();

									?>
									</legend>
									<div class="col-sm-12">
										<label class="control-label"><span class="glyphicon glyphicon-user" style="color:#2E86C1;"></span> <a href="#" id="choose_employee">Choose Employee ..</a> </label>
										
									</div>
									<div class="col-sm-12">
										<div id="payroll_form">
											<form class="form-horizontal" method="post" action="php script/add_payroll_info.php">
												<div class="thumbnail" style="border:1px solid #5dade2;">
													<div class="caption">
														<!--<div class="col-sm-12">
															
															<center><img src="img/payroll.png" class="payroll-img"/></center>
														-->
														<center><h3><u style="color:#196f3d">Employee Payroll Form</u></h3></center>
														<br/>
														<div class="container-fluid">
															<div class="row">
																<div class="col-sm-6">
																	<div class="panel panel-info">
																	    <div class="panel-footer" style="border:1px solid #BDBDBD;">
																	    	<div class="group-title"><b style="color: #21618c ">Employee Information</b></div>
															    			<div class="container-fluid">
															    				<div class="form-group">
																		    		<label class="control-label col-sm-5">Employee Name:&nbsp;<span class="red-asterisk">*</span></label>
																		    		<div class="col-sm-7">
																		    			<input id="input_payroll" type="text" name="employeeName" autocomplete="off" value="" class="form-control" placeholder="Employee Name .." required="required">
																		    		</div> 
																	    		</div>

																	    		<div class="form-group">
																		    		<label class="control-label col-sm-5">Department:&nbsp;<span class="red-asterisk">*</span></label>
																		    		<div class="col-sm-7">
																		    			<input id="input_payroll" type="text" name="empDepartment" autocomplete="off" value="" placeholder="Department .." class="form-control" required="required">
																		    		</div> 
																	    		</div>

																	    		<!--<div class="form-group">
																		    		<label class="control-label col-sm-5">Period: </label>
																		    		<div class="col-sm-7">
																		    			<input type="text" name="" autocomplete="off" value="Semi-Monthly" class="form-control" readonly="readonly">
																		    		</div> 
																	    		</div> -->


																    		</div>
																	    </div>
																   </div>

																   <div class="panel panel-info">
																	    <div class="panel-footer" style="border:1px solid #BDBDBD;">
																	    	<div class="group-title"><b style="color: #2471a3 ">Earnings</b></div>
															    			<div class="container-fluid">

															    				<div class="form-group">
																		    		<label class="control-label col-sm-5">Cut Off Period:&nbsp;<span class="red-asterisk"></span></label>
																		    		<div class="col-sm-7">
																		    			<input id="input_payroll" type="text" name="cutOffPeriod" autocomplete="off" value="Semi-Monthly" class="form-control" placeholder="Cut Off Period .." required="required">
																		    		</div> 
																	    		</div>

																	    		<div class="form-group">
																		    		<label class="control-label col-sm-5">Basic Salary:&nbsp;<span class="red-asterisk">*</span></label>
																		    		<div class="col-sm-7">
																		    			<input id="input_payroll" type="text" name="basicSalary" autocomplete="off" value="" class="form-control" placeholder="Basic Salary .." required="required">
																		    		</div> 
																	    		</div>

																	    		<div class="form-group">
																		    		<label class="control-label col-sm-5">Regular OT:&nbsp;<span class="red-asterisk">*</span></label>
																		    		<div class="col-sm-7">
																		    			<input id="input_payroll" type="text" name="regularOT" autocomplete="off" value="" class="form-control" placeholder="Regular OT .." required="required">
																		    		</div> 
																	    		</div>

																	    		<div class="form-group">
																		    		<label class="control-label col-sm-5">Holiday OT:&nbsp;<span class="red-asterisk">*</span></label>
																		    		<div class="col-sm-7">
																		    			<input id="input_payroll" type="text" name="holidayOT" autocomplete="off" value="" class="form-control" placeholder="Regular OT .." required="required">
																		    		</div> 
																	    		</div>

																	    		<div class="form-group">
																		    		<label class="control-label col-sm-5">Restday OT:&nbsp;<span class="red-asterisk">*</span></label>
																		    		<div class="col-sm-7">
																		    			<input id="input_payroll" type="text" name="restdayOT" autocomplete="off" value="" class="form-control" placeholder="Regular OT .." required="required">
																		    		</div> 
																	    		</div>

																	    		<div class="form-group">
																		    		<label class="control-label col-sm-5">Holiday / Restday OT:&nbsp;<span class="red-asterisk">*</span></label>
																		    		<div class="col-sm-7">
																		    			<input id="input_payroll" type="text" name="holidayRestdayOT" autocomplete="off" value="" class="form-control" placeholder="Regular OT .." required="required">
																		    		</div> 
																	    		</div>

																	    		<div class="form-group">
																		    		<label class="control-label col-sm-5">Tardiness:&nbsp;<span class="red-asterisk">*</span></label>
																		    		<div class="col-sm-7">
																		    			<input id="input_payroll" type="text" name="tardiness" autocomplete="off" value="" class="form-control" placeholder="Tardiness .." required="required">
																		    		</div> 
																	    		</div>

																	    		<div class="form-group">
																		    		<label class="control-label col-sm-5">Absences:&nbsp;<span class="red-asterisk">*</span></label>
																		    		<div class="col-sm-7">
																		    			<input id="input_payroll" type="text" name="absent" autocomplete="off" value="" class="form-control" placeholder="Absences .." required="required">
																		    		</div> 
																	    		</div>

																	    		<div class="form-group">
																		    		<label class="control-label col-sm-5">Adjustment:&nbsp;<span class="red-asterisk">*</span></label>
																		    		<div class="col-sm-7">
																		    			<input id="float_only" type="text" name="adjustment" autocomplete="off" value="0" class="form-control" placeholder="Absences .." required="required">
																		    		</div> 
																	    		</div>

																	    		

																	    		<div class="form-group">
																		    		<label class="control-label col-sm-5">Total Gross Income:&nbsp;<span class="red-asterisk">*</span></label>
																		    		<div class="col-sm-7">
																		    			<input id="input_payroll" type="text" name="totalGrossIncome" autocomplete="off" value="" class="form-control" placeholder="Total Gross Income .." required="required">
																		    		</div> 
																	    		</div>

																    		</div>
																	    </div>
																   </div>

																    <div class="panel panel-info">
																	    <div class="panel-footer" style="border:1px solid #BDBDBD;">
																	    	<div class="group-title"><b style="color: #2471a3 ">Allowance</b></div>
																	    	<div class="container-fluid">
																    			<div class="form-group">
																		    		<label class="control-label col-sm-5">Nontax Allowance:&nbsp;<span class="red-asterisk">*</span></label>
																		    		<div class="col-sm-7">
																		    			<input id="input_payroll" type="text" name="allowance" autocomplete="off" value="" class="form-control" placeholder="Nontax Allowance .." required="required">
																		    		</div> 
																	    		</div>
																	    		
																    		</div>
																	    </div>
																   </div> <!-- end of panel -->

															   </div>


															    <div class="col-sm-6">
																	<div class="panel panel-info">
																	    <div class="panel-footer" style="border:1px solid #BDBDBD;">
																	    	<div class="group-title"><b style="color: #ca6f1e ">Deductions</b></div>
																	    	<div class="container-fluid">

																	    		

															    				<div class="form-group">
																		    		<label class="control-label col-sm-5">SSS:&nbsp;<span class="red-asterisk">*</span></label>
																		    		<div class="col-sm-7">
																		    			<input id="input_payroll" type="text" name="sssContribution" autocomplete="off" value="" class="form-control" placeholder="SSS .." required="required">
																		    		</div> 
																	    		</div>

																	    		<div class="form-group">
																		    		<label class="control-label col-sm-5">Philhealth:&nbsp;<span class="red-asterisk">*</span></label>
																		    		<div class="col-sm-7">
																		    			<input id="input_payroll" type="text" name="philhealthContribution" autocomplete="off" value="" class="form-control" placeholder="Philhealth .." required="required">
																		    		</div> 
																	    		</div>

																	    		<div class="form-group">
																		    		<label class="control-label col-sm-5">Pag-ibig:&nbsp;<span class="red-asterisk">*</span></label>
																		    		<div class="col-sm-7">
																		    			<input id="input_payroll" type="text" name="pagibigContribution" autocomplete="off" value="" class="form-control" placeholder="Pag-ibig .." required="required">
																		    		</div> 
																	    		</div>


																	    		<div class="form-group">
																		    		<label class="control-label col-sm-5">SSS Loan:&nbsp;<span class="red-asterisk">*</span></label>
																		    		<div class="col-sm-7">
																		    			<input id="float_only" type="text" name="sssLoan" autocomplete="off" value="0" class="form-control" placeholder="SSS Loan .." required="required">
																		    		</div> 
																	    		</div>


																	    		<div class="form-group">
																		    		<label class="control-label col-sm-5">Pag-ibig Loan:&nbsp;<span class="red-asterisk">*</span></label>
																		    		<div class="col-sm-7">
																		    			<input id="float_only" type="text" name="pagibigLoan" autocomplete="off" value="0" class="form-control" placeholder="Pag-ibig Loan.." required="required">
																		    		</div> 
																	    		</div>

																	    		<div class="form-group">
																		    		<label class="control-label col-sm-5">Cash Advance:&nbsp;<span class="red-asterisk">*</span></label>
																		    		<div class="col-sm-7">
																		    			<input id="float_only" type="text" name="cashAdvance" autocomplete="off" value="0" class="form-control" placeholder="Cash Advance .." value="" required="required">
																		    		</div> 
																	    		</div>


																	    		<div class="form-group">
																		    		<label class="control-label col-sm-5">Cash Bond:&nbsp;<span class="red-asterisk">*</span></label>
																		    		<div class="col-sm-7">
																		    			<input id="input_payroll" type="text" name="cashBond" autocomplete="off" value="" class="form-control" placeholder="Cash Bond .." required="required">
																		    		</div> 
																	    		</div>


																	    		<div class="form-group">
																		    		<label class="control-label col-sm-5">Total Deductions:&nbsp;<span class="red-asterisk"></span></label>
																		    		<div class="col-sm-7">
																		    			<input id="input_payroll" type="text" name="totalDeductions" autocomplete="off" value="" class="form-control" placeholder="Total Deductions .." value="" required="required">
																		    		</div> 
																	    		</div>

																    			<input id="input_payroll" type="hidden" name="employeeID" autocomplete="off" value="" class="form-control" placeholder="Employee Name .." required="required">

																    		</div>
																	    </div>
																   </div> <!-- end of panel -->


																     <div class="panel panel-info">
																	    <div class="panel-footer" style="border:1px solid #BDBDBD;">
																	    	<div class="group-title"><b style="color: #ca6f1e ">Total Earnings</b></div>
																	    	<div class="container-fluid">
																    			<div class="form-group">
																		    		<label class="control-label col-sm-5">Taxable Income:&nbsp;<span class="red-asterisk"></span></label>
																		    		<div class="col-sm-7">
																		    			<input id="input_payroll" type="text" name="taxableIncome" autocomplete="off" value="" class="form-control" placeholder="Taxable Income .." required="required">
																		    		</div> 
																	    		</div>
																	    		<div class="form-group">
																		    		<label class="control-label col-sm-5">Tax:&nbsp;<span class="red-asterisk"></span></label>
																		    		<div class="col-sm-7">
																		    			<input id="input_payroll" type="text" name="tax" autocomplete="off" value="" class="form-control" placeholder="Tax .." required="required">
																		    		</div> 
																	    		</div>
																	    		
																    		</div>
																	    </div>
																   </div> <!-- end of panel -->


																  

																   <div class="panel panel-info">
																	    <div class="panel-body" style="border:1px solid #BDBDBD;">
																	    	<div class="group-title-net-pay"><b style="color: #196f3d ">Net Pay</b></div>
																	    	<div class="container-fluid">

																	    		<div class="form-group">
																		    		<label class="control-label col-sm-5">Net Pay:&nbsp;<span class="red-asterisk"></span></label>
																		    		<div class="col-sm-7">
																		    			<input id="input_payroll" type="text" name="netPay" autocomplete="off" value="" class="form-control" placeholder="Net Pay .." required="required">
																		    		</div> 
																	    		</div>

																	    		<div class="form-group">
																	    			<button class="btn btn-success btn-sm pull-right" type="button" id="compute_payroll">Compute Net Pay</button>
																    			</div>
					
																    		</div>
																	    </div>
																   </div> <!-- end of panel -->


																   <div id="message">

																   </div>

															   </div>
														   </div>
													   </div>

												   </div>
											   </div>
											</form>
										</div>									
									</div>
									<!--<div class="col-sm-6 col-sm-offset-3" style="margin-top:10px;">
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
									</div> -->
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


		<!-- FOR ERROR MODAL IN  -->
		<div id="update_errorModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#b03a2e;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-warning-sign' style='color:#fff'></span>&nbsp;Choose Employee Notif</h5>
					</div> 
					<div class="modal-body" id="update_error_modal_body">
							There's an error during getting of data.
					</div> 
			 		<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- FOR ERROR MODAL IN  -->
		<div id="add_errorModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#b03a2e;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-plus' style='color:#fff'></span>&nbsp;Save Payroll Info Notif</h5>
					</div> 
					<div class="modal-body" id="add_error_modal_body">
						
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
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-plus' style='color:#fff'></span>&nbsp;Save Payroll Info Notif</h5>
					</div> 
					<div class="modal-body" id="success_modal_body_add">
						
					</div> 
					<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
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