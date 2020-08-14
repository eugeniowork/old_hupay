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
include "class/department.php";
include "class/government_no_format.php";
include "class/dependent.php";
include "class/allowance_class.php";
include "class/minimum_wage_class.php";
include "class/money.php";
include "class/201_files_class.php";

// formal declaration of classes
$date_class = new date;
$department_class = new Department;

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

// for empformation
include "class/emp_information.php";
$emp_info = new EmployeeInformation;
$emp_id =  $_SESSION["view_emp_id"];
$row_profile_emp = $emp_info->getEmpInfoByRow($emp_id);


?>

<?php
	// for security browsing purpose
	if ($_SESSION["role"] == 4) {
		header("Location:Mainform.php");
	}

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
?>

<!DOCTYPE html>
<html>
	<head>

		<title>View Employee Profile - <?php echo $row_profile_emp->Firstname . " " . $row_profile_emp->Middlename . " " . $row_profile_emp->Lastname; ?></title>
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
		<link rel="stylesheet" href="css/custom.css">

		<!-- js -->
		<script src="js/jquery-1.12.2.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/plug ins/calendar/dcalendar.picker.js"></script>
		<script src="js/chevron.js"></script>
		<script src="js/notifications.js"></script>
		<script src="js/custom.js"></script>
		<script src="js/jquery-ui.js"></script>
		<script src="js/modal.js"></script>
		<script>
			<?php
				// for redirecting to employee list	

				if (!isset($_SESSION["view_emp_id"])) {
						echo "window.location.href = 'employee_list.php';";
					}


				// for updating error
				if (isset($_SESSION["update_files201_error"])){
					echo '$(document).ready(function() {
						$("#error_modal_body").html("'.$_SESSION["update_files201_error"].'");
						$("#errorModal").modal("show");
					});';
					$_SESSION["update_files201_error"] = null;
				}


				// for success updating
					if (isset($_SESSION["update_files201_success"])){
						echo '$(document).ready(function() {
							$("#success_modal_body_add").html("'.$_SESSION["update_files201_success"].'");
							$("#add_successModal").modal("show");
						});';
						$_SESSION["update_files201_success"] = null;
					}


					// for success in deleting
					if (isset($_SESSION["delete_files201_success"])){
						echo '$(document).ready(function() {
							$("#success_modal_body_add").html("'.$_SESSION["delete_files201_success"].'");
							$("#add_successModal").modal("show");
						});';
						$_SESSION["delete_files201_success"] = null;
					}

			
			?>

					
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
						<li id="home_id"><a href="employee_list.php">Employee List</a></li> 
						<li class="active" id="home_id">View Employee Profile</li> 
					</ol>
				</div>
			</div>

			
			<!-- for body -->
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-success content-element" style="">

							 <div class="panel-body">
							 	<?php
							 		
							 		

							 	?>
							 	<div class="container-fluid">
						 			<div class="row">
									 	<div class="col-xs-12">
									 		<center>
										 		<img src="<?php echo $row_profile_emp->ProfilePath;?>" class="view-emp-profile"/> <br/>
										 		<b><?php echo $row_profile_emp->Firstname . " " . $row_profile_emp->Middlename . " " . $row_profile_emp->Lastname; ?></b> </br>
										 		<i><?php echo $position_class->getPositionById($row_profile_emp->position_id)->Position; ?></i> </br>
										 		<small class="color-green">Member since <?php echo $date_class->dateFormatMonthYear($row_profile_emp->DateCreated); ?></small>
										 		<?php
										 			if ($row_profile_emp->resignation_date != ""){
										 		?>
										 		<br/>
										 		<small class="color-red">Inactive on <?php echo date_format(date_create($row_profile_emp->resignation_date),"F d, Y"); ?></small>
										 		<?php
										 			}
										 		?>
								 			</center>
								 		</div>
							 		</div>
						 		</div>

						 		<br/>

						 		<div class="container-fluid">
						 			<div class="row">
									 	<div class="col-xs-6" style="border-right:1px solid #808b96;">
									 		<fieldset  style="background-color:#ebf5fb;padding-bottom:10px;">
									 			<h4 style="background-color:#21618c ;padding:5px;color:#fff;margin-top:0px;"><span class="glyphicon glyphicon-check"></span> BASIC INFORMATION</h4>
									 			<div class="col-xs-12 col-sm-8 col-sm-offset-2 content-view-emp">
									 				<b><span class="glyphicon glyphicon-home" style="color:#2E86C1;"></span> Address:</b> <?php echo htmlspecialchars($row_profile_emp->Address); ?>
								 				</div>

								 				<div class="col-xs-12 col-sm-8 col-sm-offset-2 content-view-emp">
										 			<b><span class="glyphicon glyphicon-user" style="color:#2E86C1;"></span> Civil Status:</b> <?php echo  $row_profile_emp->CivilStatus; ?>
									 			</div>
								 			

									 			<div class="col-xs-12 col-sm-8 col-sm-offset-2 content-view-emp">
										 			<b><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Birthdate:</b> <?php echo $date_class->dateFormat($row_profile_emp->Birthdate); ?>
									 			</div>

									 			<div class="col-xs-12 col-sm-8 col-sm-offset-2 content-view-emp">
										 			<b><span class="glyphicon glyphicon-user" style="color:#2E86C1;"></span> Gender:</b> <?php echo $row_profile_emp->Gender; ?>
									 			</div>

									 			<div class="col-xs-12 col-sm-8 col-sm-offset-2 content-view-emp">
										 			<b><span class="glyphicon glyphicon-phone-alt" style="color:#2E86C1;"></span> Contact No:</b> <?php if ($row_profile_emp->ContactNo == "") { echo "N/A";}else {  echo $row_profile_emp->ContactNo;} ?>
									 			</div>


									 			<div class="col-xs-12 col-sm-8 col-sm-offset-2 content-view-emp">
										 			<b><span class="glyphicon glyphicon-envelope" style="color:#2E86C1;"></span> Email Address:</b> <?php if ($row_profile_emp->EmailAddress == "") { echo "N/A";} else { echo "<u style='color:#158cba;'>" . $row_profile_emp->EmailAddress . "</u>";} ?>
									 			</div>

									 			
								 			</fieldset>
								 		</div>

								 		<div class="col-xs-6">
								 			<fieldset style="background-color:#ebf5fb;padding-bottom:10px;">
									 			<h4 style="background-color:#21618c ;padding:5px;color:#fff;margin-top:0px;"><span class="glyphicon glyphicon-check"></span> COMPANY INFORMATION</h4>
									 			<div class="col-xs-12 col-sm-8 col-sm-offset-2 content-view-emp">
										 			<b><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Department:</b> <?php echo $department_class->getDepartmentValue($row_profile_emp->dept_id)->Department; ?>
									 			</div>
									 			
									 			<div class="col-xs-12 col-sm-8 col-sm-offset-2 content-view-emp">
										 			<b><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Date Hired:</b><?php echo $date_class->dateFormat($row_profile_emp->DateHired); ?>
									 			</div>



									 			<div class="col-xs-12 col-sm-8 col-sm-offset-2 content-view-emp">
										 			<b><span class="glyphicon glyphicon-user" style="color:#2E86C1;"></span> Immediate Head's Name:</b> <?php if ($row_profile_emp->head_emp_id == 0) { echo "N/A"; } else { echo $emp_info->geyHeadsNameByHeadEmpId($row_profile_emp->head_emp_id);} ?>
									 			</div>
									 			<div class="col-xs-12 col-sm-8 col-sm-offset-2 content-view-emp">
									 				<?php
									 					$row_user_company = $company_class->getInfoByCompanyId($row_profile_emp->company_id);
									 				?>
										 			<b><span class="glyphicon glyphicon-blackboard" style="color:#2E86C1;"></span> Company:</b> <?php echo $row_user_company->company; ?>
									 			</div>
									 			<div class="col-xs-12 col-sm-8 col-sm-offset-2 content-view-emp">
									 				<?php
									 					$employment_status = "Provisional";
									 					if ($row_profile_emp->employment_type == 1){
									 						$employment_status = "Regular";
									 					}
									 				?>
									 				<b><span class="glyphicon glyphicon-blackboard" style="color:#2E86C1;"></span> Company:</b> <?php echo $employment_status; ?>
								 				</div>
								 			</fieldset>
							 			</div>

							 		</div>
						 		</div>

						 		<div class="container-fluid">
						 			<div class="row">
								 		<div class="col-xs-6" style="border-right:1px solid #808b96;">
								 			<?php
								 				// for government no class
								 				$gov_class = new GovernmentNoFormat;
								 			?>
									 		<fieldset style="margin-top:30px;background-color:#ebf5fb;padding-bottom:10px;">
									 			<h4 style="background-color:#21618c ;padding:5px;color:#fff;margin-top:0px;"><span class="glyphicon glyphicon-check"></span> GOVERNMENT INFORMATION</h4>
									 			<div class="col-xs-12 col-sm-8 col-sm-offset-2 content-view-emp">
										 			<b><img src="img/government images/SSS-Logo.jpg" class="government-logo" alt="SSS-Logo"/><span>&nbsp; SSS No:</b> <?php if ($row_profile_emp->SSS_No == "") {echo "N/A";} else { echo $gov_class->sssNoFormat($row_profile_emp->SSS_No);} ?>
									 			</div>
									 			<div class="col-xs-12 col-sm-8 col-sm-offset-2 content-view-emp">
										 			<b><img src="img/government images/pag-ibig-logo.jpg" class="government-logo" alt="Pag-big-Logo"/><span>&nbsp; Pag-ibig No:</b> <?php if ($row_profile_emp->PagibigNo == ""){echo "N/A"; }else { echo $gov_class->pagibigNoFormat($row_profile_emp->PagibigNo);} ?>
									 			</div>
									 			<div class="col-xs-12 col-sm-8 col-sm-offset-2 content-view-emp">
										 			<b><img src="img/government images/bir-Logo.jpg" class="government-logo" alt="BIR-Logo"/><span>&nbsp; Tin No:</b> <?php if ($row_profile_emp->TinNo == ""){ echo "N/A";}else {echo $gov_class->tinNoFormat($row_profile_emp->TinNo);} ?>
									 			</div>
									 			<div class="col-xs-12 col-sm-8 col-sm-offset-2 content-view-emp">
										 			<b><img src="img/government images/philhealth-logo.jpg" class="government-logo" alt="Philhealth-Logo"/><span>&nbsp; Philhealth No:</b> <?php if ($row_profile_emp->PhilhealthNo == "") { echo "N/A";} else {echo $gov_class->philhealthNoFormat($row_profile_emp->PhilhealthNo);} ?>
									 			</div>


								 			</fieldset>
								 			<!--<div class="col-xs-12" style="margin-top:50px;">
								 				<a href="#" class="pull-right action-a" title="View Actual 201 File of Employee">View Acual 201 File</a>
							 				</div> -->
							 			</div>



							 			<?php 
							 				$min_wage_class = new MinimumWage;
							 				$min_wage = $min_wage_class->getMinimumWage();
							 				
							 				if ($row_profile_emp->Salary > $min_wage){
					 					?>
							 			<div class="col-xs-6">
									 		<fieldset style="margin-top:30px;background-color:#ebf5fb;padding-bottom:10px;">
									 			<h4 style="background-color:#21618c ;padding:5px;color:#fff;margin-top:0px;"><span class="glyphicon glyphicon-check"></span> DEPENDENT INFORMATION</h4>
									 			<?php
									 				$dependent_class = new Dependent;

									 				// if there is no dependent
									 				if ($dependent_class->existDependent($emp_id) == 0) {
									 					echo '<div class="col-xs-12">';
									 						echo "There is no declared dependent";
									 					echo '</div>';
									 				}
									 				// if have dependent
									 				else {
									 					$dependent_class->dependentInfo($emp_id);
									 				}
									 			?>
								 			</fieldset>
								 			<!--<div class="col-xs-12" style="margin-top:50px;">
								 				<a href="#" class="pull-right action-a" title="View Actual 201 File of Employee">View Acual 201 File</a>
							 				</div> -->
							 			</div>
							 			<?php
							 				}
							 				else {
					 					?>
					 						<div class="col-xs-6" style="border-right:1px solid #808b96;">
									 			<fieldset style="margin-top:30px;background-color:#ebf5fb;padding-bottom:10px;">
										 			<h4 style="background-color:#21618c ;padding:5px;color:#fff;margin-top:0px;"><span class="glyphicon glyphicon-check"></span> COMPENSATION INFORMATION</h4>
										 			<?php
										 				$allowance_class = new Allowance;
										 				// if there is no allowance
										 				if ($allowance_class->existAllowance($emp_id) == 0) {
										 					echo '<div class="col-xs-12">';
										 						echo "There is no allowance";
										 					echo '</div>';
										 				}
										 				// if there is allowance
										 				else {
										 					$allowance_class->allowanceInfo($emp_id);
										 				}



										 			?>

										 			<div class="col-xs-12 col-sm-8 content-view-emp">
											 			<b><span class="glyphicon glyphicon-ruble" style="color:#2E86C1;"></span> Salary:</b> Php <?php $money_class = new Money; echo $money_class->getMoney($row_profile_emp->Salary); ?>
										 			</div>

										 			<?php
										 	 			$total_allowance =  round($allowance_class->getTotalAllowance($emp_id),2);
										 	 			$totalMonthly = round($row_profile_emp->Salary + $total_allowance,2);
									 	 			?>

										 			<div class="col-xs-12 col-sm-8 content-view-emp">
											 			<b><span class="glyphicon glyphicon-ruble" style="color:#2E86C1;"></span> Total Monthly Pay:</b> Php <?php $money_class = new Money; echo $money_class->getMoney($totalMonthly); ?>
										 			</div>
									 			</fieldset>
							 				</div>
					 					<?php
							 				} // end of if
							 			?>
						 			</div>
						 		</div>


						 		<?php 
					 				if ($row_profile_emp->Salary > $min_wage){
			 					?>
						 		<div class="container-fluid">
						 			<div class="row">
								 		<div class="col-xs-6" style="border-right:1px solid #808b96;">
								 			<fieldset style="margin-top:30px;background-color:#ebf5fb;padding-bottom:10px;">
									 			<h4 style="background-color:#21618c ;padding:5px;color:#fff;margin-top:0px;"><span class="glyphicon glyphicon-check"></span> COMPENSATION INFORMATION</h4>
									 			<?php
									 				$allowance_class = new Allowance;
									 				// if there is no allowance
									 				if ($allowance_class->existAllowance($emp_id) == 0) {
									 					echo '<div class="col-xs-12">';
									 						echo "There is no allowance";
									 					echo '</div>';
									 				}
									 				// if there is allowance
									 				else {
									 					$allowance_class->allowanceInfo($emp_id);
									 				}



									 			?>

									 			<div class="col-xs-12 col-sm-8 content-view-emp">
										 			<b><span class="glyphicon glyphicon-ruble" style="color:#2E86C1;"></span> Salary:</b> Php <?php $money_class = new Money; echo $money_class->getMoney($row_profile_emp->Salary); ?>
									 			</div>

									 			<?php
													  $total_allowance =  round($allowance_class->getTotalAllowance($emp_id),2);
													  
													  //echo $total_allowance;
									 	 			$totalMonthly = round($row_profile_emp->Salary + $total_allowance,2);
								 	 			?>

									 			<div class="col-xs-12 col-sm-8 content-view-emp">
										 			<b><span class="glyphicon glyphicon-ruble" style="color:#2E86C1;"></span> Total Monthly Pay:</b> Php <?php $money_class = new Money; echo $money_class->getMoney($totalMonthly); ?>
									 			</div>

								 			</fieldset>
							 			</div>
				 					</div>
						 		</div>
						 		<?php
						 			} // end of if
						 		?>

						 		<div class="container-fluid">
						 			<div class="row">
									 	<div class="col-xs-6" style="border-right:1px solid #808b96;">
									 		<fieldset  style="background-color:#ebf5fb;padding-bottom:10px;">
									 			<h4 style="background-color:#21618c ;padding:5px;color:#fff;margin-top:0px;"><span class="glyphicon glyphicon-check"></span> EDUCATION INFORMATION</h4>
									 			
									 			<?php
									 				$emp_info->getViewSchoolInfoById($emp_id);

									 			?>
									 			
								 			</fieldset>
								 		</div>

								 		<div class="col-xs-6">
								 			<fieldset style="background-color:#ebf5fb;padding-bottom:10px;">
									 			<h4 style="background-color:#21618c ;padding:5px;color:#fff;margin-top:0px;"><span class="glyphicon glyphicon-check"></span> WORK EXPERIENCE</h4>
									 			<?php
									 				$emp_info->getViewWorkExperienceById($emp_id);

									 			?>
								 			</fieldset>
							 			</div>

							 		</div>
						 		</div>

						 		<br/>
						 		<div class="container-fluid">
						 			<div class="row">
					 					<div class="col-xs-12" style="">
									 		<fieldset  style="background-color:#ebf5fb;padding-bottom:10px;">
									 			<?php
								 					$files201_class = new files201_class;
								 					$exist_files201_img = $files201_class->checkExist201FilesImages($emp_id);
								 					//echo $exist_files201_img;
									 			?>
									 			<h4 style="background-color:#21618c ;padding:5px;color:#fff;margin-top:0px;"><span class="glyphicon glyphicon-check"></span> 201 FILES IMAGES</h4>
								 				
								 				 <!--
								 				 <div class = "col-sm-3">
												     <div class = "thumbnail">
												        <img src = "img/201 Files images/Bogayan, Armando Jr Corales_1.jpg" alt = "Generic placeholder thumbnail">
												     </div>
											     </div>

											     <div class = "col-sm-3">
												     <div class = "thumbnail">
												        <img src = "img/201 Files images/Bogayan, Armando Jr Corales_1.jpg" alt = "Generic placeholder thumbnail">
												     </div>
											     </div>

											     <div class = "col-sm-3">
												     <div class = "thumbnail">
												        <img src = "img/201 Files images/Bogayan, Armando Jr Corales_1.jpg" alt = "Generic placeholder thumbnail">
												     </div>
											     </div>

											     <div class = "col-sm-3">
												     <div class = "thumbnail">
												        <img src = "img/201 Files images/Bogayan, Armando Jr Corales_1.jpg" alt = "Generic placeholder thumbnail">
												     </div>
											     </div>
											 	-->

											 	<?php 

											 		if ($exist_files201_img != 0){

											 	?>

											 	

											 	<!--<div id="myCarousel" class="carousel slide col-sm-offset-3 col-sm-6" data-ride="carousel" style="background-color:#000;">-->
										 		<div id="myCarousel" class="carousel slide col-sm-offset-3 col-sm-6" data-interval="false" style="padding-left:0px;padding-right:0px;">
													<!-- Indicators -->
													<ol class="carousel-indicators" style="margin-bottom:-3%;">
														<?php $files201_class->getAll201FilesToCarouselIndicators($emp_id); ?>
													</ol>

													<!-- Wrapper for slides -->
													<div class="carousel-inner" style="">
														<?php $files201_class->getAll201FilesToCarouselInner($emp_id); ?>
													</div>

													<!-- Left and right controls -->
													<a class="left carousel-control" href="#myCarousel" data-slide="prev" style="background-image:none;" id="carousel_navigation">
														<span class="glyphicon glyphicon-chevron-left" style="margin-left:-45%;"></span>
														<span class="sr-only">Previous</span>
													</a>
													<a class="right carousel-control" href="#myCarousel" data-slide="next" style="background-image:none;" id="carousel_navigation">
														<span class="glyphicon glyphicon-chevron-right" style="margin-right:-45%;"></span>
														<span class="sr-only">Next</span>
													</a>
											    </div>
											    <div class="col-sm-offset-3 col-sm-6" style="border:1px solid #BDBDBD;background-color:#fff;border-radius:2px;padding:5px;">
											    	<div style="text-align:center;">
											    		<button type="button" class="btn btn-primary btn-sm" id="description"><span class='glyphicon glyphicon-edit'></span> Description</button>
										    			<button type="button" class="btn btn-success btn-sm" id="update_image"><span class='glyphicon glyphicon-pencil'></span> Update Image</button>
										    			<button type="button" class="btn btn-danger btn-sm" id="remove_image"><span class='glyphicon glyphicon-remove'></span> Remove</button>
										    		</div>
										    	</div>

												  <?php
												  	} // end of else

												  	// if no images yet
												  	else {
									  			 ?>
								  			 	<div class="col-xs-12">
							  			 			There is no <b>201 Files</b> images uploaded yet
							  			 		</div>
									  			 <?php
												  	} // end of else
												  ?>

								 			</fielset>
							 			</div>

					 				</div>
				 				</div>
						 		

						 		


					 			

						 	</div> <!-- end of panel-body -->

					 	</div>

				 	</div> <!-- end of col-md-12 -->

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
		<div id="updateFormModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#1d8348;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-pencil' style='color:#fff'></span>&nbsp;Update 201 Files Description</h5>
					</div> 
					<div class="modal-body" id="update_Form_body">
						
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



		<!-- for upload 201 File -->
		<div id="update_201FileModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm" style="width:400px">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#158cba;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-upload' style='color:#fff'></span>&nbsp;<span id='update_header_files201_image'>Update 201 File</span></h5>
					</div> 
					<div class="modal-body" id="">
						<form method="post" id="form_update_images_201_files" enctype="multipart/form-data" action="php script/update_image_files201.php">
							<center><input type="file" name="201_files_pic_file" accept="image/*"></center>
						</form>
					</div> 
			 		<div class="modal-footer" style="padding:5px;">
			 			<div class="pull-left" id="upload_201_files_profile_msg">

		 				</div>
						<button type="button" class="btn btn-primary" id="update_files201_image">Update</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- FOR DELETE CONFIRMATION MODAL -->
		<div id="deleteFiles201ImageConfirmationModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-sm" style="width:400px">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#21618c;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-trash' style='color:#fff'></span>&nbsp;<span id="delete_header_files201_image">Delete Notification</span></h5>
					</div> 
					<div class="modal-body" id="delete_modal_body">
						<form class="" action="" method="post" id="form_deleteFiles201">
							<div class="container-fluid">
								<div style="text-align:center;">					
									<span><span class="glyphicon glyphicon-warning-sign" style="color:#CB4335;"></span> Are you sure you want to delete the this image?</b></span>
								</div>						
							</div>
						</form>
					</div> 
					<div class="modal-footer" style="padding:5px;text-align:center;" id="delete_modal_footer">
						<a href="#" class="btn btn-default" id="delete_yes_files201_image">YES</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
					</div>
				</div>

			</div>
		</div> <!-- end of modal -->


		<!-- FOR SUCCESS MODAL -->
		<div id="view_files201Modal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-lg">
			<!-- Modal content-->
				<div class="modal-content" id="modal_content_view">
					
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