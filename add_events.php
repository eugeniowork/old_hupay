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

include "class/events.php";

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
$_SESSION["active_events"] = "background-color:#1d8348;";
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
	if ($_SESSION["role"] != 1 && $_SESSION["role"] != 2) {
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
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Add Events</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> <!-- for symbols -->
		<?php
			$row = $emp_info->getEmpInfoByRow($id);
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
		<script src="js/notifications.js"></script>
		<!--<script src="js/readmore.js"></script> -->
		<script src="js/date_validation.js"></script>
		<script src="js/custom.js"></script>
		<script src="js/jquery-ui.js"></script>
		<script src="js/modal.js"></script>
		<script>
			$(document).ready(function(){
				$("input[name='date']").dcalendarpicker();

				// date
				$("input[name='date']").on("click", function () {
					$("td[id='today']").trigger("click");

				});

				
				<?php
					// error in updating
					if (isset($_SESSION["event_upload_error"])){
						echo '$(document).ready(function() {
							$("#error_modal_body").html("'.$_SESSION["event_upload_error"].'");
							$("#errorModal").modal("show");
						});';
						$_SESSION["event_upload_error"] = null;
					}

					// upload_img_error
					if (isset($_SESSION["upload_img_error"])){
						echo '$(document).ready(function() {
							$("#error_modal_body").html("'.$_SESSION["upload_img_error"].'");
							$("#errorModal").modal("show");
						});';
						$_SESSION["upload_img_error"] = null;
					}


					// success in adding
						if (isset($_SESSION["event_success_msg"])){
							echo '$(document).ready(function() {
								$("#success_modal_body_add").html("'.$_SESSION["event_success_msg"].'");
								$("#add_successModal").modal("show");
							});';
							$_SESSION["event_success_msg"] = null;
						}
					?>
				   // minDate: 
				
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
						<li class="" id="home_id"><a href="event_list.php">Events</a></li> 
						<li class="active" id="home_id">Add Events</li> 
					</ol>
				</div>
			</div>

			<!-- for body -->
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-12 content-div">
						<div class="thumbnail" style="border:1px solid #BDBDBD;">
							<div class="caption">
								<form class="form-horizontal" action="php script/add_events_script.php" method="post" enctype="multipart/form-data">
									<fieldset>
										<legend style="border-bottom:1px solid #BDBDBD;">ADDING EVENTS</legend>
										<div class="col-sm-6 col-sm-offset-3">
											<div class="form-group">
												<label class="label-control col-sm-3"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Event:</label>
												<div class="col-sm-9">
													<textarea class="form-control event-txt" name="event" placeholder="Enter event .." rows="5" required="required"><?php if (isset($_SESSION["event"])){ echo $_SESSION["event"]; $_SESSION["event"] = null;} ?></textarea>
												</div>
											</div>

											<div class="form-group">
												<label class="label-control col-sm-3"><span class="glyphicon glyphicon-credit-card" style="color:#2E86C1;"></span> Event Title:</label>
												<div class="col-sm-9">
													<input type="text" id="department_txt" class="form-control event-txt" value="<?php if (isset($_SESSION["event_title"])){ echo $_SESSION["event_title"]; $_SESSION["event_title"] = null;} ?>" name="title" placeholder="Enter title .." required="required"/>
												</div>
											</div>

											<div class="form-group">
												<label class="label-control col-sm-3"><span class="glyphicon glyphicon-time" style="color:#2E86C1;"></span> Event Date:</label>
												<div class="col-sm-9" id="wew">
													<input type="text" class="form-control event-txt" data-toggle="tooltip" data-placement="top" title="Format:mm/dd/yyyy ex. 01/01/1990" autocomplete="off" id="date_only" value="<?php if (isset($_SESSION["event_date"])){ echo $_SESSION["event_date"]; $_SESSION["event_date"] = null;} ?>" name="date" placeholder="Enter date .."/>
												</div>
											</div>

											<div class="form-group">
												<label class="label-control col-sm-3"><span class="glyphicon glyphicon-picture" style="color:#2E86C1;"></span> Images</label>
												<div class="col-sm-9">
													<input type="file" class="event-txt" name="image[]" accept="image/*" multiple/>
												</div>
											</div>


											<div class="form-group">
												<div class="col-sm-12">
													<input type="submit" value="Add Events" class="btn btn-primary btn-sm pull-right add-event-btn"/>
												</div>
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


		<!-- FOR ERROR MODAL -->
		<div id="errorModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#b03a2e;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-alert' style='color:#fff'></span>&nbsp;Add Event Notification</h5>
					</div> 
					<div class="modal-body" id="error_modal_body">
						
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
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-plus' style='color:#fff'></span>&nbsp;Add Event Notification</h5>
					</div> 
					<div class="modal-body" id="success_modal_body_add">
						
					</div> 
					<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
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