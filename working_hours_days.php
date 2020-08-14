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

include "class/working_hours_class.php";
include "class/working_days_class.php";


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
$_SESSION["active_working_hours"] = "background-color:#1d8348";
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
	if ($_SESSION["role"] == 4) {
		header("Location:Mainform.php");
	}


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
		<title>Working Hours & Days</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> <!-- for symbols -->
		<?php
			$company_class = new Company;
			$row_company = $company_class->getInfoByCompanyId($row->company_id);
			$logo_source = $row_company->logo_source;
		?>
		<link rel="shortcut icon" href="<?php echo $logo_source; ?>"> <!-- for logo -->

		<!-- css -->
		<link rel="stylesheet" href="css/lumen.min.css">
		<link rel="stylesheet" href="text editor/jquery-te-1.4.0.css">
		<link rel="stylesheet" href="css/plug ins/calendar/dcalendar.picker.css">
		<link rel="stylesheet" href="css/plug ins/data_tables/dataTables.bootstrap.css">
		<link rel="stylesheet" href="css/custom.css">

		<!-- js -->
		<script src="js/jquery-1.12.2.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script type="text/javascript" src="text editor/jquery-te-1.4.0.min.js" charset="utf-8"></script>
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
		<script src="js/custom.js"></script>
		<script src="js/jquery-ui.js"></script>
		<script src="js/modal.js"></script>
		<script>
			$(document).ready(function(){

				/*$('#working_hours_list').dataTable( {
				     "order": [],
				    "columnDefs": [ {
				      "targets"  : 'no-sort',
				      "orderable": false,
				    }]
				});*/
				$('table').dataTable( {
				     "order": [],
				    "columnDefs": [ {
				      "targets"  : 'no-sort',
				      "orderable": false,
				    }]
				});


				 // for handling security in time in hours
			    $("input[name='hour_time_in']").on('input', function(){
			      if ($(this).val() >= 24){
			      		 $(this).val($(this).val().slice(0,-1));
			      	}

			   });

			      // for handling security in time in hours
			    $("input[name='min_time_in']").on('input', function(){
			      	if ($(this).val() >= 60){
			      		 $(this).val($(this).val().slice(0,-1));
			      	}

			   });

			      // for handling security in time in hours
			    $("input[name='sec_time_in']").on('input', function(){
			      	if ($(this).val() >= 60){
			      		 $(this).val($(this).val().slice(0,-1));
			      	}

			   });


			     // for handling security in time out hours
			    $("input[name='hour_time_out']").on('input', function(){
			      if ($(this).val() >= 24){
			      		 $(this).val($(this).val().slice(0,-1));
			      	}

			   });

			      // for handling security in time out hours
			    $("input[name='min_time_out']").on('input', function(){
			      	if ($(this).val() >= 60){
			      		 $(this).val($(this).val().slice(0,-1));
			      	}

			   });

			      // for handling security in time out hours
			    $("input[name='sec_time_out']").on('input', function(){
			      	if ($(this).val() >= 60){
			      		 $(this).val($(this).val().slice(0,-1));
			      	}

			   });


		       // for handling security in number only
			    $("input[id='number_only']").on('input', function(){
			       if ($(this).attr("maxlength") != 2){
			            if ($(this).val().length > 2){
			                $(this).val($(this).val().slice(0,-1));
			            }
			           $(this).attr("maxlength","2");
			       }

			   });


			    // for message
			    <?php
			    	// error in adding working hours
					if (isset($_SESSION["error_adding_working_hours"])){
						echo '$(document).ready(function() {
							$("#error_modal_body").html("'.$_SESSION["error_adding_working_hours"].'");
							$("#errorModal").modal("show");
						});';
						$_SESSION["error_adding_working_hours"] = null;
					}


					if (isset($_SESSION["error_deleting"])){
						echo '$(document).ready(function() {
							$("#error_modal_body").html("'.$_SESSION["error_deleting"].'");
							$("#errorModal").modal("show");
						});';
						$_SESSION["error_deleting"] = null;
					}


					// for success in adding working hours
					if (isset($_SESSION["success_adding_working_hours"])){
						echo '$(document).ready(function() {
							$("#success_modal_body_add").html("'.$_SESSION["success_adding_working_hours"].'");
							$("#add_successModal").modal("show");
						});';
						$_SESSION["success_adding_working_hours"] = null;
					}


					// for success in adding working hours
					if (isset($_SESSION["success_working_days"])){
						echo '$(document).ready(function() {
							$("#success_modal_body_add").html("'.$_SESSION["success_working_days"].'");
							$("#add_successModal").modal("show");
						});';
						$_SESSION["success_working_days"] = null;
					}


					// error in updating working hours
					if (isset($_SESSION["error_update_working_hours"])){
						echo '$(document).ready(function() {
							$("#error_modal_body").html("'.$_SESSION["error_update_working_hours"].'");
							$("#errorModal").modal("show");
						});';
						$_SESSION["error_update_working_hours"] = null;
					}

					// success in updating working hours 
					if (isset($_SESSION["success_update_working_hours"])){
						echo '$(document).ready(function() {
							$("#success_modal_body_add").html("'.$_SESSION["success_update_working_hours"].'");
							$("#add_successModal").modal("show");
						});';
						$_SESSION["success_update_working_hours"] = null;
					}


					// for success in deletion
					if (isset($_SESSION["success_update_working_hours"])){
						echo '$(document).ready(function() {
							$("#success_modal_body_add").html("'.$_SESSION["success_update_working_hours"].'");
							$("#add_successModal").modal("show");
						});';
						$_SESSION["success_update_working_hours"] = null;
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
						<li class="active" id="home_id">Working Hours & Days</li> 
					</ol>
				</div>
			</div>


			<?php
	 			$working_hours_class = new WorkingHours;
	 			$working_days_class = new WorkingDays;

	 		?>


			<!-- for body -->
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-12 content-div">
						<div class="thumbnail" style="border:1px solid #BDBDBD;">
							<div class="caption">
								<ul class="nav nav-tabs">
								  <li class="active"><a data-toggle="tab" href="#working_hours">Working Hours</a></li>
								  <li><a data-toggle="tab" href="#working_days">Working Days</a></li>
								</ul>
								<div class="tab-content">
									<div id="working_hours" class="tab-pane fade in active">
										<fieldset style="margin-top:20px;">
											<legend style="color:#357ca5;border-bottom:1px solid #BDBDBD"><span class="glyphicon glyphicon-list-alt"></span> Working Hours List 
												<?php
													if ($id != 21){
												?>	
												<small class="pull-right"><a href="#add_workinghours_modal" data-toggle="modal" class="custom-add-items"><span class="glyphicon glyphicon-plus"></span>Add New</a></small>
												<?php
													}
												?>	
											</legend>
											<div class="col-sm-10 col-sm-offset-1">
												<table id="working_hours_list" class="table table-bordered table-hover table-striped" style="border:1px solid #BDBDBD;">
													<thead>
														<tr>
															<th><span class="glyphicon glyphicon-tasks" style="color:#186a3b"></span> Working Hours</th>
															<th class="no-sort"><span class="glyphicon glyphicon-wrench" style="color:#186a3b"></span> Action</th>
														</tr>
													</thead>
													<tbody>	
														<?php
															$working_hours_class->getWorkingHoursInfoToTable();
														?>
													</tbody>
												</table>
											</div>
										</fieldset>
									</div>
									<div id="working_days" class="tab-pane fade in">
										<fieldset style="margin-top:20px;">
											<legend style="color:#357ca5;border-bottom:1px solid #BDBDBD"><span class="glyphicon glyphicon-list-alt"></span> Working Days 
												<?php
													if ($id != 21){
												?>	
												<small class="pull-right"><a href="#add_workingdays_modal" data-toggle="modal" class="custom-add-items"><span class="glyphicon glyphicon-plus"></span>Add New</a></small>
												<?php
													}
												?>	
											</legend>
											<div class="col-sm-10 col-sm-offset-1">
												<table id="working_days_list" class="table table-bordered table-hover table-striped" style="border:1px solid #BDBDBD;">
													<thead>
														<tr>
															<th><span class="glyphicon glyphicon-tasks" style="color:#186a3b"></span> Working Days</th>
															<th class="no-sort"><span class="glyphicon glyphicon-wrench" style="color:#186a3b"></span> Action</th>
														</tr>
													</thead>
													<tbody>	
														<?php
															$working_days_class->getWorkingDaysInfoToTable();
															//echo $working_days_id_array;
														?>
													</tbody>
												</table>
											</div>
										</fieldset>
									</div>
								</div>
							</div>
						</div> <!-- end of thumbnail -->
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



		<!-- FOR ADD NEW MODAL -->
		<div id="add_workinghours_modal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog" style="width:350px;">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#1b4f72;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-plus' style='color:#fff'></span>&nbsp;Add Working Hours</h5>
					</div> 
					<div class="modal-body">
						<div class="container-fluid">
							<form class="form-horizontal" action="" id="form_addWorkingHours" method="post">	<!-- ../php script/position_department_script.php -->	
								<font><b>Note:</b> Used Military Time</font>
								<div class="form-group">
									<div class="col-sm-12">
										<label class="control-label"><span class="glyphicon glyphicon-time" style="color:#2E86C1;"></span> Time In:&nbsp;<span class="red-asterisk">*</span></label>
									</div>
									<div class="col-sm-3" style="margin-left:40px;margin-right:-20px;">
										<input type="text" id="number_only" name="hour_time_in"  value="" class="form-control" placeholder="H" required="required">
									</div>
									<div class="col-sm-1" style="margin-top:10px;">
										:
									</div>
									<div class="col-sm-3" style="margin-left:-20px;margin-right:-20px;">
										<input type="text" id="number_only" name="min_time_in" value="" class="form-control" placeholder="M" required="required">
									</div>
									<div class="col-sm-1" style="margin-top:10px;">
										:
									</div>
									<div class="col-sm-3" style="margin-left:-20px;">
										<input type="text" id="input_payroll" name="sec_time_in" value="00" class="form-control" placeholder="S" required="required">
									</div>
								</div>

								<div class="form-group">
									<div class="col-sm-12">
										<label class="control-label"><span class="glyphicon glyphicon-time" style="color:#2E86C1;"></span> Time Out:&nbsp;<span class="red-asterisk">*</span></label>
									</div>
									<div class="col-sm-3" style="margin-left:40px;margin-right:-20px;">
										<input type="text" id="number_only" name="hour_time_out" value="" class="form-control" placeholder="H" required="required">
									</div>
									<div class="col-sm-1" style="margin-top:10px;">
										:
									</div>
									<div class="col-sm-3" style="margin-left:-20px;margin-right:-20px;">
										<input type="text" id="number_only" name="min_time_out" value="" class="form-control" placeholder="M" required="required">
									</div>
									<div class="col-sm-1" style="margin-top:10px;">
										:
									</div>
									<div class="col-sm-3" style="margin-left:-20px;">
										<input type="text" id="input_payroll" name="sec_time_out" value="00" class="form-control" placeholder="S" required="required">
									</div>
								</div>	

								<div class="form-group">
									<div style="text-align:center;">
										<input type="submit" id="add_working_hours" value="Add Working Hours" class="btn btn-success"/>
									</div>
								</div>
							</form>
						</div>
					</div> 
					<!-- <div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
					</div> -->
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- FOR ADD NEW MODAL -->
		<div id="add_workingdays_modal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog" style="width:350px;">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#1b4f72;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-plus' style='color:#fff'></span>&nbsp;Add Working Days</h5>
					</div> 
					<div class="modal-body">
						<div class="container-fluid">
							<form class="form-horizontal" action="" id="form_addWorkingDays" method="post">	<!-- ../php script/position_department_script.php -->	
								
								<div class="form-group">
									<div class="col-sm-12">
										<label class="control-label"><span class="glyphicon glyphicon-time" style="color:#2E86C1;"></span> Day From:&nbsp;<span class="red-asterisk">*</span></label>
									</div>
									<div class="col-sm-12">
										<select class="form-control" name="day_from" id="working_days_select">
											<option value="">Please select</option>
											<?php
												$day_of_the_week = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");

												$count = count($day_of_the_week);

												$counter = 0;
												do {

											?>
											<option value="<?php echo $day_of_the_week[$counter]; ?>"><?php echo $day_of_the_week[$counter]; ?></option>
											<?php

													$counter++;
												}while($count > $counter);

											?>
											
										</select>
									</div>
								</div>


								<div class="form-group">
									<div class="col-sm-12">
										<label class="control-label"><span class="glyphicon glyphicon-time" style="color:#2E86C1;"></span> Day To:&nbsp;<span class="red-asterisk">*</span></label>
									</div>
									<div class="col-sm-12">
										<select class="form-control" name="day_to" id="working_days_select">
											<option value="">Please select</option>
											<?php
												$day_of_the_week = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");

												$count = count($day_of_the_week);

												$counter = 0;
												do {

											?>
											<option value="<?php echo $day_of_the_week[$counter]; ?>"><?php echo $day_of_the_week[$counter]; ?></option>
											<?php

													$counter++;
												}while($count > $counter);

											?>
											
										</select>
									</div>
								</div>

								<div class="form-group">
									<div class="col-sm-12">
										<label id="wd_error_msg"></label>
									</div>
								</div>
									

								<div class="form-group">
									<div style="text-align:center;">
										<input type="button" id="add_working_days" value="Add Working Days" class="btn btn-success"/>
									</div>
								</div>
							</form>
						</div>
					</div> 
					<!-- <div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
					</div> -->
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
						 <center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> There's is an error during getting of data.</center>
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
			<div class="modal-dialog" style="width:350px;">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#1d8348;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-pencil' style='color:#fff'></span>&nbsp;Update Notification</h5>
					</div> 
					<div class="modal-body" id="update_workingHoursForm_body">
						
					</div> 
				</div>

			</div>
		</div> <!-- end of modal -->

		<!-- FOR SUCCESS MODAL -->
		<div id="updateFormModalWorkingDays" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog" style="width:350px;">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#1d8348;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-pencil' style='color:#fff'></span>&nbsp;Update Notification</h5>
					</div> 
					<div class="modal-body" id="update_workingDaysForm_body">
						
					</div> 
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- FOR DELETE CONFIRMATION MODAL -->
		<div id="deleteWorkingHoursConfirmationModal" class="modal fade" role="dialog" tabindex="-1">
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
						<a href="#" class="btn btn-default" id="delete_yes_workingHours">YES</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->

		<!-- FOR DELETE CONFIRMATION MODAL -->
		<div id="deleteWorkingDaysConfirmationModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#21618c;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-trash' style='color:#fff'></span>&nbsp;Delete Notification</h5>
					</div> 
					<div class="modal-body" id="delete_wd_modal_body">
						
					</div> 
					<div class="modal-footer" style="padding:5px;text-align:center;" id="delete_modal_footer">
						<a href="#" class="btn btn-default" id="delete_yes_workingDays">YES</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
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

	<script>
		$(document).ready(function(){

			var day_of_the_week = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
	    	var day_of_the_week_value = [0,1,2,3,4,5,6];

			$("a[id='edit_working_days']").on("click",function (){
		        var working_days_id = $(this).closest("tr").attr("id");
		        //index -=1; // to prepare in array	     

		        var datastring = "working_days_id="+working_days_id;

				$.ajax({
		            type: "POST",
		            url: "ajax/append_edit_working_days.php",
		            data: datastring,
		            cache: false,
		           // datatype: "php",
		            success: function (data) {

		              	if (data == "Error"){
		                  $("#errorModal").modal("show");
		                }
		                // if success
		                else {       
		                    $("#update_workingDaysForm_body").html(data);
		                    $("#updateFormModalWorkingDays").modal("show");
		                }
		              	
		            }
		        });
		    });

		    

		    /*$("select[id='working_days_select']").on("change",function(){
		    	

		    	if ($(this).val() != ""){
		    		var day_from = $("select[name='day_from']").val();
		    		var day_to = $("select[name='day_to']").val();

		    		//alert(day_from + " " + day_to);

		    		var validate_day_from = day_of_the_week.includes(day_from);
		    		var validate_day_to = day_of_the_week.includes(day_to);


		    		//alert(validate_day_from + " " + validate_day_to);


		    		if (validate_day_from == true && validate_day_to == true){
		    			//alert("ANOTHER LOGIC");
		    			var day_from_value = day_of_the_week_value[day_of_the_week.indexOf(day_from)]; // 1
		    			var day_to_value = day_of_the_week_value[day_of_the_week.indexOf(day_to)]; // 1

		    			//alert(day_from_value + " " + day_to_value);
		    			if (day_from_value > day_to_value ){
		    				$("#wd_error_msg").html("<span class='color-red'><b>Day From</b> must be not greater than <b>Day To</b></span>");
		    			}
		    			else if (day_from_value == day_to_value){
		    				$("#wd_error_msg").html("<span class='color-red'><b>Day From</b> must be not equal to <b>Day To</b></span>");
		    			}

		    			else {
		    				$("#wd_error_msg").html("");
		    			}
		    		}

		    	}

		    });*/

		    $("#add_working_days").on("click",function(){
		    	//alert("HELLO WORLD!");
		    	var day_from = $("select[name='day_from']").val();
	    		var day_to = $("select[name='day_to']").val(); 

	    		if (day_from == "" || day_to == ""){
	    			$("#wd_error_msg").html("<span class='color-red'>Please fill up all fields.</span>");
	    		}
	    		else {
	    			var validate_day_from = day_of_the_week.includes(day_from);
		    		var validate_day_to = day_of_the_week.includes(day_to);


		    		//alert(validate_day_from + " " + validate_day_to);


		    		if (validate_day_from == true && validate_day_to == true){
		    			//alert("ANOTHER LOGIC");
		    			var day_from_value = day_of_the_week_value[day_of_the_week.indexOf(day_from)]; // 1
		    			var day_to_value = day_of_the_week_value[day_of_the_week.indexOf(day_to)]; // 1

		    			//alert(day_from_value + " " + day_to_value);
		    			if (day_from_value > day_to_value ){
		    				$("#wd_error_msg").html("<span class='color-red'><b>Day From</b> must be not greater than <b>Day To</b></span>");
		    			}
		    			else if (day_from_value == day_to_value){
		    				$("#wd_error_msg").html("<span class='color-red'><b>Day From</b> must be not equal to <b>Day To</b></span>");
		    			}

		    			else {
		    				$(this).attr("disabled","disabled");
		    				$("#wd_error_msg").html("");
		    				//alert("READY FOR SUBMITTION");
		    				var datastring = "day_from="+day_from_value+"&day_to="+day_to_value;
		    				
		    				$("#wd_error_msg").html('<div class="loader"></div>Please wait ...');
		    				$.ajax({
					            type: "POST",
					            url: "php script/add_working_days.php",
					            data: datastring,
					            cache: false,
					           // datatype: "php",
					            success: function (data) {
					              	
					              	if (data != "Success"){
					              		$("#wd_error_msg").html(data);
					              		$("#add_working_days").removeAttr("disabled");
					              	}
					              	else {
					              		location.reload();
					              	}
					            }
					        });
		    			}
		    		}
	    		}
		    });


		    // for deleting working hours 
		  var delete_working_days_id = "";
	      $("a[id='delete_working_days']").on("click",function () {
	            var datastring = "working_days_id=" + $(this).closest("tr").attr("id");
	            //alert(datastring);

	            var working_days_id = $(this).closest("tr").attr("id");

	             $.ajax({
	              type: "POST",
	              url: "ajax/append_delete_working_days.php",
	              data: datastring,
	              cache: false,
	              success: function (data) {
	                // if has error 
	               if (data == "Error"){
	                  $("#errorModal").modal("show");
	                }
	                // if success
	                else {                         
	                    $("#delete_wd_modal_body").html(data);
	                    $("#deleteWorkingDaysConfirmationModal").modal("show");
	                    delete_working_days_id = working_days_id;

	                }
	                
	              }
	           });

	      });


	      // for delete yes working hours 
	      $("a[id='delete_yes_workingDays']").on("click",function () {
	      		//alert("wew");
	           // delete_working_hours.php
	           $("#form_deleteWorkingDays").append("<input type='hidden' name='working_days_id' value='"+delete_working_days_id+"' />");
	           $("#form_deleteWorkingDays").attr("action","php script/delete_working_days.php");
	           $("#form_deleteWorkingDays").submit();
	      });

	   	});
   	</script>
	</body>
</html>