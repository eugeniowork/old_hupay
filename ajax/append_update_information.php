<?php
session_start();
include "../class/connect.php";
include "../class/emp_information.php";
include "../class/role.php";
include "../class/department.php";
include "../class/position_class.php";
include "../class/date.php";
include "../class/dependent.php";
include "../class/allowance_class.php";
include "../class/working_hours_class.php";
include "../class/company_class.php";
include "../class/working_days_class.php";


$emp_id = $_SESSION["update_emp_id"]; 
$emp_info_class = new EmployeeInformation;
$date_class = new date;
$working_days_class = new WorkingDays;
$row = $emp_info_class->getEmpInfoByRow($emp_id);

if ($_GET["update_info"] == "Basic Information"){

?>

	<form class="form-horizontal" action="" method="post">
		<fieldset>
				<legend style="border-bottom:1px solid #808b96"><?php echo $_GET["update_info"]; ?></legend>
				<div class="col-sm-10 col-sm-offset-1">
					<div class="form-group">
						<div class="col-sm-6">
							<span class="glyphicon glyphicon-user" style="color:#2E86C1;"></span> <label class="control-label">Lastname &nbsp;<span class="red-asterisk">*</span></label>
							<input type="text" id="txt_only" class="form-control" placeholder="Enter Lastname" value="<?php echo $row->Lastname; ?>" name="update_lastname" required="required"/>
						</div>
						<div class="col-sm-6">
							<span class="glyphicon glyphicon-user" style="color:#2E86C1;"></span> <label class="control-label">Firstname &nbsp;<span class="red-asterisk">*</span></label>
							<input type="text" id="txt_only" class="form-control" placeholder="Enter Firstname" value="<?php echo $row->Firstname; ?>" name="update_firstname" required="required"/>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-6">
							<span class="glyphicon glyphicon-user" style="color:#2E86C1;"></span> <label class="control-label">Middlename</label>
							<input type="text" id="txt_only" class="form-control" placeholder="Enter Middlename" value="<?php echo $row->Middlename; ?>" name="update_middlename"/>
						</div>
						<div class="col-sm-6">
							<span class="glyphicon glyphicon-user" style="color:#2E86C1;"></span> <label class="control-label">Civil Status &nbsp;<span class="red-asterisk">*</span></label>
							<select class="form-control" name="update_civil_stat" required="required">
								<option value=""></option>
								<option value="Single" <?php if ($row->CivilStatus == "Single") { echo "selected=selected";} ?> >Single</option>
								<option value="Married" <?php if ($row->CivilStatus == "Married") { echo "selected=selected";} ?> >Married</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12">
							<span class="glyphicon glyphicon-home" style="color:#2E86C1;"></span> <label class="control-label">Address &nbsp;<span class="red-asterisk">*</span></label>
							<textarea class="form-control" placeholder="Enter Address" name="update_address" required="required"><?php echo $row->Address; ?></textarea>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-6">
							<span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> <label class="control-label">Birthdate &nbsp;<span class="red-asterisk">*</span></label>
							<input type="text" class="form-control" data-toggle="tooltip" data-placement="left" title="Format:mm/dd/yyyy ex. 01/01/1990" autocomplete="off" id="date_only" value="<?php echo $date_class->dateDefault($row->Birthdate); ?>" placeholder="Enter Birthdate" name="update_birthdate" required="required"/>
						</div>
						<div class="col-sm-6">
							<span class="glyphicon glyphicon-user" style="color:#2E86C1;"></span> <label class="control-label">Gender &nbsp;<span class="red-asterisk">*</span></label>
							<select class="form-control" name="update_gender" required="required">
								<option value=""></option>
								<option value="Male" <?php if ($row->Gender == "Male") { echo "selected=selected";} ?> >Male</option>
								<option value="Female" <?php if ($row->Gender == "Female") { echo "selected=selected";} ?>>Female</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-6">
							<span class="glyphicon glyphicon-phone-alt" style="color:#2E86C1;"></span> <label class="control-label">Contact No</label>
							<input type="text" id="number_only" placeholder="Enter Contact No" value="<?php echo $row->ContactNo; ?>" class="form-control" name="update_contact_no"/>
						</div>
						<div class="col-sm-6">
							<span class="glyphicon glyphicon-envelope" style="color:#2E86C1;"></span> <label class="control-label">Email Address</label>
							<input type="email" id="email_address_txt" placeholder="Enter Email Address" value="<?php echo $row->EmailAddress; ?>" class="form-control" name="update_email_add"/>
						</div>
					</div>
					<div class="form-group">
						<div style="text-align:center;">
							<input type="button" id="back_update_btn" value="&nbsp;&nbsp;&nbsp;Back&nbsp;&nbsp;&nbsp;" class="btn btn-success"/>
							<input type="button" value="Update" id="update_basic_info_btn" class="btn btn-success"/>
						</div>
					</div>

					<div class="form-group">
						<div class="col-sm-12">
							<!-- for message purpose -->
							<strong id="update_message">&nbsp;</strong>
						</div>
					</div>
				</div>
					
		</fieldset>
	</form>


<?php

} // end of if
else if ($_GET["update_info"] == "Company Information"){

?>
	<form class="form-horizontal" action="php script/update_company_information.php" method="post">
		<fieldset>
			<legend style="border-bottom:1px solid #808b96"><?php echo $_GET["update_info"]; ?></legend>
			<div class="col-sm-10 col-sm-offset-1">
				<div class="form-group">
					<div class="col-sm-6">
						<span class="glyphicon glyphicon-blackboard" style="color:#2E86C1;"></span> <label class="control-label">Department &nbsp;<span class="red-asterisk">*</span></label>
						<select class="form-control" name="update_department" required="required">
							<option value=""></option>
							<?php
								$department_class = new Department;
								$dept_id = $row->dept_id;
								$department_class->updateEmpInfo($dept_id);
							?>
						</select>
					</div>
					<div class="col-sm-6">
						<span class="glyphicon glyphicon-tasks" style="color:#2E86C1;"></span> <label class="control-label">Position &nbsp;<span class="red-asterisk">*</span></label>
						<select class="form-control" name="update_position" required="required">
							<option value=""></option>
							<?php
								$position_class = new Position;
								$position_id = $row->position_id;
								$position_class->updateEmpInfo($dept_id,$position_id);
							?>
						</select>
					</div>
					
				</div>

				<div class="form-group">
					<div class="col-sm-6">
						<span class="glyphicon glyphicon-ruble" style="color:#2E86C1;"></span> <label class="control-label">Salary &nbsp;<span class="red-asterisk">*</span></label>
						<input type="text" id="number_only" placeholder="Enter Salary" value="<?php echo $row->Salary; ?>" class="form-control" name="update_salary" required="required"/> 
					</div>

					<div class="col-sm-6">
						<span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> <label class="control-label">Date Hired/ Promote &nbsp;<span class="red-asterisk">*</span></label>
						<input type="text" data-toggle="tooltip" data-placement="bottom" title="Format:mm/dd/yyyy ex. 01/01/1990" autocomplete="off" id="date_only" placeholder="Enter Date Hired" value="<?php echo $date_class->dateDefault($row->DateHired); ?>" class="form-control" name="update_dateHired" required="required"/>
					</div>
				</div>


				<div class="form-group">
					<div class="col-sm-6">
						<span class="glyphicon glyphicon-time" style="color:#2E86C1;"></span> <label class="control-label">Working Hours &nbsp;<span class="red-asterisk">*</span></label>
						<select class="form-control" name="update_workingHours" required="required">
							<option value=""></option>
							<?php
								$working_hours_class = new WorkingHours;
								$working_hours_class->getWorkingHoursToUpdate($row->working_hours_id);								
							?>
						</select>
					</div>

					<div class="col-sm-6">						
						<label class="control-label"><span class="glyphicon glyphicon-user" style="color:#2E86C1;"></span> Immediate Head's Name&nbsp;</label>
						<input type="text" class="form-control typeahead tt-query" value="<?php if ($row->head_emp_id != 0) { echo $emp_info_class->geyHeadsNameByHeadEmpId($row->head_emp_id);} ?>" autocomplete="off" style="" name="update_headName" id="txt_only" />
					</div>

				</div>

				<div class="form-group">
					<div class="col-sm-6">
						<label class="control-label"><span class="glyphicon glyphicon-blackboard" style="color:#2E86C1;"></span> Company&nbsp;</label>
						<select name="update_company_id" class="form-control">
							<option></option>
							<?php
								$company_class = new Company;
								$company_class->selectCompanyToDropdown($row->company_id);
							?>
						</select>
					</div>
					<div class="col-sm-6">
						<label class="control-label"><span class="glyphicon glyphicon-blackboard" style="color:#2E86C1;"></span> Employment Type&nbsp;</label>
						<select name="update_employment_type" class="form-control">
							<option></option>
							<option value="Provisional" <?php  if ($row->employment_type == 0) { echo "selected=selected"; } ?> >Provisional</option>
							<option value="Regular" <?php  if ($row->employment_type == 1) { echo "selected=selected";} ?> >Regular</option>
						</select>
					</div>
				</div>


				<div class="form-group">
					<div class="col-sm-6">
						<span class="glyphicon glyphicon-time" style="color:#2E86C1;"></span> <label class="control-label">Working Days &nbsp;<span class="red-asterisk">*</span></label>
						<select class="form-control" name="update_workingDays" required="required">
							<option value=""></option>
							<?php
								$working_days_class = new WorkingDays;
								$working_days_class->getWorkingDaysToUpdate($row->working_days_id);								
							?>
						</select>
					</div>
				</div>


				<div class="form-group">
					<div style="text-align:center;">
						<input type="button" id="back_update_btn" value="&nbsp;&nbsp;&nbsp;Back&nbsp;&nbsp;&nbsp;" class="btn btn-success"/>
						<a href="#" id="update_company_info_btn" class="btn btn-success">Update</a>					
						<label class="control-label">To update and save to <b>LFC Employment History</b> Click <a href="#" id="update_company_info_btn">here</a></label>
					</div>

				</div>

				<div class="form-group">
					<div class="col-sm-12">
						<!-- for message purpose -->
						<strong id="update_message">&nbsp;</strong>
					</div>
				</div>
			</div>

		</fieldset>
	</form>
	
<?php
} // end of else if for company information

else if ($_GET["update_info"] == "Government Information"){

?>
	<form class="form-horizontal" action="php script/update_govt_information.php" method="post">
		<fieldset>
			<legend style="border-bottom:1px solid #808b96"><?php echo $_GET["update_info"]; ?></legend>
			<div class="col-sm-10 col-sm-offset-1">
				<div class="form-group">
					<div class="col-sm-6">
					    <label class="control-label"><img src="img/government images/SSS-Logo.jpg" class="government-logo" alt="SSS-Logo"/><span>&nbsp; SSS No.</label>
						<input type="text" placeholder="Enter SSS No." id="number_only" value="<?php echo $row->SSS_No; ?>" class="form-control" name="update_sssNo"/>
					</div>
					<div class="col-sm-6">
						<label class="control-label"><img src="img/government images/pag-ibig-logo.jpg" class="government-logo" alt="Pag-big-Logo"/><span>&nbsp; Pag-ibig No.</label>
						<input type="text" placeholder="Enter Pag-ibig No." id="number_only" value="<?php echo $row->PagibigNo; ?>" class="form-control" name="update_pagibigNo"	/>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-6">						
						<label class="control-label"><img src="img/government images/bir-Logo.jpg" class="government-logo" alt="BIR-Logo"/><span>&nbsp; Tin No.</label>
						<input type="text" name="update_tinNo" id="number_only" value="<?php echo $row->TinNo; ?>" class="form-control" placeholder="Enter Tin No.">
					</div>
					<div class="col-sm-6">
						<label class="control-label"><img src="img/government images/philhealth-logo.jpg" class="government-logo" alt="Philhealth-Logo"/><span>&nbsp; Philhealth No.</label>
						<input type="text" name="update_philhealthNo" id="number_only" value="<?php echo $row->PhilhealthNo; ?>" class="form-control" placeholder="Enter Philhealth No.">
					</div>
				</div>

				<div class="form-group">
					<div style="text-align:center;">
						<input type="button" id="back_update_btn" value="&nbsp;&nbsp;&nbsp;Back&nbsp;&nbsp;&nbsp;" class="btn btn-success"/>
						<input type="button" id="update_govt_info_btn" value="Update" class="btn btn-success"/>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-12">
						<!-- for message purpose -->
						<strong id="update_message">&nbsp;</strong>
					</div>
				</div>

			</div>
		</fieldset>
	</form>
<?php
	
} // end of else if for government information

else if ($_GET["update_info"] == "School Information"){

?>
<form class="form-horizontal" action="" method="post" id="form_school_info">
	<fieldset>
			<legend style="border-bottom:1px solid #808b96"><?php echo $_GET["update_info"]; ?></legend>
			<div class="col-sm-10 col-sm-offset-1">
				


				<?php
					$highest_education_attain = $row->highest_educational_attain;


					if ($highest_education_attain == ""){
				?>
					<div class="form-group">	
						<div class="col-sm-6">						
							<label class="control-label"><span class="glyphicon glyphicon-education" style="color:#2E86C1;"></span>&nbsp;Highest Educational Attain&nbsp;<span class="red-asterisk">*</span></label>
							<select class="form-control" name="update_education_attain" required="required">
								<option value="">Select Highest Educational Attain</option>
								<option value="Secondary">Secondary</option>
								<option value="Tertiary" >Tertiary</option>
							</select>
						</div>

					</div>

					<div id="append_tertiary_education">

					</div>
				<?php
					}
					else {
						echo '<div id="append_tertiary_education">';
						$emp_info_class->getSchoolInfoByUserId($emp_id);
						echo '</div>';
					}

				?>

				

				<div class="form-group">
					<div style="text-align:center;">
						<input type="button" id="back_update_btn" value="&nbsp;&nbsp;&nbsp;Back&nbsp;&nbsp;&nbsp;" class="btn btn-success"/>
						<input type="submit" id="update_school_info_btn" value="Update" class="btn btn-success"/>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12">
						<!-- for message purpose -->
						<strong id="update_message">&nbsp;</strong>
					</div>
				</div>
			</div>
				
	</fieldset>
</form>

<?php
}
else if ($_GET["update_info"] == "Work Experience"){
?>

<form class="form-horizontal" action="" method="post" id="form_work_xp">
	<fieldset>
			<legend style="border-bottom:1px solid #808b96"><?php echo $_GET["update_info"]; ?></legend>
			<div class="col-sm-10 col-sm-offset-1">
				<div id="append_work_experience">

				<?php
					$emp_info_class->getWorkExperienceById($emp_id);
				?>
				</div>

				<div class="form-group">
					<div style="text-align:center;">
						<input type="button" id="back_update_btn" value="&nbsp;&nbsp;&nbsp;Back&nbsp;&nbsp;&nbsp;" class="btn btn-success"/>
						<input type="submit" id="update_work_xp_btn" value="Update" class="btn btn-success"/>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12">
						<!-- for message purpose -->
						<strong id="update_message">&nbsp;</strong>
					</div>
				</div>
			</div>
				
	</fieldset>
</form>
<?php
} // end of school information

else if ($_GET["update_info"] == "Account Information"){

?>
	<form class="form-horizontal" action="" method="post">
		<fieldset>
			<legend style="border-bottom:1px solid #808b96"><?php echo $_GET["update_info"]; ?></legend>
			<div class="col-sm-10 col-sm-offset-1">
				<div class="form-group">
					<div class="col-sm-6 col-sm-offset-3">
							<span class="glyphicon glyphicon-user" style="color:#2E86C1;"></span> <label class="control-label">Role &nbsp;<span class="red-asterisk">*</span></label>
							<select class="form-control" name="update_role" required="required">
							<option value=""></option>
							<?php																
								$row_class = new Role;
								$role_id = $row->role_id;
								$row_class->updateEmpInfo($role_id);
							?>
							</select>
						</div>
				</div>
				<div class="form-group">
					<div style="text-align:center;">
						<input type="button" id="back_update_btn" value="&nbsp;&nbsp;&nbsp;Back&nbsp;&nbsp;&nbsp;" class="btn btn-success"/>
						<input type="button" id="update_account_info_btn" value="Update" class="btn btn-success"/>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-12">
						<!-- for message purpose -->
						<strong id="update_message">&nbsp;</strong>
					</div>
				</div>

			</div>
		</fieldset>

	</form>

<?php
} // end of else if for account information
	

else if ($_GET["update_info"] == "Add/ Update Dependant"){
?>
	<form class="form-horizontal" action="" method="post">
		<fieldset>
			<legend style="border-bottom:1px solid #808b96"><?php echo $_GET["update_info"]; ?></legend>
			<div class="col-sm-10 col-sm-offset-1">
				<div class="form-group">
					<strong>Note: Maximum of 4 dependant only.</strong>
				<?php
					$dependent_class = new Dependent;
				    $exist_dependent =  $dependent_class->existDependent($emp_id);
				    // if no dependend
					if ($exist_dependent == 0){
						echo '<label class="control-label">There is no declared dependant, <span id="add_department_without" class="add-dependent-without" title="Add Dependant">Add Dependant</span></label>';
					}

					//else if ($exist_dependent != 0){
					//	echo '<label class="control-label">There is no declared dependant, <span id="add_department_without" class="add-dependent-without" title="Add Dependant">Add Dependent</span></label>';
					//}

					else {
						echo '<label class="control-label">List of declared dependants, <span id="add_department_without" class="add-dependent-without" title="Add Dependant">Add Dependant</span></label>';
					}

				?>
					<!-- appending dependent -->
					<div id="add_dependent_div">
						<?php

							if ($exist_dependent != 0){
								$dependent_class->getAllDependentInfo($emp_id);
							}

						?>
					</div>
				</div>
				<div class="form-group">
					<div style="text-align:center;">
						<input type="button" id="back_update_btn" value="&nbsp;&nbsp;&nbsp;Back&nbsp;&nbsp;&nbsp;" class="btn btn-success"/>
						<input type="button" id="add_update_info_btn" value="Update" class="btn btn-success"/>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-12">
						<!-- for message purpose -->
						<strong id="update_message">&nbsp;</strong>
					</div>
				</div>

			</div>


			<div>
				<input type="hidden" name="dependant_total_count" />

			</div>
		</fieldset>
	</form>
<?php

} // end of else if for account add/update dependant
else if ($_GET["update_info"] == "Add/ Update Allowance"){
?>
	<form class="form-horizontal" action="" method="post">
		<fieldset>
			<legend style="border-bottom:1px solid #808b96"><?php echo $_GET["update_info"]; ?></legend>
			<div class="col-sm-10 col-sm-offset-1">
				<div class="form-group">
					<?php
						$allowance_class = new Allowance;
					    $exist_allowance =  $allowance_class->existAllowance($emp_id);
					    // if no dependend
						if ($exist_allowance == 0){
							echo '<label class="control-label">There is no allowance, <span id="add_allowance" class="add-dependent-without" title="Add Allowance">Add Allowance</span></label>';
						}

						//else if ($exist_allowance == 1){
						//	echo '<label class="control-label">There is no allowance, <span id="add_allowance" class="add-dependent-without" title="Add Allowance">Add Allowance</span></label>';
						//}

						else {
							echo '<label class="control-label">List of allowances, <span id="add_allowance" class="add-dependent-without" title="Add Allowance">Add Allowance</span></label>';
						}

					?>

					<!-- appending allowance -->
					<div id="add_allowance_div">
							<?php

							if ($exist_allowance != 0){
								$allowance_class->getAllAllowanceInfo($emp_id);
							}

						?>
					</div>
				</div>
				<div class="form-group">
					<div style="text-align:center;">
						<input type="button" id="back_update_btn" value="&nbsp;&nbsp;&nbsp;Back&nbsp;&nbsp;&nbsp;" class="btn btn-success"/>
						<input type="button" id="add_update_allowance" value="Update" class="btn btn-success"/>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-12">
						<!-- for message purpose -->
						<strong id="update_message">&nbsp;</strong>
					</div>
				</div>

			</div>


			<div>
				<input type="hidden" name="allowance_total_count" />

			</div>
		</fieldset>
	</form>
<?php

} // end of else if for account add/update dependant
else if ($_GET["update_info"] == "Leave Information"){
?>
	
     
  <table class="table table-bordered">
    <thead>
      <tr>
        <th class="color-white bg-color-gray"><small>Leave Type</small></th>
        <th class="color-white bg-color-gray"><small>Leave Count</small></th>
      </tr>
    </thead>
    <tbody>
      <?php
      	$emp_info_class->getEmpLeaveCount($emp_id);
      ?>
    </tbody>
  </table>

<div class="form-group">
	<div style="text-align:center;">
		<input type="button" id="back_update_btn" value="&nbsp;&nbsp;&nbsp;Back&nbsp;&nbsp;&nbsp;" class="btn btn-success"/>
		
	</div>	
</div>


<?php
}

else if ($_GET["update_info"] == "Pet Information"){

	



?>
	<form class="form-horizontal" action="" method="post">
		<fieldset>
			<legend style="border-bottom:1px solid #808b96"><?php echo $_GET["update_info"]; ?></legend>
			<div class="col-sm-10 col-sm-offset-1">
				<div class="form-group">
					<div class="col-md-12">
					<?php
						$pet_count = $emp_info_class->countPetInfo($emp_id);
					    // if no dependend
						if ($pet_count == 0){
							echo '<label class="control-label">There is no Pet Info, <button class="btn btn-primary btn-xs" type="button" id="add_pet_info">Add</button>';
						}

						//else if ($exist_allowance == 1){
						//	echo '<label class="control-label">There is no allowance, <span id="add_allowance" class="add-dependent-without" title="Add Allowance">Add Allowance</span></label>';
						//}

						else {
							echo '<label class="control-label">List of Pet Info, <button class="btn btn-primary btn-xs" type="button" id="add_pet_info">Add</button>';
						}

					?>

					</div>
					<!-- appending allowance -->
					<div id="add_pet_info_div">
							<?php

							if ($pet_count != 0){
								$emp_info_class->getAllPetInfo($emp_id);
							}

						?>
					</div>
				</div>
				<div class="form-group">
					<div style="text-align:center;">
						<input type="button" id="back_update_btn" value="&nbsp;&nbsp;&nbsp;Back&nbsp;&nbsp;&nbsp;" class="btn btn-success"/>
						<input type="button" id="add_update_pet_info" value="Update" class="btn btn-success"/>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-12">
						<!-- for message purpose -->
						<strong id="update_message">&nbsp;</strong>
					</div>
				</div>

			</div>


			
		</fieldset>
	</form>

	<script>
		$(document).ready(function(){

			var global_max_pet_count = 2;
			var global_pet_count = "<?php echo $pet_count; ?>";
			//alert(global_pet_count);
			$("#add_pet_info").on("click",function(){
				//alert("HELLO WORLD!");
				global_pet_count++;
				if (global_pet_count == global_max_pet_count){
					$(this).attr("disabled","disabled");
				}
				if (global_pet_count <= global_max_pet_count){
					var html = "";
					html += '<div>';
						html +=  '<div class="col-sm-6">';
							html += '<label class="control-label">Pet Type &nbsp;<span class="red-asterisk">*</span></label><input type="text" value="" class="form-control" id="txt_only" name="pet_type[]" placeholder="Enter Pet Type"/ required="required">';
						html += '</div>'; 
						html += '<div class="col-sm-5">';
							html += '<label class="control-label">Pet Name &nbsp;<span class="red-asterisk">*</span></label><input type="text" value="" class="form-control" id="number_only" name="pet_name[]" placeholder="Enter Pet Name" required="required"/>';
						html += '</div>';
						html += '<div class="col-sm-1 remove-without-dependent">';
							html += '<a class="btn btn-danger btn-sm" title="Remove" id="remove_pet_info">&times;</a>';
						html += '</div>';
					html += '</div>';

					$("#add_pet_info_div").append(html);
				}
			});


			$("#add_pet_info_div").on("click","#remove_pet_info",function(){
				//alert("HELLO WORLD!");
				$(this).closest("div").parent("div").remove();
				global_pet_count--;
				$("#add_pet_info").removeAttr("disabled");
			});	

			$("#add_update_pet_info").on("click",function(){
				//alert("HELLO WORLD!");
				var pet_type_array = [];
				var pet_name_array = [];

				var ready_to_submit = true;
				$("input[name='pet_type[]'").each(function( index ) {
					pet_type_array.push($(this).val());

					if ($(this).val() == ""){
						ready_to_submit = false;
					}
				});

				$("input[name='pet_name[]'").each(function( index ) {
				 	pet_name_array.push($(this).val());

				 	if ($(this).val() == ""){
						ready_to_submit = false;
					}
				});

				if (ready_to_submit == true){
					//alert("READY TO SUBMIT");
					var datastring = "";
					datastring += "pet_type_array="+JSON.stringify(pet_type_array);
					datastring += "&pet_name_array="+JSON.stringify(pet_name_array);
					datastring += "&emp_id="+"<?php echo $emp_id; ?>";
					
			        $("#update_message").html("<center><div class='loader'></div>Loading Information</center>");
		       		$.ajax({
			            type: "POST",
			            url: "php script/add_update_pet_info.php",
			            data: datastring,
			            cache: false,
			            success: function (data) {
			              $("#update_message").html(data);
			            } 
			        }); 
				}
			});
		});
	</script>

<?php
} // end of else if for account information

?>

<script>
	$(document).ready(function(){



		$("select[name='update_education_attain']").on("change",function(){
		    //alert("HELLO WORLD!");
		    var html = "";

		    // for secondary
		    html +='<div class="form-group">';
		      html +='<div class="col-sm-12">';
		        html += '<label style="color: #27ae60 "><i>Secondary Information</i></label>';
		       html +='</div>';
		      html +='<div class="col-sm-8">';
		        html +='<label class="control-label"><span class="glyphicon glyphicon-home" style="color:#2E86C1;"></span> School Name:&nbsp;<span class="red-asterisk">*</span></label>';
		        html +='<input type="text" name="school_name[]" class="form-control" required="required"/>';
		      html +='</div>';
		      html +='<div class="col-sm-6">';
		        html +='<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year From:&nbsp;<span class="red-asterisk">*</span></label>';
		        html +='<input type="text" name="year_from[]" class="form-control" required="required" id="year_only" placeholder="year from"/>';
		      html +='</div>';
		      html +='<div class="col-sm-6">';
		        html +='<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year To:&nbsp;<span class="red-asterisk">*</span></label>';
		        html +='<input type="text" name="year_to[]" class="form-control" required="required" id="year_only" placeholder="year to"/>';
		      html +='</div>';
		      html +='<div class="col-sm-3" style="display:none">';
		        html +='<label class="control-label"><span class="glyphicon glyphicon-education" style="color:#2E86C1;"></span> Course:&nbsp;<span class="red-asterisk">*</span></label>';
		        html +='<textarea class="form-control" name="course[]"></textarea>';
		      html +='</div>';
		    html +='</div>';
		    // for tertiary
		    html +='<div class="form-group">';
		       html +='<div class="col-sm-12">';
		        html += '<label style="color: #27ae60 "><i>Tertiary Information</i></label>';
		       html +='</div>';
		       html +='<div class="col-sm-6">';
		          html +='<label class="control-label"><span class="glyphicon glyphicon-home" style="color:#2E86C1;"></span> School Name:&nbsp;<span class="red-asterisk">*</span></label>';
		          html +='<input type="text" name="school_name[]" class="form-control" required="required"/>';
		        html +='</div>';
		        html +='<div class="col-sm-6">';
		          html +='<label class="control-label"><span class="glyphicon glyphicon-education" style="color:#2E86C1;"></span> Course:&nbsp;<span class="red-asterisk">*</span></label>';
		          html +='<textarea class="form-control" name="course[]" required="required"></textarea>';
		        html +='</div>';
		        html +='<div class="col-sm-5">';
		          html +='<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year From:&nbsp;<span class="red-asterisk">*</span></label>';
		          html +='<input type="text" name="year_from[]" class="form-control" required="required"/>';
		        html +='</div>';
		        html +='<div class="col-sm-5">';
		          html +='<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year To:&nbsp;<span class="red-asterisk">*</span></label>';
		          html +='<input type="text" name="year_to[]" class="form-control" required="required"/>';
		        html +='</div>';
		        html +='<div class="col-md-2">';
		          html +='<button id="add_education_attain" class="btn btn-primary btn-sm" type="button" style="margin-top:30px"><span class="glyphicon glyphicon-plus"></span></button>';
		        html +='</div>';


		      html +='</div>';

		      var education_attain = $(this).val();

		      if (education_attain == ""){
		          $("#append_tertiary_education").html("");
		      }

		      else if (education_attain == "Secondary"){
		          html = "";
		          html +='<div class="form-group">';
		            html +='<div class="col-sm-12">';
		              html += '<label style="color: #27ae60 "><i>Secondary Information</i></label>';
		             html +='</div>';
		            html +='<div class="col-sm-8">';
		              html +='<label class="control-label"><span class="glyphicon glyphicon-home" style="color:#2E86C1;"></span> School Name:&nbsp;<span class="red-asterisk">*</span></label>';
		              html +='<input type="text" name="school_name[]" class="form-control" required="required"/>';
		            html +='</div>';
		            html +='<div class="col-sm-6">';
		              html +='<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year From:&nbsp;<span class="red-asterisk">*</span></label>';
		              html +='<input type="text" name="year_from[]" class="form-control" required="required" id="year_only" placeholder="year from"/>';
		            html +='</div>';
		            html +='<div class="col-sm-6">';
		              html +='<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year To:&nbsp;<span class="red-asterisk">*</span></label>';
		              html +='<input type="text" name="year_to[]" class="form-control" required="required" id="year_only" placeholder="year to"/>';
		            html +='</div>';
		          html +='</div>';
		          $("#append_tertiary_education").html(html); 


		      }
		      else if(education_attain == "Tertiary"){
		          $("#append_tertiary_education").html(html);       
		      }


		      // for year only
		    $("input[id='year_only']").keydown(function (e) {
		        // Allow: backspace, delete, tab, escape, enter , F5
		        if ($.inArray(e.keyCode, [46,8, 9, 27, 13, 110,116]) !== -1 ||
		             // Allow: Ctrl+A, Command+A
		            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
		             // Allow: home, end, left, right, down, up
		            (e.keyCode >= 35 && e.keyCode <= 40)) {
		                 // let it happen, don't do anything
		                 return;
		        }
		        // Ensure that it is a number and stop the keypress
		        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
		            e.preventDefault();
		        }
		    });


		      // float only
		      $("input[id='year_only']").on('input', function(){
		         if ($(this).attr("maxlength") != 4){
		              if ($(this).val().length > 4){
		                  $(this).val($(this).val().slice(0,-1));
		              }
		             $(this).attr("maxlength","4");
		         }

		     });

		      
		  });

		
		$("#append_tertiary_education").on("click","#add_education_attain",function(){
      //lert("HELLO WORLD!");
      var html =  "";
      html +='<div class="form-group">';
       html +='<div class="col-sm-6">';
          html +='<label class="control-label"><span class="glyphicon glyphicon-home" style="color:#2E86C1;"></span> School Name:&nbsp;<span class="red-asterisk">*</span></label>';
          html +='<input type="text" name="school_name[]" class="form-control" required="required"/>';
        html +='</div>';
        html +='<div class="col-sm-6">';
          html +='<label class="control-label"><span class="glyphicon glyphicon-education" style="color:#2E86C1;"></span> Course:&nbsp;<span class="red-asterisk">*</span></label>';
          html +='<textarea class="form-control" name="course[]"></textarea>';
        html +='</div>';
        html +='<div class="col-sm-5">';
          html +='<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year From:&nbsp;<span class="red-asterisk">*</span></label>';
          html +='<input type="text" name="year_from[]" class="form-control" required="required" placeholder="year from" id="year_only"/>';
        html +='</div>';
        html +='<div class="col-sm-5">';
          html +='<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year To:&nbsp;<span class="red-asterisk">*</span></label>';
          html +='<input type="text" name="year_to[]" class="form-control" required="required" placeholder="year to" id="year_only"/>';
        html +='</div>';
        html +='<div class="col-md-2">';
          html +='<button id="remove_education_attain" class="btn btn-danger btn-sm" type="button" style="margin-top:30px"><span class="glyphicon glyphicon-remove"></span></button>';
        html +='</div>';


      html +='</div>';
      $("#append_tertiary_education").append(html);   


      // for year only
      $("input[id='year_only']").keydown(function (e) {
          // Allow: backspace, delete, tab, escape, enter , F5
          if ($.inArray(e.keyCode, [46,8, 9, 27, 13, 110,116]) !== -1 ||
               // Allow: Ctrl+A, Command+A
              (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
               // Allow: home, end, left, right, down, up
              (e.keyCode >= 35 && e.keyCode <= 40)) {
                   // let it happen, don't do anything
                   return;
          }
          // Ensure that it is a number and stop the keypress
          if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
              e.preventDefault();
          }
      });


        // float only
        $("input[id='year_only']").on('input', function(){
           if ($(this).attr("maxlength") != 4){
                if ($(this).val().length > 4){
                    $(this).val($(this).val().slice(0,-1));
                }
               $(this).attr("maxlength","4");
           }

       });
  });

  $("#append_tertiary_education").on("click","#remove_education_attain",function(){

    $(this).closest("div").parent("div").remove();
  }); 


  // for add work expirience
  $("#add_work_xp").on("click",function(){
    //alert("HELLO WORLD!");
    var html = "";
    html += '<div class="form-group">';
      html += '<div class="col-md-12">';
        html += '<button class="btn btn-danger btn-xs pull-right" id="remove_work_xp" type="button" style="margin-top:30px"><span class="glyphicon glyphicon-remove"></span>&nbsp;Remove';
        html += '</button>';
      html += '</div>';
    html += '</div>';
    html +='<div class="form-group">'; 
      html +='<div class="col-sm-5">';            
        html +='<label class="control-label"><span class="glyphicon glyphicon-user" style="color:#2E86C1;"></span> Position&nbsp;<span class="red-asterisk">*</span></label>';
        html +='<input type="text" name="work_position[]" id="txt_only" class="form-control" placeholder="Enter Last Name" required="required">';
      html +='</div>';

      html +='<div class="col-sm-7">';            
        html +='<label class="control-label"><span class="glyphicon glyphicon-blackboard" style="color:#2E86C1;"></span> Company Name&nbsp;<span class="red-asterisk">*</span></label>';
        html +='<input type="text" name="company_name[]" id="txt_only" value="" class="form-control" placeholder="Enter First Name" required="required">';
      html +='</div>';
      html +='<div class="col-sm-8">';            
        html +='<label class="control-label"><span class="glyphicon glyphicon-info-sign" style="color:#2E86C1;"></span> Job Description</label>';
        html +='<textarea class="form-control" name="job_description[]" placeholder="Job Description" required="required"></textarea>';
      html +='</div>';
      html +='<div class="col-sm-4">';
            html +='<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year From:&nbsp;<span class="red-asterisk">*</span></label>';
            html +='<input type="text" name="work_year_from[]"  class="form-control" required="required" placeholder="year from" id="year_only"/>';
          html +='</div>';
          html +='<div class="col-sm-4">';
            html +='<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year To:&nbsp;<span class="red-asterisk">*</span></label>';
            html +='<input type="text" name="work_year_to[]"  class="form-control" required="required" placeholder="year to" id="year_only"/>';
          html +='</div>';
    html +='</div>';

    $("#append_work_experience").append(html);

    // for year only
    $("input[id='year_only']").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter , F5
        if ($.inArray(e.keyCode, [46,8, 9, 27, 13, 110,116]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });


      // float only
      $("input[id='year_only']").on('input', function(){
         if ($(this).attr("maxlength") != 4){
              if ($(this).val().length > 4){
                  $(this).val($(this).val().slice(0,-1));
              }
             $(this).attr("maxlength","4");
         }

     });

  });


  $("#append_work_experience").on("click","button[id='remove_work_xp']",function(){
     $(this).closest("div").parent("div").next("div").remove();
      $(this).closest("div").parent("div").remove();
  });

	// for back purpose
	$("input[id='back_update_btn']").on("click", function () {
		 var datastring = "emp_id="+ <?php echo $emp_id; ?>;
		 $("#modal_body_update_info").html("<center><div class='loader'></div>Loading Information</center>");
         $.ajax({
            type: "POST",
            url: "ajax/script_update_information.php",
            data: datastring,
            cache: false,
           // datatype: "php",
            success: function (data) {
              //$("#edit_modal_body").html(data);
             // $("#editModal").modal("show");
             if (data == "Error" || data == 1){
                $("#errorModal").modal("show");
             }
             else {
                $("#modal_dialog_update_info").attr("class","modal-dialog modal-sm"); // for refreshing the size of modal
                $("#modal_body_update_info").html(data);
                $("#updadeEmpInfo").modal("show");
             }
            }
         }); 
       

	});


		// for add new in dependent
		var static_dependent_count = <?php if (isset($exist_dependent)) {echo $exist_dependent;} else { echo "0";} ?>;
		var static_cont_value = <?php if (isset($exist_dependent)) { echo $exist_dependent; } else { echo "0";} ?>;
		$("span[id='add_department_without']").on("click", function () {
			
			//alert(static_cont_value);
			if (static_cont_value < 4){
				static_cont_value++;
				//alert(static_cont_value);
				$("#add_dependent_div").append('<div id="dependent_'+ ++static_dependent_count+'"><div class="col-sm-6"><label class="control-label">Full Name &nbsp;<span class="red-asterisk">*</span></label><input type="text" class="form-control" id="txt_only" name="full_name_'+static_dependent_count+'" placeholder="Enter Full Name"/ required="required"></div> <div class="col-sm-5"><label class="control-label">Birthdate</label><input type="text" class="form-control" data-toggle="tooltip" data-placement="top" title="Format:mm/dd/yyyy ex. 01/01/1990" autocomplete="off" id="date_only" name="birthdate_'+static_dependent_count+'" placeholder="Enter Birthdate"/></div><div class="col-sm-1 remove-without-dependent"><a class="btn btn-danger btn-sm" title="Remove" id="remove_dependent_without_btn" id="">&times;</a></div></div>');								
			 	$("input[name='dependant_total_count']").val(static_dependent_count); // for saving purpose
			 	$("input[name='birthdate_"+static_dependent_count+"']").dcalendarpicker();

			 	$("input[id='date_only']").keydown(function (e) {

				    //alert(e.keyCode);
				 	 //return false;
				 	 // Allow: backspace, delete, tab, escape, enter , F5 , backslash
			        if ($.inArray(e.keyCode, [46,8, 9, 27, 13, 110,116,191]) !== -1 ||
			             // Allow: Ctrl+A, Command+A
			            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
			             // Allow: home, end, left, right, down, up
			            (e.keyCode >= 35 && e.keyCode <= 40)) {
			                 // let it happen, don't do anything
			                 return;
			        }
			        // Ensure that it is a number and stop the keypress
			        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
			            e.preventDefault();
			        }
			 	 });

				 // for security purpose return false 
			     $("input[id='date_only").on("paste", function(){
			          return false;
			     });



			     $("input[id='date_only']").change(function(){
			     	$("div[class='datepicker dropdown-menu']").attr("style","none");
			       	//$("div[class='datepicker']").attr("style","display:none");
			       //	alert("Hello World!");
			 	 });


			 	   // for handling security in Salary
				    $("input[id='date_only']").on('input', function(){
				       if ($(this).attr("maxlength") != 10){
				            if ($(this).val().length > 10){
				                $(this).val($(this).val().slice(0,-1));
				            }
				           $(this).attr("maxlength","10");
				       }

				   });

				    $('[data-toggle="tooltip"]').tooltip(); 


			 }
		});

		$('#add_dependent_div').on('click', 'a[id="remove_dependent_without_btn"]', function() {
			static_cont_value--;
			//alert(static_cont_value);
			var dependent_id_without = $(this).parent().parent().attr("id").replace(/[a-z_]/g, "");
			$("#dependent_"+dependent_id_without).remove();


		});

	



		// FOR UPPERCASE
	    // fucntion for first letter Upperletter
	    $("input[id='txt_only']").on('input', function(){
	        $(this).val($(this).val().charAt(0).toUpperCase() + $(this).val().slice(1));
	       // document.getElementById(id).value = inputTxt.value.charAt(0).toUpperCase() + inputTxt.value.slice(1);
	     }); 

	    // for txt only
	    $(document).on('keypress', 'input[id="txt_only"]', function (event) {
	        var regex = new RegExp("^[0-9?!@#$%^&*()_+<>/]+$");
	        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);

	        if (regex.test(key)) {
	            event.preventDefault();
	            return false;
	        }
	    });

	       // for security purpose return false
	     $("input[id='txt_only").on("paste", function(){ 
	          return false;
	     });


	      // for handling security in contactNo
	    $("input[id='txt_only']").on('input', function(){

	       if ($(this).attr("maxlength") != 50){
	            if ($(this).val().length > 50){
	                $(this).val($(this).val().slice(0,-1));
	            }
	           $(this).attr("maxlength","50");
	       }

	   });





	    // for number only
	     $("input[id='number_only']").keydown(function (e) {
	        // Allow: backspace, delete, tab, escape, enter , F5
	        if ($.inArray(e.keyCode, [46,8, 9, 27, 13, 110,116]) !== -1 ||
	             // Allow: Ctrl+A, Command+A
	            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
	             // Allow: home, end, left, right, down, up
	            (e.keyCode >= 35 && e.keyCode <= 40)) {
	                 // let it happen, don't do anything
	                 return;
	        }
	        // Ensure that it is a number and stop the keypress
	        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
	            e.preventDefault();
	        }
	    });


	     // for security purpose return false
	     $("input[id='number_only").on("paste", function(){
	          return false;
	     });



	      // for handling security in contactNo
	    $("input[name='update_contact_no']").on('input', function(){
	       if ($(this).attr("maxlength") != 11){
	            if ($(this).val().length > 11){
	                $(this).val($(this).val().slice(0,-1));
	            }
	           $(this).attr("maxlength","11");
	       }

	   });



	     // for handling security in Salary
	    $("input[name='update_salary']").on('input', function(){
	       if ($(this).attr("maxlength") != 6){
	            if ($(this).val().length > 6){
	                $(this).val($(this).val().slice(0,-1));
	            }
	           $(this).attr("maxlength","6");
	       }

	   });


	    $("input[id='date_only']").keydown(function (e) {

	    //alert(e.keyCode);
	 	 //return false;
	 	 // Allow: backspace, delete, tab, escape, enter , F5 , backslash
        if ($.inArray(e.keyCode, [46,8, 9, 27, 13, 110,116,191]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
 	 });

	 // for security purpose return false 
     $("input[id='date_only").on("paste", function(){
          return false;
     });



     $("input[id='date_only']").change(function(){
     	$("div[class='datepicker dropdown-menu']").attr("style","none");
       	//$("div[class='datepicker']").attr("style","display:none");
       //	alert("Hello World!");
 	 });


 	   // for handling security in Salary
	    $("input[id='date_only']").on('input', function(){
	       if ($(this).attr("maxlength") != 10){
	            if ($(this).val().length > 10){
	                $(this).val($(this).val().slice(0,-1));
	            }
	           $(this).attr("maxlength","10");
	       }

	   });


	    // for update company information department getting dynamic position using ajax
    	 $("select[name='update_department']").change(function(){
	       var datastring = "department_id="+$(this).val();
	       $("select[name='update_position']").html("<option value=''></option>");
	       $.ajax({
	            type: "POST",
	            url: "ajax/append_position.php",
	            data: datastring,
	            cache: false,
	           // datatype: "php",
	            success: function (data) {
	              $("select[name='update_position']").html(data);
	               // $('#update_modal_body').html(data);
	              //  $("#update_info_modal").modal("show");
	            }
	        });
	     });


	       // for updating basic information using ajax   
	       $("input[id='update_basic_info_btn']").on("click", function () {

	       		var lastname = $("input[name='update_lastname']").val();
	       		var firstname = $("input[name='update_firstname']").val();
	       		var middlename = $("input[name='update_middlename']").val();
	       		var civilstatus = $("select[name='update_civil_stat']").val();
	       		var address =  $("textarea[name='update_address']").val();       		
	       		var birthdate = $("input[name='update_birthdate']").val();
	       		var gender = $("select[name='update_gender']").val();
	       		var contactNo = $("input[name='update_contact_no']").val();
	       		var emailAdd = $("input[name='update_email_add']").val();



	       		// for required fields
	       		if (lastname == "" || firstname == "" || civilstatus == "" || address =="" || birthdate == "" || gender == "") {
       				$("#update_message").html("<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Please fill up all fields.</center>");
	       		}

	       		// if success
	       		else {
	       			var datastring = "lastname="+lastname + "&firstname=" + firstname + "&middlename=" + middlename + "&civilstatus=" + civilstatus + "&address=" + address + "&birthdate=" + birthdate + "&gender=" + gender + "&contactNo=" + contactNo + "&emailAdd=" + emailAdd;
	       			// var datastring = "address=" +address;
	       			$("#update_message").html("<center><div class='loader'></div>Loading Information</center>");
				     $.ajax({
			            type: "POST",
			            url: "php script/update_basic_information.php",
			            data: datastring,
			            cache: false,
			           // datatype: "php",
			            success: function (data) {
			           //   $("select[name='update_position']").html(data);
			               // $('#update_modal_body').html(data);
			              //  $("#update_info_modal").modal("show");
			              $("#update_message").html(data);
			             // alert(datastring);
			            } 
			        }); 
	       		}

    		});


		// for updating company information using ajax   
	       $("a[id='update_company_info_btn']").on("click", function () {
	       	    var withHistory = $(this).html();
	       		var department = $("select[name='update_department']").val();
	       		var position = $("select[name='update_position']").val();
	       		var salary = $("input[name='update_salary']").val();
	       		var dateHired = $("input[name='update_dateHired']").val();
	       		var workingHours = $("select[name='update_workingHours']").val();
	       		var headName = $("input[name='update_headName']").val();
	       		var company_id = $("select[name='update_company_id']").val();
	       		var employment_type = $("select[name='update_employment_type']").val();
	       		var workingDays = $("select[name='update_workingDays']").val();

	       		// if required all fields
	       		if (department == "" || position == "" || salary == "" || dateHired == "" || workingHours == "" || employment_type == "" || workingDays == ""){
	       			$("#update_message").html("<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Please fill up all fields.</center>");
	       		}
	       		// if success
	       		else {
	       			var datastring = "update_department="+department + "&update_position=" + position + "&update_salary=" + salary + "&update_dateHired=" + dateHired + "&with_history="+withHistory+"&update_workingHours="+workingHours+"&update_headName="+headName+"&update_company_id="+company_id+"&update_employment_type="+employment_type+"&update_workingDays="+workingDays;
	       			// alert(datastring);
	       			$("#update_message").html("<center><div class='loader'></div>Loading Information</center>");
				     $.ajax({
			            type: "POST",
			            url: "php script/update_company_information.php",
			            data: datastring,
			            cache: false,
			           // datatype: "php",
			            success: function (data) {
			           //   $("select[name='update_position']").html(data);
			               // $('#update_modal_body').html(data);
			              //  $("#update_info_modal").modal("show");
			              $("#update_message").html(data);
			            } 
			        }); 
	       		}
	       	});


			// for updating govt information using ajax   
	       $("input[id='update_govt_info_btn']").on("click", function () {
	       		var sssNo = $("input[name='update_sssNo']").val();
	       		var pagibigNo = $("input[name='update_pagibigNo']").val();
	       		var tinNo = $("input[name='update_tinNo']").val();
	       		var philhealthNo = $("input[name='update_philhealthNo']").val();

	       		var datastring = "update_sssNo="+sssNo + "&update_pagibigNo=" + pagibigNo + "&update_tinNo=" + tinNo + "&update_philhealthNo="+philhealthNo;
	       		//alert(datastring);
       			// alert(datastring);
       			$("#update_message").html("<center><div class='loader'></div>Loading Information</center>");
			     $.ajax({
		            type: "POST",
		            url: "php script/update_govt_information.php",
		            data: datastring,
		            cache: false,
		           // datatype: "php",
		            success: function (data) {
		           //   $("select[name='update_position']").html(data);
		               // $('#update_modal_body').html(data);
		              //  $("#update_info_modal").modal("show");
		              $("#update_message").html(data);
		            } 
		        }); 

	       	});


			// for updating govt information using ajax   
	       $("input[id='update_account_info_btn']").on("click", function () {
	       		var role = $("select[name='update_role']").val();
	       		if (role == "") {
	       			$("#update_message").html("<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Please fill up all fields.</center>");
	       		}
	       		else {
		       		var datastring = "update_role="+role;
		       		$("#update_message").html("<center><div class='loader'></div>Loading Information</center>");
		       		$.ajax({
			            type: "POST",
			            url: "php script/update_account_info.php",
			            data: datastring,
			            cache: false,
			           // datatype: "php",
			            success: function (data) {
			           //   $("select[name='update_position']").html(data);
			               // $('#update_modal_body').html(data);
			              //  $("#update_info_modal").modal("show");
			              $("#update_message").html(data);
			            } 
			        }); 
		        }
	       		
	       	});


	       // for updating govt information using ajax   
	       $("input[id='add_update_info_btn']").on("click", function () {
	       		//static_dependent_count

	       		var counter = 0;
	       		var has_error = false;
	       		var name_values = "";
	       		var birthdate_values = "";
	       		do {
	      			counter++;
	      			//alert($("input[name='full_name_"+counter+"']").val());
	      			if ($("input[name='full_name_"+counter+"']").val() != null){
	      				// if did not fill up required fields

	      				if (name_values == ""){
	      					name_values =  $("input[name='full_name_"+counter+"']").val();
	      				}
	      				else {
	      				 	name_values = name_values + "#" + $("input[name='full_name_"+counter+"']").val();
	      				}

	      				if (birthdate_values == ""){
	      					birthdate_values =  $("input[name='birthdate_"+counter+"']").val();
	      				}

	      				else {
	      					 birthdate_values = birthdate_values + "#" + $("input[name='birthdate_"+counter+"']").val();
	      				}		

	      				if ($("input[name='full_name_"+counter+"']").val() == ""){
	      					has_error = true;
	      				}

	      			}
	       			
	       		}while(counter < static_dependent_count);


	       		// for condition
	       		if (has_error == true) {
	       			$("#update_message").html("<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Please fill up all fields.</center>");
	       		}

	       		// if success
	       		else {   		
	      			var datastring = "name_values="+name_values +"&dependent_count=" + static_cont_value+"&birthdate_values="+birthdate_values;
		       		//alert(datastring);
		       		$("#update_message").html("<center><div class='loader'></div>Loading Information</center>");
		       		$.ajax({
			            type: "POST",
			            url: "php script/add_update_dependent.php",
			            data: datastring,
			            cache: false,
			            success: function (data) {
			              $("#update_message").html(data);
			            } 
			        }); 
	       		}
	       		
	       	});


			// for add new in dependent
			var static_allowance_count = <?php if (isset($exist_allowance)) {echo $exist_allowance;} else { echo "0";} ?>;
			var static_allowance_count_value = <?php if (isset($exist_allowance)) { echo $exist_allowance; } else { echo "0";} ?>;
			$("span[id='add_allowance']").on("click", function () {
				
				//alert(static_cont_value);
				//if (static_allowance_count_value < 4){
					static_allowance_count_value++;
					//alert(static_cont_value);
					$("#add_allowance_div").append('<div id="allowance_'+ ++static_allowance_count+'"><div class="col-sm-6"><label class="control-label">Allowance Type &nbsp;<span class="red-asterisk">*</span></label><input type="text" class="form-control" id="txt_only" name="allowanceType_'+static_allowance_count+'" placeholder="Enter Allowance Type"/ required="required"></div> <div class="col-sm-5"><label class="control-label">Value &nbsp;<span class="red-asterisk">*</span></label><input type="text" id="number_only" class="form-control" name="allowanceValue_'+static_allowance_count+'" placeholder="Enter Allowance Value" required="required"/></div><div class="col-sm-1 remove-without-dependent"><a class="btn btn-danger btn-sm" title="Remove" id="remove_allowance">&times;</a></div></div>');								
				 	$("input[name='allowance_total_count']").val(static_allowance_count); // for saving purpose


				 	  // for number only
				     $("input[id='number_only']").keydown(function (e) {
				        // Allow: backspace, delete, tab, escape, enter , F5
				        if ($.inArray(e.keyCode, [46,8, 9, 27, 13, 110,116]) !== -1 ||
				             // Allow: Ctrl+A, Command+A
				            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
				             // Allow: home, end, left, right, down, up
				            (e.keyCode >= 35 && e.keyCode <= 40)) {
				                 // let it happen, don't do anything
				                 return;
				        }
				        // Ensure that it is a number and stop the keypress
				        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
				            e.preventDefault();
				        }
				    });

				     // for security purpose return false
				     $("input[id='number_only").on("paste", function(){
				          return false;
				     });


				       // for handling security in contactNo
				    $("input[id='number_only']").on('input', function(){
				       if ($(this).attr("maxlength") != 5){
				            if ($(this).val().length > 5){
				                $(this).val($(this).val().slice(0,-1));
				            }
				           $(this).attr("maxlength","5");
				       }

				   });


				 	//$("input[id='birthdate_dependent']").dcalendarpicker();
				// }
			});


			$('#add_allowance_div').on('click', 'a[id="remove_allowance"]', function() {
				static_allowance_count_value--;
				//alert(static_cont_value);
				var allowance_sub_div = $(this).parent().parent().attr("id").replace(/[a-z_]/g, "");
				$("#allowance_"+allowance_sub_div).remove();


			});




			// for updating govt information using ajax   
	       $("input[id='add_update_allowance']").on("click", function () {
	       		//static_dependent_count
	       		var counter = 0;
	       		var has_error = false;
	       		var allowance_values = "";
	       		var value_values = "";
	       		do {
	      			counter++;
	      			//alert($("input[name='full_name_"+counter+"']").val());
	      			if ($("input[name='allowanceType_"+counter+"']").val() != null){
	      				// if did not fill up required fields

	      				if (allowance_values == ""){
	      					allowance_values =  $("input[name='allowanceType_"+counter+"']").val();
	      				}
	      				else {
	      				 	allowance_values = allowance_values + "#" + $("input[name='allowanceType_"+counter+"']").val();
	      				}

	      				if (value_values == ""){
	      					value_values =  $("input[name='allowanceValue_"+counter+"']").val();
	      				}

	      				else {
	      					 value_values = value_values + "#" + $("input[name='allowanceValue_"+counter+"']").val();
	      				}		

	      				if ($("input[name='allowanceType_"+counter+"']").val() == "" || $("input[name='allowanceValue_"+counter+"']").val() == ""){
	      					has_error = true;
	      				}

	      			}
	       			
	       		}while(counter < static_allowance_count);

	       		// for condition
	       		if (has_error == true) {
	       			$("#update_message").html("<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Please fill up all fields.</center>");
	       		}

	       		
	       		// if success
	       		else {   		
	      			var datastring = "allowance_values="+allowance_values +"&allowance_count=" + static_allowance_count_value+"&value_values="+value_values;
		       		$("#update_message").html("<center><div class='loader'></div>Loading Information</center>");
		       		$.ajax({
			            type: "POST",
			            url: "php script/add_update_allowance.php",
			            data: datastring,
			            cache: false,
			            success: function (data) {
			              $("#update_message").html(data);
			            } 
			        }); 
	       		}
	       		
	       	});


	       $("#update_school_info_btn").on("click",function(){
	       		//alert("HELLO WORLD!");
	       		$("#form_school_info" ).submit(function(event) {          
	              event.preventDefault();	             
	            });
	           // alert("HELLO WORLD!");
	           var submit_status = true;
	           $("input[required='required']").each(function() {
	           		//alert($(this).val());
				  	if ($(this).val() == ""){
				  		submit_status = false;
				  	}
				});

	            $("textarea[required='required']").each(function() {
	           		//alert($(this).val());
				  	if ($(this).val() == ""){
				  		submit_status = false;
				  	}
				});

	           //alert(submit_status);
	           if (submit_status == true){
	           		var school_name_array = [];
					var course_array = [];
					var year_from_array = [];
					var year_to_array = [];
					var update_education_attain = $("select[name='update_education_attain']").val();

			      	$("input[name='school_name[]']").each(function() {
					 	school_name_array.push($(this).val());

					});

					$("textarea[name='course[]']").each(function() {
					 	course_array.push($(this).val());

					});

					$("input[name='year_from[]']").each(function() {
					 	year_from_array.push($(this).val());

					});

					$("input[name='year_to[]']").each(function() {
					 	year_to_array.push($(this).val());

					});

					//alert(school_name_array);
					//alert(course_array);
					//alert(year_from_array);
					//alert(year_to_array);

					$("#update_message").html("<center><div class='loader'></div>Loading Information</center>");

	    			var interval = setInterval(function(){ 


	    				clearInterval(interval);

	    				var datastring="emp_id=<?php echo $emp_id; ?>" + "&school_name_array="+school_name_array+"&course_array="+course_array+"&year_from_array="+year_from_array+"&year_to_array="+year_to_array+"&update_education_attain="+update_education_attain;
	    				//alert(datastring);

	    				$.ajax({
				       		type: "POST",
				        	url: "php script/update_school_information.php",
				          	data: datastring,
				          	cache: false,
				          	success: function (data) {
				          		
				          		//alert(data);
				          		$("#update_message").html(data);

				        	}
					  	 });	

	    			}, 1000); //END OF TIMER//
	           }
	       });



	       $("#update_work_xp_btn").on("click",function(){
	       		//alert("HELLO WORLD!");
	       		$("#form_work_xp" ).submit(function(event) {          
	              event.preventDefault();	             
	            });
	           // alert("HELLO WORLD!");
	           var submit_status = true;
	           $("input[required='required']").each(function() {
	           		//alert($(this).val());
				  	if ($(this).val() == ""){
				  		submit_status = false;
				  	}
				});

	            $("textarea[required='required']").each(function() {
	           		//alert($(this).val());
				  	if ($(this).val() == ""){
				  		submit_status = false;
				  	}
				});

	           //alert(submit_status);
	           if (submit_status == true){
	           		var work_position_array = [];
					var company_name_array = [];
					var job_description_array = [];
					var year_from_array = [];
					var year_to_array = [];

			      	$("input[name='work_position[]']").each(function() {
					 	work_position_array.push($(this).val());

					});


					$("input[name='company_name[]']").each(function() {
					 	company_name_array.push($(this).val());

					});

					$("textarea[name='job_description[]']").each(function() {
					 	job_description_array.push($(this).val());

					});

					$("input[name='work_year_from[]'").each(function() {
					 	year_from_array.push($(this).val());

					});

					$("input[name='work_year_to[]'").each(function() {
					 	year_to_array.push($(this).val());

					});

					//alert(school_name_array);
					//alert(course_array);
					//alert(year_from_array);
					//alert(year_to_array);

					$("#update_message").html("<center><div class='loader'></div>Loading Information</center>");

	    			var interval = setInterval(function(){ 


	    				clearInterval(interval);

	    				var datastring="emp_id=<?php echo $emp_id; ?>" + "&work_position_array="+work_position_array+"&company_name_array="+company_name_array+"&job_description_array="+job_description_array+"&year_from_array="+year_from_array+"&year_to_array="+year_to_array;
	    				//alert(datastring);

	    				$.ajax({
				       		type: "POST",
				        	url: "php script/update_work_experience.php",
				          	data: datastring,
				          	cache: false,
				          	success: function (data) {
				          		
				          		//alert(data);
				          		$("#update_message").html(data);

				        	}
					  	 });	

	    			}, 1000); //END OF TIMER//
	           }
	       });

	
	 });

	/*$("button[id='remove_dependent_without_btn']").live('click', function(){
		 alert('you clicked me!');
	}); */
		// for removing dependent without
</script>

<script>
	$(document).ready(function(){
		$("input[name='update_birthdate']").dcalendarpicker();
		// birthdate_dependent
		$("input[id='birthdate_dependent']").dcalendarpicker();
		$("input[name='update_dateHired']").dcalendarpicker();

		// date_only
		$("input[id='date_only']").dcalendarpicker();

		$('[data-toggle="tooltip"]').tooltip(); 

		 var emp_name_list = <?php echo $emp_info_class->searchEmployeeName(); ?>

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