<?php
session_start();
include "../class/connect.php";
include "../class/emp_information.php";
include "../class/role.php";
include "../class/department.php";
include "../class/position_class.php";
include "../class/date.php";

if (isset($_POST["emp_id"])) {
	$emp_id = $_POST["emp_id"];
	$_SESSION["update_emp_id"] = $emp_id; 

	// for num rows exist id
	$emp_info_class = new EmployeeInformation;
	if ($emp_info_class->checkExistEmpId($emp_id) == 1){
		$row = $emp_info_class->getEmpInfoByRow($emp_id);

	?>
		<form class="form-horizontal" action="" method="post" id="form_update_reg_bio_id">
			<fieldset>			
					<div class="col-sm-10 col-sm-offset-1">
						<div class="form-group">
							<div class="col-sm-12">
								<span class="glyphicon glyphicon-asterisk" style="color:#2E86C1;"></span> <label class="control-label">Biometrics ID &nbsp;<span class="red-asterisk">*</span></label>
								<input type="text" id="number_only" class="form-control" placeholder="Enter Biometrics ID" value="<?php if ($row->bio_id != 0) {echo $row->bio_id;} ?>" name="update_bio_id" required="required"/>
							</div>
						</div>
						
						<div class="form-group">
							<div style="text-align:center;">
								<input type="submit" value="<?php if ($row->bio_id != 0) { echo 'Update';} else { echo 'Register';}?>" id="update_reg_bio_id" class="btn btn-success"/>
							</div>
						</div>

					</div>
						
			</fieldset>
		</form>

		<script>
		$(document).ready(function(){
			// update_reg_bio_id.php
			$("input[id='update_reg_bio_id']").on("click", function () {
				//alert("Hello World!");
				if ($("input[name='update_bio_id']").val() != ""){
					$("#form_update_reg_bio_id").attr("action","php script/update_reg_bio_id.php");
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
		    $("input[name='update_bio_id']").on('input', function(){
		       if ($(this).attr("maxlength") != 4){
		            if ($(this).val().length > 4){
		                $(this).val($(this).val().slice(0,-1));
		            }
		           $(this).attr("maxlength","4");
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