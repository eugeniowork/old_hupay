<?php
session_start();
include "../class/connect.php";
include "../class/emp_information.php";

if (isset($_POST["emp_id"])) {
	$emp_id = $_POST["emp_id"];

	// for num rows exist id
	$emp_info_class = new EmployeeInformation;
	if ($emp_info_class->checkExistEmpId($emp_id) == 1){
		$row = $emp_info_class->getEmpInfoByRow($emp_id);


		$_SESSION["update_emp_atm_status_id"] = $emp_id;

		$atmStatus = "Without ATM";
		$updateTo = "With ATM";
		if ($row->WithAtm == 1) {
			$atmStatus = "With ATM";
			$updateTo = "Without ATM";
		}

?>
	
	<div class="container-fluid">
		<div class="row">
			
				<div class="col-sm-12">
					<div class="form-group">
						<label class="control-label"><b>ATM Record Status:</b> <span style="color: #2e86c1 "><?php echo $atmStatus; ?></span></label> <br/>
						<label class="control-label"><center>Do you want to update ATM Record Status of <b><?php echo $row->Lastname. ", " . $row->Firstname . " " . $row->Middlename; ?></b> to <b><?php echo $updateTo; ?></b>?</center></label>						
					</div>

					<?php
						if ($row->WithAtm == 0){
					?>
					<div class="form-group">
						<input required="required" type="text" class="form-control" name="atmNo" placeholder="Enter Account number here ..." id="number_only" maxlength="12"/>
					</div>
					<div id="errorMessage">
						
					</div>
					<?php
						} // end of if
					?>


				</div>
			
		</div>
	</div>

	<script>
		 $(document).ready(function(){
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


	    	 // for maxlength
		    $("input[name='atmNo']").on('input', function(){
		       if ($(this).attr("maxlength") != 12){
		            if ($(this).val().length > 12){
		                $(this).val($(this).val().slice(0,-1));
		            }
		           $(this).attr("maxlength","12");
		       }

		   });
		});

	</script>

<?php
	} // end of if

	else { // ibig savihin error message
		echo "Error";
	}
}

else {
	header("Location:../Mainform.php");
}


?>