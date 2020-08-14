<?php
include "../class/connect.php";
include "../class/Payroll.php";
include "../class/emp_information.php";
include "../class/cut_off.php";

if (isset($_POST["emp_id"])){

	$emp_id = $_POST["emp_id"];

	$payroll_class = new Payroll;
	$emp_info_class = new EmployeeInformation;
	$cut_off_class = new CutOff;

	$cutOffPeriod = $cut_off_class->getCutOffPeriodLatest();

	if($payroll_class->checkExistEmpIdPayrollInfo($emp_id) == 0){
		echo "Error";
	}

	else {

		// for getting emp info
		$row_emp = $emp_info_class->getEmpInfoByRow($emp_id);
		$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname;
		if ($row_emp->Middlename != ""){
			$fullName = $fullName . " " . $row_emp->Middlename;
		}

		// for getting payroll info
		$row = $payroll_class->getPayrollInfoByEmpIdCutOffPeriod($emp_id,$cutOffPeriod);

		$totalDeductions = round($row->totalDeductions - $row->Tax + $row->adjustmentDeductions,2); // for showing the current total deduction

?>
	<div class="container-fluid">
		<div class="row">
			<form class="form-horizontal" method="post" id="form_updatePayrollInfo" action="">
				<div class="form-group">
					<div class="col-sm-10 col-sm-offset-1">
						<label class="control-label col-sm-4" style="margin-right:-3%;"><b>Employee Name:</b></label>
						<div class="col-sm-8">
							<input type="text" id="input_payroll" name="empName" value="<?php echo $fullName; ?>" class="form-control" placeholder="Employee Name ..." required="required"/>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-10 col-sm-offset-1">
						<label class="control-label col-sm-4" style="margin-right:-3%;"><b>Tax Code:</b></label>
						<div class="col-sm-8">
							<input type="text" id="input_payroll" name="taxCode" value="<?php echo $row->taxCode; ?>" class="form-control" placeholder="Employee Name ..." required="required"/>
						</div>
						<input type="hidden" name="earningsAdjustmentPansalo" value="<?php echo $row->adjustmentEarnings; ?>"/>
						<input type="hidden" name="totalGrossIncomePansalo" value="<?php echo $row->totalGrossIncome; ?>"/>
						<input type="hidden" name="dynamicNetpay" value="<?php echo $row->netPay; ?>"/> <!-- for adjustment after -->
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-10 col-sm-offset-1">
						<label class="control-label col-sm-4" style="margin-right:-3%;"><b>Regular OT:</b></label>
						<div class="col-sm-8">
							<input type="text" name="regOT" id="float_only" value="<?php echo $row->regularOT; ?>" class="form-control" placeholder="Total Gross Income ..." required="required"/>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-10 col-sm-offset-1">
						<label class="control-label col-sm-4" style="margin-right:-3%;"><b>Restday OT:</b></label>
						<div class="col-sm-8">
							<input type="text" name="rdOT" id="float_only" value="<?php echo $row->restdayOT; ?>" class="form-control" placeholder="Total Gross Income ..." required="required"/>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-10 col-sm-offset-1">
						<label class="control-label col-sm-4" style="margin-right:-3%;"><b>Regular Holiday OT:</b></label>
						<div class="col-sm-8">
							<input type="text" name="reg_holidayOT" id="float_only" value="<?php echo $row->reg_holidayOT; ?>" class="form-control" placeholder="Total Gross Income ..." required="required"/>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-10 col-sm-offset-1">
						<label class="control-label col-sm-4" style="margin-right:-3%;"><b>Special Holiday OT:</b></label>
						<div class="col-sm-8">
							<input type="text" name="special_holidayOT" id="float_only" value="<?php echo $row->special_holidayOT; ?>" class="form-control" placeholder="Total Gross Income ..." required="required"/>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-10 col-sm-offset-1">
						<label class="control-label col-sm-4" style="margin-right:-3%;"><b>RD/ Regular Holiday OT:</b></label>
						<div class="col-sm-8">
							<input type="text" name="rd_regularHolidayOT" id="float_only" value="<?php echo $row->rd_reg_holidayOT; ?>" class="form-control" placeholder="Total Gross Income ..." required="required"/>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-10 col-sm-offset-1">
						<label class="control-label col-sm-4" style="margin-right:-3%;"><b>RD/ Special Holiday OT:</b></label>
						<div class="col-sm-8">
							<input type="text" name="rd_specialHolidayOT" id="float_only" value="<?php echo $row->rd_special_holidayOT; ?>" class="form-control" placeholder="Total Gross Income ..." required="required"/>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-10 col-sm-offset-1">
						<label class="control-label col-sm-4" style="margin-right:-3%;"><b>Tardiness:</b></label>
						<div class="col-sm-8">
							<input type="text" name="tardiness" id="float_only" value="<?php echo $row->Tardiness; ?>" class="form-control" placeholder="Total Gross Income ..." required="required"/>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-10 col-sm-offset-1">
						<label class="control-label col-sm-4" style="margin-right:-3%;"><b>Absences:</b></label>
						<div class="col-sm-8">
							<input type="text" name="absences" id="float_only" value="<?php echo $row->Absences; ?>" class="form-control" placeholder="Total Gross Income ..." required="required"/>
						</div>
					</div>
				</div>



				<div class="form-group">
					<div class="col-sm-10 col-sm-offset-1">
						<label class="control-label col-sm-4" style="margin-right:-3%;"><b>Total Gross Income:</b></label>
						<div class="col-sm-8">
							<input type="text" name="totalGrossIncome" id="float_only" value="<?php echo $row->totalGrossIncome; ?>" class="form-control" placeholder="Total Gross Income ..." required="required"/>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-10 col-sm-offset-1">
						<label class="control-label col-sm-4" style="margin-right:-3%;"><b>Adjustment:</b></label>
						<div class="col-sm-8">
							<input type="text" name="earningsAdjustment" id="float_only" value="<?php echo $row->adjustmentEarnings; ?>" class="form-control" placeholder="Adjustment ..." required="required"/>
						</div>
					</div>
				</div>



				<div class="form-group">
					<div class="col-sm-10 col-sm-offset-1">
						<label class="control-label col-sm-4" style="margin-right:-3%;"><b>SSS Contribution:</b></label>
						<div class="col-sm-8">
							<input type="text" name="sssDeduction" id="float_only" value="<?php echo $row->sssDeduction; ?>" class="form-control" placeholder="Adjustment ..." required="required"/>
						</div>
						
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-10 col-sm-offset-1">
						<label class="control-label col-sm-4" style="margin-right:-3%;"><b>SSS Loan:</b></label>
						<div class="col-sm-8">
							<input type="text" name="sssLoan" id="float_only" value="<?php echo $row->sssLoan; ?>" class="form-control" placeholder="Adjustment ..." required="required"/>
						</div>
						
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-10 col-sm-offset-1">
						<label class="control-label col-sm-4" style="margin-right:-3%;"><b>Philhealth Contribution:</b></label>
						<div class="col-sm-8">
							<input type="text" name="philhealthDeduction" id="float_only" value="<?php echo $row->philhealthDeduction; ?>" class="form-control" placeholder="Adjustment ..." required="required"/>
						</div>
						
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-10 col-sm-offset-1">
						<label class="control-label col-sm-4" style="margin-right:-3%;"><b>Pag-ibig Contribution:</b></label>
						<div class="col-sm-8">
							<input type="text" name="pagibigContribution" id="float_only" value="<?php echo $row->pagibigDeduction; ?>" class="form-control" placeholder="Adjustment ..." required="required"/>
						</div>
						
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-10 col-sm-offset-1">
						<label class="control-label col-sm-4" style="margin-right:-3%;"><b>Pag-ibig Loan:</b></label>
						<div class="col-sm-8">
							<input type="text" name="pagibigLoan" id="float_only" value="<?php echo $row->pagibigLoan; ?>" class="form-control" placeholder="Adjustment ..." required="required"/>
						</div>
						
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-10 col-sm-offset-1">
						<label class="control-label col-sm-4" style="margin-right:-3%;"><b>Cashbond:</b></label>
						<div class="col-sm-8">
							<input type="text" name="cashbond" id="float_only" value="<?php echo $row->CashBond; ?>" class="form-control" placeholder="Adjustment ..." required="required"/>
						</div>
						
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-10 col-sm-offset-1">
						<label class="control-label col-sm-4" style="margin-right:-3%;"><b>Cash Advance:</b></label>
						<div class="col-sm-8">
							<input type="text" name="cashAdvance" id="float_only" value="<?php echo $row->cashAdvance; ?>" class="form-control" placeholder="Adjustment ..." required="required"/>
						</div>
						
					</div>
				</div>


				<div class="form-group">
					<div class="col-sm-10 col-sm-offset-1">
						<label class="control-label col-sm-4" style="margin-right:-3%;"><b>Total Deductions:</b></label>
						<div class="col-sm-8">
							<input type="text" name="totalDeductions" id="float_only" value="<?php echo $totalDeductions; ?>" class="form-control" placeholder="Adjustment ..." required="required"/>
						</div>
						
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-10 col-sm-offset-1">
						<label class="control-label col-sm-4" style="margin-right:-3%;"><b>Adjustment:</b></label>
						<div class="col-sm-8">
							<input type="text" name="deductionAdjustment" id="float_only" value="<?php echo $row->adjustmentDeductions; ?>" class="form-control" placeholder="Adjustment ..." required="required"/>
						</div>
						<input type="hidden" name="deductionAdjustmentPansalo" value="<?php echo $row->adjustmentDeductions; ?>"/>
						<input type="hidden" name="totalDeductionPansalo" value="<?php echo $totalDeductions; ?>"/>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-10 col-sm-offset-1">
						<label class="control-label col-sm-4" style="margin-right:-3%;"><b>Tax:</b></label>
						<div class="col-sm-8">
							<input type="text" name="tax" id="input_payroll" value="<?php echo $row->Tax; ?>" class="form-control" placeholder="Tax ..." required="required"/>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-10 col-sm-offset-1">
						<label class="control-label col-sm-4" style="margin-right:-3%;"><b>Nontax Allowance:</b></label>
						<div class="col-sm-8">
							<input type="text" name="nontaxAllowance" id="input_payroll" value="<?php echo $row->NontaxAllowance; ?>" class="form-control" placeholder="Nontax Allowance ..." required="required"/>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-10 col-sm-offset-1">
						<label class="control-label col-sm-4" style="margin-right:-3%;"><b>Adjustment:</b></label>
						<div class="col-sm-8">
							<input type="text" name="afterAdjustment" id="float_only" value="<?php echo $row->adjustmentAfter; ?>" value="<?php ?>" class="form-control" placeholder="Adjustment ..." required="required"/>
						</div>
					</div>
				</div>

				

				<div class="form-group">
					<div class="col-sm-10 col-sm-offset-1">
						<label class="control-label col-sm-4" style="margin-right:-3%;"><b>Net Pay:</b></label>
						<div class="col-sm-8">
							<input type="text" name="netPay" value="<?php echo $row->netPay; ?>" id="input_payroll" class="form-control" placeholder="Net Pay ..." required="required"/>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-10 col-sm-offset-1">
							<input type="submit" value="Update" id="updatePayrollInfo" class="btn btn-primary btn-sm pull-right" style="margin-right:6%;"/>
					</div>
				</div>
			</form>
		</div>
	</div>

	<script>
		$(document).ready(function(){
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

		    // max length
		    $("input[id='float_only']").on('input', function(){
		       if ($(this).attr("maxlength") != 9){
		            if ($(this).val().length > 9){
		                $(this).val($(this).val().slice(0,-1));
		            }
		           $(this).attr("maxlength","9");
		       }

		   });


		    // for adjustment
		     $("input[name='earningsAdjustment'").on('input', function(){

		          if ($(this).val()==""){
		             $(this).val("0"); 
		          }
		          // alert("wew");

	   	 	 });


	   	 	     // onpaste
		        $("input[name='deductionAdjustment'").on('input', function(){

		        	//alert("wew");
			          if ($(this).val()==""){
			             $(this).val("0"); 
			          }

			     });


		           // onpaste
		        $("input[name='afterAdjustment'").on('input', function(){

			          if ($(this).val()==""){
			             $(this).val("0"); 
			          }

			     });


		      $("input[name='earningsAdjustment'").change(function(){
	      		//var emp_id = $(this).attr("name").slice(11);


	      		var grossIncome = $("input[name='totalGrossIncomePansalo']").val() - $("input[name='earningsAdjustmentPansalo']").val();

	      		//alert(staticGrossIncome);


	      		//var grossIncome = $("input[name='grossIncome_"+emp_id+"']").val();
	      		//alert(grossIncome);

	      		//current_gross_income = grossIncome;

	      		var adjustment = $(this).val();


	      		if (adjustment == "-." || adjustment == "-0"){
	      			 $(this).val($(this).val().slice(0,-1));

	      		}

	      		//else if (adjustment == 0){
	      			//alert("wew");
      				
	      		//}


	      		else {

		      		if (adjustment != "-") {
			      		 var totalGrossIncome = parseFloat(grossIncome) + parseFloat(adjustment);

			      		 // for 2 decimal places
			              totalGrossIncome = totalGrossIncome.toString().split('e');
			              totalGrossIncome = Math.round(+(totalGrossIncome[0] + 'e' + (totalGrossIncome[1] ? (+totalGrossIncome[1] + 2) : 2)));

			              totalGrossIncome = totalGrossIncome.toString().split('e');
			              totalGrossIncome=  (+(totalGrossIncome[0] + 'e' + (totalGrossIncome[1] ? (+totalGrossIncome[1] - 2) : -2))).toFixed(2);


			      		$("input[name='totalGrossIncome']").val(totalGrossIncome);


			      		//var emp_id = $("input[name='emp_id_"+emp_id+"']").val();
			      		// taxCode_
			      		var taxCode = $("input[name='taxCode']").val();

			      		var datastring = "adjustGrossIncome=" + totalGrossIncome + "&taxCode=" +taxCode;
			      		//alert(datastring);
			      		 $.ajax({
			                type: "POST",
			                url: "ajax/append_adjustment_tax.php",
			                data: datastring,
			                cache: false,
			                success: function(data) {
			                //	alert(data);

			                	var totalDeduction = parseFloat($("input[name='totalDeductions']").val()) + parseFloat(data);


			                	var allowance = $("input[name='nontaxAllowance']").val();

			                	var adjustmentAfter = $("input[name='afterAdjustment']").val();
			                	var netPay = parseFloat(totalGrossIncome) + parseFloat(allowance) - parseFloat(totalDeduction) + parseFloat(adjustmentAfter);	

			          


		                		netPay = netPay.toString().split('e');
				                netPay = Math.round(+(netPay[0] + 'e' + (netPay[1] ? (+netPay[1] + 2) : 2)));

				                netPay = netPay.toString().split('e');
				                netPay=  (+(netPay[0] + 'e' + (netPay[1] ? (+netPay[1] - 2) : -2))).toFixed(2);


	                			$("input[name='tax']").val(data); // for tax
	                			$("input[name='netPay']").val(netPay);
	                			$("input[name='dynamicNetpay']").val(netPay);
		                	}
	                	 }); 



		      		}
	      		}
	      		//$(this).val(emp_id);
      	 	 });


			// for adjustment deduction adjustmentdeduction_
		 $("input[name='deductionAdjustment'").change(function(){
	      		//var emp_id = $(this).attr("name").slice(20);

	      		var totalDeduction = $("input[name='totalDeductionPansalo']").val() - $("input[name='deductionAdjustmentPansalo']").val();

	      		var adjustment = $(this).val();


	      		if (adjustment == "-." || adjustment == "-0"){
	      			 $(this).val($(this).val().slice(0,-1));
	      		}

	      		//else if (adjustment == 0){
	      			//alert("wew");
      				
	      		//}


	      		else {

	      			  var totalDeduction = parseFloat(totalDeduction) + parseFloat(adjustment);

		      		 // for 2 decimal places
		              totalDeduction = totalDeduction.toString().split('e');
		              totalDeduction = Math.round(+(totalDeduction[0] + 'e' + (totalDeduction[1] ? (+totalDeduction[1] + 2) : 2)));

		              totalDeduction = totalDeduction.toString().split('e');
		              totalDeduction=  (+(totalDeduction[0] + 'e' + (totalDeduction[1] ? (+totalDeduction[1] - 2) : -2))).toFixed(2);


		             // alert(totalDeduction);

		      		 $("input[name='totalDeductions']").val(totalDeduction);


		      		 var grossIncome = parseFloat($("input[name='totalGrossIncome']").val());

		      		 var totalDeduction = parseFloat($("input[name='totalDeductions']").val()) + parseFloat($("input[name='tax']").val());

	      			 var allowance = $("input[name='nontaxAllowance']").val();

	      			 var adjustmentAfter = $("input[name='afterAdjustment']").val();

		      		 var netPay = parseFloat(grossIncome) + parseFloat(adjustmentAfter)+  parseFloat(allowance) - parseFloat(totalDeduction);



		      		  netPay = netPay.toString().split('e');
		              netPay = Math.round(+(netPay[0] + 'e' + (netPay[1] ? (+netPay[1] + 2) : 2)));

		              netPay = netPay.toString().split('e');
		              netPay=  (+(netPay[0] + 'e' + (netPay[1] ? (+netPay[1] - 2) : -2))).toFixed(2);

		      		 //alert(netPay);

		      		  //netPay = netPay.toString().split('e');
		              //netPay = Math.round(+(netPay[0] + 'e' + (netPay[1] ? (+netPay[1] + 2) : 2)));

		              //netPay = netPay.toString().split('e');
		              //netPay=  (+(netPay[0] + 'e' + (netPay[1] ? (+netPay[1] - 2) : -2))).toFixed(2);
		      		

		              $("input[name='netPay']").val(netPay);
		              $("input[name='dynamicNetpay']").val(netPay);


      			}
	      		

  		 });




		 $("input[name='afterAdjustment'").change(function(){
        		//var emp_id = $(this).attr("name").slice(15);
        		var adjustment = $(this).val();
        		
        		if (adjustment == "-." || adjustment == "-0"){
	      			 $(this).val($(this).val().slice(0,-1));
	      		}

	      		//else if (adjustment == 0){
	      			//alert("wew");
      				
	      		//}
	      		else {

	      			//var netPay = $("input[name='dynamicNetpay_"+emp_id+"']").val();

	      			//alert(netPay);
	      			var totalGrossIncome = $("input[name='totalGrossIncome']").val();
	      			var totalDeduction = $("input[name='totalDeductions']").val();
	      			var tax = $("input[name='tax']").val();
	      			var allowace = $("input[name='nontaxAllowance']").val();

	      			//alert(totalGrossIncome);
	      		//	alert(totalDeduction);
	      		//	alert(tax);
	      			//alert(allowace);


	      			var totalNetpay = parseFloat(adjustment) + parseFloat(totalGrossIncome) - parseFloat(totalDeduction) - parseFloat(tax) + parseFloat(allowace);

      				totalNetpay = totalNetpay.toString().split('e');
		            totalNetpay = Math.round(+(totalNetpay[0] + 'e' + (totalNetpay[1] ? (+totalNetpay[1] + 2) : 2)));

		            totalNetpay = totalNetpay.toString().split('e');
		            totalNetpay=  (+(totalNetpay[0] + 'e' + (totalNetpay[1] ? (+totalNetpay[1] - 2) : -2))).toFixed(2);

		             $("input[name='netPay']").val(totalNetpay);

      			}
        		

    	 });


			
	      // FOR DECIMAL POINT
		      $("input[name='earningsAdjustment'").keydown(function (e) {


		      //	alert(e.keyCode);
		     	if ($(this).val() == 0 && e.keyCode == "9") {
		     		$(this).val("0");
	     	 	}

		      	//var new_value =0;
		      	else if ($(this).val() == 0) {
		      		$(this).val($(this).val().slice(1,-1));
		      	}


		      	if (e.keyCode == "190" && $(this).val() == 0) {
		      	 	$(this).val("0.");
		      	 //	alert("wew");
	      	  	}

		        // for decimal pint
		        if (e.keyCode == "190") {
		            if ($(this).val().replace(/[0-9]/g, "") == ".") {
		                return false;  
		            }
		        }


		        if (e.keyCode == "189" || e.keyCode == "173") {
		            if ($(this).val().replace(/[0-9]/g, "") == "-") {
		                return false;  
		            }
		        }


		        // Allow: backspace, delete, tab, escape, enter , F5
		        if ($.inArray(e.keyCode, [46,8, 9, 27, 13, 110,116,190,189,173]) !== -1 ||
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


			// FOR DECIMAL POINT
	      $("input[name='deductionAdjustment'").keydown(function (e) {


	      //	alert(e.keyCode);
	     	if ($(this).val() == 0 && e.keyCode == "9") {
	     		$(this).val("0");
     	 	}

	      	//var new_value =0;
	      	else if ($(this).val() == 0) {
	      		$(this).val($(this).val().slice(1,-1));
	      	}


	      	if (e.keyCode == "190" && $(this).val() == 0) {
	      	 	$(this).val("0.");
      	  	}

	        // for decimal pint
	        if (e.keyCode == "190") {
	            if ($(this).val().replace(/[0-9]/g, "") == ".") {
	                return false;  
	            }
	        }


	        if (e.keyCode == "189" || e.keyCode == "173") {
	            if ($(this).val().replace(/[0-9]/g, "") == "-") {
	                return false;  
	            }
	        }





	        // Allow: backspace, delete, tab, escape, enter , F5
	        if ($.inArray(e.keyCode, [46,8, 9, 27, 13, 110,116,190,189,173]) !== -1 ||
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



			 $("input[name='afterAdjustment'").keydown(function (e) {


	      //	alert(e.keyCode);
	     	if ($(this).val() == 0 && e.keyCode == "9") {
	     		$(this).val("0");
     	 	}

	      	//var new_value =0;
	      	else if ($(this).val() == 0) {
	      		$(this).val($(this).val().slice(1,-1));
	      	}


	      	if (e.keyCode == "190" && $(this).val() == 0) {
	      	 	$(this).val("0.");
      	  	}

	        // for decimal pint
	        if (e.keyCode == "190") {
	            if ($(this).val().replace(/[0-9]/g, "") == ".") {
	                return false;  
	            }
	        }


	        if (e.keyCode == "189" || e.keyCode == "173") {
	            if ($(this).val().replace(/[0-9]/g, "") == "-") {
	                return false;  
	            }
	        }





	        // Allow: backspace, delete, tab, escape, enter , F5
	        if ($.inArray(e.keyCode, [46,8, 9, 27, 13, 110,116,190,189,173]) !== -1 ||
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


			$("input[name='earningsAdjustment'").on("paste", function(){
	          return false;
	     });


			$("input[name='deductionAdjustment'").on("paste", function(){
	          return false;
	     });

			$("input[name='afterAdjustment'").on("paste", function(){
	          return false;
	     });


		});
	</script>
<?php
	} // end of else

}

else {
	header("Location:../MainForm.php");
}



?>