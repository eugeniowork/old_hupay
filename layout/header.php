<?php
	
	//session_start();
	include "class/emp_loan_class.php";

?>


<link rel="stylesheet" href="css/color.css">
<script>
	$(document).ready(function(){
		//alert("HELLO WORLD!");
		//alert($("title").html());
		
		if ($("title").html() == "My Profile"){

			$(".change-profile-div").attr("style","");
		}

	});

</script>

<!-- for nav menu -->
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
					<div class="change-profile-div" title="Change Profile Picture" style="display:none">
						<span class="glyphicon glyphicon-camera" style="color:#000"></span> Change Profile

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
					<a href="working_hours_days.php">
						<span class="glyphicon glyphicon-time" style=""></span><span>&nbsp; Working Hours & Days</span>
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
					<ul class="children collapse" id="sub-item-1-attendance">

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
								<span class="glyphicon glyphicon-list-alt" style="color: #5dade2 "></span><span>&nbsp; Attendance List</span>
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
								if ($id != 153){		
						?>
						<li>
							<a class="" href="file_ot_list.php">
								<span class="glyphicon glyphicon-file" style="color: #5dade2 "></span><span>&nbsp; File Overtime <span class="badge" style="background-color:#ff4136;border-radius:5px;font-size:11px;"><?php if ($pending_file_ot_count != 0) { echo $pending_file_ot_count;} ?></span></span>
							</a>
						</li>
						<?php
								}
							}
						?>

						<?php
							if ($_SESSION["role"] != 4 || $emp_info->alreadyHead($id) != 0) {				
								if ($id != 153){
						?>
						<li>
							<a class="" href="ot_list_approve.php">
								<span class="glyphicon glyphicon-file" style="color: #5dade2 "></span><span>&nbsp; OT List Approved</span>
							</a>
						</li>
						<?php
								}
							}
						?>


						<?php
							if ($_SESSION["role"] == 1 || $_SESSION["role"] == 2 || $emp_info->alreadyHead($id) != 0) {
								if ($id != 153){				
						?>
						<li>
							<a class="" href="attendance_notif_list.php">
								<span class="glyphicon glyphicon-tags" style="color: #5dade2 "></span><span>&nbsp; Attendance Updates <span class="badge" style="background-color:#ff4136;border-radius:5px;font-size:11px;"><?php if ($pending_file_attendance_request_count != 0) { echo $pending_file_attendance_request_count;} ?></span></span>
							</a>
						</li>
						<?php
								}
							}
						?>

						<?php
							if ($_SESSION["role"] == 1 || $_SESSION["role"] ==3) {
						?>
						<li>
							<a class="" href="add_attendance.php">
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
						if ($id != 153){

				?>					
				<li class="parent">
					

					<a data-toggle="collapse" href="#sub-item-1-leave">
						<span class="glyphicon glyphicon-leaf"></span>&nbsp; Leaves <span class="pull-right glyphicon glyphicon glyphicon-menu-down" id=""></span>
					</a>

					<ul class="children collapse" id="sub-item-1-leave">
						<li class="">
							<a class="" href="leave.php">
								<span class="glyphicon glyphicon-file" style="color: #5dade2 "></span><span>&nbsp;Leave<span class="badge" style="background-color:#ff4136;border-radius:5px;font-size:11px;"><?php if ($leave_class->getFileLeavePendingCount($_SESSION["role"],$_SESSION["id"]) != 0) { echo $leave_class->getFileLeavePendingCount($_SESSION["role"],$_SESSION["id"]);} ?></span>
							</a>
						</li>
						<?php
							if ($_SESSION["role"] == 1){
						?>
						<li>
							<a class="" href="leave_maintenance.php">
								<span class="glyphicon glyphicon-wrench" style="color: #5dade2"></span><span>&nbsp; Leave Maintenance</span>
							</a>
						</li>
						<?php
							}
						?>
					</ul>
				</li>
				<?php
						}
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
									<span class="glyphicon glyphicon-ruble" style="color: #5dade2 "></span>&nbsp; Salary Loan <span class="badge" style="background-color:#ff4136;border-radius:5px;font-size:11px;"><?php if ($_SESSION["role"] == 1) { if ($salary_loan_class->getFileSalaryLoanPendingCount() == 0) {} else { echo $salary_loan_class->getFileSalaryLoanPendingCount();}} ?></span>
								</a>
							</li>



							<?php

								$emp_loan_class = new EmployeeLoan;

							?>


							<li class="parent">
								<a href="file_loan.php">
									<!--<span class="glyphicon glyphicon-modal-window"></span>&nbsp; SSS Loan -->
									<span class="glyphicon glyphicon-ruble" style="color: #5dade2 "></span>&nbsp; File Loan</span> <span class="badge" style="background-color:#ff4136;border-radius:5px;font-size:11px;"><?php 

										if ($_SESSION["role"] == 1 || $_SESSION["role"] == 2) { if ($emp_loan_class->getPendingFileLoanCount() == 0) {} else { echo $emp_loan_class->getPendingFileLoanCount();}}

										if ($_SESSION["role"] == 3) { if ($emp_loan_class->getProcessFileLoanCount() == 0) {} else { echo $emp_loan_class->getProcessFileLoanCount();}}

										 ?></span>
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
						<span class="glyphicon glyphicon-piggy-bank"></span>&nbsp; Cash Bond <span class="badge" style="background-color:#ff4136;border-radius:5px;font-size:11px;"><?php if ($_SESSION["role"] == 1) { if ($cashbond_class->getCountPendingCashbondWithdrawal() == 0) {} else { echo $cashbond_class->getCountPendingCashbondWithdrawal(); }}?></span>
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
					if ($_SESSION["role"] == 1 || $_SESSION["role"] == 3 || ($_SESSION["role"] == 2 && $_SESSION["id"] != 153 && $_SESSION["id"] != 21) || $_SESSION["id"] == 47 || $_SESSION["id"] == 44) {
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

						<?php
							if ($_SESSION["role"] != 2 && $_SESSION["id"] != 47 && $_SESSION["id"] != 44) {
						?>
						<li>
							<a class="" href="adjustment_reports.php">
								<span class="glyphicon glyphicon-print" style="color: #5dade2 "></span><span>&nbsp; Adjustment</span>
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