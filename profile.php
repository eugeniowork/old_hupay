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
include "class/attendance_overtime.php"; // fixed class
include "class/attendance_notif.php"; // fixed class

include "class/department.php"; 
include "class/government_no_format.php"; 
include "class/money.php"; 
include "class/history_position.php"; 
include "class/dependent.php"; 
include "class/minimum_wage_class.php"; 
include "class/allowance_class.php";
include "class/memorandum_class.php";

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
$_SESSION["active_sub_payroll"] = null;
$_SESSION["active_sub_payroll_adjust"] = null;


// this area is for null of session
$_SESSION["view_emp_id"] = null; // sa view emp info
$_SESSION["update_emp_id"] = null; // sa update emp info
?>

<?php
	// for security browsing purpose
	//if ($_SESSION["role"] == 1) {
	//	header("Location:Mainform.php");
//	}

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
		<title>My Profile</title>
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
		<script src="js/jquery-ui.js"></script>
		<script src="js/modal.js"></script>
		<script src="js/plug ins/calendar/dcalendar.picker.js"></script>
		<script src="js/plug ins/data_tables/jquery.dataTables.js"></script>
		<script src="js/plug ins/data_tables/dataTables.bootstrap.js"></script>
		<script src="js/chevron.js"></script>
		<script src="js/readmore_message.js"></script>
		<script src="js/string_uppercase.js"></script>
		<script src="js/numbers_only.js"></script>
		<script src="js/maxlength.js"></script>
		<script src="js/format.js"></script>
		<script src="js/notifications.js"></script>
		<script src="js/custom.js"></script>
		<script src="js/jquery-ui.js"></script> 
		<script src="js/modal.js"></script>
		<script>
			$(document).ready(function(){
				// memo_own_list memo_all_list
				$('#memo_own_list').dataTable( {
				     "order": [],
				    "columnDefs": [ {
				      "targets"  : 'no-sort',
				      "orderable": false,
				    }]
				});

				$('#memo_all_list').dataTable( {
				     "order": [],
				    "columnDefs": [ {
				      "targets"  : 'no-sort',
				      "orderable": false,
				    }]
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
						<li class="active" id="home_id">Profile</li> 
					</ol>
				</div>
			</div>

			<!-- for body -->
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-primary" style="margin-top:10px;"> <!-- content-element -->

							<div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-edit"></span> Basic Information</center></div> 


							 <div class="panel-body">

							 	<form class="form-horizontal">
								 	<div class="col-sm-12">

								 		<div class="form-group">
									 	 	<div class="col-sm-offset-1 col-sm-3">
								 	 			<label class="control-label" style="color:#317eac;"><span class="glyphicon glyphicon-user" style="color: #196f3d "></span>&nbsp;<b>Employee Name:</b></label>	<br/>				
								 	 			<div style="margin-left:15px;"><b><?php echo $row->Lastname . ", " . $row->Firstname . " " . $row->Middlename; ?></b></div>
								 	 		</div>
								 	 		<div class="col-sm-6">
									 	 		<label class="control-label" style="color:#317eac;"><span class="glyphicon glyphicon-home" style="color: #196f3d "></span>&nbsp;<b>Address:</b></label> <br/>
								 	 			<div style="margin-left:15px;"><b><?php echo htmlspecialchars($row->Address); ?></b></div>
								 	 		</div>
							 	 		</div>

							 	 		<div class="form-group" style="margin-top:-10px;">
								 	 		<div class="col-sm-3 col-sm-offset-1">
									 	 		<label class="control-label" style="color:#317eac;"><span class="glyphicon glyphicon-user" style="color: #196f3d "></span>&nbsp;<b>Civil Status:</b></label> <br/>
								 	 			<div style="margin-left:15px;"><b><?php echo $row->CivilStatus; ?></b></div>
							 	 			</div>
								 	 		<div class="col-sm-3">
									 	 		<label class="control-label" style="color:#317eac;"><span class="glyphicon glyphicon-calendar" style="color: #196f3d "></span>&nbsp;<b>Birthdate:</b></label> <br/>
								 	 			<div style="margin-left:15px;"><b><?php echo $date_class->dateFormat($row->Birthdate); ?></b></div>
								 	 		</div>
								 	 		<div class="col-sm-3">
									 	 		<label class="control-label" style="color:#317eac;"><span class="glyphicon glyphicon-user" style="color: #196f3d "></span>&nbsp;<b>Gender:</b></label> <br/>
								 	 			<div style="margin-left:15px;"><b><?php echo $row->Gender; ?></b></div>
								 	 		</div>
							 	 		</div>


						 	 			<div class="form-group" style="margin-top:-10px;">
								 	 		<div class="col-sm-offset-1 col-sm-3">
									 	 		<label class="control-label" style="color:#317eac;"><span class="glyphicon glyphicon-phone-alt" style="color: #196f3d "></span>&nbsp;<b>Contact No:</b></label>
								 	 			<div style="margin-left:15px;"><b><?php if ($row->ContactNo != ""){ echo $row->ContactNo;} else  { echo "N/A";} ?></b></div>
								 	 		</div>
								 	 		<div class="col-sm-6">
									 	 		<label class="control-label" style="color:#317eac;"><span class="glyphicon glyphicon-envelope" style="color: #196f3d "></span>&nbsp;<b>Email Address:</b></label>
								 	 			<div style="margin-left:15px;<?php if ($row->EmailAddress != '') { echo 'color:#5dade2;';} ?>"><b><?php if ($row->EmailAddress != ""){ echo "<u>" . $row->EmailAddress . "</u>";} else { echo "N/A";} ?></b></div>
								 	 		</div>
							 	 		</div>

							 	 	</div>
						 	 	</form>
							</div>
						</div>

						<?php
							// for department
							$department_class = new Department;
							$department = $department_class->getDepartmentValue($row->dept_id)->Department;

							// for position
							$position = $position_class->getPositionById($row->position_id)->Position;

							// for money
							$money_class = new Money;
							$salary = "Php " . $money_class->getMoney($row->Salary);

							$dateHired = $date_class->dateFormat($row->DateHired);


							// for government format
							$govt_format_class = new GovernmentNoFormat;
							$history_position_class = new HistoryPosition;

							// for dependent
							$dependent_class = new Dependent;
							
							// for minimum wage class
							$minimum_wage_class = new MinimumWage;
							
							$minimum_wage = $minimum_wage_class->getMinimumWage();

							// for allowance class
							$allowance_class = new Allowance;

							$memo_class = new Memorandum;




						?>

						<div class="panel panel-primary" style="margin-top:-20px;"> <!-- content-element -->

							<div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-edit"></span> Company Information</center></div> 

							 <div class="panel-body">
							 	<form class="form-horizontal">
								 	<div class="col-sm-12">

								 	 	<div class="form-group">
								 	 		<div class="col-sm-offset-1 col-sm-3">
									 	 		<label class="control-label" style="color:#317eac;"><span class="glyphicon glyphicon-blackboard" style="color: #196f3d "></span>&nbsp;<b>Department:</b></label>
									 	 		<div style="margin-left:15px;"><b><?php echo $department; ?></b></div>
								 	 		</div>
								 	 		<div class="col-sm-3">
									 	 		<label class="control-label" style="color:#317eac;"><span class="glyphicon glyphicon-tasks" style="color: #196f3d "></span>&nbsp;<b>Position:</b></label>
								 	 			<div style="margin-left:15px;"><b><?php echo $position; ?></b></div>
								 	 		</div>
								 	 		
							 	 		
								 	 		<div class="col-sm-3">
									 	 		<label class="control-label" style="color:#317eac;"><span class="glyphicon glyphicon-calendar" style="color: #196f3d "></span>&nbsp;<b>Date Hired:</b></label>
								 	 			<div style="margin-left:15px;"><b><?php echo $dateHired; ?></b></div>
								 	 		</div>

							 	 		</div>

							 	 		<!--
							 	 		<div class="form-group" style="margin-top:-10px;">
								 	 		<div class="col-sm-3 col-sm-offset-1">
									 	 		<label class="control-label" style="color:#317eac;"><span class="glyphicon glyphicon-calendar" style="color: #196f3d "></span>&nbsp;<b>Date Hired:</b></label>
								 	 			<div style="margin-left:15px;"><b><?php echo $dateHired; ?></b></div>
								 	 		</div>
							 	 		</div>
										-->
							 	 	</div>
						 	 	</form>
							</div>
						</div>


						<div class="panel panel-primary" style="margin-top:-20px;"> <!-- content-element -->

							<div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-edit"></span> Government Information</center></div> 

							 <div class="panel-body">
							 	<form class="form-horizontal">
								 	<div class="col-sm-12">

								 		<div class="form-group">
								 	 		<div class="col-sm-offset-1 col-sm-3">
									 	 		<label class="control-label" style="color:#317eac;"><img src="img/government images/SSS-Logo.jpg" class="government-logo" alt="SSS-Logo"/>&nbsp;<b>SSS No:</b></label>
								 	 			<div style="margin-left:15px;"><b><?php if ($row->SSS_No != "") {echo $govt_format_class->sssNoFormat($row->SSS_No);} else { echo "N/A";} ?></b></div>
								 	 		</div>
								 	 		<div class="col-sm-3">
									 	 		<label class="control-label" style="color:#317eac;"><img src="img/government images/pag-ibig-logo.jpg" class="government-logo" alt="Pag-big-Logo"/>&nbsp;<b>Pag-ibig No:</b></label>
								 	 			<div style="margin-left:15px;"><b><?php if($row->PagibigNo != "") {echo $govt_format_class->pagibigNoFormat($row->PagibigNo);} else {echo "N/A";} ?></b></div>
								 	 		</div>
								 	 		<div class="col-sm-3">
									 	 		<label class="control-label" style="color:#317eac;"><img src="img/government images/bir-Logo.jpg" class="government-logo" alt="BIR-Logo"/>&nbsp;<b>Tin No:</b></label>
								 	 			<div style="margin-left:15px;"><b><?php if ($row->TinNo != "") { echo $govt_format_class->tinNoFormat($row->TinNo);} else { echo "N/A";} ?></b></div>
								 	 		</div>
							 	 		</div>

							 	 		<div class="form-group" style="margin-top:-10px;">

								 	 		<div class="col-sm-3 col-sm-offset-1">
									 	 		<label class="control-label" style="color:#317eac;"><img src="img/government images/philhealth-logo.jpg" class="government-logo" alt="Philhealth-Logo"/>&nbsp;<b>Philhealth No:</b></label>
								 	 			<div style="margin-left:15px;"><b><?php if ($row->PhilhealthNo != "") { echo $govt_format_class->philhealthNoFormat($row->PhilhealthNo);} else { echo "N/A";} ?></b></div>
								 	 		</div>
							 	 		</div>

							 	 	</div>
						 		</form>
							</div>
						</div>




						<?php
							if ($row->Salary > $minimum_wage) {

						?>

						<div class="panel panel-primary" style="margin-top:-20px;"> <!-- content-element -->

							<div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-edit"></span> Dependent Information</center></div> 

							 <div class="panel-body">
							 	<form class="form-horizontal">
								 	<div class="col-sm-12">

								 	 	<?php
								 	 		$dependent_class->getDependentInfoToProfile($id);

								 	 	?>


							 	 	</div>
						 	 	</form>
							</div>
						</div>

						<?php
							} // end of if , if above minimum wage so expected my panel siya para sa dependent information
						?>


						<div class="panel panel-primary" style="margin-top:-20px;"> <!-- content-element -->

							<div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-edit"></span> Compensation Information</center></div> 

							 <div class="panel-body">
							 	<form class="form-horizontal">
								 	<div class="col-sm-12">

							 			<div class="form-group">
								 	 	<?php
								 	 		$allowance_class->getAllowanceInfoToProfile($id);

								 	 	?>

									 	 	<div class="col-sm-3">
									 	 		<label class="control-label" style="color:#317eac;"><span class="glyphicon glyphicon-ruble" style="color: #196f3d "></span>&nbsp;<b>Salary:</b></label>
								 	 			<div style="margin-left:15px;"><b><?php echo $salary; ?></b></div>
								 	 		</div>

								 	 		<?php
								 	 			$total_allowance =  round($allowance_class->getTotalAllowance($id),2);
								 	 			$totalMonthly = round($row->Salary + $total_allowance,2);
								 	 		?>

								 	 		<div class="col-sm-3">
									 	 		<label class="control-label" style="color:#317eac;"><span class="glyphicon glyphicon-ruble" style="color: #196f3d "></span>&nbsp;<b>Total Monthly Pay:</b></label>
								 	 			<div style="margin-left:15px;"><b>Php <?php echo $money_class->getMoney($totalMonthly); ?></b></div>
								 	 		</div>
							 	 		</div>


							 	 	</div>
						 	 	</form>
							</div>
						</div>

					</div> <!-- end of col -->

					<div class="col-md-12">


						


						<?php
							if ($_SESSION["role"] != 1){



						?>
						<div class="panel panel-primary" style="margin-top:-10px;"> <!-- content-element -->

							<div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-calendar"></span> LFC Employment History</center></div> 


							<div class="panel-body">
						 		<div class="col-sm-12">
						 			<table id="" class="table table-bordered table-hover" style="border:1px solid #BDBDBD;">
										<thead>
											<tr>
												<th class="no-sort" style="background-color: #85929e;color:#fff;"><center><small>Department</small></center></th>
												<th class="no-sort" style="background-color: #85929e;color:#fff;"><center><small>Position</small></center></th>
												<th class="no-sort" style="background-color: #85929e;color:#fff;"><center><small>Salary</small></center></th>
												<th class="no-sort" style="background-color: #85929e;color:#fff;"><center><small>Date Hired/ Promote</small></center></th>												
											</tr>
										</thead>

										<tbody>	
											<?php
												$history_position_class = new HistoryPosition;
												$history_position_class->getHistoryEmploymentToTable($id);
											?>
										</tbody>
									</table>
					 			</div>
							</div>
						</div> <!-- end of panel-primary -->

						<?php
							} // end of if

						?>


						



						


						
					</div> <!-- end of col -->


					<div class="col-md-12">
						<div class="panel panel-primary" style="margin-top:-10px;"> <!-- content-element -->

							<div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-file"></span> Memorandum</center></div> 


							 <div class="panel-body">
							 	<div class="col-sm-10 col-sm-offset-1" style="">
						 			<table id="memo_own_list" class="table table-bordered table-hover" style="border:1px solid #BDBDBD;">
										<thead>
											<tr>
												<th class="no-sort" style="background-color: #85929e;color:#fff;" width="70%"><center>Memorandum</center></th>
												<th class="no-sort" style="background-color: #85929e;color:#fff;" width="15%"><center>From</center></th>
												<th class="no-sort" style="background-color: #85929e;color:#fff;" width="15%"><center>Category</center></th>
												<?php

													if ($_SESSION["role"] == 3){
														//echo $_SESSION["role"];
												?>
												<th class="no-sort" style="background-color: #85929e;color:#fff;" width="15%"><center>Action</center></th>
												<?php
													} // end of if
												?>
											</tr>
										</thead>

										<tbody>	
											<?php
											
												$memo_class->getAllMemoToTable($id,$_SESSION["role"]);
											?>
										</tbody>
									</table>
					 			</div>


					 			<!--<div class="col-sm-8 col-sm-offset-2" style="">
						 			<table id="memo_all_list" class="table table-bordered table-hover" style="border:1px solid #BDBDBD;">
										<thead>
											<tr>
												<th class="no-sort" style="background-color: #85929e;color:#fff;"><center><small>Memorandum List - All Employee</small></center></th>
												
											</tr>
										</thead>

										<tbody>	
											<?php
												$memo_class->getAllMemo();
											?>
										</tbody>
									</table>
					 			</div> -->
							</div>
						</div> <!-- end of panel-primary -->

					</div> <!-- end of col -->




					<div class="col-sm-4">
						<div class="panel panel-primary" style="margin-top:10px;"> <!-- content-element -->

							<div class="custom-panel-heading" style="color:#317eac;"><center><span class="glyphicon glyphicon-cog"></span> Accouting Settings</center></div> 


							 <div class="panel-body">
							 	<a data-toggle="collapse" href="#change_password" title="Change Password" id="change_password_a"><center><span class="glyphicon glyphicon-lock"></span>&nbsp;Change Password</center></a>
								<div class="collapse" id="change_password">
						 	 		<div class="col-sm-8 col-sm-offset-2">
						 	 			<form class="form-horizontal" action="" method="post" id="form_change_password">
						 	 				<div class="form-group">
							 	 				<label class="control-label">&nbsp;<b>Current Password:</b></label>
							 	 				<input type="password" class="form-control" placeholder="Enter Current Password ..." name="currentPassword" required="required"/>
						 	 				</div>

						 	 				<div class="form-group">
							 	 				<label class="control-label">&nbsp;<b>New Password:</b></label>
							 	 				<input type="password" class="form-control" placeholder="Enter New Password ..." name="newPassword" required="required"/>
						 	 				</div>

						 	 				<div class="form-group">
							 	 				<label class="control-label">&nbsp;<b>Confirm New Password:</b></label>
							 	 				<input type="password" class="form-control" placeholder="Confirm New Password ..." name="confirmPassword" required="required"/>
						 	 				</div>

						 	 				<div class="form-group">
						 	 					<input type="submit" value="Change Password" class="btn btn-primary btn-sm pull-right" id="submit_change_password"/>
					 	 					</div>

					 	 					<div class="form-group">
					 	 						<div id="change_password_message">


				 	 							</div>
				 	 						</div>
						 	 			</form>				 	 			
						 	 		</div>
								</div>
							</div>
						</div> <!-- end of panel-primary -->

						
					</div> <!-- end of col-->
				</div> <!-- end of row -->
			</div> <!-- end of container fluid -->




			<!--<div class="hr-payroll-system-footer">
				<strong>All Right Reserves 2017 | V1.0</strong>
			</div> -->
			
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




		<!-- for for view memo -->


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
		<div id="memoInfoModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-lg">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#1d8348;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-file' style='color:#fff'></span>&nbsp;Memorandum Information</h5>
					</div> 
					<div class="modal-body" id="memo_info_body">
						
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