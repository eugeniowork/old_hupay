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

//include "class/cut_off.php";
include "class/Payroll.php";


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
$_SESSION["active_salary_information"] = null;
$_SESSION["active_audit_trail"] = null;
$_SESSION["active_sub_loan_adjustment"] = null;
$_SESSION["active_sub_simkimban_adjustment"] = null;
$_SESSION["active_sub_payroll"] = "active-sub-menu";
$_SESSION["active_sub_payroll_adjust"] = null;


// this area is for null of session
$_SESSION["view_emp_id"] = null; // sa view emp info
$_SESSION["update_emp_id"] = null; // sa update emp info
?>

<?php
	// for security browsing purpose
	if ($_SESSION["role"] == 4 && $_SESSION["id"] != 47 && $_SESSION["id"] != 44) {
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
		<title>Payroll Reports</title>
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
		<script src="js/custom.js"></script>
		<script src="js/jquery-ui.js"></script> 
		<script src="js/modal.js"></script>
		<script>
			$(document).ready(function(){
					// payroll_approval_list
				$('#payroll_reports').dataTable( {
				     "order": [],
				    "columnDefs": [ {
				      "targets"  : 'no-sort',
				      "orderable": false,
				    }]
				});


				// for print payroll reports print_payroll_reports
		      $("a[id='print_payroll_reports']").on("click", function () {
		         var datastring = "approve_payroll_id=" + $(this).closest("tr").attr("id");
		        
		           $.ajax({
		              type: "POST",
		              url: "ajax/script_check_exist_approve_payroll_id.php",
		              data: datastring,
		              cache: false,
		              success: function (data) {
		                // if has error 
		               if (data == "Error") {
		                  $("#errorModal").modal("show");
		               }
		               // if success
		               else {
		                 $("#select_type_reportsModal").modal("show");
		                  //window.open("print_payroll_reports.php");
		             
		               }

		             }
		           });
		         
		         
		        
		      });


				$("a[id='pre_approve_payroll']").on("click", function () {

			      // alert("HELLO WORLD!");

			       var datastring = "approve_payroll_id="+$(this).closest("tr").attr("id") +"&approve=" + $(this).attr("title");
			       $("#payrollApprove_body").html("<center><div class='loader'></div>Loading Information</center>");
			      // alert(datastring);
			        $.ajax({
			            type: "POST",
			            url: "ajax/script_pre_approve_payroll.php",
			            data: datastring,
			            cache: false,
			            success: function (data) {
			              // if has error 
			             if (data == "Error") {
			                $("#errorModal").modal("show");
			             }
			             // if success
			             else {
			                $("#payrollApprove_body").html(data);
			                $("#payrollApproveModal").modal("show");
			             }

			           }
			         });
			       
			      
			    });


			    $("a[id='approve_payroll']").on("click", function () {

			        // alert("HELLO WORLD!");

			         var datastring = "approve_payroll_id="+$(this).closest("tr").attr("id") +"&approve=" + $(this).attr("title");
			         $("#payrollApprove_body").html("<center><div class='loader'></div>Loading Information</center>");
			        // alert(datastring);
			          $.ajax({
			              type: "POST",
			              url: "ajax/script_approve_payroll.php",
			              data: datastring,
			              cache: false,
			              success: function (data) {
			                // if has error 
			               if (data == "Error") {
			                  $("#errorModal").modal("show");
			               }
			               // if success
			               else {
			                  $("#payrollApprove_body").html(data);
			                  $("#payrollApproveModal").modal("show");
			               }

			             }
			           });
			         
			        
			      });

				<?php
					if (isset($_SESSION["approve_payroll_success"])){
						echo '$(document).ready(function() {
							$("#approveSuccessModal").modal("show");
						});';
						$_SESSION["approve_payroll_success"] = null;
					}
				?>


				<?php
					if (isset($_SESSION["preapprove_payroll_success"])){
						echo '$(document).ready(function() {
							$("#preApproveSuccessModal").modal("show");
						});';
						$_SESSION["preapprove_payroll_success"] = null;
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
						<li class="active" id="home_id">Payroll Reports</li> 
					</ol>
				</div>
			</div>


			<?php
	 			//$memo_class = new Memorandum;

	 		?>


			<!-- for body -->
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-12 content-div">
						<div class="thumbnail" style="border:1px solid #BDBDBD;">
							<div class="caption">
								<fieldset>

									<legend style="color:#357ca5;border-bottom:1px solid #BDBDBD"><span class="glyphicon glyphicon-list-alt"></span> Payroll Reports List</legend>
									<!-- <span><small><b><span class="glyphicon glyphicon-info-sign" style="color:#2E86C1;"></span> Note: If the position already used for creating an employee it can not be edit and delete.</b></small></span><br/><br/> -->
									
									<div class="col-sm-8 col-sm-offset-2">

										<table id="payroll_reports" class="table table-bordered table-hover table-striped" style="border:1px solid #BDBDBD;">
											<thead>
												<tr>
													<th class="no-sort"><span class="glyphicon glyphicon-calendar" style="color:#186a3b"></span> Cuf Off Period</th>
													<th class="no-sort"><span class="glyphicon glyphicon-stats" style="color:#186a3b"></span> Status</th>
													<th class="no-sort"><span class="glyphicon glyphicon-wrench" style="color:#186a3b"></span> Action</th>
												</tr>
											</thead>
											<tbody>	
											<?php
												
												$payroll_class = new Payroll;
												$payroll_class->payrollReportToTable();
												


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
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-alert' style='color:#fff'></span>&nbsp;Error Notification</h5>
					</div> 
					<div class="modal-body" id="error_modal_body">
						<span class="glyphicon glyphicon-remove" style="color:#a93226;"></span> There's an error during gettig of data
					</div> 
			 		<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->

		<!-- FOR SUCCESS MODAL -->
		<div id="send_successModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#1d8348;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff">&nbsp;Success Notification</h5>
					</div> 
					<div class="modal-body" id="">
						<span class="glyphicon glyphicon-ok" style="color:#196F3D"></span> Payroll Reports is Successfully Sent
					</div> 
					<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->


		<div id="select_type_reportsModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#158cba;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-print' style='color:#fff'></span>&nbsp;Print Option</h5>
					</div> 
					<div class="modal-body" id="">
						<div class="container-fluid">
							<form action="print_payroll_reports.php" method="post">
								<div class="form-group">
									<select class="form-control" name="reports_type">
										<option value="Excel">Excel</option>
										<option value="PDF">PDF</option>
									</select>
								</div>
								<div class="form-group">
									<input type="submit" value="Choose" class="btn btn-primary btn-sm pull-right"/>
								</div>
							</form>
						</div>
					</div> 
				</div>

			</div>
		</div> <!-- end of modal -->
		

		
		<!-- for approval id modal -->
		<div id="payrollApproveModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm" id="modal_dialog_update_info">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#158cba;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-user' style='color:#fff'></span>&nbsp;Approve Payroll</h5>
					</div> 
					<div class="modal-body" id="payrollApprove_body">
						
					</div> 
			 		
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- FOR SUCCESS MODAL -->
		<div id="preApproveSuccessModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#1d8348;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-check' style='color:#fff'></span>&nbsp;Approve Payroll</h5>
					</div> 
					<div class="modal-body">
						<center><span class='glyphicon glyphicon-ok' style='color:#196F3D'></span> Payroll Information is Successfully Pre approved.</center>
					</div> 
					<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- FOR SUCCESS MODAL -->
		<div id="approveSuccessModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#1d8348;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-check' style='color:#fff'></span>&nbsp;Approve Payroll</h5>
					</div> 
					<div class="modal-body">
						<center><span class='glyphicon glyphicon-ok' style='color:#196F3D'></span> Payroll Information is Successfully Approved.</center>
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