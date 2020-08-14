<?php
session_start();
include "../class/connect.php";
include "../class/leave_class.php";

if(isset($_POST["leave_id"]) && isset($_POST["approve"])) {
	$leave_id = $_POST["leave_id"];
	$approve = $_POST["approve"];
	//echo $leave_id;

	$leave_class = new Leave;
	// if edited in the inspect element and attendance notif id is not existed so error 
	if ($leave_class->checkExistLeaveId($leave_id) == 0){
		echo "Error";
	}

	// if success
	else {
	?>
		<form class="form-horizontal" action="" method="post" id="approve_file_leaveForm">

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
					
					// 
					if ($("input[name='password']").val() == "") {
				 	 		$("#message").html("<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Please fill up your password.</center>");
				 	 }
				 	 	// if success ajax will be
			 	 	else {
			 	 		var datastring = "password="+$("input[name='password']").val();
			 	 		$("#message").html("<center><div class='loader'></div>Loading Information</center>");
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
				             	}
				             	// if success
				             	else {
				             		// if equal to approve
				             	
					             		$("#approve_file_leaveForm").attr("action","php script/update_file_leave.php?leave_id=<?php echo $leave_id; ?>&approve=<?php echo $approve; ?>")
					             		$("#approve_file_leaveForm").submit();
				             		
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
					             	}
					             	// if success
					             	else {
					             		// if equal to approve
					             	
						             		$("#approve_file_leaveForm").attr("action","php script/update_file_leave.php?leave_id=<?php echo $leave_id; ?>&approve=<?php echo $approve; ?>")
						             		$("#approve_file_leaveForm").submit();
					             		
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