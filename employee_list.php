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
include "class/cashbond_class.php"; // fixed class
include "class/salary_loan_class.php"; // fixed class
include "class/leave_class.php"; // fixed class
include "class/company_class.php"; // fixed class
include "class/memorandum_class.php"; // fixed class
include "class/attendance_overtime.php"; // fixed class
include "class/attendance_notif.php"; // fixed class

include "class/events.php"; // nakainclude na pla dito ung data na class

// for universal variables
$id = $_SESSION["id"];


// for session
$_SESSION["active_dashboard"] = null;
$_SESSION["active_sub_registration"] = null;
$_SESSION["active_sub_employee_list"] = "active-sub-menu";
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
	if ($_SESSION["role"] == 4) {
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
		<title>Employee List</title>
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
			.bs-example {
				font-family: sans-serif;
				position: relative;
				margin: 100px;
			}
			.typeahead, .tt-query, .tt-hint {
				border: 2px solid #CCCCCC;
				border-radius: 8px;
				/*font-size: 22px; /* Set input font size */
				/*height: 30px;*/
				line-height: 30px;
				outline: medium none;
				padding: 8px 12px;
				width: 120%;

				
			}
			.typeahead {
				background-color: #FFFFFF;
			}
			.typeahead:focus {
				border: 2px solid #0097CF;
			}
			.tt-query {
				box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
			}
			.tt-hint {
				color: #999999;
			}
			.tt-menu {
				background-color: #FFFFFF;
				border: 1px solid rgba(0, 0, 0, 0.2);
				border-radius: 8px;
				box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
				margin-top: 12px;
				padding: 8px 0;
				width: 300px;
			}
			.tt-suggestion {
				font-size: 15px;  /* Set suggestion dropdown font size */
				padding: 3px 12px;
			}
			.tt-suggestion:hover {
				cursor: pointer;
				background-color: #0097CF;
				color: #FFFFFF;
			}
			.tt-suggestion p {
				margin: 0;
			}
		</style>

		<!-- js -->
		
		<script src="js/jquery-1.12.2.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/plug ins/typehead/typeahead.bundle.min.js"></script> 
		<script src="js/plug ins/calendar/dcalendar.picker.js"></script>
		<script src="js/plug ins/data_tables/jquery.dataTables.js"></script>
		<script src="js/plug ins/data_tables/dataTables.bootstrap.js"></script>
		<script src="js/chevron.js"></script>
		<script src="js/string_uppercase.js"></script>
		<script src="js/numbers_only.js"></script>
		<script src="js/maxlength.js"></script>
		<script src="js/format.js"></script>
		<!--<script src="js/httpRequest.js"></script> -->
		<script src="js/readmore.js"></script>
		<script src="js/notifications.js"></script>
		<script src="js/custom.js"></script>
		<script src="js/jquery-ui.js"></script> 
		<!-- <script src="js/modal.js"></script> -->
		<script>
			$(document).ready(function(){
				$('#emp_list').dataTable( {
				     "order": [],
				    "columnDefs": [ {
				      "targets"  : 'no-sort',
				      "orderable": false,
				    }]
				});

				// error in adding
				<?php

					// for updating emp info
					if (isset($_SESSION["update_emp_info_error_msg"])){
						echo '$(document).ready(function() {
							$("#update_error_modal_body").html("'.$_SESSION["update_emp_info_error_msg"].'");
							$("#update_errorModal").modal("show");
						});';
						$_SESSION["update_emp_info_error_msg"] = null;
					}

					if (isset($_SESSION["success_msg_update_basic_info"])){
						echo '$(document).ready(function() {
							$("#success_modal_body_update").html("'.$_SESSION["success_msg_update_basic_info"].'");
							$("#update_successModal").modal("show");
						});';
						$_SESSION["success_msg_update_basic_info"] = null;
					}


					// if success updated atm status success_update_atm_status
					if (isset($_SESSION["success_update_atm_status"])){
						echo '$(document).ready(function() {
							$("#success_modal_body_update").html("'.$_SESSION["success_update_atm_status"].'");
							$("#update_successModal").modal("show");
						});';
						$_SESSION["success_update_atm_status"] = null;
					}


					// for success in uploading 201 files 
					// for adding success
					if (isset($_SESSION["success_msg_upload_201_files"])){
						echo '$(document).ready(function() {
							$("#success_modal_body_add").html("'.$_SESSION["success_msg_upload_201_files"].'");
							$("#add_successModal").modal("show");
						});';
						$_SESSION["success_msg_upload_201_files"] = null;
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
						<li class="active" id="home_id">Employee List</li> 
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
									<legend style="color:#357ca5;border-bottom:1px solid #BDBDBD"><span class="glyphicon glyphicon-list-alt"></span> Employee List </legend>
									<!-- <span><small><b><span class="glyphicon glyphicon-info-sign" style="color:#2E86C1;"></span> Note: If the position already used for creating an employee it can not be edit and delete.</b></small></span><br/><br/> -->
									
									<div class="col-md-12" style="margin-bottom:10px;">
										<div id="print_employee_list_reports" style="cursor:pointer;color:#2980b9" class="pull-right"><span class="glyphicon glyphicon-print" style="color:#2c3e50"></span>&nbsp;Print Employee List Reports</div>
									</div>


									<?php
										// for hr and admin only
										//if ($_SESSION["role"] == 1 ||$_SESSION["role"] == 2 ){
									?>

									<!--<b>
										<small>
											<a data-toggle="collapse" href="#icon_legends">View Action Icon Legends</a><br/>
											<div class="collapse" id="icon_legends">
												<span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span> - Edit Employee Information <br/>
												<span class='glyphicon glyphicon-stats' style='color:#357ca5'></span> - Make Employee Active or Inactive <br/>
												<span class='glyphicon glyphicon-eye-open' style='color:#186a3b'></span> - View Employee Information <br/>
												<span class='glyphicon glyphicon-upload' style='color:#dc7633'></span> - Upload 201 File <br/>
												<span class='glyphicon glyphicon-print' style='color:#2c3e50'></span> - Print Employee Information <br/>
												<span class='glyphicon glyphicon-bookmark' style='color:#cb4335'></span> - View LFC Employee Information <br/>
											</div>
										</small>
									</b> -->

									<b>
										<div class="col-sm-12" style="border-radius:10px;background-color: #e5e8e8;margin-bottom:10px;padding:10px;text-align:center;">
											<small>
												<!--<span style='color:#186a3b;'>Icon Legends: </span> -->
												<span class='glyphicon glyphicon-pencil' style='color:#b7950b;margin-left:5px;'></span> - Edit Employee Info
												<span class='glyphicon glyphicon-stats' style='color:#357ca5;margin-left:5px;'></span> - Make Employee Active or Inactive 
												<span class='glyphicon glyphicon-eye-open' style='color:#186a3b;margin-left:5px;'></span> - View Employee Info 
												<span class='glyphicon glyphicon-upload' style='color:#dc7633;margin-left:5px;'></span> - Upload 201 File
												<span class='glyphicon glyphicon-print' style='color:#2c3e50;margin-left:5px;'></span> - Print Employee Info 
												<span class='glyphicon glyphicon-briefcase' style='color:#cb4335;margin-left:5px;'></span> - View LFC Employee Info
												<span class='glyphicon glyphicon-credit-card' style='color:#186a3b;margin-left:5px;'></span> - ATM Record
												<span class='glyphicon glyphicon-ruble' style='color:#357ca5;margin-left:5px;'></span> - Add Increase Info
											</small>
										</div>
									</b>
									<?php
									// } // end of if
									?>





									<table id="emp_list" class="table table-bordered table-hover table-striped" style="border:1px solid #BDBDBD;">
										<thead>
											<tr> 	
												<th class="no-sort"></th>
												<th class="no-sort"></th>
												<th class="no-sort"><span class="glyphicon glyphicon-record" style="color:#186a3b" title="Unfill up Fields"></span></th>
												<th class="no-sort"><span class="glyphicon glyphicon-credit-card" style="color:#186a3b" title="ATM Records"></span></th>
												<th><span class="glyphicon glyphicon-user" style="color:#186a3b"></span> Employee Name</th>
												<th><span class="glyphicon glyphicon-home" style="color:#186a3b"></span> Address</th>
												<th><span class="glyphicon glyphicon-tasks" style="color:#186a3b"></span> Position</th>
												<th><span class="glyphicon glyphicon-stats" style="color:#186a3b"></span> Status</th>
												<th class="no-sort"><span class="glyphicon glyphicon-wrench" style="color:#186a3b"></span> Action</th>
											</tr>
										</thead>
										<tbody>	
										<?php
											$emp_info->getEmpInfoToTable();

										?>
										</tbody>
									</table>

									
								</fieldset>
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


		<!-- FOR ERROR MODAL -->
		<div id="errorModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#b03a2e;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-alert' style='color:#fff'></span>&nbsp;View Employee Profile Notif</h5>
					</div> 
					<div class="modal-body" id="">
						<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There's an error during getting of data, Please refresh the page</center>
					</div> 
			 		<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- for form purposes -->
		<div id="submit_form">


		</div> <!-- end of div -->


		<!-- for upload 201 File -->
		<div id="201FileModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm" style="width:400px">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#158cba;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-upload' style='color:#fff'></span>&nbsp;Upload 201 File</h5>
					</div> 
					<div class="modal-body" id="">

						<form method="post" class="form-horizontal" id="form_201_files" enctype="multipart/form-data" action="php script/script_upload_201_files.php">
							<div class="form-group">
								<div class="col-md-12">
									<i><span class="glyphicon glyphicon-info-sign" style="color:blue"></span>&nbsp;Only image file will accept</i>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<input type="text" class="form-control" placeholder="Enter File Name" name="files_201_name"/>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<input type="file" name="201_files_pic_file[]" accept="image/*" multiple>
								</div>
							</div>
						</form>
					</div> 
			 		<div class="modal-footer" style="padding:5px;">
			 			<div class="pull-left" id="upload_201_files_profile_msg">

		 				</div>
						<button type="button" class="btn btn-primary" id="upload_201_files_btn">Upload</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->


		<div id="updadeEmpInfo" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm" id="modal_dialog_update_info">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#158cba;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-pencil' style='color:#fff'></span>&nbsp;Update Information</h5>
					</div> 
					<div class="modal-body" id="modal_body_update_info" >
						
					</div> 
			 		
				</div>

			</div>
		</div> <!-- end of modal -->

		
		
		<div id="generateCodeModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm" style="width:400px">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#158cba;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>Generate Code</h5>
					</div> 
					<div class="modal-body" id="generate_code_modal_body">

						
					</div> 
			 		
				</div>

			</div>
		</div> <!-- end of modal -->


		<div id="change_profile_pic_modal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm" style="width:400px">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#158cba;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-camera' style='color:#fff'></span>&nbsp;Change Profile Picture</h5>
					</div> 
					<div class="modal-body" id="">
						<center>
						
								<img src="" class="" id="profile_img"/>
						
							<input type="file" name="profile_pic_file" accept="image/*">
						</center>
					</div> 
			 		<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-primary" data-dismiss="modal">Change</button>
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
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-warning-sign' style='color:#fff'></span>&nbsp;Update Employee Information</h5>
					</div> 
					<div class="modal-body" id="update_error_modal_body">
						
					</div> 
			 		<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
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
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-check' style='color:#fff'></span>&nbsp;Update Employee Information</h5>
					</div> 
					<div class="modal-body" id="success_modal_body_update">
						
					</div> 
					<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- for INACTIVE & ACTIVE USER -->
		<!-- FOR DELETE CONFIRMATION MODAL -->
		<div id="active_inactive_emp_modal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#21618c;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-stats' style='color:#fff'></span>&nbsp;Active Status Notification</h5>
					</div> 
					<div class="modal-body" id="active_stat_modal_body">
						
					</div> 
					<div class="modal-footer" style="padding:5px;text-align:center;" id="active_inactive_stat_modal_footer">
						<a href="#" class="btn btn-default" id="active_stat_yes_emp_info">YES</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- for form -->
		<!--<form id="print_emp_info_form" action="" method="post">
			<input type="hidden" value="" name="emp_id"/>

		</form> -->
		<!-- FOR DELETE CONFIRMATION MODAL -->
		<div id="view_emp_lfc_history_modal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#21618c;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-briefcase' style='color:#fff'></span>&nbsp;LFC Employment History</h5>
					</div> 
					<div class="modal-body" id="lfc_history_modal_body">
						
					</div> 

				</div>
			</div>
		</div> <!-- end of modal -->




		<!-- for atm modal -->
		<form class="form-horizontal" method="post" action="" id="form_updateAtmRecord">
			<div id="updateATM_modal" class="modal fade" role="dialog" tabindex="-1">
				<div class="modal-dialog modal-sm">
				<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header" style="background-color:#21618c;">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-credit-card' style='color:#fff'></span>&nbsp;Update ATM Record</h5>
						</div> 
						<div class="modal-body" id="atm_record_modal_body">
							
		
						</div> 
						<div class="modal-footer" style="padding:5px;text-align:center;margin-top:-15px;" id="">
							<input class="btn btn-default" id="update_atm_stat_emp_yes" type="submit" value="YES" />
							<button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
						</div>

					</div>
				</div>
			</div> <!-- end of modal -->
		</form>


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



		<div id="addIncreaseInfoModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm" id="modal_dialog_update_info">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#158cba;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-ruble' style='color:#fff'></span>&nbsp;Increase Salary Information</h5>
					</div> 
					<div class="modal-body" id="modal_body_increase_salary" >
						
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