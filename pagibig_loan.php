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

include "class/pagibig_loan_class.php"; // for pag-ibig loan class


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
$_SESSION["active_sub_pagibig_loan"] = "active-sub-menu";
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
		<title>Pag-ibig Loan</title>
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
				$('#emp_list_with_pag_ibig_loan').dataTable( {
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
				$('#my_pag_ibig_loan_list').dataTable( {
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
					if (isset($_SESSION["add_pagibigloan_success"])){
						echo '$(document).ready(function() {
							$("#success_modal_body_add").html("'.$_SESSION["add_pagibigloan_success"].'");
							$("#add_successModal").modal("show");
						});';
						$_SESSION["add_pagibigloan_success"] = null;
					}


					// for adding error
					if (isset($_SESSION["add_pagibigloan_error"])){
						echo '$(document).ready(function() {
							$("#error_modal_body").html("'.$_SESSION["add_pagibigloan_error"].'");
							$("#errorModal").modal("show");
						});';
						$_SESSION["add_pagibigloan_error"] = null;
					}


					// for updating error
					if (isset($_SESSION["update_pagibigloan_error"])){
						echo '$(document).ready(function() {
							$("#error_modal_body").html("'.$_SESSION["update_pagibigloan_error"].'");
							$("#errorModal").modal("show");
						});';
						$_SESSION["update_pagibigloan_error"] = null;
					}


					// for success updating
					if (isset($_SESSION["update_pagibigloan_success"])){
						echo '$(document).ready(function() {
							$("#success_modal_body_add").html("'.$_SESSION["update_pagibigloan_success"].'");
							$("#add_successModal").modal("show");
						});';
						$_SESSION["update_pagibigloan_success"] = null;
					}


					// for success in deleting 
					if (isset($_SESSION["success_delete_pagibig_loan"])){
						echo '$(document).ready(function() {
							$("#success_modal_body_add").html("'.$_SESSION["success_delete_pagibig_loan"].'");
							$("#add_successModal").modal("show");
						});';
						$_SESSION["success_delete_pagibig_loan"] = null;
					}


					// for adjustment error 
						if (isset($_SESSION["adjustment_pagibigLoan_error"])){
							echo '$(document).ready(function() {
								$("#error_modal_body").html("'.$_SESSION["adjustment_pagibigLoan_error"].'");
								$("#errorModal").modal("show");
							});';
						$_SESSION["adjustment_pagibigLoan_error"] = null;
					}

					// for success in adjustment 
						if (isset($_SESSION["adjustment_pagibigLoan_success"])){
							echo '$(document).ready(function() {
								$("#success_modal_body_add").html("'.$_SESSION["adjustment_pagibigLoan_success"].'");
								$("#add_successModal").modal("show");
							});';
							$_SESSION["adjustment_pagibigLoan_success"] = null;
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
						<li class="active" id="home_id">Pag-ibig Loan</li> 
					</ol>
				</div>
			</div>


			<?php
	 			$pagibig_loan_class  = new PagibigLoan;

	 		?>


			<!-- for body -->
			<div class="container-fluid">
				<div class="row">
					<?php
						if ($_SESSION["role"] == 1 || $_SESSION["role"] == 3){
					?>
					<div class="col-md-12">						
						<div class="panel panel-primary" style="margin-top:10px;"> <!-- content-element -->

							<div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-list-alt"></span> List of Employee With Existing Pag-ibig Loan</center></div> 


							 <div class="panel-body">
							 	<div class="col-sm-12">
							 		<table id="emp_list_with_pag_ibig_loan" class="table table-bordered table-hover table-striped" style="border:1px solid #BDBDBD;">
										<thead>
											<tr> 	
												<th><span class="glyphicon glyphicon-user" style="color:#186a3b"></span> Employee Name</th>
												<th><span class="glyphicon glyphicon-calendar" style="color:#186a3b"></span> Range of Payment</th>
												<th><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Amount Loan</th>
												<th><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Deduction</th>
												<th><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Outstanding Balance</th>
												<th><span class="glyphicon glyphicon-wrench" style="color:#186a3b"></span> Action</th>
											</tr>
										</thead>
										<tbody>	
										<?php
											//$emp_info->getEmpInfoToTable();
											$pagibig_loan_class->getPagibigLoanInfoToTable();

										?>
										</tbody>
									</table>
							 	</div>
							</div>
						</div>
					</div>

					<?php 
						} // end of if $_SESSION['role'] == 1 or 3 // for admin and payroll admin only
					?>
								


					<div class="col-md-12">						
						<div class="panel panel-primary" style="<?php if($_SESSION['role'] == 2 ||$_SESSION['role'] == 4) { echo 'margin-top:10px';} ?>"> <!-- content-element -->

							<div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-list-alt"></span> Pag-ibig Loan List History</center></div> 


							 <div class="panel-body">
							 	<div class="col-sm-12">
							 		<table id="my_pag_ibig_loan_list" class="table table-bordered table-hover table-striped" style="border:1px solid #BDBDBD;">
										<thead>
											<tr> 	
												
												<th class="no-sort"><span class="glyphicon glyphicon-calendar" style="color:#186a3b"></span> Range of Payment</th>
												<th><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Amount Loan</th>
												<th><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Deduction</th>
												<th><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Outstanding Balance</th>
												<th><span class="glyphicon glyphicon-stats" style="color:#186a3b"></span> Status</th>
											</tr>
										</thead>
										<tbody>	
										<?php
											//$emp_info->getEmpInfoToTable();
											$pagibig_loan_class->getPagibigLoanInfoHistoryToTable($id);

										?>
										</tbody>
									</table>
							 	</div>
							</div>
						</div>
					</div>

					<?php
						if ($_SESSION["role"] == 1 || $_SESSION["role"] == 3){
					?>

					<div class="col-md-8 col-md-offset-2">
						<div class="panel panel-primary" style="margin-top:-10px;"> <!-- content-element -->

							<div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-plus"></span> <a data-toggle="collapse" href="#collapse1">Add Employee with Pag-ibig Loan</a></center></div>
						

							<div id="collapse1" class="panel-collapse collapse">
								<div class="panel-body">
								 	<form class="form-horizontal" action="" id="form_addPagibigLoan" method="post">
								 		<div class="form-group">
						 					<label class="control-label col-sm-3 col-sm-offset-1"><b>Employee Name:</b></label>
						 					<div class="col-sm-6 txt-pagibig-loan" style="margin-right:-15px;">
						 						<input type="text" class="form-control" name="empName" placeholder="Employee Name ..." id="input_payroll" required="required"/>
					 						</div>
					 						<label class="col-sm-1 control-label"><a href="#" id="choose_employee_pagibig_loan">Choose</a></label>
							 			</div>
							 			<div class="form-group">
						 					<label class="control-label col-sm-3 col-sm-offset-1"><b>Date From:</b></label>
						 					<div class="col-sm-6 txt-pagibig-loan">
						 						<input type="text" class="form-control" placeholder="Date From ..." name="dateFrom" id="date_only" required="required" data-toggle="tooltip" data-placement="right" title="Format:mm/dd/yyyy ex. 01/01/1990" autocomplete="off"/>
					 						</div>
							 			</div>
							 			<div class="form-group">
						 					<label class="control-label col-sm-3 col-sm-offset-1"><b>Date To:</b></label>
						 					<div class="col-sm-6 txt-pagibig-loan">
						 						<input type="text" class="form-control" placeholder="Date To ..." name="dateTo" id="date_only" required="required" data-toggle="tooltip" data-placement="right" title="Format:mm/dd/yyyy ex. 01/01/1990" autocomplete="off"/>
					 						</div>
							 			</div>
							 			<div class="form-group">
						 					<label class="control-label col-sm-3 col-sm-offset-1"><b>Amount Loan:</b></label>
						 					<div class="col-sm-6 txt-pagibig-loan">
						 						<input type="text" class="form-control" placeholder="Amount Loan ..." id="float_only" name="amountLoan" required="required"/>
					 						</div>
							 			</div>

							 			<div class="form-group">
						 					<label class="control-label col-sm-3 col-sm-offset-1"><b>Deduction:</b></label>
						 					<div class="col-sm-6 txt-pagibig-loan">
						 						<input type="text" class="form-control" placeholder="Deduction ..." id="float_only" name="decution" required="required"/>
					 						</div>
							 			</div>


							 			<div class="form-group">
						 					<label class="control-label col-sm-3 col-sm-offset-1"><b>Outstanding Balance:</b></label>
						 					<div class="col-sm-6 txt-pagibig-loan">
						 						<input type="text" class="form-control" placeholder="Remaining Balance ..." id="float_only" name="remainingBalance" required="required"/>
					 						</div>
							 			</div>

							 			<div class="form-group">
							 				<div class="col-sm-offset-6">
							 					<input type="submit" value="Submit" id="submitPagibigLoan" class="btn btn-primary btn-sm"/>
						 					</div>
						 				</div>

						 				<div class="form-group">
						 					<div id="error_msg">
					 						</div>
					 					</div>
							 		</form>
							 	</div>
							</div> <!-- end of pane-collapese -->
						</div>
					</div>

					<?php 
						} // end of if $_SESSION['role'] == 1 or 3 // for admin and payroll admin only
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
					<div class="modal-body" id="update_pagibigForm_body">
						
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
						<a href="#" class="btn btn-default" id="delete_yes_pagibig">YES</a>
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
					<div class="modal-body" id="adjust_pagibigForm_body">
						
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