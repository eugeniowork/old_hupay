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

include "class/cut_off.php";

// for universal variables
$id = $_SESSION["id"];




// this area is for null of session
$_SESSION["view_emp_id"] = null; // sa view emp info
$_SESSION["update_emp_id"] = null; // sa update emp info


?>

<?php
	include "class/emp_information.php";
	$emp_info = new EmployeeInformation;

	// for security browsing purpose
	if ($_SESSION["role"] == 2 || $_SESSION["role"] == 4) {
		header("Location:Mainform.php");
	}

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
		<title>Add Attendance</title>
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
		<script src="js/notifications.js"></script>
		<script src="js/numbers_only.js"></script>
		<script src="js/custom.js"></script>
		<script src="js/jquery-ui.js"></script>
		<script src="js/modal.js"></script>
		<script type="text/javascript" language="javascript">
			$(document).ready(function() {


				 <?php
				// $name = "wew";
				 //$cut_off_period = "wew";
			   	 //$_SESSION["success_add_attendance_no_bio"] ="<span class='sad'></span> <b></b> attendance for the cut off <b></b> is successfully added"; 
			    	if (isset($_SESSION["success_add_attendance_no_bio"])){
						echo '$(document).ready(function() {
							$("#success_modal_body_add").html("'.$_SESSION["success_add_attendance_no_bio"].'");
							$("#add_successModal").modal("show");
						});';
						$_SESSION["success_add_attendance_no_bio"] = null;
					}
			    ?>

				$('#employee_list').dataTable( {
				     "order": [],
				    "columnDefs": [ {
				      "targets"  : 'no-sort',
				      "orderable": false,
				    }]
				});

				//$("#add_successModal").modal("show");
				  // for handling security in time in hours
			    $("input[title='hour']").on('input', function(){
			      if ($(this).val() >= 13){
			      		 $(this).val($(this).val().slice(0,-1));
			      	}

			   });

			      // for handling security in time in hours
			    $("input[title='min']").on('input', function(){
			      	if ($(this).val() >= 60){
			      		 $(this).val($(this).val().slice(0,-1));
			      	}

			   });




    		});
		</script>
	</head>
	<body>
		<!-- for nav menu -->
		<nav class="navbar navbar-inverse navbar-fixed-top" style="background-color:#357ca5;">
			<div class="container-fluid">
				<div class="navbar-header">
				<!--
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#collapsemenu">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button> -->
				<a class="navbar-brand" href="MainForm.php">
					<img src="<?php echo $logo_source; ?>" class="lloyds-logo"/>
				  	<!--<span style="color:rgba(255, 255, 255, 0.8);">&nbsp;HR & Payroll System</span>-->
			  		<span class="hupay-color">&nbsp;HuPay System</span>
				</a>
				</div>

				<div class="dropdown pull-right full-name">
					<span style="color:#ffffff">
						<?php
							
							$position_class = new Position;
							$date_class = new date;

							$cashbond_class = new Cashbond;
							$salary_loan_class = new SalaryLoan;
							
							
							echo $row->Firstname . " " . $row->Middlename . " " . $row->Lastname;
						?>
					</span>

					<span style="color:#ffffff">|</span>
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">		
						<span class="caret" style="color: #145a32 "></span>
					</a>
					<!-- <a class="log-out" id="log_out" href="../php script/log_out_script.php"> Log Out</a> -->
					<ul class="dropdown-menu log-out-menu" role="menu">
						<div class="log-out-div">
							
								<img src="<?php echo $row->ProfilePath; ?>" class="log-out-pic" alt="Profile Picture"/> <br/>
						
							<p class="log-out-p">  <?php echo $row->Firstname . " " . $row->Middlename . " " . $row->Lastname . " - " . $position_class->getPositionById($row->position_id)->Position; ?>
								<small style="display:block;font-size:12px;">Employed since <?php echo $date_class->dateFormat($row->DateHired); ?></small>
							</p>
						</div>
						<span class="col-sm-12 log-out-footer">
							<div class="pull-left">
								<a href="profile.php" class="btn btn-primary">Profile</a>
							</div>
								<!-- <li><a href="#"><svg class="glyph stroked gear"><use xlink:href="#stroked-gear"></use></svg> Settings</a></li> -->
							<div class="pull-right">
								<a href="php script/log_out_script.php" class="btn btn-primary">Logout</a>
							</div>
						</span>
					</ul>


				</div>


				<div class="dropdown pull-right full-name" style="margin-right:10px;">
				    <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="attendance_notifications">
						<span class="glyphicon glyphicon-time" style="color: #ffffff "></span>&nbsp;&nbsp;<span class="label label-danger notif-count" id="notif_attendance_count"><?php $attendance_notifications_class = new Attendance_Notifications;if ($attendance_notifications_class->unreadNotif($id) != 0){echo $attendance_notifications_class->unreadNotif($id);}?></span>
					</a>
					<ul class="dropdown-menu notification-menu" role="menu">
						<div class="notif-label">
							<strong>&nbsp;&nbsp;Attendance Notifications</strong>
						</div>
						<div class="container-fluid">
							<div class="row">
								<ul class="notif-list">
									<?php

										if ($attendance_notifications_class->checkExistNotif($id) == 0){
									?>
									<li style="border-bottom:#ffffff;">
										<div>
											<div class="container-fluid">
												<div class="col-sm-12">
													<center><span class="no-notif">There is no notifications yet.</span></center>
											 	</div>
											</div>
										</div>
									</li>

									<?php
										}
										else {
											$attendance_notifications_class->notifications($id);
										}
									?>


								</ul>
							</div>
						</div>
					</ul>
				</div> <!-- end of notifications div -->


				


				<?php
					$events_notif_class = new EventsNotification;
				?>

				<div class="dropdown pull-right full-name" style="margin-right:5px;">
				    <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="events_notifications">
						<span class="glyphicon glyphicon-calendar" style="color: #ffffff "></span>&nbsp;&nbsp;<span class="label label-danger notif-count" id="notif_events_count"><?php if ($events_notif_class->unreadEventsNotifCount($id) == 0) { echo ""; } else {echo $events_notif_class->unreadEventsNotifCount($id);} ?></span>
					</a>
					<ul class="dropdown-menu notification-menu" role="menu">
						<div class="notif-label">
							<strong>&nbsp;&nbsp;Events Notifications</strong>
						</div>
						<div class="container-fluid">
							<div class="row">
								<ul class="notif-list">
									<?php
										$events_notif_class->getAllEventsNotif($id);

									?>									
								</ul>
							</div>
						</div>
					</ul>
				</div> <!-- end of notifications div -->


				<?php
					$payroll_notif_class = new PayrollNotif;
				?>

				<div class="dropdown pull-right full-name" style="margin-right:10px;">
				    <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="payroll_notifications">
						<span class="glyphicon glyphicon-ruble" style="color: #ffffff "></span>&nbsp;&nbsp;<span class="label label-danger notif-count" id="notif_payroll_count"><?php if ($payroll_notif_class->unreadPayrollNotifCount($id) == 0) { echo ""; } else {echo $payroll_notif_class->unreadPayrollNotifCount($id);} ?></span>
					</a>
					<ul class="dropdown-menu notification-menu" role="menu">
						<div class="notif-label">
							
							<strong>&nbsp;&nbsp;Payroll Notifications</strong>
						</div>
						<div class="container-fluid">
							<div class="row">
								<ul class="notif-list">
									<?php
										$payroll_notif_class->getAllPayrollNotif($id);

									?>
								</ul>
							</div>
						</div>
					</ul>
				</div> <!-- end of notifications div -->


				<div class="dropdown pull-right full-name" style="margin-right:10px;">
				    <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="memo_notifications">
						<span class="glyphicon glyphicon-file" style="color: #ffffff "></span>&nbsp;&nbsp;<span class="label label-danger notif-count" id="notif_memo_count"><?php if ($memorandum_class->unreadMemoNotifCount($id) == 0) { echo ""; } else {echo $memorandum_class->unreadMemoNotifCount($id);} ?></span>
					</a>
					<ul class="dropdown-menu notification-menu" role="menu">
						<div class="notif-label">
							
							<strong>&nbsp;&nbsp;Memurandum Notifications</strong>
						</div>
						<div class="container-fluid">
							<div class="row">
								<ul class="notif-list">
									<?php
										$memorandum_class->getAllMemoNotif($id);

									?>
								</ul>
							</div>
						</div>
					</ul>
				</div> <!-- end of notifications div -->



			</div>	<!-- end of  div -->

		</nav> <!-- end of nav -->


		<div class="sidebar">
			<ul class="nav">
				<div style="text-align:center;padding:15px;background-color:#e9ecf2;">
					<div id="div_image_display">
						<img src="<?php echo $row->ProfilePath; ?>" class="profile-pic"/>
					</div>
					<div><b><?php echo $row->Firstname . " " . $row->Middlename . " " . $row->Lastname ?></b></div>
					<div><strong style="color:#1b4f72"><?php echo $position_class->getPositionById($row->position_id)->Position; ?></strong></div>
				</div>
				<!-- <li class="divider"></li> -->
				<li class="parent">
					<a href="MainForm.php">
						<span class="glyphicon glyphicon-dashboard"></span>&nbsp; Dashboard
					</a>
				</li>

				<?php
					//if ($_SESSION["role"] == 1 || $_SESSION["role"] == 2) {
					if ($_SESSION["role"] != 4) {
				?>

				<li class="parent">
					<a data-toggle="collapse" href="#sub-item-1-employee">
						<span class="glyphicon glyphicon-user"></span>&nbsp; Employee <span class="pull-right glyphicon glyphicon glyphicon-menu-down" id=""></span>
					</a>
					<ul class="children collapse" id="sub-item-1-employee">
						
						<?php

							// for miss yvvette
							if ($_SESSION["id"] != 21) {

						?>
							<li class="">
								<a class="" href="emp_registration.php">
									<span class="glyphicon glyphicon-registration-mark" style="color: #5dade2 "></span><span>&nbsp; Registration</span>
								</a>
							</li>
						<?php
							}
						?>
						<li>
							<a class="" href="employee_list.php">
								<span class="glyphicon glyphicon-list-alt" style="color: #5dade2 "></span><span>&nbsp; Employee List</span>
							</a>
						</li>

						<?php 
							if ($_SESSION["role"] == 1){
						?>
						<li>
							<a class="" href="user_information.php">
								<span class="glyphicon glyphicon-lock" style="color: #5dade2 "></span><span>&nbsp; User Authentication</span>
							</a>
						</li>
						<?php
							}
						?>

						<!--<li>
							<a class="" href="position.php">
								<span class="glyphicon glyphicon-briefcase" style="color: #5dade2 "></span><span>&nbsp; Position</span>
							</a>
						</li>-->
					</ul>
				</li>
				<?php
					}
				?>
				
				<?php
					$message_class = new Message;
				?>
				<li class="parent">
					<a data-toggle="collapse" href="#sub-item-1-messaging">
						<span class="glyphicon glyphicon-envelope"></span>&nbsp; Messaging <span class="pull-right glyphicon glyphicon glyphicon-menu-down" id=""></span>
					</a>
					<ul class="children collapse" id="sub-item-1-messaging">
						
						<li class="">
							<a class="" href="create_message.php">
								<span class="glyphicon glyphicon-edit" style="color: #5dade2 "></span><span>&nbsp; Create</span>
							</a>
						</li>
						<li>
							<a class="" href="message_inbox.php">
								<span class="glyphicon glyphicon-inbox" style="color: #5dade2 "></span><span>&nbsp; Inbox <span class="badge" style="background-color:#ff4136;border-radius:5px;font-size:11px;"><?php if ($message_class->unreadMessagesCount($id) == 0) { echo "";} else { echo $message_class->unreadMessagesCount($id); } ?></span></span>
							</a>
						</li>
					</ul>
				</li> 
				<!--<?php
					if ($_SESSION["role"] == 3) {
				?>
				<li class="parent">
					<a href="employee_list.php">
						<span class="glyphicon glyphicon-list-alt" style=""></span><span>&nbsp; Employee List</span>
					</a>
				</li>
				<?php
					}
				?> -->

				<?php
					if ($_SESSION["role"] != 4) {
				?>
				<li class="parent">
					<a href="atm_account_no.php">
						<span class="glyphicon glyphicon-credit-card" style=""></span><span>&nbsp; ATM Account No</span>
					</a>
				</li>
				<?php
					}
				?>

				<?php
					if ($_SESSION["role"] != 4) {
				?>
				<li class="parent">
					<a href="working_hours.php">
						<span class="glyphicon glyphicon-time" style=""></span><span>&nbsp; Working Hours</span>
					</a>
				</li>
				<?php
					}
				?>

				<?php
					if ($_SESSION["role"] == 2 || $_SESSION["role"] == 1) {
				?>
				<li class="parent">
					<a href="memorandum.php">
						<span class="glyphicon glyphicon-file" style=""></span><span>&nbsp; Memorandum</span>
					</a>
				</li>
				<?php
					}
				?>



				<?php
					if ($_SESSION["role"] == 1 || $_SESSION["role"] == 3) {
				?>
				<li class="parent">
					<a href="minimum_wage.php">
						<span class="glyphicon glyphicon-ruble"></span>&nbsp; Minimum Wage
					</a>
				</li>
				<?php
					}
				?>

				<?php
					if ($_SESSION["role"] == 1) {
				?>
				<li class="parent">
					<a href="biometrics_info.php">
						<span class="glyphicon glyphicon-registration-mark"></span>&nbsp; Biometrics Registration
					</a>
				</li>
				<?php
					}
				?>

				<?php
					if ($_SESSION["role"] != 4 ) {
				?>
				<li class="parent">
					<a href="department_list.php">
						<span class="glyphicon glyphicon-blackboard"></span>&nbsp; Department
					</a>
				</li>
				<?php
					}
				?>

				<?php
					if ($_SESSION["role"] != 4 ) {
				?>
				<li class="parent">
					<a href="position_list.php">
						<span class="glyphicon glyphicon-tasks" ></span>&nbsp; Position
					</a>
				</li>
				<?php
					}
				?>

				<?php
					if ($_SESSION["role"] == 1 || $_SESSION["role"] == 3) {
				?>
				<li class="parent">
					<a data-toggle="collapse" href="#sub-item-1-government">
						<span class="glyphicon glyphicon-asterisk"></span>&nbsp; Gov't Table<span class="pull-right glyphicon glyphicon glyphicon-menu-down" id=""></span>
					</a>
					<ul class="children collapse" id="sub-item-1-government">
						<li class="">
							<a class="" href="sssContributionTable.php">
								<img src="img/government images/SSS-Logo.jpg" class="government-logo" alt="SSS-Logo"/><span>&nbsp; SSS</span>
							</a>
						</li>
						<li>
							<a class="" href="birContributionTable.php">
								<img src="img/government images/bir-Logo.jpg" class="government-logo" alt="BIR-Logo"/><span>&nbsp; BIR</span>
							</a>
						</li>
						<li>
							<a class="" href="pagibigContributionTable.php">
								<img src="img/government images/pag-ibig-logo.jpg" class="government-logo" alt="Pag-big-Logo"/><span>&nbsp; Pag-ibig</span>
							</a>
						</li>
						<li>
							<a class="" href="philhealthContributionTable.php">
								<img src="img/government images/philhealth-logo.jpg" class="government-logo" alt="Philhealth-Logo"/><span>&nbsp; Philhealth</span>
							</a>
						</li>
					</ul>
				</li>
				<?php
					}
				?>


				<?php
					if ($_SESSION["role"] == 1) {
				?>
				<li class="parent">
					<a href="holiday.php">
						<span class="glyphicon glyphicon-equalizer"></span>&nbsp; Holiday
					</a>
				</li>
				<?php
					}
				?>


				<?php
					if ($_SESSION["role"] == 1 || $_SESSION["role"] == 2) {
				?>
				<li class="parent">
					<a href="event_list.php">
						<span class="glyphicon glyphicon-calendar"></span>&nbsp; Events
					</a>
				</li>
				<?php
					}
				?>


				<?php 
					if ($_SESSION["role"] == 1 || $_SESSION["role"] == 2 || $_SESSION["role"] == 3 || ($_SESSION["role"] == 4 && $row->bio_id != 0)) {

				?>		
				<li class="parent">
					<a data-toggle="collapse" href="#sub-item-1-attendance">
						<span class="glyphicon glyphicon-time"></span>&nbsp; Attendance <span class="pull-right glyphicon glyphicon glyphicon-menu-down" id=""></span>
					</a>
					<ul class="children collapse in" id="sub-item-1-attendance">

						<?php
							if ($_SESSION["role"] == 1) {				
						?>
						<li class="">
							<a class="" href="attendance_upload.php">
								<span class="glyphicon glyphicon-upload" style="color: #5dade2 "></span><span>&nbsp; Upload Attendance</span>
							</a>
						</li>
						<?php
							}
						?>

						<?php
							if ($_SESSION["role"] == 2 || $_SESSION["role"] == 3 || ($_SESSION["role"] == 4 && $row->bio_id != 0)) {				
						?>
						<li>
							<a class="" href="view_attendance.php">
								<span class="glyphicon glyphicon-eye-open" style="color: #5dade2 "></span><span>&nbsp; View Attendance</span>
							</a>
						</li>
						<?php
							}
						?>

						<?php
							if ($_SESSION["role"] != 4) {				
						?>
						<li>
							<a class="" href="attendance_list.php">
								<span class="glyphicon glyphicon-list-alt" style="color:#5dade2"></span><span>&nbsp; Attendance List</span>
							</a>
						</li>
						<?php
							}
						?>

						<?php
							if ($emp_info->alreadyHead($id) != 0) {				
						?>
						<li>
							<a class="" href="sub_attendance_list.php">
								<span class="glyphicon glyphicon-list-alt" style="color: #5dade2 "></span><span>&nbsp; Sub. Attendance List</span>
							</a>
						</li>
						<?php
							}
						?>

						<?php
							//echo $emp_info->alreadyHead($id);
							if ($_SESSION["role"] == 1 || $_SESSION["role"] == 2 || $emp_info->alreadyHead($id) != 0) {				
						?>
						<li>
							<a class="" href="file_ot_list.php">
								<span class="glyphicon glyphicon-file" style="color: #5dade2 "></span><span>&nbsp; File Overtime <span class="badge" style="background-color:#ff4136;border-radius:5px;font-size:11px;"><?php if ($pending_file_ot_count != 0) { echo $pending_file_ot_count;} ?></span></span>
							</a>
						</li>
						<?php
							}
						?>

						<?php
							if ($_SESSION["role"] != 4 || $emp_info->alreadyHead($id) != 0) {				
						?>
						<li>
							<a class="" href="ot_list_approve.php">
								<span class="glyphicon glyphicon-file" style="color: #5dade2 "></span><span>&nbsp; OT List Approved</span>
							</a>
						</li>
						<?php
							}
						?>


						<?php
							if ($_SESSION["role"] == 1 || $_SESSION["role"] == 2 || $emp_info->alreadyHead($id) != 0) {				
						?>
						<li>
							<a class="" href="attendance_notif_list.php">
								<span class="glyphicon glyphicon-tags" style="color: #5dade2 "></span><span>&nbsp; Attendance Updates <span class="badge" style="background-color:#ff4136;border-radius:5px;font-size:11px;"><?php if ($pending_file_attendance_request_count != 0) { echo $pending_file_attendance_request_count;} ?></span></span>
							</a>
						</li>
						<?php
							}
						?>

						<?php
							if ($_SESSION["role"] == 1 || $_SESSION["role"] ==3) {
						?>
						<li>
							<a class="active-sub-menu" href="add_attendance.php">
								<span class="glyphicon glyphicon-plus" style="color: #5dade2 "></span><span>&nbsp; Add Attendance</span>
							</a>
						</li>
						<?php
							}
						?>

					</ul>
				</li>
				<?php
					}
				?>

				<!--
				<?php 
					if ($_SESSION["role"] == 4 && $row->bio_id != 0) {

				?>

					<li class="parent">
						<a href="view_attendance.php">
							<span class="glyphicon glyphicon-eye-open"></span>&nbsp; View Attendance
						</a>
					</li>
				<?php
					}
				?>

				-->

				<?php 
					if ($_SESSION["role"] == 2 || $_SESSION["role"] == 3 || $_SESSION["role"] == 1 || $emp_info->alreadyHead($id) != 0) {

				?>					
				<li class="parent">
					<a href="leave.php">
						<span class="glyphicon glyphicon-leaf"></span>&nbsp; Leaves <span class="badge" style="background-color:#ff4136;border-radius:5px;font-size:11px;"><?php if ($leave_class->getFileLeavePendingCount($_SESSION["role"],$_SESSION["id"]) != 0) { echo $leave_class->getFileLeavePendingCount($_SESSION["role"],$_SESSION["id"]);} ?></span>
					</a>
				</li>
				<?php
					}
				?>


				<?php 
					//if ($_SESSION["role"] == 3 || $_SESSION["role"] == 1) {

				?>
					<li class="parent">
						<a data-toggle="collapse" href="#sub-item-1-loans">
							<span class="glyphicon glyphicon-modal-window"></span>&nbsp; Loans <span class="pull-right glyphicon glyphicon glyphicon-menu-down" id=""></span> 
						</a>

						<ul class="children collapse" id="sub-item-1-loans">
							<li class="parent">
								<a href="pagibig_loan.php">
									<!--<span class="glyphicon glyphicon-modal-window"></span>&nbsp; Pag-ibig Loan -->
									<img src="img/government images/pag-ibig-logo.jpg" class="government-logo" alt="Pag-big-Logo"/><span>&nbsp; Pag-ibig Loan</span>
								</a>
							</li>

							<li class="parent">
								<a href="sss_loan.php">
									<!--<span class="glyphicon glyphicon-modal-window"></span>&nbsp; SSS Loan -->
									<img src="img/government images/SSS-Logo.jpg" class="government-logo" alt="SSS-Logo"/><span>&nbsp; SSS Loan</span>
								</a>
							</li>

							<li class="parent">
								<a href="salary_loan.php">
									<span class="glyphicon glyphicon-ruble" style="color: #5dade2 "></span>&nbsp; Salary Loan <span class="badge" style="background-color:#ff4136;border-radius:5px;font-size:11px;"><?php if ($_SESSION["role"] == 1 || $_SESSION["role"] == 3) { if ($salary_loan_class->getFileSalaryLoanPendingCount() == 0) {} else { echo $salary_loan_class->getFileSalaryLoanPendingCount();}} ?></span>
								</a>
							</li>

						</ul>
					</li>
				<?php
					//}
				?>


				<?php
					if ($_SESSION["role"] == 3 || $_SESSION["role"] == 1) {
				?>
				<li class="parent">
					<a data-toggle="collapse" href="#sub-item-1-payroll">
						<span class="glyphicon glyphicon-ruble"></span>&nbsp; Payroll <span class="pull-right glyphicon glyphicon glyphicon-menu-down" id=""></span> 
					</a>

					<ul class="children collapse" id="sub-item-1-payroll">
						<li class="">
							<a class="" href="generate_payroll.php">
								<span class="glyphicon glyphicon-usd" style="color: #5dade2 "></span><span>&nbsp; Create Salary</span>
							</a>
						</li>

						<li>
							<a class="" href="payroll_information.php">
								<span class="glyphicon glyphicon-eye-open" style="color: #5dade2 "></span><span>&nbsp; View Payroll Info</span>
							</a>
						</li>

						<!--
						<li>
							<a class="" href="send_payroll.php">
								<span class="glyphicon glyphicon-send" style="color: #5dade2 "></span><span>&nbsp; Send Payroll Reports</span>
							</a>
						</li>
						-->




						<li>
							<a class="" href="my_payslip.php">
								<span class="glyphicon glyphicon-print" style="color: #5dade2 "></span><span>&nbsp; My Payslip</span>
							</a>
						</li>

					</ul>
				</li>
				<?php
					}
				?>


				<!--
				<?php
					if ($_SESSION["role"] == 1) {
				?>
				<li class="parent">
					<a href="generate_payroll.php">
						<span class="glyphicon glyphicon-usd"></span>&nbsp; Create Salary
					</a>
				</li>

				<?php
					}
				?>


				<?php
					if ($_SESSION["role"] == 1) {
				?>
				<li class="parent">
					<a href="payroll_information.php">
						<span class="glyphicon glyphicon-eye-open"></span>&nbsp; View Payroll Info
					</a>
				</li>

				<?php
					}
				?>
				
				-->

				<?php
					//if ($_SESSION["role"] == 4 || $_SESSION["role"] == 2 ) {
					if ($_SESSION["role"] != 3 && $_SESSION["role"] != 1) {
				?>
				<li class="parent">
					<a href="my_payslip.php" style="">
						<span class="glyphicon glyphicon-print"></span>&nbsp; My Payslip
					</a>
				</li>
				<?php
					}
				?>
				

				<?php
					//if ($_SESSION["role"] == 1 || $_SESSION["role"] == 3) {
				?>
				<li class="parent">
					<a href="simkimban.php">
						<span class="glyphicon glyphicon-share"></span>&nbsp; SIMKIMBAN
					</a>
				</li>
				<?php
					//}
				?>

				<?php
					//if ($_SESSION["role"] == 1 || $_SESSION["role"] == 3) {
				?>
				<li class="parent">
					<a href="cashbond.php">
						<span class="glyphicon glyphicon-piggy-bank"></span>&nbsp; Cash Bond <span class="badge" style="background-color:#ff4136;border-radius:5px;font-size:11px;"><?php if ($_SESSION["role"] == 1 || $_SESSION["role"] == 3) { if ($cashbond_class->getCountPendingCashbondWithdrawal() == 0) {} else { echo $cashbond_class->getCountPendingCashbondWithdrawal(); }}?></span>
					</a>
				</li>
				<?php
				//	}
				?>

				<?php
					if ($_SESSION["role"] == 1 || $_SESSION["role"] == 3) {
				?>
				<li class="parent">
					<a href="year_total_deduction.php">
						<span class="glyphicon glyphicon-ruble"></span>&nbsp; Year Total Deduction
					</a>
				</li>
				<?php
					}
				?>
				
				<?php
					if ($_SESSION["role"] == 1 || $_SESSION["role"] == 3 || $_SESSION["id"] == 47) {
				?>
				<li class="parent">
					<a href="employee_salary.php">
						<span class="glyphicon glyphicon-ruble"></span>&nbsp; Salary Information
					</a>
				</li>
				<?php
					}
				?>

				<!--
				<?php
					if ($_SESSION["role"] == 3 || $_SESSION["role"] == 1) {
				?>
				<li class="parent">
					<a href="updatePayroll.php">
						<span class="glyphicon glyphicon-edit"></span>&nbsp; Update Payroll Info
					</a>
				</li>

				<?php
					}
				?>
				-->

				

			
				<?php
					if ($_SESSION["role"] == 1) {
				?>
				<li class="parent">
					<a href="audit_trail.php">
						<span class="glyphicon glyphicon-edit"></span>&nbsp; Audit Trail
					</a>
				</li> 
				<!--
				<li class="parent">
					<a href="payroll_approval.php">
						<span class="glyphicon glyphicon-road"></span>&nbsp; Payroll Approval
					</a>
				</li> 
				-->
				<?php
					}
				?>

				<?php
					if ($_SESSION["role"] == 1 || $_SESSION["role"] == 3) {
				?>
				<li class="parent">
					<a data-toggle="collapse" href="#sub-item-1-adjustment-report">
						<span class="glyphicon glyphicon-adjust"></span>&nbsp; Adjustment Reports<span class="pull-right glyphicon glyphicon glyphicon-menu-down" id=""></span> 
					</a>

					<ul class="children collapse" id="sub-item-1-adjustment-report">
						<li class="">
							<a class="" href="loan_adjustment_reports.php">
								<span class="glyphicon glyphicon-print" style="color: #5dade2 "></span><span>&nbsp; Loan Adjustment</span>
							</a>
						</li>


						<li>
							<a class="" href="simkimban_adjustment_reports.php">
								<span class="glyphicon glyphicon-print" style="color: #5dade2 "></span><span>&nbsp; SIMKIMBAN Adjustment</span>
							</a>
						</li>

					</ul>
				</li>
				<?php
					}
				?>

				<?php
					if ($_SESSION["role"] == 1 || $_SESSION["role"] == 3) {
				?>
				<li class="parent">
					<a data-toggle="collapse" href="#sub-item-1-payroll-report">
						<span class="glyphicon glyphicon-ruble"></span>&nbsp; Payroll Reports<span class="pull-right glyphicon glyphicon glyphicon-menu-down" id=""></span> 
					</a>

					<ul class="children collapse" id="sub-item-1-payroll-report">
						<li class="">
							<a class="" href="payroll_reports.php">
								<span class="glyphicon glyphicon-print" style="color: #5dade2 "></span><span>&nbsp; Payroll</span>
							</a>
						</li>


						<li>
							<a class="" href="adjustment_reports.php">
								<span class="glyphicon glyphicon-print" style="color: #5dade2 "></span><span>&nbsp; Adjustment</span>
							</a>
						</li>

					</ul>
				</li>
				<?php
					}
				?>

				<!-- for margin bottom serve as -->
				<li class="parent">
					<a>
					
					</a>
				</li>

				<li class="parent">
					<a>
					
					</a>
				</li>
			</ul>
		</div> <!-- end of sidebar -->

		<div class="content">

			<!-- for menu directory at the top -->
			<div class="container-fluid">
				<div class="row" style="border-bottom:1px solid #BDBDBD;">
					<ol class="breadcrumb">
						<li><a href="MainForm.php">&nbsp;&nbsp;<span class="glyphicon glyphicon-home"></span></a></li>
						<li class="active" id="home_id">Add Attendance</li> 
					</ol>
				</div>
			</div>

			<!-- for body -->
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-12 content-div">
						<div class="thumbnail" style="border:1px solid #BDBDBD;">
							<div class="caption">
								<i><b>Note:</b> To view the <b>list of attendance</b>, please click <a href="attendance_list.php" target="_blank">here</a>.</i><br/></br>
								<fieldset>


									<legend style="color:#357ca5;border-bottom:1px solid #BDBDBD"><span class="glyphicon glyphicon-list-alt"></span> Employee List with no actual Registered BIOMETRICS ID
										
									</legend>
									
									<div class="col-sm-8 col-sm-offset-2">
										
										<table id="employee_list" class="table table-bordered table-hover table-striped" style="border:1px solid #BDBDBD;">
											<thead>
												<tr>
													<th><span class="glyphicon glyphicon-user" style="color:#186a3b"></span> Employee Name</th>
													<th class="no-sort"><span class="glyphicon glyphicon-wrench" style="color:#186a3b"></span> Action</th>
												</tr>
											</thead>
											<tbody>	
												<?php
													$emp_info->getAllEmpIdWithoutBioId();
													//$department_class = new Department;
													//$department_class->getDepartmentToTable();
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
			$version_class = new SystemVersion	;
			$version = $version_class->getLatestVersion();
		?>	
		<div class="footer">
			<img src="img/logo/lloyds logo.png" style="width:15px;"/> <strong><small>Copyright <span class="glyphicon glyphicon-copyright-mark"></span> <?php echo $version; ?></small></strong>
		</div>	


		<!-- FOR SUCCESS MODAL -->
		<div id="attAttendanceModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color:#1d8348;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title" style="color:#fff"><span class='glyphicon glyphicon-plus' style='color:#fff'></span>&nbsp;Add Attendance</h5>
					</div> 
					<div class="modal-body" id="add_attendance_body">

						
						<div class="container-fluid">
							<div><b>Employee Name:</b> <span id="emp_name"></span></div>
							<form id="form_add_attendance" action="" method="post">
							<center>
								
								<?php
									$cut_off_class = new CutOff;
									$cut_off_class->generateCutOffAttendance();
								?>
								<br/>
							</center>
							<button type="submit" class="btn btn-success btn-sm pull-right">Save</button>
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