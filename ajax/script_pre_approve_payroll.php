<?php
session_start();
include "../class/connect.php";
include "../class/Payroll.php";

if(isset($_POST["approve_payroll_id"]) && isset($_POST["approve"])) {
	$approve_payroll_id = $_POST["approve_payroll_id"];
	$approve = $_POST["approve"];


	$payroll_class = new Payroll;
	// if edited in the inspect element and attendance notif id is not existed so error 
	if ($payroll_class->existApprovePayrollId($approve_payroll_id) == 0){
		echo "Error";
	}

	// if success
	else {
	?>
		<form class="form-horizontal" action="" method="post" id="approve_update_attendance_form">

			<!--<div style="text-align:center">
				<b><i><?php echo $approve;  ?></i></b>
			</div> -->

			<div class="form-group">
				<div class="col-sm-12">				
					<label class="control-label"><span class="glyphicon glyphicon-lock" style="color:#2E86C1;"></span> Please enter your password:</label>
					<div class="input-group">
						<input type="password" name="password" placeholder="Enter Your Password" class="form-control" required="required">
					 	<input type="text" name="hidden" style="display:none;"/>
					 	<span class="input-group-btn">
					    	<button class="btn btn-primary" type="button" id="enter"><?php echo $approve; ?></button>
					    </span>

					</div>	

				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-12">
					<!-- for message purpose -->
					<strong id="message">&nbsp;</strong>
				</div>
			</div>

		</form>


		<script>
			$(document).ready(function(){

				// for click enter button
				$("button[id='enter']").on("click", function () {
					//alert("HELLO WORLD!");
					// 
					if ($("input[name='password']").val() == "") {
				 	 		$("#message").html("<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Please fill up your password.</center>");
				 	 }
				 	 	// if success ajax will be
			 	 	else {
			 	 		var datastring = "password="+$("input[name='password']").val();
			 	 		$("#message").html("<center><div class='loader'></div>Loading Information</center>");
			 	 		$("button[id='enter']").attr("disabled","disabled"); // for security purpose
			 	 		 $.ajax({
				            type: "POST",
				            url: "ajax/approve_attendance_script.php",
				            data: datastring,
				            cache: false,
				           // datatype: "php",
				            success: function (data) {
				            	// if invalid password
				            	if (data == "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> You have entered an invalid password.</center>"){
				             		$("#message").html(data);
				             		$("button[id='enter']").removeAttr("disabled"); // for security purpose
				             	}
				             	// if success
				             	else {
				             		// if equal to approve
				             	
					             		$("#approve_update_attendance_form").attr("action","php script/pre_approve_payroll_script.php?approve_payroll_id=<?php echo $approve_payroll_id; ?>&approve=<?php echo $approve; ?>")
					             		$("#approve_update_attendance_form").submit();
				             		
				             	}
				               // $('#update_modal_body').html(data);
				              //  $("#update_info_modal").modal("show");
				            }
				        });
			 	 	}

				});

				// if press was enter
				 $("input[name='password']").on('keypress', function (e) {
				 	 if(e.which === 13){
				 	 	if ($("input[name='password']").val() == "") {
				 	 		$("#message").html("<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Please fill up your password.</center>");
				 	 	}
				 	 	// if success ajax will be
				 	 	else {
				 	 		var datastring = "password="+$("input[name='password']").val();
				 	 		$("#message").html("<center><div class='loader'></div>Loading Information</center>");
				 	 		$("button[id='enter']").attr("disabled","disabled"); // for security purpose
				 	 		 $.ajax({
					            type: "POST",
					            url: "ajax/approve_attendance_script.php",
					            data: datastring,
					            cache: false,
					           // datatype: "php",
					            success: function (data) {
					            	// if invalid password
					            	if (data == "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> You have entered an invalid password.</center>"){
					             		$("#message").html(data);
					             		$("button[id='enter']").removeAttr("disabled"); // for security purpose
					             	}
					             	// if success
					             	else {
					             		// if equal to approve
					             	
						             		$("#approve_update_attendance_form").attr("action","php script/pre_approve_payroll_script.php?approve_payroll_id=<?php echo $approve_payroll_id; ?>&approve=<?php echo $approve; ?>")
						             		$("#approve_update_attendance_form").submit();
					             		
					             	}
					               // $('#update_modal_body').html(data);
					              //  $("#update_info_modal").modal("show");
					            }
					        });
				 	 	}
			 	 	}

				 });
			});

		</script>



	<?php
	} // end of else
} // end of if
else {
	header("Location:../Mainform.php");
}

?>