<?php
session_start();
include "../class/connect.php";
include "../class/simkimban_class.php";
include "../class/emp_information.php";
include "../class/date.php";
include "../class/money.php";


if (isset($_POST["simkimban_id"])){
	$simkimban_id = $_POST["simkimban_id"];

	//echo $pagibig_loan_id;

	$simkimban_class = new Simkimban;
	$emp_info_class = new EmployeeInformation;
	$date_class = new date;
	$money_class = new Money;

    if ($simkimban_class->checkExistSimkimbanUpdate($simkimban_id) == 1){
		$row = $simkimban_class->getInfoBySimkimbaId($simkimban_id);
		$row_emp = $emp_info_class->getEmpInfoByRow($row->emp_id);


		// values from db
		if ($row_emp->Middlename == ""){
			$full_name = $row_emp->Lastname . ", " . $row_emp->Firstname;
		}
		else {
			$full_name = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;
		}

		$dateFrom = $date_class->dateDefault($row->dateFrom);
		$dateTo = $date_class->dateDefault($row->dateTo);
		$item = $row->Items;
		$amountLoan = $row->amountLoan;
		$deduction = $row->deduction;
		$remainingBalance = $row->remainingBalance;


		$disabled = "";
		if ($row->deductionType == "Semi-monthly"){
			$disabled = "disabled='disabled'";
		}

?>
		
		<form class="form-horizontal" action="" id="form_updateSimkimban" method="post">
 			<div class="form-group">
				<label class="control-label col-sm-3 col-sm-offset-1">Employee Name:</label>
				<div class="col-sm-6 txt-pagibig-loan" style="margin-right:-15px;">
					<input type="text" class="form-control" name="update_empName" value="<?php echo $full_name; ?>" placeholder="Employee Name ..." id="input_payroll" required="required"/>
				</div>
				<!-- <label class="col-sm-1 control-label"><a href="#" id="choose_employee_pagibig_loan">Choose</a></label> -->
			</div>
			<div class="form-group">
											
				<label class="control-label col-sm-3 col-sm-offset-1" style="">Deduction Type:</label>
				<div class="col-sm-3 txt-pagibig-loan">
					<select class="form-control" required="required" name="deductionTypeExist">
						<option value=""></option>	
						<option value="Semi-monthly" <?php if ($row->deductionType == "Semi-monthly") { echo "selected='selected'";} ?> >Semi-monthly</option>	
						<option value="Monthly" <?php if ($row->deductionType == "Monthly") { echo "selected='selected'";} ?>>Monthly</option>													
					</select>
				</div>

				
			</div>
			<div class="form-group">
				<label class="control-label col-sm-3 col-sm-offset-1" style=""><small>If monthly, Deduction day:</small></label>
				<div class="col-sm-8 txt-pagibig-loan">
					<label class="radio-inline"><input required="required" type="radio" value="15" name="opt_deductedPayrollDate" <?php if ($row->deductionDay == 15) { echo "checked='checked'"; } ?> <?php echo $disabled; ?> >15</label>
					<label class="radio-inline"><input required="required" type="radio" value="30" name="opt_deductedPayrollDate" <?php if ($row->deductionDay == 30) { echo "checked='checked'"; } ?> <?php echo $disabled; ?> >30</label>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-3 col-sm-offset-1" style="">Total Months:</label>
				<div class="col-sm-2 txt-pagibig-loan">
					<select class="form-control" required="required" name="totalMonthsExist">
						<option value=""></option>
						<option value="1" <?php if ($row->totalMonths == 1) { echo "selected='selected'"; } ?> >1</option>
						<option value="2" <?php if ($row->totalMonths == 2) { echo "selected='selected'"; } ?> >2</option>
						<option value="3" <?php if ($row->totalMonths == 3) { echo "selected='selected'"; } ?> >3</option>
						<option value="4" <?php if ($row->totalMonths == 4) { echo "selected='selected'"; } ?> >4</option>
						<option value="5" <?php if ($row->totalMonths == 5) { echo "selected='selected'"; } ?> >5</option>
						<option value="6" <?php if ($row->totalMonths == 6) { echo "selected='selected'"; } ?> >6</option>
						<option value="7" <?php if ($row->totalMonths == 7) { echo "selected='selected'"; } ?> >7</option>
						<option value="8" <?php if ($row->totalMonths == 8) { echo "selected='selected'"; } ?> >8</option>
						<option value="9" <?php if ($row->totalMonths == 9) { echo "selected='selected'"; } ?> >9</option>
						<option value="10" <?php if ($row->totalMonths == 10) { echo "selected='selected'"; } ?> >10</option>
						<option value="11" <?php if ($row->totalMonths == 11) { echo "selected='selected'"; } ?> >11</option>
						<option value="12" <?php if ($row->totalMonths == 12) { echo "selected='selected'"; } ?> >12</option>											
					</select>
				</div>
				
			</div>
			<div class="form-group">
				<label class="control-label col-sm-3 col-sm-offset-1">Date From:</label>
				<div class="col-sm-6 txt-pagibig-loan">
					<input type="text" class="form-control" placeholder="Date From ..." value="<?php echo $dateFrom; ?>" name="update_dateFrom"  required="required"/>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-3 col-sm-offset-1">Date To:</label>
				<div class="col-sm-6 txt-pagibig-loan">
					<input type="text" class="form-control" placeholder="Date To ..." value="<?php echo $dateTo; ?>" name="update_dateTo" required="required"/>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-3 col-sm-offset-1">Item:</label>
				<div class="col-sm-6 txt-pagibig-loan">
					<input type="text" class="form-control" id="department_txt" placeholder="Item ..." value="<?php echo $item; ?>" name="update_item" required="required"/>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-3 col-sm-offset-1">Amount Loan:</label>
				<div class="col-sm-6 txt-pagibig-loan">
					<input type="text" class="form-control" placeholder="Amount Loan ..." id="float_only" value="<?php echo $amountLoan; ?>" name="update_amountLoan" required="required"/>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-3 col-sm-offset-1">Deduction:</label>
				<div class="col-sm-6 txt-pagibig-loan">
					<input type="text" class="form-control" placeholder="Deduction ..." id="float_only" value="<?php echo $deduction; ?>" name="update_decution" required="required"/>
				</div>
			</div>


			<div class="form-group">
				<label class="control-label col-sm-3 col-sm-offset-1">Remaining Balance:</label>
				<div class="col-sm-6 txt-pagibig-loan">
					<input type="text" class="form-control" placeholder="Remaining Balance ..." id="float_only" value="<?php echo $remainingBalance; ?>" name="update_remainingBalance" required="required"/>
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-offset-6">
					<input type="submit" value="Update" id="updateSimkimban" class="btn btn-primary btn-sm"/>
				</div>
			</div>

			<div class="form-group">
				<div id="update_error_msg">
				</div>
			</div>
		</form>



		<script>
			$(document).ready(function(){
				//  input_payroll
				$("input[id='input_payroll']").keydown(function (e) {
				//  return false;
				if(e.keyCode != 116) {
				    return false;
				}
				});

				// onpaste
				$("input[id='input_payroll").on("paste", function(){
				  return false;
				});


				$("input[name='update_dateFrom']").dcalendarpicker(); //
				$("input[name='update_dateTo']").dcalendarpicker(); //


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



				 // department_txt
			     $("input[id='department_txt").on("paste", function(){
			    
			          return false;
			     });



			       // for txt only
			    $(document).on('keypress', 'input[id="department_txt"]', function (event) {


			        var regex = new RegExp("^[<>/?]+$");
			        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);

			        if (regex.test(key)) {
			            event.preventDefault();
			            return false;
			        }
			    });



			      $("input[id='department_txt']").on('input', function(){

			       if ($(this).attr("maxlength") != 50){
			            if ($(this).val().length > 50){
			                $(this).val($(this).val().slice(0,-1));
			            }
			           $(this).attr("maxlength","50");
			       }

			   });

			      // for selecting deduction type in manual by payroll admin for those has exist salaryloan
				    $("select[name='deductionTypeExist']").change(function(){
				        var deductionType = $(this).val();

				        if (deductionType == ""){
				            //$("div[id='errorDeduction']").html("<span class='glyphicon glyphicon-remove'></span> Please select <b>Deduction Type</b> first.");
				            $("input[name='deduction']").val("");
				            $("input[name='opt_deductedPayrollDate']").attr("disabled","disabled");
				            $("input[name='opt_deductedPayrollDate']").removeAttr("checked",false);
				        }
				        else {

				          
				            if (deductionType == "Semi-monthly") {
				                $("input[name='opt_deductedPayrollDate']").attr("disabled","disabled");
				                $("input[name='opt_deductedPayrollDate']").removeAttr("checked",false);
				            }


				            if (deductionType == "Monthly") {
				                $("input[name='opt_deductedPayrollDate']").removeAttr("disabled");
				            }

				            
				        }
				         
				       
				    });

			});

		</script>


		
<?php
	} // end of else if

	else {
		echo "Error";
	}
}

else {
	header("Location:../MainForm.php");
}

?>