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

include "class/minimum_wage_class.php"; 

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
$_SESSION["active_minimum_wage"] = "background-color:#1d8348";
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
	if ($_SESSION["role"] != 1 && $_SESSION["role"] != 3) {
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
		<title>Minimum Wage</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> <!-- for symbols -->
		<?php
			$company_class = new Company;
			$row_company = $company_class->getInfoByCompanyId($row->company_id);
			$logo_source = $row_company->logo_source;
		?>
		<link rel="shortcut icon" href="<?php echo $logo_source; ?>"> <!-- for logo -->
		<!-- css --> 
		<link rel="stylesheet" href="css/pe-icon-7-stroke.css">
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
		<script src="js/notifications.js"></script>
		<script src="js/date_validation.js"></script>
		<script src="js/custom.js"></script>
		<script src="js/jquery-ui.js"></script> 
		<script src="js/modal.js"></script>
		<script>
			$(document).ready(function(){
				$("input[name='effectiveDate']").dcalendarpicker();
				$('#minimumWage_history_list').dataTable( {
				     "order": [],
				    "columnDefs": [ {
				      "targets"  : 'no-sort',
				      "orderable": false,
				    }]
				});

				<?php

				// error in updating
				if (isset($_SESSION["update_error_min_wage"])){
					echo '$(document).ready(function() {
						$("#error_modal_body").html("'.$_SESSION["update_error_min_wage"].'");
						$("#errorModal").modal("show");
					});';
					$_SESSION["update_error_min_wage"] = null;
				}



					// error in adding
				if (isset($_SESSION["add_error_min_wage"])){
					echo '$(document).ready(function() {
						$("#add_error_modal_body").html("'.$_SESSION["add_error_min_wage"].'");
						$("#add_errorModal").modal("show");
					});';
					$_SESSION["add_error_min_wage"] = null;
				}


				// success in adding
				if (isset($_SESSION["add_success_min_wage"])){
					echo '$(document).ready(function() {
						$("#success_modal_body_add").html("'.$_SESSION["add_success_min_wage"].'");
						$("#add_successModal").modal("show");
					});';
					$_SESSION["add_success_min_wage"] = null;
				}

				// success in adding
				if (isset($_SESSION["update_success_min_wage"])){
					echo '$(document).ready(function() {
						$("#success_modal_body").html("'.$_SESSION["update_success_min_wage"].'");
						$("#successModal").modal("show");
					});';
					$_SESSION["update_success_min_wage"] = null;
				}

				// delete_success_min_wage
				// success in deleting
				if (isset($_SESSION["delete_success_min_wage"])){
					echo '$(document).ready(function() {
						$("#success_modal_body_del").html("'.$_SESSION["delete_success_min_wage"].'");
						$("#successDelete").modal("show");
					});';
					$_SESSION["delete_success_min_wage"] = null;
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
						<li class="active" id="home_id">Minimum Wage</li> 
					</ol>
				</div>
			</div>

			<!-- for body -->
			<div class="container-fluid">
				<div class="row">

					<?php
						if ($_SESSION["role"] == 1){
					?>
					<!-- for updating minimum wage -->
					<div class="col-sm-5 content-div">
						<div class="panel panel-primary">
					      <div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-list-alt"></span> Minimum Wage Form</center></div> 
					      <div class="panel-body">
					 
					      	<h4 style="color:#317eac">Stay updated with the latest minimum wage issued by the government</h4>

						       <form class="form-horizontal" action="php script/add_min_wage.php" id="min_wage_form" method="post">
									
						       		<div class="form-group">
										<label class="control-label col-sm-4 label-min-wage">Effective Date: </label>
										<div class="col-sm-8">
											<input type="text" name="effectiveDate" data-toggle="tooltip" data-placement="top" title="Format:mm/dd/yyyy ex. 01/01/1990" autocomplete="off" id="date_only" class="form-control" placeholder="Input Effective Date" required="required">
										</div>
									</div>

									<div class="form-group">
										<label class="control-label col-sm-4 label-min-wage">Basic Wage: </label>
										<div class="col-sm-8">
											<input id="float_only" type="text" name="basicWage" class="form-control" placeholder="Input Minimum Wage" required="required">
										</div>
									</div>

									<div class="form-group">
										<label class="control-label col-sm-4 label-min-wage">COLA: </label>
										<div class="col-sm-8">
											<input id="float_only" type="text" name="cola" class="form-control" placeholder="Input COLA" required="required">
										</div>
									</div>
								
									<button type="submit" class="btn btn-success btn-sm pull-right submit-min-wage" id="submit_min_wage">Submit</button>								
								</form> 

							<div class="col-sm-12">
							Your may visit this site in order to view the latest minimum wage from government in different sector, Click <a href="http://www.nwpc.dole.gov.ph/pages/ncr/cmwr.html" target="_blank">here</a>
							</div>
			      				
					      </div>
					    </div>
					</div>

					<?php
						}
					?>


					<?php 
						if ($_SESSION["role"] == 1) {
					?>
						<div class="col-sm-7 content-div">
					<?php
						}
						else {
					?>
						<div class="col-sm-8 col-sm-offset-2 content-div">
					<?php
						}
					?>
						<div class="panel panel-success">
					      <!--<div class="panel-heading">Updating Minimum Wage</div> -->
					        <div class="panel-body">
					 
		     					<?php
		     						$min_wage_class = new MinimumWage; // for declaring the class

		     						// for checking if has min wage
		     						$existMinWage = $min_wage_class->existMinWage();

		     						
		     					?>


			      				<center>
			      					<b>CURRENTLY DAILY MINIMUM WAGES RATES </br> (Effective:
			      						 <span id="effectiveDateSpan">
			      							<?php 
			      								if ($existMinWage != 0) {
			      									echo $min_wage_class->getMinWageEffectiveDate();
			      								} 

			      								?>
			      						</span>)
			      					</b>
		      					</center>
			      				<table class="table table-bordered">
								    <thead>
								      <tr>
								        <th style="background-color: #85929e;color:#fff;"><center>Basic Wage</center></th>
								        <th style="background-color: #85929e;color:#fff;"><center>COLA</center></th>
								        <th style="background-color: #85929e;color:#fff;"><center>Minimum Wage Rates</center></th>
								        <th style="background-color: #85929e;color:#fff;"><center>Action</center></th>
								      </tr>
								    </thead>
								    <tbody>
								     	<?php

								     		if ($existMinWage != 0){
								     			$min_wage_class->getLatestMinimumWage();
								     		}

								     		else {
								     			echo "<tr>";
								     				echo "<td colspan='4'><center>There is no data ..</center></td>";
								     			echo "</tr>";
								     		}
								     	?>
								    </tbody>
								</table>

								<div class="col-sm-12" id="updateLatestMinWage">

								</div>
					        </div>
					    </div>

					    <div class="panel panel-primary">
					      	<div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-calendar"></span> Minimum Wage History</center></div> 
						     	 <div class="panel-body">		     	 	
							      	<table id="" class="table table-bordered table-hover" style="border:1px solid #BDBDBD;">
										<thead>
											<tr>
												<th class="no-sort"><span class="glyphicon glyphicon-calendar" style="color:#186a3b"></span> Effective Date</th>
												<th class="no-sort"><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Basic Wage</th>
												<th class="no-sort"><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> COLA</th>
												<th class="no-sort"><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Minimum Wage Rates</th>
												<th class="no-sort"><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> Monthly Rate</th>													
											</tr>
										</thead>
										<tbody>	
											<?php
												if ($existMinWage != 0){
													$min_wage_class->getMinimumWageHistory();
												}
												else {
								     			echo "<tr>";
								     				echo "<td colspan='5'><center>There is no data ..</center></td>";
								     			echo "</tr>";
								     			}
											?>
										</tbody>
									</table>
								</div>			      				
					     	 </div>
					    </div>


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



		<div id="submitMinWageConfirmation" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#21618c;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-check' style='color:#fff'></span>&nbsp;Adding Latest Minimum Wage</h5>
					</div> 
					<div class="modal-body" id="active_stat_modal_body">
					
					</div> 
					<div class="modal-footer" style="padding:5px;text-align:center;" id="active_inactive_stat_modal_footer">
						<a href="#" class="btn btn-default" id="submit_yes_min_wage">YES</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
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
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-plus' style='color:#fff'></span>&nbsp;Add Minimum Wage Notif</h5>
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
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-plus' style='color:#fff'></span>&nbsp;Add Department Notification</h5>
					</div> 
					<div class="modal-body" id="success_modal_body_add">
						
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
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-alert' style='color:#fff'></span>&nbsp;Update Mininum Wage</h5>
					</div> 
					<div class="modal-body" id="error_modal_body">
						
					</div> 
			 		<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->



		<div id="deleteConfirmModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#21618c;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-check' style='color:#fff'></span>&nbsp;Delete Latest Minimum Wage</h5>
					</div> 
					<div class="modal-body" id="active_stat_modal_body">
						<center>Are you sure you want to delete the latest minimum wage?</center>
					</div> 
					<div class="modal-footer" style="padding:5px;text-align:center;" id="active_inactive_stat_modal_footer">
						<a href="#" class="btn btn-default" id="delete_yes_min_wage">YES</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- FOR SUCCESS MODAL -->
		<div id="successModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#1d8348;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-check' style='color:#fff'></span>&nbsp;Update Latest Minimum Wage</h5>
					</div> 
					<div class="modal-body" id="success_modal_body">
						
					</div> 
					<div class="modal-footer" style="padding:5px;">
						<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
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
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-trash' style='color:#fff'></span>&nbsp;Delete Latest Minimum Wage</h5>
					</div> 
					<div class="modal-body" id="success_modal_body_del">
						
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