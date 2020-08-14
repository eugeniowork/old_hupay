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

include "class/Pagibig_Contribution.php";

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
$_SESSION["active_sub_pagibig"] = "active-sub-menu";
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
	if ($_SESSION["role"] == 4 || $_SESSION["role"] == 2) {
		header("Location:MainForm.php");
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
		<title>Pag-ibig Contribution Table</title>
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
		<script src="js/numbers_only.js"></script>
		<script src="js/maxlength.js"></script>
		<script src="js/chevron.js"></script>
		<script src="js/notifications.js"></script>
		<script src="js/custom.js"></script>
		<script src="js/jquery-ui.js"></script> 
		<script src="js/modal.js"></script>
		<script>
			$(document).ready(function(){
				$('#sss_contribution_list').dataTable( {
				     "order": [],
				    "columnDefs": [ {
				      "targets"  : 'no-sort',
				      "orderable": false,
				    }]
				});

				<?php
					// error in adding
					if (isset($_SESSION["error_msg_pagibig_contrib"])){
						echo '$(document).ready(function() {
							$("#add_error_modal_body").html("'.$_SESSION["error_msg_pagibig_contrib"].'");
							$("#add_errorModal").modal("show");
						});';
						$_SESSION["error_msg_pagibig_contrib"] = null;
					}


					// success in adding
					if (isset($_SESSION["success_msg_pagibigcontrib"])){
						echo '$(document).ready(function() {
							$("#success_modal_body_add").html("'.$_SESSION["success_msg_pagibigcontrib"].'");
							$("#add_successModal").modal("show");
						});';
						$_SESSION["success_msg_pagibigcontrib"] = null;
					}


					// success in deleting
					if (isset($_SESSION["success_msg_del_pagibig_contrib"])){
						echo '$(document).ready(function() {
							$("#success_modal_body_del").html("'.$_SESSION["success_msg_del_pagibig_contrib"].'");
							$("#successDelete").modal("show");
						});';
						$_SESSION["success_msg_del_pagibig_contrib"] = null;
					}


					// for error in updating 
					if (isset($_SESSION["error_msg_update_pagibig_contrib"])){
							echo '$(document).ready(function() {
								$("#error_msg_update").html("'.$_SESSION["error_msg_update_pagibig_contrib"].'");
								$("#update_errorModal").modal("show");
							});';
							$_SESSION["error_msg_update_pagibig_contrib"] = null;
					}


					// for success in updating 
					if (isset($_SESSION["success_msg_update_pagibig_contrib"])){
							echo '$(document).ready(function() {
								$("#success_modal_body_update").html("'.$_SESSION["success_msg_update_pagibig_contrib"].'");
								$("#update_successModal").modal("show");
							});';
							$_SESSION["success_msg_update_pagibig_contrib"] = null;
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
						<li class="active" id="home_id">Pag-ibig Contribution Table</li> 
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
									<legend style="color:#357ca5;border-bottom:1px solid #BDBDBD"><span class="glyphicon glyphicon-list-alt"></span> Pag-ibig Contribution Table 
										<?php 
											if ($_SESSION["role"] == 1){
										?>
											<small class="pull-right">
												<a href="#add_contribution_modal" data-toggle="modal" class="custom-add-items"><span class="glyphicon glyphicon-plus"></span>Add New</a>
											</small>
										<?php 
											}
										?>
									</legend>
									<div class="col-sm-8 col-sm-offset-2">
										
										<table id="sss_contribution_list" class="table table-bordered table-hover table-striped" style="border:1px solid #BDBDBD;">
											<thead>
												<tr>
													<th class="no-sort"><span class="glyphicon glyphicon-arrow-right" style="color:#186a3b"></span> Range of Compensation</th>
													<th class="no-sort"><span class="glyphicon glyphicon-bitcoin" style="color:#186a3b"></span> Contribution</th>
													<th class="no-sort"><span class="glyphicon glyphicon-wrench" style="color:#186a3b"></span> Action</th>
												</tr>
											</thead>
											<tbody>	
												<?php
													$pagibigContribution_class = new Pagibig_Contribution;
													$pagibigContribution_class->getContributionToTable();

												?>
											</tbody>
										</table>
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

		<div id="change_profile_pic_modal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm" style="width:400px">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#158cba;">
						<button type="button" class="close" id="close_change_profile" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-camera' style='color:#fff'></span>&nbsp;Change Profile Picture</h5>
					</div> 
					<div class="modal-body" id="">
						<center>
							
							<div id="img_append">

							</div>
							
							<form id="change_profile_form">
								<input type="file" name="profile_pic_file" accept="image/*">

							</form>
						</center>
					</div> 
			 		<div class="modal-footer" style="padding:5px;">
			 			<div class="pull-left" id="change_profile_msg">

		 				</div>
						<button type="button" class="btn btn-primary" id="submit_profile_pic">Change</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->



		<!-- FOR ADD NEW MODAL -->
		<div id="add_contribution_modal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#158cba;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-plus' style='color:#fff'></span>&nbsp;Add Contribution</h5>
					</div> 
					<div class="modal-body">
						<div class="container-fluid">
							<form class="form-horizontal" action="php script/add_pagibigContribution.php" method="post">		
								<div class="col-sm-12">

									<?php
										if ($pagibigContribution_class->existOver() == 0){
									?>
									<div class="form-check">
									    <label class="form-check-label" style="margin-left:-15px;">
									      <input type="checkbox" name="checkOver" class="form-check-input" id="check_compen_to"> Check if <b>Compensantion Range To</b> will be "over"
									    </label>
									</div>
									<?php
										}
									?>

									<div class="form-group">
										<label class="control-label"><span class="glyphicon glyphicon-bitcoin" style="color:#2E86C1;"></span> Compensantion From</label>
										<input type="text" id="float_only" name="compensationFrom" autocomplete="off" placeholder="Enter Compensation From" class="form-control" required="required">									 													
									</div>	


									<div class="form-group">
										<label class="control-label"><span class="glyphicon glyphicon-bitcoin" style="color:#2E86C1;"></span> Compensation To</label>
										<input type="text" id="float_only" name="compensationTo" autocomplete="off" placeholder="Enter Compensation To" class="form-control" required="required">									 													
									</div>	

									<div class="form-group">
										<label class="control-label"><span class="glyphicon glyphicon-bitcoin" style="color:#2E86C1;"></span> Contribution</label>
										<input type="text" id="float_only" name="Contribution" autocomplete="off" placeholder="Enter Contribution" class="form-control" required="required">									 													
									</div>


									<div class="form-group">
										<div style="text-align:center;">
											<input type="submit" class="btn btn-success" value="Add Contribution">
										</div>									 													
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


		<!-- FOR ERROR MODAL IN  -->
		<div id="add_errorModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#b03a2e;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-plus' style='color:#fff'></span>&nbsp;Add Pag-ibig Contribution</h5>
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
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-plus' style='color:#fff'></span>&nbsp;Add Pag-ibig Contribution</h5>
					</div> 
					<div class="modal-body" id="success_modal_body_add">
						
					</div> 
					<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->
	

		<!-- FOR DELETE MODAL IN  -->
		<!-- for update bio id modal -->
		<div id="delete_modal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm" id="modal_dialog_update_info">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#158cba;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-trash' style='color:#fff'></span>&nbsp;Delete Pag-ibig Contribution Info</h5>
					</div> 
					<div class="modal-body" id="modal_body_delete" >
						
					</div> 
			 		<div class="modal-footer" style="padding:5px;text-align:center;" id="delete_modal_footer">
						<a href="#" class="btn btn-default" id="yes_delete_pagibig_contribution">YES</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- FOR SUCCESS MODAL -->
		<div id="successDelete" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#1d8348;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-trash' style='color:#fff'></span>&nbsp;Delete Pag-ibig Notification</h5>
					</div> 
					<div class="modal-body" id="success_modal_body_del">
						
					</div> 
					<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
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
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-warning-sign' style='color:#fff'></span>&nbsp;Update Pag-ibig Contribution Info</h5>
					</div> 
					<div class="modal-body" id="error_msg_update">
						<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There is an error during getting of data</center>
					</div> 
			 		<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- for update bio id modal -->
		<div id="updatePagibigContribModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm" id="modal_dialog_update_info">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#158cba;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-pencil' style='color:#fff'></span>&nbsp;Update Pag-ibig Contribution Info</h5>
					</div> 
					<div class="modal-body" id="modal_body_update" >

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
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-pencil' style='color:#fff'></span>&nbsp;Update Pag-ibig Contribution</h5>
					</div> 
					<div class="modal-body" id="success_modal_body_update">
						
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