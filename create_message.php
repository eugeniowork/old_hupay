<?php
session_start();
if (!isset($_SESSION["id"])){
	header("Location:index.php");
}
include "class/connect.php"; // fixed class
include "class/position_class.php"; // fixed clas
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

include "class/Payroll.php";

// for universal variables
$id = $_SESSION["id"];

// for session
$_SESSION["active_dashboard"] = null;
$_SESSION["active_sub_registration"] = null;
$_SESSION["active_sub_employee_list"] = null;
$_SESSION["active_sub_user_authentication"] = null;
$_SESSION["active_sub_messaging_create"] = "active-sub-menu";
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
	include "class/emp_information.php";
	$emp_info = new EmployeeInformation;
	// for security browsing purpose
	//if ($_SESSION["role"] == 1) {
	//	header("Location:Mainform.php");
	//}
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
		<title>Create Message</title>
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
				/*width: 396px;*/
				
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
		
		<script src="js/plug ins/typehead/typeahead.bundle.min.js"></script> 
		<script src="js/bootstrap.min.js"></script>
		
		<script src="js/plug ins/calendar/dcalendar.picker.js"></script>
		<script src="js/plug ins/data_tables/jquery.dataTables.js"></script>
		<script src="js/plug ins/data_tables/dataTables.bootstrap.js"></script>
		<script src="js/chevron.js"></script>
		<script src="js/notifications.js"></script>
		<script src="js/custom.js"></script>
		<script src="js/jquery-ui.js"></script> 
		<script src="js/modal.js"></script>
		<script type="text/javascript" language="javascript">
			$(document).ready(function() {
				$('#payslip_list').DataTable();
			

			// this is for message information
			var emp_name_list = <?php if ($_SESSION["role"] == 3 || $_SESSION["role"] == 4) { echo $emp_info->searchAdminAndPayrollAdmin();} else { echo $emp_info->searchEmployeeOnly($id); } ?>

		    // Constructing the suggestion engine
		    var emp_name_list = new Bloodhound({
		        datumTokenizer: Bloodhound.tokenizers.whitespace,
		        queryTokenizer: Bloodhound.tokenizers.whitespace,
		        local: emp_name_list
		    });
		    
		    // Initializing the typeahead
		    $('.typeahead').typeahead({
		        hint: true,
		        highlight: true, /* Enable substring highlighting */
		        minLength: 1 /* Specify minimum characters required for showing result */
		    },
		    {
		        name: 'emp_name',
		        source: emp_name_list
		    });
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
						<li class="active" id="home_id">Create Message</li> 
					</ol>
				</div>
			</div>

			<!-- for body -->
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-12 content-div">
						<div class="thumbnail" style="border:1px solid #BDBDBD;">
							<div class="caption">
								<form class="form-horizontal" method="post" action="" id="form_message">
									<fieldset>
										<legend style="border-bottom:1px solid #BDBDBD;"><span class="glyphicon glyphicon-edit" style="color:#2E86C1;"></span> CREATE MESSAGE</legend>
										<div class="form-group">
											<label class="col-md-4 control-label"><span class="glyphicon glyphicon-user" style="color:#2E86C1;"></span> To:</label>
											<div class="col-md-4">
												<input type="text" name="message_to" value="<?php if (isset($_SESSION["message_message_to"])) { echo $_SESSION["message_message_to"]; $_SESSION["message_message_to"] = null; } ?>" class="form-control typeahead tt-query" required="required"/>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label"><span class="glyphicon glyphicon-credit-card" style="color:#2E86C1;"></span> Subject:</label>
											<div class="col-md-4">
												<input type="text" name="message_subject" value="<?php if (isset($_SESSION["message_message_subject"])) { echo $_SESSION["message_message_subject"]; $_SESSION["message_message_subject"] = null; } ?>" class="form-control" required="required"/>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label"><span class="glyphicon glyphicon-envelope" style="color:#2E86C1;"></span> Message:</label>
											<div class="col-md-5">
												<textarea class="form-control" rows="10" required="required" name="message"><?php if (isset($_SESSION["message_message"])) { echo $_SESSION["message_message"]; $_SESSION["message_message"] = null; } ?></textarea>
											</div>
										</div>

										<div class="form-group">
											<div class="col-md-offset-4 col-md-4">
												<label><?php if (isset($_SESSION["error_send_message"])) { echo $_SESSION["error_send_message"]; $_SESSION["error_send_message"] = null; } ?><?php if (isset($_SESSION["success_send_message"])) { echo $_SESSION["success_send_message"]; $_SESSION["success_send_message"] = null; } ?></label>
											</div>
											<div class="col-md-1">
												<button type="submit" class="btn btn-primary pull-right" id="send_message">Send</button>
											</div>
										</div>
									</fieldset>
								</form>
							</div>
						</div> <!-- end of thumbnail -->
					</div>
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
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-warning-sign' style='color:#fff'></span>&nbsp;Print Payslip Info</h5>
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

		<div id="submit_div">

		</div>
		



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