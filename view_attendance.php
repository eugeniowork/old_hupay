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

include "class/time_in_time_out.php";
include "class/cut_off.php";
//include "class/attendance_overtime.php";
//include "class/attendance_notif.php";
include "class/holiday_class.php";

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
$_SESSION["active_sub_view_attendance"] = "active-sub-menu";
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


	// for security purpose dapat d nila mapupuntahan
	

	
?>

<?php
	// for security browsing purpose
	if ($_SESSION["role"] == 1 || $row->bio_id == "0") {
		header("Location:Mainform.php");
	}

?>


<!DOCTYPE html>
<html>
	<head>
		<title>View Attendance</title>
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
		<script src="js/numbers_only.js"></script>
		<script src="js/maxlength.js"></script>
		<script src="js/notifications.js"></script>
		<script src="js/date_validation.js"></script>
		<script src="js/readmore.js"></script>
		<script src="js/custom.js"></script>
		<script src="js/jquery-ui.js"></script> 
		<script src="js/modal.js"></script>
		<script>

			function attendance_ot_date(attendance_date){
				var datastring = "date="+attendance_date.value;
		        //alert(datastring);
		          var xhttp = new XMLHttpRequest();
		          xhttp.onreadystatechange = function() {
				    if (this.readyState == 4 && this.status == 200) {
				    	var otType = this.responseText;
				    	//alert(otType);
				    	if (otType == "Regular"){
				    		$("input[name='hour_time_in']").attr("disabled","disabled");
				    		$("input[name='min_time_in']").attr("disabled","disabled");
				    		$("select[name='period_time_in']").attr("disabled","disabled");
				    	}
				    	else {
				    		$("input[name='hour_time_in']").removeAttr("disabled");
				    		$("input[name='min_time_in']").removeAttr("disabled");
				    		$("select[name='period_time_in']").removeAttr("disabled");
				    	}
				     // document.getElementsByName("remarks").innerHTML = this.responseText;
				    }
				  };
				  xhttp.open("POST","ajax/append_type_overtime.php", true);
				  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				  xhttp.send(datastring);		

		      }


			$(document).ready(function(){
				//$('#attendance_list').DataTable();
				/*$('#attendance_list').dataTable( {
				     "order": [],
				    "columnDefs": [ {
				      "targets"  : 'no-sort',
				      "orderable": false,
				    }]
				});*/

				$("input[name='attendance_date_ot']").dcalendarpicker(); // dateFrom 
				$("input[name='attendance_date']").dcalendarpicker(); // dateFrom_Leave
				$("input[name='dateFrom_Leave']").dcalendarpicker(); // dateTo_Leave
				$("input[name='dateTo_Leave']").dcalendarpicker();
				$("input[name='formal_dateFrom_Leave']").dcalendarpicker(); // dateTo_Leave
				$("input[name='formal_dateTo_Leave']").dcalendarpicker(); //
				$("input[name='halfday_leave_date']").dcalendarpicker();
				$("input[name='dateFrom']").dcalendarpicker();
				$("input[name='dateTo']").dcalendarpicker();

				 
				  // for handling security in time in hours
			    $("input[name='hour_time_in']").on('input', function(){
			      if ($(this).val() >= 13){
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
			   /* $("input[name='sec_time_in']").on('input', function(){
			      	if ($(this).val() >= 60){
			      		 $(this).val($(this).val().slice(0,-1));
			      	}

			   });*/


			     // for handling security in time out hours
			    $("input[name='hour_time_out']").on('input', function(){
			      if ($(this).val() >= 13){
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
			    



				<?php

					// for updating emp info
					if (isset($_SESSION["success_update_request_attendance"])){
						echo '$(document).ready(function() {						
							$("#update_successModal").modal("show");
						});';
						$_SESSION["success_update_request_attendance"] = null;
					}


					// for updating emp info
					if (isset($_SESSION["success_file_ot"])){
						echo '$(document).ready(function() {						
							$("#fileOt_successModal").modal("show");
						});';
						$_SESSION["success_file_ot"] = null;
					}


					if (isset($_SESSION["success_crud_attendance_info"])){
						echo '$(document).ready(function() {
							$("#success_update_body").html("'.$_SESSION["success_crud_attendance_info"].'");
							$("#update_successModal").modal("show");
						});';
						$_SESSION["success_crud_attendance_info"] = null;
					}

					if (isset($_SESSION["success_crud_ot"])){
						echo '$(document).ready(function() {
							$("#success_update_file_ot").html("'.$_SESSION["success_crud_ot"].'");
							$("#addfileOt_successModal").modal("show");
						});';
						$_SESSION["success_crud_ot"] = null;
					}

				
					// for success in adding attendance 
					if (isset($_SESSION["add_success_attendance"])){
						echo '$(document).ready(function() {
							$("#add_modal_body").html("'.$_SESSION["add_success_attendance"].'");
							$("#addAttendanceSuccessModal").modal("show");
						});';
						$_SESSION["add_success_attendance"] = null;
					}


					// error in adding attendance
					if (isset($_SESSION["add_error_attendance"])){
						echo '$(document).ready(function() {
							$("div[id=error_modal_body"]").html("'.$_SESSION["add_error_attendance"].'");
							$("#errorModal").modal("show");
						});';
						$_SESSION["add_error_attendance"] = null;
					}


					// error in adding ot
					if (isset($_SESSION["add_error_overtime"])){
						echo '$(document).ready(function() {
							$("#error_modal_body_add_ot").html("'.$_SESSION["add_error_overtime"].'");
							$("#errorModal_addOT").modal("show");
						});';
						$_SESSION["add_error_overtime"] = null;
					}

					// attendance_ot_modal_body
					//update_errorModal
					

					// error in updating ot
					if (isset($_SESSION["update_error_overtime"])){
						echo '$(document).ready(function() {
							$("#attendance_ot_modal_body").html("'.$_SESSION["update_error_overtime"].'");
							$("#update_errorModal").modal("show");
						});';
						$_SESSION["update_error_overtime"] = null;
					}

					// success in adding
					if (isset($_SESSION["add_success_file_ot"])){
						echo '$(document).ready(function() {				
							$("#addfileOt_successModal").modal("show");
						});';
						$_SESSION["add_success_file_ot"] = null;
					}



					// for filing of vacation leave error
					if (isset($_SESSION["file_leave_error"])){
						echo '$(document).ready(function() {
							$("#error_modal_body").html("'.$_SESSION["file_leave_error"].'");
							$("#errorModal").modal("show");
						});';
						$_SESSION["file_leave_error"] = null;
					}

					// 
					// for filing of vacation leave success
					if (isset($_SESSION["file_leave_success"])){
						echo '$(document).ready(function() {
							$("#success_modal_body_file").html("'.$_SESSION["file_leave_success"].'");
							$("#file_successModal").modal("show");
						});';
						$_SESSION["file_leave_success"] = null;
					}


					if (isset($_SESSION["success_crud_leave"])){
						echo '$(document).ready(function() {
							$("#success_modal_body_file").html("'.$_SESSION["success_crud_leave"].'");
							$("#file_successModal").modal("show");
						});';
						$_SESSION["success_crud_leave"] = null;
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
						<li class="active" id="home_id">View Attendance</li> 
					</ol>
				</div>
			</div>

			<!-- for body -->
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-9">
						<div class="panel panel-primary" style="margin-top:10px;"> <!-- content-element -->
							<div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-list-alt"></span> Attendance List</center></div> 
						 	
						 	<div class="panel-body">
							
								<fieldset>
									<!-- <legend style="color:#357ca5;border-bottom:1px solid #BDBDBD"><span class="glyphicon glyphicon-list-alt"></span> My Attendance</legend> -->
								
									<div class="col-sm-12" style="background-color: #f2f4f4 ;padding:5px 5px 10px 5px;"> 
										
										<form>
											<span><b>Search by:</b></span>
											<div class="col-sm-12">

												<label class="radio-inline">
													<input type="radio" name="optionSearch" value="All">All
												</label>
												<label class="radio-inline">
													<input type="radio" name="optionSearch" value="Current Cut off">Current Cut off
												</label>
												<label class="radio-inline">
													<input type="radio" name="optionSearch" value="Specific Date">Specific Date
												</label>
											</div>


											<div class="col-sm-2" style="margin-top:7px;margin-right:-15px;">
												<span><b>If Specific date:</b></span>
											</div>
											<div class="col-sm-3">
												<input type="text" name="dateFrom" data-toggle="tooltip" data-placement="top" title="Format:mm/dd/yyyy ex. 01/01/1990" autocomplete="off" id="date_only" class="form-control" placeholder="Input Date From ..."/>
											</div>
											<div class="col-sm-1" style="margin-left:-20px;margin-right:-40px;margin-top:7px;">
												<span class="glyphicon glyphicon-arrow-right"></span>
											</div>
											<div class="col-sm-3">
												<input type="text" name="dateTo" class="form-control" data-toggle="tooltip" data-placement="top" title="Format:mm/dd/yyyy ex. 01/01/1990" autocomplete="off" id="date_only"placeholder="Input Date To ..."/>
											</div>

											<div class="col-sm-3">
												<input type="button" class="btn btn-primary btn-sm" value="Search" id="searchAttendance"/>
											</div>
										</form>

									</div>


									<div id="attendance_table">

										<!--
										<div class="col-sm-12" style="margin-top:50px;border:1px solid #BDBDBD;">
											<div style="margin-left:-15px;margin-right:-15px;background-color:  #1abc9c ;margin-bottom:10px;">
												<label class="control-label" style="color:#fff;">&nbsp;<b>Search Result - </b>All</label>
											</div>
											<table id="attendance_list" class="table table-bordered table-hover table-striped" style="border:1px solid #BDBDBD;">
												<thead>
													<tr>
														<th class="no-sort"><center><span class="glyphicon glyphicon-calendar" style="color:#186a3b"></span> Date</center></th>
														<th class="no-sort"><center><span class="glyphicon glyphicon-time" style="color:#186a3b"></span> Time In</center></th>
														<th class="no-sort"><center><span class="glyphicon glyphicon-time" style="color:#186a3b"></span> Time Out</center></th>
														<th  class="no-sort"><center><span class="glyphicon glyphicon-wrench" style="color:#186a3b"></span> Action</center></th>
													</tr>
												</thead>
												<tbody>	
												<?php
													// for showing own attendance only
													//$attendance_class = new Attendance;
													//$bio_id = $row->bio_id;
													//$attendance_class->attendanceTotTable($bio_id);

												?>
												</tbody>
											</table>
										</div>
										-->
									</div>
								</fieldset>
							</div> <!-- end of panel-body -->
						</div> <!-- end of panel - primary -->
					</div> <!-- end of col -->


					<?php
						if ($row->bio_id != 0){


					?>

					<div class="col-sm-3">
						<div class="panel panel-primary" style="margin-top:10px;"> <!-- content-element -->
							<div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-edit"></span> Attendance Menu</center></div> 
						 	
						 	<div class="panel-body">
					 			<div class="col-sm-12">
									<?php 
										
										//if ($_SESSION["role"] != 2) {

									?>
									<div class="col-sm-offset-2">
										<span class="glyphicon glyphicon-file"></span><a href="#addOvertimeModal" data-toggle="modal" class=""> File Overtime</a>
									</div>
									<!-- <span class="pull-right" style="margin-left:5px;margin-right:5px;">|</span> -->
									<?php
										//} 
									?>
									<div class="col-sm-offset-2">
										<span class="glyphicon glyphicon-plus-sign"></span><a href="#addAttendanceModal" data-toggle="modal" class=""> Add Attendance</a>
									</div>

									<div class="col-sm-offset-2">
										<span class="glyphicon glyphicon-file"></span><a href="#" id="file_leave"  class=""> File Leave</a>
									</div>

									<div class="col-sm-offset-2">
										<div style="cursor:pointer" id="view_leave_status_history"><span class="glyphicon glyphicon-search"></span> <span style="color:#317eac;">View Leave Status and History</span></div>
									</div>

								</div>

					 		</div>

				 		</div> <!-- end of panel-primary -->

				 		<div class="panel panel-primary" style="margin-top:10px;"> <!-- content-element -->
							<div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-file"></span> File OT and Attendance Status</center></div> 
						 	<?php
						 		$cut_off_class = new CutOff;
						 	?>
						 	<div class="panel-body">
					 			<div class="col-sm-12">
					 				<p><b>Cut Off:</b> <i><?php $cut_off_class->getCutOffPeriod(); ?></i></p>
					 				
									<div class="pull-right" style="cursor:pointer;color:#317eac;" title="Click to view OT and Add Attendance Status" id="view_file_ot_attendance"><span class="glyphicon glyphicon-search"></span> View</div>

								</div>

					 		</div>

				 		</div> <!-- end of panel-primary -->


				 		<div class="panel panel-primary" style="margin-top:10px;"> <!-- content-element -->
							<div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-file"></span> Cut off's Holiday</center></div> 
						 	<?php
						 		$holiday_class = new Holiday;
						 	?>
						 	<div class="panel-body">
					 			<div class="col-sm-12">
					 				<?php 
					 					if ($holiday_class->holidayCutOffCount() == 0){
					 						echo "<center><b><span class='glyphicon glyphicon-ban-circle' style='color:#CB4335'></span> There is no holiday</b></center>";
					 					}
					 					else {
							 				$holiday_class->getCutOffHoliday();
						 				} 
					 				?>
					 				
								</div>

					 		</div>

				 		</div> <!-- end of panel-primary -->


				 		
				 		<div class="panel panel-primary" style="margin-top:10px;"> 
							<div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-leaf"></span> Leave Counts</center></div> 
						 	<?php
						 		//$holiday_class = new Holiday;
						 	?>
						 	<div class="panel-body">
					 			<div class="col-sm-12">
				 					<table class="table table-bordered">
								    <thead>
								      <tr>
								        <th class="color-white bg-color-gray"><small>Leave Type</small></th>
								        <th class="color-white bg-color-gray"><small>Leave Count</small></th>
								      </tr>
								    </thead>
								    <tbody>
								      <?php
								      	$emp_info->getEmpLeaveCount($_SESSION["id"]);
								      ?>
								    </tbody>
								  </table>
					 				
								</div>

					 		</div>

				 		</div> <!--end of panel-primary -->
				 		

				 		
			 		</div>
			 		<?php
		 				} // end of if
			 		?>
				</div> <!-- end of row -->
			</div> <!-- end of container-fluid -->	

			
		</div> <!-- end of content -->

		<?php

			include "layout/footer.php";

		?>


		<!-- FOR ERROR MODAL IN  -->
		<div id="update_errorModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#b03a2e;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-warning-sign' style='color:#fff'></span>&nbsp;Update Attendance Info</h5>
					</div> 
					<div class="modal-body" id="attendance_ot_modal_body">
						<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There is an error during getting of data</center>
					</div> 
			 		<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->



		<!-- for update bio id modal -->
		<div id="updateAttendanceModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm" id="modal_dialog_update_info">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#158cba;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-pencil' style='color:#fff'></span>&nbsp;Update Attendance Info</h5>
					</div> 
					<div class="modal-body" id="modal_body_update_attendance" >
						
					</div> 
			 		
				</div>

			</div>
		</div> <!-- end of modal -->



		<!-- for update bio id modal -->
		<div id="fileOTModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm" id="modal_dialog_update_info">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#158cba;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-file' style='color:#fff'></span>&nbsp;File Overtime</h5>
					</div> 
					<div class="modal-body" id="modal_body_fileOT" >
						
					</div> 
			 		
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- FOR SUCCESS MODAL -->
		<div id="update_successModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#1d8348;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-check' style='color:#fff'></span>&nbsp;Request Update Attendance Info</h5>
					</div> 
					<div class="modal-body" id="success_update_body">
						<center><span class='glyphicon glyphicon-ok'  style='color:#196F3D'></span> Your Request is Successfully Submitted.</center>
					</div> 
					<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- FOR SUCCESS MODAL -->
		<div id="fileOt_successModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#1d8348;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-check' style='color:#fff'></span>&nbsp;File OT of Attendance Info</h5>
					</div> 
					<div class="modal-body">
						<center><span class='glyphicon glyphicon-ok'  style='color:#196F3D'></span> Your File of Overtime is Successfully Submitted.</center>
					</div> 
					<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- FOR SUCCESS MODAL -->
		<div id="addfileOt_successModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#1d8348;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-check' style='color:#fff'></span>&nbsp;File OT of Attendance Info</h5>
					</div> 
					<div class="modal-body" id="success_update_file_ot">
						<center><span class='glyphicon glyphicon-ok'  style='color:#196F3D'></span> Your File of Overtime is Successfully Submitted.</center>
					</div> 
					<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- for add attendance -->
		<div id="addAttendanceModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm" id="modal_dialog_update_info">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#158cba;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-plus' style='color:#fff'></span>&nbsp;Add Attendance</h5>
					</div> 
					<div class="modal-body">
						<form class="form-horizontal" action="php script/add_attendance.php" method="post" id="">

							<!--<font><b>Note:</b> Used Military Time</font> -->
					
							<div class="form-group">
								<div class="col-sm-12">				
									<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Date:&nbsp;<span class="red-asterisk">*</span></label>
									<input type="text" name="attendance_date" data-toggle="tooltip" data-placement="top" title="Format:mm/dd/yyyy ex. 01/01/1990" autocomplete="off" id="date_only" value="" class="form-control" placeholder="Input Date" required="required">
								</div>
							</div>

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
								<!--<div class="col-sm-1" style="margin-top:10px;">
									:
								</div> -->
								<div class="col-sm-4" style="">
									<select class="form-control" name="period_time_in" required="required">
										<option></option>
										<option value="AM">AM</option>
										<option value="PM">PM</option>
									</select>
									<!--<input type="text" id="number_only" name="sec_time_in" value="" class="form-control" placeholder="S" required="required"> -->

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
								<!--<div class="col-sm-1" style="margin-top:10px;">
									:
								</div> -->
								<div class="col-sm-4" style="">
									<select class="form-control" name="period_time_out" required="required">
										<option></option>
										<option value="AM">AM</option>
										<option value="PM">PM</option>
									</select>
									<!--<input type="text" id="number_only" name="sec_time_in" value="" class="form-control" placeholder="S" required="required"> -->

								</div>
							</div>



							<div class="form-group">
								<div class="col-sm-12">
									<label class="control-label"><span class="glyphicon glyphicon-map-marker" style="color:#2E86C1;"></span> Remarks:&nbsp;<span class="red-asterisk">*</span></label>
									<textarea class="form-control" name="remarks" placeholder="Input Remarks" required="required"></textarea>
								</div>
							</div>

							<div class="form-group">
								<div style="text-align:center;">
									<input type="submit" id="request_update_btn" value="Request Add" class="btn btn-success"/>
								</div>
							</div>

							<div class="form-group">
								<div class="col-sm-12">
									<!-- for message purpose -->
									<strong id="update_message">&nbsp;</strong>
								</div>
							</div>
					
					</form>
					</div> 
			 		
				</div>

			</div>
		</div> <!-- end of modal -->


		<div id="updateFileAttendanceInfoModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm" id="modal_dialog_update_info">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#158cba;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-edit' style='color:#fff'></span>&nbsp;Update File Attendance</h5>
					</div> 
					<div class="modal-body" id="update_file_attendance_info_body">

					</div>
				</div>
			</div>
		</div>


		<div id="updateFileOTModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm" id="modal_dialog_update_info">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#158cba;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-edit' style='color:#fff'></span>&nbsp;Update File Overtime</h5>
					</div> 
					<div class="modal-body" id="update_file_ot_info_body">

					</div>
				</div>
			</div>
		</div>


		<div id="updateFileLeaveModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm" id="modal_dialog_update_info">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#158cba;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-edit' style='color:#fff'></span>&nbsp;Update File Leave</h5>
					</div> 
					<div class="modal-body" id="update_file_leave_info_body">

					</div>
				</div>
			</div>
		</div>


		<!-- FOR SUCCESS MODAL -->
		<div id="addAttendanceSuccessModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#1d8348;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-check' style='color:#fff'></span>&nbsp;Add Attendance Info</h5>
					</div> 
					<div class="modal-body" id="add_modal_body">
						
					</div> 
					<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
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
						
					</div> 
			 		<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- for file ot -->
		<div id="addOvertimeModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm" id="modal_dialog_update_info">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#158cba;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-plus' style='color:#fff'></span>&nbsp;File Overtime</h5>
					</div> 
					<div class="modal-body">
						<form class="form-horizontal" action="php script/add_specific_ot.php" method="post" id="">

							<!--<font><b>Note:</b> Used Military Time</font> -->
					
							<div class="form-group">
								<div class="col-sm-12">				
									<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Date:&nbsp;<span class="red-asterisk">*</span></label>
									<input type="text" onchange="attendance_ot_date(this)" name="attendance_date_ot" data-toggle="tooltip" data-placement="top" title="Format:mm/dd/yyyy ex. 01/01/1990" autocomplete="off" id="input_payroll"  value="" class="form-control" placeholder="Input Date" required="required">
								</div>
							</div>

							<div class="form-group">
								<div class="col-sm-12">
									<label class="control-label"><span class="glyphicon glyphicon-time" style="color:#2E86C1;"></span> From:&nbsp;<span class="red-asterisk">*</span></label>
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
								<!--<div class="col-sm-1" style="margin-top:10px;">
									:
								</div> -->
								<div class="col-sm-4" style="">
									<select class="form-control" name="period_time_in" required="required">
										<option></option>
										<option value="AM">AM</option>
										<option value="PM">PM</option>
									</select>
									<!--<input type="text" id="number_only" name="sec_time_in" value="" class="form-control" placeholder="S" required="required"> -->

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
								<!--<div class="col-sm-1" style="margin-top:10px;">
									:
								</div> -->
								<div class="col-sm-4" style="">
									<select class="form-control" name="period_time_out" required="required">
										<option></option>
										<option value="AM">AM</option>
										<option value="PM">PM</option>
									</select>
									<!--<input type="text" id="number_only" name="sec_time_in" value="" class="form-control" placeholder="S" required="required"> -->

								</div>
							</div>


							<!--
							<div class="form-group">
								<div class="col-sm-12">				
									<label class="control-label"><span class="glyphicon glyphicon-time" style="color:#2E86C1;"></span> Choose Type of Overtime &nbsp;<span class="red-asterisk">*</span></label>
									<select name="type_ot" class="form-control">
										
									</select>		
								</div>
							</div>
							-->

							<div class="form-group">
								<div class="col-sm-12">
									<label class="control-label"><span class="glyphicon glyphicon-map-marker" style="color:#2E86C1;"></span> Remarks:&nbsp;<span class="red-asterisk">*</span></label>
									<textarea class="form-control" name="remarks" placeholder="Input Remarks" required="required"></textarea>
								</div>
							</div>

							<div class="form-group">
								<div style="text-align:center;">
									<input type="submit" id="" value="Request Add" class="btn btn-success"/>
								</div>
							</div>

					
						</form>
					</div> 
			 		
				</div>

			</div>
		</div> <!-- end of modal -->



		<!-- FOR ERROR MODAL -->
		<div id="errorModal_addOT" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#b03a2e;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-alert' style='color:#fff'></span>&nbsp;Add Overtime Notification</h5>
					</div> 
					<div class="modal-body" id="error_modal_body_add_ot">
						
					</div> 
			 		<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->



		<!-- FOR ERROR MODAL IN  -->
		<div id="searchAttendanceModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#b03a2e;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-warning-sign' style='color:#fff'></span>&nbsp;Error Info</h5>
					</div> 
					<div class="modal-body" id="searchAttendance_body">
						<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Please choose from <b>Search Option</b></center>
					</div> 
			 		<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->




		<!-- for file vacation leave -->
		<div id="fileLeaveModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm" id="modal_dialog_update_info">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#158cba;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-file' style='color:#fff'></span>&nbsp;File Leave</h5>
					</div> 
					<div class="modal-body">
						<form class="form-horizontal" action="" method="post" id="fileLeave_Form">

							<div class="form-group">
								<div class="col-sm-12">				
									<label class="control-label"><span class="glyphicon glyphicon-file" style="color:#2E86C1;"></span> Type of Leave:&nbsp; <span class="red-asterisk">*</span></label>
									<select class="form-control" name="leaveType" required="required">
										<option value="">Please select</option>
										<?php

											$leave_class = new Leave;

											$leave_class->getLeaveTypeToDropdown(0,"Add");
										?>	
									</select>
								</div>
							</div>


					
							<div class="form-group">
								<div class="col-sm-12">				
									<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Date From:&nbsp;<span class="red-asterisk">*</span></label>
									<input type="text" name="dateFrom_Leave" data-toggle="tooltip" data-placement="top" title="Format:mm/dd/yyyy ex. 01/01/1990" autocomplete="off" id="date_only"  value="" class="form-control" placeholder="Input Date" required="required">
								</div>
							</div>

							<div class="form-group">
								<div class="col-sm-12">				
									<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Date To:&nbsp;<span class="red-asterisk">*</span></label>
									<input type="text" name="dateTo_Leave" data-toggle="tooltip" data-placement="top" title="Format:mm/dd/yyyy ex. 01/01/1990" autocomplete="off" id="date_only" value="" class="form-control" placeholder="Input Date" required="required">
								</div>
							</div>


							<div class="form-group">
								<div class="col-sm-12">
									<label class="control-label"><span class="glyphicon glyphicon-map-marker" style="color:#2E86C1;"></span> Remarks:&nbsp;<span class="red-asterisk">*</span></label>
									<textarea class="form-control" name="remarks_Leave" placeholder="Input Remarks" required="required"></textarea>
								</div>
							</div>

							<div class="form-group">
								<div style="text-align:center;">
									<input type="submit" id="file_vacationLeave" value="File Leave" class="btn btn-success"/>
								</div>
							</div>

					
						</form>
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



		<!-- for view file ot and add attendance -->
		<div id="viewFileOTAddAttendanceModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog" id="modal_dialog_update_info">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#158cba;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-file' style='color:#fff'></span>&nbsp;File OT and Attendance Status</h5>
					</div> 
					<div class="modal-body">
						<table class="table table-condensed table-bordered">
							<thead>
								<tr>
									<th colspan="5" style="color:#fff;background-color: #117864 ">File Overtime</th>					
								</tr>
								<tr>
									<th><span class="glyphicon glyphicon-calendar" style="color:#186a3b"></span>&nbsp;Date of OT</th>
									<th><span class="glyphicon glyphicon-time" style="color:#186a3b"></span>&nbsp;OT Rendered</th>
									<th><span class="glyphicon glyphicon-time" style="color:#186a3b"></span>&nbsp;Type of OT</th>
									<th><span class="glyphicon glyphicon-stats" style="color:#186a3b"></span>&nbsp;Status</th>
									<th><span class="glyphicon glyphicon-wrench" style="color:#186a3b"></span>&nbsp;Action</th>
								</tr>
							</thead>
							<tbody>
									<?php 
										$attendance_ot_class = new Attendance_Overtime;
										$attendance_ot_class->getOTStatusCurrentCutOff($id);
									?>	
							</tbody>
						</table>

						<table class="table table-condensed table-bordered">
							<thead>
								<tr>
									<th colspan="4" style="color:#fff;background-color: #117864 ">Attendance</th>					
								</tr>
								<tr>
									<th><span class="glyphicon glyphicon-calendar" style="color:#186a3b"></span>&nbsp;Date of Attendance</th>
									<th><span class="glyphicon glyphicon-time" style="color:#186a3b"></span>&nbsp;Time Rendered</th>
									<th><span class="glyphicon glyphicon-stats" style="color:#186a3b"></span>&nbsp;Status</th>
									<th><span class="glyphicon glyphicon-wrench" style="color:#186a3b"></span>&nbsp;Action</th>
								</tr>
							</thead>
							<tbody>
									<?php
										$attendance_notif_class = new AttendanceNotif;
										$attendance_notif_class->getOTStatusCurrentCutOff($id);
									?>
							</tbody>
						</table>
					</div> 
			 		
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- for view file ot and add attendance -->
		<div id="viewFileLeaveHistoryModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-lg" id="modal_dialog_update_info">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#158cba;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-file' style='color:#fff'></span>&nbsp;File Leave Status</h5>
					</div> 
					<div class="modal-body">
						<b>
							<div class="col-sm-12" style="border-radius:10px;background-color: #e5e8e8;margin-bottom:10px;padding:10px;text-align:center;">
								<small>
									<!--<span style='color:#186a3b;'>Icon Legends: </span> -->
									<span class='glyphicon glyphicon-pencil' style='color:#b7950b;margin-left:5px;'></span> - Edit File Leave Info
									<!--<span class='glyphicon glyphicon-remove' style='color:#c0392b;margin-left:5px;'></span> - Cancel File Leave Info -->
								</small>
							</div>
						</b>
						<table class="table table-condensed table-bordered">
							<thead>
								<tr>
									<th colspan="6" style="color:#fff;background-color: #117864 ">File Leave History</th>					
								</tr>
								<tr>
									<th width="15%"><small><span class="glyphicon glyphicon-file" style="color:#186a3b"></span>&nbsp;Leave Type</small></th>
									<th width="20%"><small><span class="glyphicon glyphicon-calendar" style="color:#186a3b"></span>&nbsp;Date Range</small></th>
									<th width="30%"><small><span class="glyphicon glyphicon-edit" style="color:#186a3b"></span>&nbsp;Remarks</small></th>
									<th width="15%"><small><span class="glyphicon glyphicon-file" style="color:#186a3b"></span>&nbsp;File Leave Type</small></th>
									<th width="10%"><small><span class="glyphicon glyphicon-stats" style="color:#186a3b"></span>&nbsp;Status</small></th>
									<th width="10%"><small><span class="glyphicon glyphicon-wrench" style="color:#186a3b"></span>&nbsp;Action</small></th>
								</tr>
							</thead>
							<tbody>
									<?php 
										
										$leave_class->getFileLeaveHistoryStatus($id);
									?>	
							</tbody>
						</table>

						
					</div> 
			 		
				</div>

			</div>
		</div> <!-- end of modal -->



		<!-- for file leave type -->
		<div id="fileLeaveOption" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm" id="modal_dialog_update_info">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#158cba;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-file' style='color:#fff'></span>&nbsp;File Leave Option</h5>
					</div> 
					<div class="modal-body">
						<ul style="list-style-type:none;">
							<li><span class="glyphicon glyphicon-ruble" style="float:left;color:#239b56"></span><div style="color:#158cba;cursor:pointer;" id="leave_with_pay">&nbsp;File Leave</div></li>
							

						</ul>
					</div> 
			 		
				</div>

			</div>
		</div> <!-- end of modal -->




		


		



		<!-- FOR DELETE CONFIRMATION MODAL -->
		<div id="cancelAttendanceUpdatesModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#21618c;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span>&nbsp;Cancel Attendance Request Updates</h5>
					</div> 
					<div class="modal-body" id="cancel_modal_body">
						
					</div> 
					<div class="modal-footer" style="padding:5px;text-align:center;" id="delete_modal_footer">
						<a href="#" class="btn btn-default" id="cancel_yes_attendance_updates">YES</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->


		<div id="cancelFileOTModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#21618c;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span>&nbsp;Cancel File OT</h5>
					</div> 
					<div class="modal-body" id="cancel_fileot_modal_body">
						
					</div> 
					<div class="modal-footer" style="padding:5px;text-align:center;" id="delete_modal_footer">
						<a href="#" class="btn btn-default" id="cancel_yes_file_ot">YES</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
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