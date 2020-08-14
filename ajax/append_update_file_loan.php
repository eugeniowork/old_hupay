<?php
	include "../class/connect.php";
	include "../class/emp_loan_class.php";	


	if (isset($_POST["file_loan_id"])){
		$file_loan_id = $_POST["file_loan_id"];

		$emp_loan_class = new EmployeeLoan;

		$row = $emp_loan_class->getFileLoanInfoById($file_loan_id);


?>
	<form class="form-horizontal" id="form_edit_file_loan" method="post">
		<div class="form-group">
			<div class="col-md-6">
				<label class="control-label">Amount</label>
				<input type="text" class="form-control" name="update_amount" value="<?php echo $row->amount; ?>" id="float_only" required="required"/>
			</div>

			<div class="col-md-6">
				<label class="control-label">Type</label>
				<select name="update_loan_type" class="form-control" required="required">
					<option value=""></option>
					<option value="1" <?php if ($row->type == 1) { echo "selected='selected'"; } ?> >Salary Loan</option>
					<option value="2" <?php if ($row->type == 2) { echo "selected='selected'"; } ?>>SIMKIMBAN</option>
					<option value="3" <?php if ($row->type == 3) { echo "selected='selected'"; } ?>>Employee Benifit Program Loan</option>
				</select>
			</div>
		</div>


		<div class="form-group" id="update_div_program" style="<?php if ($row->type != 3) {  echo 'display: none'; } ?>">
			<div class="col-md-6 col-md-offset-6">
				<label class="control-label">Program</label>
				<select name="update_program" class="form-control" required="required">
					<option value=""></option>
					<option value="1" <?php if ($row->program == 1) { echo "selected='selected'"; } ?> >Service Rewards</option>
					<option value="2" <?php if ($row->program == 2) { echo "selected='selected'"; } ?> >Tulong Pangkabuhayan Program</option>
					<option value="3" <?php if ($row->program == 3) { echo "selected='selected'"; } ?> >Education Assistance Program</option>
					<option value="4" <?php if ($row->program == 4) { echo "selected='selected'"; } ?> >Housing Renovation Program</option>
					<option value="5" <?php if ($row->program == 5) { echo "selected='selected'"; } ?> >Emergency and Medical Assistance Program</option>
				</select>
				
			</div>

		</div>
		<div class="form-group">
			<div class="col-md-12">
				<label class="control-label">Purpose</label>
				<textarea name="update_purpose" class="form-control" required="required"><?php echo $row->purpose; ?></textarea>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12">
				<button type="submit" class="btn btn-primary btn-sm pull-right" id="btn_update_file_loan">Update</button>
			</div>
		</div>
	</form>


	<script>
		$(document).ready(function(){

			// FOR DECIMAL POINT
		      $("input[id='float_only']").keydown(function (e) {


		       
		        // for decimal pint
		        if (e.keyCode == "190") {
		            if ($(this).val().replace(/[0-9]/g, "") == ".") {
		                return false;  
		            }
		        }


		        // Allow: backspace, delete, tab, escape, enter , F5
		        if ($.inArray(e.keyCode, [46,8, 9, 27, 13, 110,116,190]) !== -1 ||
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
		     $("input[id='float_only").on("paste", function(){
		          return false;
		     });
			
			$("#form_edit_file_loan").submit(function(event) {          
	              event.preventDefault();
	             
	            });


			$("select[name='update_loan_type']").on("change",function(){
					//alert($(this).val());
				if ($(this).val() == 3){
					$("#update_div_program").removeAttr("style");
					$("select[name='update_program']").attr("required","required");
				}

				else {
					$("select[name='program']").val("");
					$("#update_div_program").attr("style","display:none");
					$("select[name='update_program']").removeAttr("required");
				}
			});
			$("#btn_update_file_loan").on("click",function(){
			//	alert("HELLO WORLD!");

				var amount = $("input[name='update_amount']").val();
				var type = $("select[name='update_loan_type']").val();
				var program = $("select[name='update_program']").val();
				var purpose = $("textarea[name='update_purpose']").val();

				if (amount != "" && type != "" && purpose != ""){

					if (type == 3 && program == ""){
						//alert("Wew");
					}

					else {
					//alert("HELLO WORLD!");
					//var datastring = "amount="+amount+"&type="+type+"&purpose="+purpose;
					//alert(datastring);
						$("#form_edit_file_loan").attr("action","php script/update_emp_file_loan.php?file_loan_id=<?php echo $file_loan_id; ?>");
						$("#form_edit_file_loan").unbind().submit();

					}
				}


			});
		});

	</script>
<?php
	}

	else {
		header("Location:../MainForm.php");
	}

?>