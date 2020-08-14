<?php
include "../class/connect.php";
include "../class/emp_information.php";

if (isset($_POST["emp_id"])) {
	$emp_id = $_POST["emp_id"];

	// for num rows exist id
	$emp_info_class = new EmployeeInformation;
	if ($emp_info_class->checkExistEmpId($emp_id) == 1){

		$row_emp = $emp_info_class->getEmpInfoByRow($emp_id); // for naming purposes

		if ($row_emp->generated_code == ""){



			$generated_code = "UCP-" . $emp_id . "0".date("YmdHis");
?>
	
			<form method="post" class="form-horizontal" id="form_genete_code" enctype="multipart/form-data" action="php script/script_save_generated_code.php">
				
				<center>
					<button class="btn btn-primary btn-sm" id="get_generate_code" type="button">Generate Code</button>
					<br/>

					<b>
						<span id="generated_code"></span>
					</b>
					<br/>
					<button type="button" id="submit_generated_code" class="btn btn-success btn-sm" disabled="disabled">SUBMIT</button>
				</center>
			</form>

			<script>
				$(document).ready(function(){

					var already_generated = false;

					$("#get_generate_code").on("click",function(){

						$(this).attr("disabled","disabled");

						if (already_generated == false){
							already_generated = true;

							$("span[id='generated_code']").html("<?php echo $generated_code; ?>");

							$("#submit_generated_code").removeAttr("disabled");
						}
					});


					$("#submit_generated_code").on("click",function(){

						if (already_generated == true){
							//alert("READY FOR SUBMITTION");
							$(this).attr("disabled","disabled");

							$("#form_genete_code").append("<input type='hidden' name='generated_code' value='<?php echo $generated_code; ?>'/>");
							$("#form_genete_code").append("<input type='hidden' name='emp_id' value='<?php echo $emp_id; ?>'/>");
							$("#form_genete_code").submit();
						}
					})
				});

			</script>
<?php
		} // if wlang laman

		else {
?>
			<form method="post" class="form-horizontal" id="form_genete_code" enctype="multipart/form-data" action="php script/script_save_generated_code.php">
				
				<center>

					<b>
						<span id="generated_code"><?php echo $row_emp->generated_code; ?></span>
					</b>
				</center>
			</form>
<?php
		} // else may laman
	}

	else { // ibig savihin error message
		echo "Error";
	}
}

else {
	header("Location:../Mainform.php");
}


?>