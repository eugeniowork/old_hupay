<?php
session_start();
include "../class/connect.php";
include "../class/emp_information.php";
include "../class/role.php";
include "../class/department.php";
include "../class/position_class.php";
include "../class/date.php";
include "../class/minimum_wage_class.php";

if (isset($_POST["emp_id"])) {
	$emp_id = $_POST["emp_id"];
	$_SESSION["update_emp_id"] = $emp_id; 

	// for num rows exist id
	$emp_info_class = new EmployeeInformation;
	$min_wage_class = new MinimumWage;

	// if edited and the admin has 1 emp id so dapat error at di nya makita ung modal
	if ($emp_id == "1"){
		echo "1";
	}

	else if ($emp_info_class->checkExistEmpId($emp_id) == 1){
		$row = $emp_info_class->getEmpInfoByRow($emp_id);

		$minimumWage = $min_wage_class->getMinimumWage();

	?>
		

			<div class="container-fluid">
				<div class="row">
					<?php
						// for admin and hr only
							//if ($_SESSION["role"] == 1 || $_SESSION["role"] == 2){
					?>
					<label class="control-label">Choose information to update</label>
					<?php
						//}
					?>
					<ul style="list-style-type:none;;">
						<?php
						// for admin and hr only
							//if ($_SESSION["role"] == 1 || $_SESSION["role"] == 2){
						?>
						<li><span class="glyphicon glyphicon-user" style="color:#239b56"></span> <a href="#" id="update_info">Basic Information</a></li>
						<li><span class="glyphicon glyphicon-briefcase" style="color:#239b56"></span> <a href="#" id="update_info">Company Information</a></li>
						<li><span class="glyphicon glyphicon-file" style="color:#239b56"></span> <a href="#" id="update_info">Government Information</a></li>
						<li><span class="glyphicon glyphicon-education" style="color:#239b56"></span> <a href="#" id="update_info">School Information</a></li>
						<li><span class="glyphicon glyphicon-briefcase" style="color:#239b56"></span> <a href="#" id="update_info">Work Experience</a></li>
						<li><span class="glyphicon glyphicon-lock" style="color:#239b56"></span> <a href="#" id="update_info">Account Information</a></li>
						<?php
							if ($row->Salary > $minimumWage){
						?>
						<li><span class="glyphicon glyphicon-user" style="color:#239b56"></span> <a href="#" id="update_info">Add/ Update Dependant</a></li>
						<?php
							} // end of if
						?>
						<li><span class="glyphicon glyphicon-ruble" style="color:#239b56"></span> <a href="#" id="update_info">Add/ Update Allowance</a></li>
						<!-- <li><span class="glyphicon glyphicon-minus-sign" style="color:#239b56"></span> <a href="#" id="update_info">Add/ Update Deduction</a></li> -->
						<?php
							//} // end of if in condition
						?>


						<?php
						// for payroll only
							//if ($_SESSION["role"] == 3){
						?>
						<!--<li><span class="glyphicon glyphicon-file" style="color:#239b56"></span> <a href="#" id="update_info">Government Information</a></li>-->
						<!-- <li><span class="glyphicon glyphicon-minus-sign" style="color:#239b56"></span> <a href="#" id="update_info">Add/ Update Deduction</a></li> -->
						<?php
							//} // end of if in condition
						?>

						<li><span class="glyphicon glyphicon-briefcase" style="color:#239b56"></span> <a href="#" id="update_info">Leave Information</a></li>
						<li><span class="glyphicon glyphicon-piggy-bank" style="color:#239b56"></span> <a href="#" id="update_info">Pet Information</a></li>

					</ul>
				</div>
			</div>

			<script>
				$(document).ready(function(){
					// for update information
					 // if success ready for AJAX
			       $("a[id='update_info']").on("click", function () {
			          if ($(this).html() == "Basic Information" || $(this).html() == "Company Information" || $(this).html() == "Government Information" || $(this).html() == "School Information" || $(this).html() == "Work Experience" || $(this).html() == "Account Information" || $(this).html() == "Add/ Update Dependant" || $(this).html() == "Add/ Update Allowance" || $(this).html() == "Leave Information" || $(this).html() == "Pet Information") {
		          			 var datastring = "update_info=" +$(this).html();
			                 $("#modal_body_update_info").html("<center><div class='loader'></div>Loading Information</center>");
			                 $.ajax({
					            type: "GET",
					            url: "ajax/append_update_information.php",
					            data: datastring,
					            cache: false,
					           // datatype: "php",
					            success: function (data) {
					            $("#modal_dialog_update_info").attr("class","modal-dialog");
					            $("#modal_body_update_info").html(data);
					        }
					        }); 
			          	      
			         }
					});         

		     	 });
			</script>
	<?php
	}

	else { // ibig savihin error message
		echo "Error";
	}
}
else {
	header("Location:../Mainform.php");
}

?>