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
include "class/salary_loan_class.php"; // fixed class
include "class/leave_class.php"; // fixed class
include "class/company_class.php"; // fixed class
include "class/memorandum_class.php"; // fixed class
include "class/attendance_overtime.php"; // fixed class
include "class/attendance_notif.php"; // fixed class

include "class/cashbond_class.php";
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
$_SESSION["active_sub_salary_loan"] = null;
$_SESSION["active_sub_create_salary"] = null;
$_SESSION["active_sub_view_payroll_info"] = null;
$_SESSION["active_sub_my_payslip"] = null;
$_SESSION["active_my_payslip"] = null;
$_SESSION["active_simkimban"] = null;
$_SESSION["active_cashbond"] = null;
$_SESSION["active_year_total_deduction"] = null;
$_SESSION["active_salary_information"] = "background-color:#1d8348";
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
	//if ($_SESSION["role"] == 2 || $_SESSION["role"] == 4) {
	//	header("Location:Mainform.php");
	//}
	include "class/emp_information.php";
	$emp_info = new EmployeeInformation;
	$position_class = new Position;
	$date_class = new date;
	$cashbond_class  = new Cashbond;
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
		<title>Salary Information</title>
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
		<!--<script src="js/modal.js"></script> -->
		<script>
			$(document).ready(function(){
				$('#cashbond_list').dataTable( {
				     "order": [],
				    "columnDefs": [ {
				      "targets"  : 'no-sort',
				      "orderable": false,
				    }]
				});

				//$('.modal-dialog').draggable();


				// cashbond_withdraw_history
				$('#cashbond_withdraw_history').dataTable( {
				     "order": [],
				    "columnDefs": [ {
				      "targets"  : 'no-sort',
				      "orderable": false,
				    }]
				});

				// file_cashnond_withdrawal_list
				$('#file_cashnond_withdrawal_list').dataTable( {
				     "order": [],
				    "columnDefs": [ {
				      "targets"  : 'no-sort',
				      "orderable": false,
				    }]
				});

				<?php
					if (isset($_SESSION["update_error_msg_cashbond"])){
						echo '$(document).ready(function() {
							$("#error_modal_body").html("'.$_SESSION["update_error_msg_cashbond"].'");
							$("#errorModal").modal("show");
						});';
						$_SESSION["update_error_msg_cashbond"] = null;
					}

					// success in updating
					if (isset($_SESSION["update_success_msg_cashbond"])){
						echo '$(document).ready(function() {
							$("#success_modal_body").html("'.$_SESSION["update_success_msg_cashbond"].'");
							$("#successModal").modal("show");
						});';
						$_SESSION["update_success_msg_cashbond"] = null;
					}


					// for success in approving or disapproving file cashbond withdrawal
					if (isset($_SESSION["success_approve_file_cashbond_withdrawal"])){
						echo '$(document).ready(function() {
							$("#success_modal_body").html("'.$_SESSION["success_approve_file_cashbond_withdrawal"].'");
							$("#successModal").modal("show");
						});';
						$_SESSION["success_approve_file_cashbond_withdrawal"] = null;
					}


					// for success in approving or disapproving file cashbond withdrawal
					if (isset($_SESSION["success_crud_cashbond"])){
						echo '$(document).ready(function() {
							$("#success_modal_body").html("'.$_SESSION["success_crud_cashbond"].'");
							$("#successModal").modal("show");
						});';
						$_SESSION["success_crud_cashbond"] = null;
					}


					// success in filing cashbond withdrawal
					if (isset($_SESSION["success_file_cashbond_withdrawal"])){
						echo '$(document).ready(function() {
							$("#success_modal_body").html("'.$_SESSION["success_file_cashbond_withdrawal"].'");
							$("#successModal").modal("show");
						});';
						$_SESSION["success_file_cashbond_withdrawal"] = null;
					}

					// error in filing cashbond withdrawal 
					if (isset($_SESSION["error_file_cashbond_withdrawal"])){
						echo '$(document).ready(function() {
							$("#error_modal_body").html("'.$_SESSION["error_file_cashbond_withdrawal"].'");
							$("#errorModal").modal("show");
						});';
						$_SESSION["error_file_cashbond_withdrawal"] = null;
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
						<li class="active" id="home_id">Salary</li> 
					</ol>
				</div>
			</div>


			<?php
	 			
	 			$money_class = new Money;
	 		?>


			<!-- for body -->
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">

						<?php
							//if ($_SESSION["role"] == 1 || $_SESSION["role"] == 3){

						?>
						<div class="panel panel-primary" style="margin-top:10px;"> <!-- content-element -->

							<div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-list-alt"></span> List of Active Employee's Salary Information</center></div> 


							 <div class="panel-body">
							 
							 	<div class="col-sm-10 col-sm-offset-1">
							 		
							 		<table id="cashbond_list" class="table table-bordered table-hover table-striped" style="border:1px solid #BDBDBD;">
										<thead>
											<tr> 	
												<th><span class="glyphicon glyphicon-user" style="color:#186a3b"></span> Employee Name</th>
												<th><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Salary</th>
												<th class="no-sort"><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Allowance</th>
												<th><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Total</th>
											</tr>
										</thead>
										<tbody>	
										<?php
											$emp_info->getEmployeeSalaryInformationToTable();
											//$cashbond_class->getCashbondInfoToTable();

										?>
										</tbody>
									</table>
							 	</div>


							</div>
						</div>

						<?php
							//}
						?>


						

						


						<!--
						<div class="panel panel-primary" style="margin-top:10px;"> 

							<div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-file"></span> File </center></div> 


							 <div class="panel-body">
							 	<div class="col-sm-8 col-sm-offset-2">
							 		
							 	</div>


							</div>
						</div>
						-->


					</div>

			<!--<div class="hr-payroll-system-footer">
				<strong>All Right Reserves 2017 | V1.0</strong>
			</div> -->
			
		</div> <!-- end of content -->


		<?php

			include "layout/footer.php";

		?>


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