<?php
include "../class/connect.php";
include "../class/emp_loan_class.php";	
include "../class/emp_information.php";	


if (isset($_POST["file_loan_id"])){
	$file_loan_id = $_POST["file_loan_id"];

	$emp_loan_class = new EmployeeLoan;

	$row = $emp_loan_class->getFileLoanInfoById($file_loan_id);

	$emp_id = $row->emp_id;

	$program = $row->program;

	$emp_info_class = new EmployeeInformation;

	$row_emp = $emp_info_class->getEmpInfoByRow($emp_id);

	$date_hired = $row_emp->DateHired;

	$now = date("Y-m-d");

	$date1 = $date_hired;
	$date1= date_create($date1);

	$date2= date_create($now);

	$diff =date_diff($date1,$date2);
	$wew =  $diff->format("%R%a");
	$days = str_replace("+","",$wew);


	$years = $days / 365;
	$years = floor($years);
	

	$loan_type = $row->type;


?>

<?php
	
	if ($loan_type == 1 || $loan_type == 3){

		$loan_type_str = "SALARY LOAN";
		if ($loan_type == 3){
			$loan_type_str = "EMPLOYEE BENIFIT PROGRAM";
		}

?>

<form class="form-horizontal" id="form_create_loan_payment_schedule" method="post">
	
	<div class="form-group">
		<div class="col-sm-12">
			<center>
				<h4><u style="color:#ff4136;">FILE <?php echo $loan_type_str; ?> FORM</u></h4>
			</center>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-12">
			<label class="control-label col-md-3" style="color:#158cba">Deduction Type:</label>
			<div class="col-md-7">
				<select class="form-control" required="required" name="deductionType">
					<option value=""></option>	
					<option value="Semi-monthly">Semi-monthly</option>	
					<option value="Monthly">Monthly</option>													
				</select>
			</div>

		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-12">
			<label class="control-label col-md-3" style="color:#158cba">If monthly, specify the date payroll to be deducted:</label>
			<div class="col-md-9">
				<label class="radio-inline"><input type="radio" value="15" name="opt_deductedPayrollDate" disabled="disabled">15</label>
				<label class="radio-inline"><input type="radio" value="30" name="opt_deductedPayrollDate" disabled="disabled">30</label>
			</div>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-12">
			<label class="control-label col-md-3" style="color:#158cba">Date From :</label>
			<!--<div class="col-md-4">
				<input type="text" required="required" class="form-control" autocomplete="off" id="number_only" value="" name="dateFromFileSalaryLoan" placeholder="MM"/>
			</div>-->
			
			
			<div class="col-md-3" style="margin-right:-5%;">
				<select class="form-control" style="width:70%;" required="required" name="dateFromMonth" data-toggle="tooltip" data-placement="top" title="Month">
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					<option value="9">9</option>
					<option value="10">10</option>
					<option value="11">11</option>
					<option value="12">12</option>
					<option value="13">13</option>
					<option value="14">14</option>
					<option value="15">15</option>
					<option value="16">16</option>
					<option value="17">17</option>
					<option value="18">18</option>
					<option value="19">19</option>
					<option value="20">20</option>
					<option value="21">21</option>
					<option value="22">22</option>
					<option value="23">23</option>
					<option value="24">24</option>
				</select>
			</div>
			<div class="col-md-3" style="margin-right:-5%;">
				<select class="form-control" style="width:70%;" required="required" name="dateFromDay" data-toggle="tooltip" data-placement="top" title="Day">
					<option value="15">15</option>	
					<option value="30">30</option>												
				</select>
			</div>

			<div class="col-md-3">
				<select class="form-control" style="width:70%;" required="required" name="dateFromYear" data-toggle="tooltip" data-placement="top" title="Year">
					<?php
						$year = date('Y');
						$nextYear = $year + 1;

					?>
					<option value="<?php echo $year; ?>"><?php echo $year; ?></option>	
					<option value="<?php echo $nextYear; ?>"><?php echo $nextYear; ?></option>													
				</select>
			</div>
		
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-12">
			<label class="control-label col-md-3" style="color:#158cba">Total Months:</label>
			<div class="col-md-7">
				<select class="form-control" required="required" name="totalMonths">
					<option value=""></option>													
				</select>
			</div>
			<div style="color:#CB4335;margin-top:10px;" id="errorTotalMonths"></div>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-12">
			<label class="control-label col-md-3" style="color:#158cba">Date To :</label>
			<div class="col-md-7">
				<input type="text" class="form-control" readonly="readonly" name="dateTo" data-toggle="tooltip" data-placement="top" title="Format:mm/dd/yyyy ex. 01/01/1990" autocomplete="off" id="date_only" value="" placeholder=""/>
			</div>
		</div>
	</div>
	

	<?php

		if ($loan_type == 1){
	?>

	<div class="form-group">
		<div class="col-sm-12">
			<label class="control-label col-md-3" style="color:#158cba">Amount Loan:</label>
			<div class="col-md-7">
				<input type="text" class="form-control"  name="amountLoan"  value="" readonly="readonly" placeholder="" id="float_only"/>
			</div>
		</div>
	</div>

	<?php
		}
	?>


	<?php

		if ($loan_type == 3){

			$min_amount = 0;
			$max_amount = 0;
			if ($years == 1){
				$min_amount = 1000;
				$max_amount = 5000;
			}

			else if ($years == 2){
				$min_amount = 5000;
				$max_amount = 10000;
			}

			else if ($years == 3){
				$min_amount = 10000;
				$max_amount = 15000;
			}

			else if ($years == 4){
				$min_amount = 15000;
				$max_amount = 20000;
			}

			else if ($years == 5){
				$min_amount = 20000;
				$max_amount = 30000;
			}

			else if ($years >= 6){
				$min_amount = 20000;
				$max_amount = 35000;
			}

	?>

	<div class="form-group">
		<div class="col-sm-12">
			<label class="control-label col-md-3" style="color:#158cba">Amount Loan:</label>
			<div class="col-md-7">
				<input type="number" class="form-control"  name="amountLoan"  min="<?php echo $min_amount; ?>" max="<?php echo $max_amount; ?>" value="" readonly="readonly" placeholder="" id="float_only"/>
			</div>
		</div>
	</div>

	<?php
		}
	?>

	<div class="form-group">
		<div class="col-sm-12">
			<label class="control-label col-md-3" style="color:#158cba">Total Payment:</label>
			<div class="col-md-5">
				<input type="text" class="form-control"  name="totalPayment"  readonly="readonly" placeholder="" id="float_only"/>
				
			</div>
			<div style="color:#CB4335;margin-top:10px;" id="errorTotalPayment">&nbsp;</div>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-12">
			<label class="control-label col-md-3" style="color:#158cba">Deduction:</label>
			<div class="col-md-5">
				<input type="text" class="form-control"  name="deduction" placeholder="" readonly="readonly"/>
			</div>
			<div style="color:#CB4335;margin-top:10px;" id="errorDeduction">&nbsp;</div>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-12">
			<label class="control-label col-md-3" style="color:#158cba">Remarks:</label>
			<div class="col-md-7">
				<textarea class="form-control" name="remarks" required="required"><?php echo $row->purpose; ?></textarea>
			</div>
			
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-12">
			<div class="col-md-10">
				<button type="button" class="btn btn-primary btn-sm pull-right" id="file_salary_btn">File Loan</button>
			</div>
		
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-12">
			<div class="col-md-10" id="error_message">&nbsp;</div>
		</div>
	</div>
</form>


<script>
	$(document).ready(function(){


		 var loan_type = <?php echo $loan_type; ?>;


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

		$("#form_create_loan_payment_schedule").submit(function(event) {          
              event.preventDefault();
             
            });

		$("select[name='deductionType']").change(function(){
	        var deductionType = $(this).val();

	        if (deductionType == ""){
	            //$("div[id='errorDeduction']").html("<span class='glyphicon glyphicon-remove'></span> Please select <b>Deduction Type</b> first.");
	            $("input[name='deduction']").val("");
	            $("input[name='opt_deductedPayrollDate']").attr("disabled","disabled");
	            $("input[name='opt_deductedPayrollDate']").removeAttr("checked",false);
	        }
	        else {

	          if (deductionType != "" && (deductionType != "Semi-monthly" && deductionType != "Monthly")){
	            $("div[id='error_message']").html("<span class='glyphicon glyphicon-remove' style='color:#CB4335;'></span> There's an error during submitting information please refresh the page.");
	          }
	          else {
	            if (deductionType == "Semi-monthly") {
	                $("input[name='opt_deductedPayrollDate']").attr("disabled","disabled");
	                $("input[name='opt_deductedPayrollDate']").removeAttr("checked",false);
	            }


	            if (deductionType == "Monthly") {
	                $("input[name='opt_deductedPayrollDate']").removeAttr("disabled");
	            }

	            var totalMonths = $("select[name='totalMonths']").val();


	            var deductionType = $("select[name='deductionType']").val();



	            if (deductionType == "Semi-monthly" ) {
	              totalMonths = parseInt(totalMonths) * 2;

	            }

	            var amountLoan = $("input[name='amountLoan']").val();

	            if (amountLoan != ""){


	               $.ajax({
	                    type: "POST",
	                    url: "ajax/script_check_exist_loan.php",
	                    //data: datastring,
	                    cache: false,
	                    success: function (data) {



	                      if (data == "exist" || $("input[name='amountLoan']").val() != $("input[name='totalPayment']").val()) {

	                      	interest_rate = .036;
	                      	if (loan_type == 3){
	                      		interest_rate = .01;
	                      	}

	                        interest = (parseFloat(amountLoan) * interest_rate) * (parseFloat($("select[name='totalMonths']").val()));   
	                                                            
	                      }

	                      else {
	                        
	                        interest  = (parseFloat(amountLoan) * 0) * (parseFloat($("select[name='totalMonths']").val()));
	                      }


	                      var totalPayment = parseFloat(amountLoan) + parseFloat(interest);
	                      var deduction = parseFloat(totalPayment) / parseFloat(totalMonths);

	                      // for 2 decimal places
	                      totalPayment = totalPayment.toString().split('e');
	                      totalPayment = Math.round(+(totalPayment[0] + 'e' + (totalPayment[1] ? (+totalPayment[1] + 2) : 2)));

	                      totalPayment = totalPayment.toString().split('e');
	                      final_totalPayment =  (+(totalPayment[0] + 'e' + (totalPayment[1] ? (+totalPayment[1] - 2) : -2))).toFixed(2);
	                      $("input[name='totalPayment']").val(final_totalPayment);
	              

	                      // for 2 decimal places
	                      deduction = deduction.toString().split('e');
	                      deduction = Math.round(+(deduction[0] + 'e' + (deduction[1] ? (+deduction[1] + 2) : 2)));

	                      deduction = deduction.toString().split('e');
	                      final_deduction =  (+(deduction[0] + 'e' + (deduction[1] ? (+deduction[1] - 2) : -2))).toFixed(2);

	                      $("input[name='deduction']").val(final_deduction);
	                    }
	              });

	             
	            }
	          }
	         
	       }
	    });


	    $("select[name='totalMonths']").change(function(){

		       //alert("HELLO WORLD!");

		         //alert("Wew");
		          /*var totalMonths = parseInt($(this).val()) + 1;

		    


		          // alert(totalMonths);

		          var currentDate = new Date($("input[name='dateFromFileSalaryLoan']").val());
		          var currentMonth = currentDate.setMonth(currentDate.getMonth() + totalMonths);

		          //alert(currentMonth);
		          var month = currentDate.getMonth();

		          //alert(month);

		          // for december
		          if (month == 0){
		              month = 12;
		             
		          }




		          

		          //alert(getMonth);

		          var currentYear = currentDate.getFullYear();

		          if (month == 0){
		              currentYear = currentYear - 1;
		          }

		          var currentDay = currentDate.getDate();

		          var newDate = month + "/" + currentDay + "/" + currentYear;
		          $("input[name='dateTo']").val(newDate);
		        */
		        var totalMonths = parseFloat($(this).val());

		      	// alert(totalMonths);

		        if (totalMonths != 0) {

		          var dateFrom = $("select[name='dateFromMonth']").val() + "/"+$("select[name='dateFromDay']").val() +"/"+$("select[name='dateFromYear']").val();

		         // alert(dateFrom);

		          //alert(totalMonths);
		          var nextMonth = addMonths(new Date(dateFrom), totalMonths);

		         // var newYear = addMonths(new Date($("input[name='dateFromFileSalaryLoan']").val()), $(this).val());

		          //alert(nextMonth);


		          var currentMonth = nextMonth.getMonth() + 1;
		          //alert(currentMonth);
		          //alert(currentMonth);
		          if (currentMonth == 0){
		              currentMonth= 12;
		          }

		          //alert("Hello World!");
		         // alert(nextMonth);







		          var currentDate = new Date(dateFrom);
		          var currentDay = currentDate.getDate();

		          var currentYear = nextMonth.getFullYear();

		        
		        if ($("select[name='deductionType']").val() == "Semi-monthly") {
		          if (currentDay == 30){
		              currentDay = 15;
		           }

		           else if (currentDay == 15){
		              currentDay = 30;
		              currentMonth = currentMonth - 1;
		           }      
		         }

		         else {
		         	currentMonth = currentMonth - 1;
		         }


		          if (currentMonth == 2 && currentDay == 30){
		                currentDay = 28;
		           }



		         if (currentDay == 28){
		            currentDay = 30;
		         }


		          //alert(currentYear);

		          //if (currentDay)

		          if (currentMonth == 0){
		          	currentMonth = 12;
		          	currentYear = currentYear - 1;
		          }

		          var newDate = currentMonth + "/" + currentDay + "/" + currentYear;

		          

		          $("input[name='dateTo']").val(newDate);
		        }

		        else {
		          currentMonth = $("select[name='dateFromMonth']").val();
		          currentDay = $("select[name='dateFromDay']").val();
		          currentYear = $("select[name='dateFromYear']").val();

		           var newDate = currentMonth + "/" + currentDay + "/" + currentYear;
		           $("input[name='dateTo']").val(newDate);
		        }

		          $("input[name='amountLoan']").removeAttr("readonly"); 

		          // if may value
		          var amountLoan = $("input[name='amountLoan']").val();
		          if (amountLoan != ""){

		             var totalMonths = $("select[name='totalMonths']").val();

		             var deductionType = $("select[name='deductionType']").val();

		            if (deductionType != "" && deductionType != "Semi-monthly" && deductionType != "Monthly"){
		              $("div[id='error_message']").html("<span class='glyphicon glyphicon-remove' style='color:#CB4335;'></span> There's an error during submitting information please refresh the page.");
		            }

		            else if (totalMonths == 0){
		              $("input[name='deduction']").val(amountLoan);
		            }

		            else {
		              if (deductionType == "Semi-monthly" ) {
		                  totalMonths = parseInt(totalMonths) * 2;
		              }

		              if (deductionType == "" ) {
		                  $("div[id='errorDeduction']").html("<span class='glyphicon glyphicon-remove'></span> Please select <b>Deduction Type</b> first.");
		                  $("input[name='deduction']").val("");
		              }

		              else {
		                //alert("wew");

		                 $.ajax({
		                    type: "POST",
		                    url: "ajax/script_check_exist_loan.php",
		                    //data: datastring,
		                    cache: false,
		                    success: function (data) {
		                  //    alert(data);

		                     if (data == "exist" || $("input[name='amountLoan']").val() != $("input[name='totalPayment']").val()) {

		                     	interest_rate = .036;
		                      	if (loan_type == 3){
		                      		interest_rate = .01;
		                      	}

		                        interest = (parseFloat(amountLoan) * interest_rate) * (parseFloat($("select[name='totalMonths']").val()));   
		                                                            
		                      }

		                      else {
		                        
		                        interest  = (parseFloat(amountLoan) * 0) * (parseFloat($("select[name='totalMonths']").val()));
		                      }


		                    var totalPayment = parseFloat(amountLoan) + parseFloat(interest);
		                    var deduction = parseFloat(totalPayment) / parseFloat(totalMonths);

		                    // for 2 decimal places
		                    totalPayment = totalPayment.toString().split('e');
		                    totalPayment = Math.round(+(totalPayment[0] + 'e' + (totalPayment[1] ? (+totalPayment[1] + 2) : 2)));

		                    totalPayment = totalPayment.toString().split('e');
		                    final_totalPayment =  (+(totalPayment[0] + 'e' + (totalPayment[1] ? (+totalPayment[1] - 2) : -2))).toFixed(2);
		                    $("input[name='totalPayment']").val(final_totalPayment);

		                     // for 2 decimal places
		                    deduction = deduction.toString().split('e');
		                    deduction = Math.round(+(deduction[0] + 'e' + (deduction[1] ? (+deduction[1] + 2) : 2)));

		                    deduction = deduction.toString().split('e');
		                    final_deduction =  (+(deduction[0] + 'e' + (deduction[1] ? (+deduction[1] - 2) : -2))).toFixed(2);

		                     $("input[name='deduction']").val(final_deduction);

		                }
		              });
		            }
		          }
		        }

		    });

		

		var emp_years = <?php echo $years; ?>;
		$("select[name='totalMonths']").focus(function(){
     // alert("wew");

	          var dateformat = /^(0[1-9]|1[012])[\/\-](0[1-9]|[12][0-9]|3[01])[\/\-]\d{4}$/;
	          
	          var dateFromMonth = $("input[name='dateFromMonth']").val();
	          var dateFromDay = $("input[name='dateFromDay']").val();
	          var dateFromYear = $("input[name='dateFromYear']").val();

	          if (dateFromMonth == "" || dateFromDay == "" || dateFromYear){
	             $(this).html("");
	              $("div[id='errorTotalMonths']").html("<span class='glyphicon glyphicon-remove'></span> Please provide a <b>Date From</b> first");
	          }

	         /* else if (!dateFrom.match(dateformat)){
	              $(this).html("");
	              $("div[id='errorTotalMonths']").html("<span class='glyphicon glyphicon-remove'></span> Please provide a <b> Valid Date From</b>");
	          }*/

	          else {
	              $("div[id='errorTotalMonths']").html("");
	              //var option = "<option value=''></option>";

	              <?php
	              	if ($loan_type == 1){
	              ?>

	              var option0 = "";
	              if ($("select[name='deductionType']").val() == "" || $("select[name='deductionType']").val() == "Monthly"){
	                //var option0 = "<option value='0'>0</option>";
	                var option1 = "<option value='1'>1</option>";
	                var option2 = "<option value='2'>2</option>";
	                var option3 = "<option value='3'>3</option>";
	                var option4 = "<option value='4'>4</option>";
	                var option5 = "<option value='5'>5</option>";
	                var option6 = "<option value='6'>6</option>";
	                var option7 = "<option value='7'>7</option>";
	                var option8 = "<option value='8'>8</option>";
	                var option9 = "<option value='9'>9</option>";
	                var option10 = "<option value='10'>10</option>";
	                var option11 = "<option value='11'>11</option>";
	                var option12 = "<option value='12'>12</option>";
	                var option13 = "<option value='13'>13</option>";
	                var option14 = "<option value='14'>14</option>";
	                var option15 = "<option value='15'>15</option>";
	                var option16 = "<option value='16'>16</option>";
	                var option17 = "<option value='17'>17</option>";
	                var option18 = "<option value='18'>18</option>";
	                var option19 = "<option value='19'>19</option>";
	                var option20 = "<option value='20'>20</option>";
	                var option21 = "<option value='21'>21</option>";
	                var option22 = "<option value='22'>22</option>";
	                var option23 = "<option value='23'>23</option>";
	                var option24 = "<option value='24'>24</option>";
	              }
	              else {
	                var option0 = "<option value='0'>0</option>";
	                var option1 = "<option value='1'>1</option>";
	                var option2 = "<option value='2'>2</option>";
	                var option3 = "<option value='3'>3</option>";
	                var option4 = "<option value='4'>4</option>";
	                var option5 = "<option value='5'>5</option>";
	                var option6 = "<option value='6'>6</option>";
	                var option7 = "<option value='7'>7</option>";
	                var option8 = "<option value='8'>8</option>";
	                var option9 = "<option value='9'>9</option>";
	                var option10 = "<option value='10'>10</option>";
	                var option11 = "<option value='11'>11</option>";
	                var option12 = "<option value='12'>12</option>";
	                var option13 = "<option value='13'>13</option>";
	                var option14 = "<option value='14'>14</option>";
	                var option15 = "<option value='15'>15</option>";
	                var option16 = "<option value='16'>16</option>";
	                var option17 = "<option value='17'>17</option>";
	                var option18 = "<option value='18'>18</option>";
	                var option19 = "<option value='19'>19</option>";
	                var option20 = "<option value='20'>20</option>";
	                var option21 = "<option value='21'>21</option>";
	                var option22 = "<option value='22'>22</option>";
	                var option23 = "<option value='23'>23</option>";
	                var option24 = "<option value='24'>24</option>";
	              
	              }

	              var allOption =option0+ option1+option2+option3+option4+option5+option6+option7+option8+option9+option10+option11+option12+option13+option14+option15+option16+option17+option18+option19+option20+option21+option22+option23+option24;

	              $(this).html(allOption);

	              <?php
	              	}

	              	if ($loan_type == 3){

      			  ?>

      			  	  //alert(emp_years);
  			  		  var option0 = "";
  			  		  var option1 = "";
  			  		  var option2 = "";
  			  		  var option3 = "";
  			  		  var option4 = "";
  			  		  var option5 = "";
  			  		  var option6 = "";
  			  		  var option7 = "";
  			  		  var option8 = "";
  			  		  var option9 = "";
  			  		  var option10 = "";
  			  		  var option11 = "";
  			  		  var option12 = "";
  			  		  var option13 = "";
  			  		  var option14 = "";
  			  		  var option15 = "";
  			  		  var option16 = "";
  			  		  var option17 = "";
  			  		  var option18 = "";
  			  		  var option19 = "";
  			  		  var option20 = "";
  			  		  var option21 = "";
  			  		  var option22 = "";
  			  		  var option23 = "";
  			  		  var option24 = "";
		              if ($("select[name='deductionType']").val() == "" || $("select[name='deductionType']").val() == "Monthly"){
		                //var option0 = "<option value='0'>0</option>";


		                if (emp_years == 1){
		                	option1 = "<option value='1'>1</option>";
			                option2 = "<option value='2'>2</option>";
			                option3 = "<option value='3'>3</option>";
			                option4 = "<option value='4'>4</option>";
			                option5 = "<option value='5'>5</option>";
			                option6 = "<option value='6'>6</option>";
		                }

		                if (emp_years == 2 || emp_years == 3){
		                	option1 = "<option value='1'>1</option>";
			                option2 = "<option value='2'>2</option>";
			                option3 = "<option value='3'>3</option>";
			                option4 = "<option value='4'>4</option>";
			                option5 = "<option value='5'>5</option>";
			                option6 = "<option value='6'>6</option>";
			                option7 = "<option value='7'>7</option>";
			                option8 = "<option value='8'>8</option>";
			                option9 = "<option value='9'>9</option>";
			                option10 = "<option value='10'>10</option>";
			                option11 = "<option value='11'>11</option>";
			                option12 = "<option value='12'>12</option>";
		                }


		                if (emp_years == 4 || emp_years == 5 || emp_years >= 6 ){
		                	option1 = "<option value='1'>1</option>";
			                option2 = "<option value='2'>2</option>";
			                option3 = "<option value='3'>3</option>";
			                option4 = "<option value='4'>4</option>";
			                option5 = "<option value='5'>5</option>";
			                option6 = "<option value='6'>6</option>";
			                option7 = "<option value='7'>7</option>";
			                option8 = "<option value='8'>8</option>";
			                option9 = "<option value='9'>9</option>";
			                option10 = "<option value='10'>10</option>";
			                option11 = "<option value='11'>11</option>";
			                option12 = "<option value='12'>12</option>";
			                option13 = "<option value='13'>13</option>";
			                option14 = "<option value='14'>14</option>";
			                option15 = "<option value='15'>15</option>";
			                option16 = "<option value='16'>16</option>";
			                option17 = "<option value='17'>17</option>";
			                option18 = "<option value='18'>18</option>";
			                option19 = "<option value='19'>19</option>";
			                option20 = "<option value='20'>20</option>";
			                option21 = "<option value='21'>21</option>";
			                option22 = "<option value='22'>22</option>";
			                option23 = "<option value='23'>23</option>";
			                option24 = "<option value='24'>24</option>";
		                }

		                
		              }
		              else {



		                if (emp_years == 1){
		                	option0 = "<option value='0'>0</option>";
		                	option1 = "<option value='1'>1</option>";
			                option2 = "<option value='2'>2</option>";
			                option3 = "<option value='3'>3</option>";
			                option4 = "<option value='4'>4</option>";
			                option5 = "<option value='5'>5</option>";
			                option6 = "<option value='6'>6</option>";
		                }

		                if (emp_years == 2 || emp_years == 3){
		                	option0 = "<option value='0'>0</option>";
		                	option1 = "<option value='1'>1</option>";
			                option2 = "<option value='2'>2</option>";
			                option3 = "<option value='3'>3</option>";
			                option4 = "<option value='4'>4</option>";
			                option5 = "<option value='5'>5</option>";
			                option6 = "<option value='6'>6</option>";
			                option7 = "<option value='7'>7</option>";
			                option8 = "<option value='8'>8</option>";
			                option9 = "<option value='9'>9</option>";
			                option10 = "<option value='10'>10</option>";
			                option11 = "<option value='11'>11</option>";
			                option12 = "<option value='12'>12</option>";
		                }


		                if (emp_years == 4 || emp_years == 5 || emp_years >= 6 ){
		                	option0 = "<option value='0'>0</option>";
		                	option1 = "<option value='1'>1</option>";
			                option2 = "<option value='2'>2</option>";
			                option3 = "<option value='3'>3</option>";
			                option4 = "<option value='4'>4</option>";
			                option5 = "<option value='5'>5</option>";
			                option6 = "<option value='6'>6</option>";
			                option7 = "<option value='7'>7</option>";
			                option8 = "<option value='8'>8</option>";
			                option9 = "<option value='9'>9</option>";
			                option10 = "<option value='10'>10</option>";
			                option11 = "<option value='11'>11</option>";
			                option12 = "<option value='12'>12</option>";
			                option13 = "<option value='13'>13</option>";
			                option14 = "<option value='14'>14</option>";
			                option15 = "<option value='15'>15</option>";
			                option16 = "<option value='16'>16</option>";
			                option17 = "<option value='17'>17</option>";
			                option18 = "<option value='18'>18</option>";
			                option19 = "<option value='19'>19</option>";
			                option20 = "<option value='20'>20</option>";
			                option21 = "<option value='21'>21</option>";
			                option22 = "<option value='22'>22</option>";
			                option23 = "<option value='23'>23</option>";
			                option24 = "<option value='24'>24</option>";
		                }
		              
		              }

		              var allOption =option0+ option1+option2+option3+option4+option5+option6+option7+option8+option9+option10+option11+option12+option13+option14+option15+option16+option17+option18+option19+option20+option21+option22+option23+option24;
		              //alert(allOption);
		              $(this).html(allOption);
      			  <?php
	              	}
	              ?>


	            
	          }


	    });



	    // for changing amount loan amountLoan
	    $("input[name='amountLoan']").change(function(){

	    	//alert()
	        var totalMonths = $("select[name='totalMonths']").val();


	       if (loan_type == 1){
	       		//  alert("wew");
			        //alert(totalMonths);

			        var amountLoan = $(this).val();
			        var interest  = 0;
			        // .php
			       // var user_id = $("input[name='user_id']").val();
			       //// alert(user_id);
			        $.ajax({
			          type: "POST",
			          url: "ajax/script_check_exist_loan.php",
			          //data: datastring,
			          cache: false,
			          success: function (data) {
			               // alert(data);
			            
			                if (amountLoan == ""){
			                   $("input[name='deduction']").val("");
			                }

			                else if (totalMonths == 0){
			                  $("input[name='deduction']").val(amountLoan);
			                }
			                else {

			                  var deductionType = $("select[name='deductionType']").val();
			                  if (deductionType != "" && deductionType != "Semi-monthly" && deductionType != "Monthly"){
			                    $("div[id='error_message']").html("<span class='glyphicon glyphicon-remove' style='color:#CB4335;'></span> There's an error during submitting information please refresh the page.");
			                  }
			                  else {

			                  
			                    if (deductionType == "Semi-monthly" ) {
			                      totalMonths = parseInt(totalMonths) * 2;
			                    }

			                    if (deductionType == "" ) {
			                      $("div[id='errorTotalPayment']").html("<span class='glyphicon glyphicon-remove'></span> Please select <b>Deduction Type</b> first.");
			                      $("input[name='totalPayment']").val("");


			                      $("div[id='errorDeduction']").html("<span class='glyphicon glyphicon-remove'></span> Please select <b>Deduction Type</b> first.");
			                      $("input[name='deduction']").val("");
			                    }

			                    else {
			                      // alert(totalMonths);

			                      // if (data == "exist") {

			                      	interest_rate = .036;
			                      	if (loan_type == 3){
			                      		interest_rate = .01;
			                      	}

			                      	//alert(loan_type);

			                        interest = (parseFloat(amountLoan) * interest_rate) * (parseFloat($("select[name='totalMonths']").val()));   
			                                                            
			                      /*}

			                      else {
			                        
			                        interest  = (parseFloat(amountLoan) * 0) * (parseFloat($("select[name='totalMonths']").val()));
			                      }*/




			                      var totalPayment = parseFloat(amountLoan) + parseFloat(interest);
			                      var deduction = parseFloat(totalPayment) / parseFloat(totalMonths);

			                      // for 2 decimal places
			                      totalPayment = totalPayment.toString().split('e');
			                      totalPayment = Math.round(+(totalPayment[0] + 'e' + (totalPayment[1] ? (+totalPayment[1] + 2) : 2)));

			                      totalPayment = totalPayment.toString().split('e');
			                      final_totalPayment =  (+(totalPayment[0] + 'e' + (totalPayment[1] ? (+totalPayment[1] - 2) : -2))).toFixed(2);
			                      $("input[name='totalPayment']").val(final_totalPayment);

			                      // for 2 decimal places
			                      deduction = deduction.toString().split('e');
			                      deduction = Math.round(+(deduction[0] + 'e' + (deduction[1] ? (+deduction[1] + 2) : 2)));

			                      deduction = deduction.toString().split('e');
			                      final_deduction =  (+(deduction[0] + 'e' + (deduction[1] ? (+deduction[1] - 2) : -2))).toFixed(2);
			                      $("input[name='deduction']").val(final_deduction);
			                    }
			                  }
			                }
			            
			          
			            
			          }
			       });
	       }

	        if (loan_type == 3){
	        	//alert("wew");
	        	 var min_amount = $(this).attr("min");
       			 var max_amount = $(this).attr("max");


		        if ($(this).val() > max_amount && $(this).val() < min_amount){
		        	$("div[id='error_message']").html("<span class='glyphicon glyphicon-remove' style='color:#CB4335;'></span> Amount must be greater than or equal to the minimum amount of "+min_amount+" and less than or greater than the maximum amount of "+max_amount+".");
		       	
		        }

		        else {
		          
			      //  alert("wew");
			        //alert(totalMonths);

			        var amountLoan = $(this).val();
			        var interest  = 0;
			        // .php
			       // var user_id = $("input[name='user_id']").val();
			       //// alert(user_id);
			        $.ajax({
			          type: "POST",
			          url: "ajax/script_check_exist_loan.php",
			          //data: datastring,
			          cache: false,
			          success: function (data) {
			               // alert(data);
			            
			                if (amountLoan == ""){
			                   $("input[name='deduction']").val("");
			                }

			                else if (totalMonths == 0){
			                  $("input[name='deduction']").val(amountLoan);
			                }
			                else {

			                  var deductionType = $("select[name='deductionType']").val();
			                  if (deductionType != "" && deductionType != "Semi-monthly" && deductionType != "Monthly"){
			                    $("div[id='error_message']").html("<span class='glyphicon glyphicon-remove' style='color:#CB4335;'></span> There's an error during submitting information please refresh the page.");
			                  }
			                  else {

			                  
			                    if (deductionType == "Semi-monthly" ) {
			                      totalMonths = parseInt(totalMonths) * 2;
			                    }

			                    if (deductionType == "" ) {
			                      $("div[id='errorTotalPayment']").html("<span class='glyphicon glyphicon-remove'></span> Please select <b>Deduction Type</b> first.");
			                      $("input[name='totalPayment']").val("");


			                      $("div[id='errorDeduction']").html("<span class='glyphicon glyphicon-remove'></span> Please select <b>Deduction Type</b> first.");
			                      $("input[name='deduction']").val("");
			                    }

			                    else {
			                      // alert(totalMonths);

			                      // if (data == "exist") {

			                      	interest_rate = .036;
			                      	if (loan_type == 3){
			                      		interest_rate = .01;
			                      	}

			                      	//alert(loan_type);

			                        interest = (parseFloat(amountLoan) * interest_rate) * (parseFloat($("select[name='totalMonths']").val()));   
			                                                            
			                      /*}

			                      else {
			                        
			                        interest  = (parseFloat(amountLoan) * 0) * (parseFloat($("select[name='totalMonths']").val()));
			                      }*/




			                      var totalPayment = parseFloat(amountLoan) + parseFloat(interest);
			                      var deduction = parseFloat(totalPayment) / parseFloat(totalMonths);

			                      // for 2 decimal places
			                      totalPayment = totalPayment.toString().split('e');
			                      totalPayment = Math.round(+(totalPayment[0] + 'e' + (totalPayment[1] ? (+totalPayment[1] + 2) : 2)));

			                      totalPayment = totalPayment.toString().split('e');
			                      final_totalPayment =  (+(totalPayment[0] + 'e' + (totalPayment[1] ? (+totalPayment[1] - 2) : -2))).toFixed(2);
			                      $("input[name='totalPayment']").val(final_totalPayment);

			                      // for 2 decimal places
			                      deduction = deduction.toString().split('e');
			                      deduction = Math.round(+(deduction[0] + 'e' + (deduction[1] ? (+deduction[1] + 2) : 2)));

			                      deduction = deduction.toString().split('e');
			                      final_deduction =  (+(deduction[0] + 'e' + (deduction[1] ? (+deduction[1] - 2) : -2))).toFixed(2);
			                      $("input[name='deduction']").val(final_deduction);
			                    }
			                  }
			                }
			            
			          
			            
			          }
			       });
			    }

		    }


	        

	    });



	    // error_message
	    $("button[id='file_salary_btn']").on("click",function(){
	       // alert("Wew");
	         var deductionType = $("select[name='deductionType']").val();
	         var dateFrom = $("input[name='dateFromFileSalaryLoan']").val();
	         var totalMonths = $("input[name='totalMonths']").val();
	         var dateTo = $("input[name='dateTo']").val();
	         var amountLoan = $("input[name='amountLoan']").val();
	         var deduction = $("input[name='deduction']").val(); // totalPayment
	         var totalPayment = $("input[name='totalPayment']").val(); 
	         var remarks = $("textarea[name='remarks']").val();

	         //alert(deductionType);

	         // for please up all fields
	         if (deductionType == "" || dateFrom == "" || totalMonths == "" || dateTo== "" || amountLoan == "" || totalPayment == "" || deduction == "" || remarks == ""){
	            $("div[id='error_message']").html("<span class='glyphicon glyphicon-remove' style='color:#CB4335;'></span> Please fill up all fields.");
	         }

	         else if (deductionType == "Monthly" && !$("input[name='opt_deductedPayrollDate']").is(':checked')){
	            $("div[id='error_message']").html("<span class='glyphicon glyphicon-remove' style='color:#CB4335;'></span> Please choose date payroll to be deducted.");
	         }

	         // if edited in the inspect element
	         else if (deductionType != "" && deductionType != "Semi-monthly" && deductionType != "Monthly"){
	            $("div[id='error_message']").html("<span class='glyphicon glyphicon-remove' style='color:#CB4335;'></span> There's an error during submitting information please refresh the page.");
	         }

	         else {
	            $("#form_create_loan_payment_schedule").attr("action","php script/script_create_loan_payment_schedule.php?file_loan_id=<?php  echo $file_loan_id; ?>");
	            $("#form_create_loan_payment_schedule").unbind().submit();
	         }
	    });



	     // for next year
	    function isLeapYear(year) { 
	        return (((year % 4 === 0) && (year % 100 !== 0)) || (year % 400 === 0)); 
	    }

	    function getDaysInMonth(year, month) {
	        return [31, (isLeapYear(year) ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][month];
	    }

	    function addMonths(date, value) {
	        var d = new Date(date),
	            n = date.getDate();
	        d.setDate(1);
	        d.setMonth(d.getMonth() + value);
	        d.setDate(Math.min(n, getDaysInMonth(d.getFullYear(), d.getMonth())));
	        return d;
	    }
	});

</script>

<?php

}


else if ($loan_type == 2){

	$row_emp = $emp_loan_class->getEmpInfoByRow($row->emp_id);

	$emp_full_name = $row_emp->Firstname . " " . $row_emp->Lastname;

?>
<form class="form-horizontal" action="" id="form_addSimkimbanLoan" method="post">
	<div class="form-group">
		<div class="col-sm-12">
			<label class="control-label col-md-3" style="color:#158cba">Employee Name:</label>
			<div class="col-sm-7" style="margin-right:-15px;">
				<input type="text" class="form-control" value="<?php echo $emp_full_name; ?>" name="empName" placeholder="Employee Name ..." id="input_payroll" required="required" readonly="readonly"/>
			</div>
		</div>
		
	</div>


	<div class="form-group">
		<div class="col-sm-12">
			<label class="control-label col-md-3" style="color:#158cba">Items:</label>
			<div class="col-sm-7" style="margin-right:-15px;">
				<input type="text" class="form-control" value="" name="item" placeholder="Item ..." id="input_payroll" required="required"/>
			</div>
		</div>
		
	</div>
	<div class="form-group">
		<div class="col-sm-12">
			<label class="control-label col-md-3" style="color:#158cba">Deduction Type:</label>
			<div class="col-md-7">
				<select class="form-control" required="required" name="deductionType">
					<option value=""></option>	
					<option value="Semi-monthly">Semi-monthly</option>	
					<option value="Monthly">Monthly</option>													
				</select>
			</div>

		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-12">
			<label class="control-label col-md-3" style="color:#158cba">If monthly, specify the date payroll to be deducted:</label>
			<div class="col-md-9">
				<label class="radio-inline"><input type="radio" value="15" name="opt_deductedPayrollDate" disabled="disabled">15</label>
				<label class="radio-inline"><input type="radio" value="30" name="opt_deductedPayrollDate" disabled="disabled">30</label>
			</div>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-12">
			<label class="control-label col-md-3" style="color:#158cba">Date From :</label>
			<!--<div class="col-md-4">
				<input type="text" required="required" class="form-control" autocomplete="off" id="number_only" value="" name="dateFromFileSalaryLoan" placeholder="MM"/>
			</div>-->
			
			
			<div class="col-md-3" style="margin-right:-5%;">
				<select class="form-control" style="width:70%;" required="required" name="dateFromMonth" data-toggle="tooltip" data-placement="top" title="Month">
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					<option value="9">9</option>
					<option value="10">10</option>
					<option value="11">11</option>
					<option value="12">12</option>
					<option value="13">13</option>
					<option value="14">14</option>
					<option value="15">15</option>
					<option value="16">16</option>
					<option value="17">17</option>
					<option value="18">18</option>
					<option value="19">19</option>
					<option value="20">20</option>
					<option value="21">21</option>
					<option value="22">22</option>
					<option value="23">23</option>
					<option value="24">24</option>
				</select>
			</div>
			<div class="col-md-3" style="margin-right:-5%;">
				<select class="form-control" style="width:70%;" required="required" name="dateFromDay" data-toggle="tooltip" data-placement="top" title="Day">
					<option value="15">15</option>	
					<option value="30">30</option>												
				</select>
			</div>

			<div class="col-md-3">
				<select class="form-control" style="width:70%;" required="required" name="dateFromYear" data-toggle="tooltip" data-placement="top" title="Year">
					<?php
						$year = date('Y');
						$nextYear = $year + 1;

					?>
					<option value="<?php echo $year; ?>"><?php echo $year; ?></option>	
					<option value="<?php echo $nextYear; ?>"><?php echo $nextYear; ?></option>													
				</select>
			</div>
		
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-12">
			<label class="control-label col-md-3" style="color:#158cba">Total Months:</label>
			<div class="col-md-7">
				<select class="form-control" required="required" name="totalMonths">
					<option value=""></option>													
				</select>
			</div>
			<div style="color:#CB4335;margin-top:10px;" id="errorTotalMonths"></div>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-12">
			<label class="control-label col-md-3" style="color:#158cba">Date To :</label>
			<div class="col-md-7">
				<input type="text" class="form-control" readonly="readonly" name="dateTo" data-toggle="tooltip" data-placement="top" title="Format:mm/dd/yyyy ex. 01/01/1990" autocomplete="off" id="date_only" value="" placeholder=""/>
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-12">
			<label class="control-label col-md-3" style="color:#158cba">Amount Loan:</label>
			<div class="col-md-7">
				<input type="text" class="form-control"  name="amountLoan"  value="" readonly="readonly" placeholder="" id="float_only"/>
			</div>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-12">
			<label class="control-label col-md-3" style="color:#158cba">Total Payment:</label>
			<div class="col-md-5">
				<input type="text" class="form-control"  name="totalPayment"  readonly="readonly" placeholder="" id="float_only"/>
				
			</div>
			<div style="color:#CB4335;margin-top:10px;" id="errorTotalPayment">&nbsp;</div>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-12">
			<label class="control-label col-md-3" style="color:#158cba">Deduction:</label>
			<div class="col-md-5">
				<input type="text" class="form-control"  name="deduction" placeholder="" readonly="readonly"/>
			</div>
			<div style="color:#CB4335;margin-top:10px;" id="errorDeduction">&nbsp;</div>
		</div>
	</div>

	<!--<div class="form-group">
		<div class="col-sm-12">
			<label class="control-label col-md-3" style="color:#158cba">Remarks:</label>
			<div class="col-md-7">
				<textarea class="form-control" name="remarks" required="required"><?php echo $row->purpose; ?></textarea>
			</div>
			
		</div>
	</div> -->
	
	<div class="form-group">
		<div class="col-sm-12">
			<div class="col-md-10">
				<button type="button" class="btn btn-primary btn-sm pull-right" id="file_salary_btn">File Loan</button>
			</div>
		
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-12">
			<div class="col-md-offset-4 col-md-4" id="error_message">&nbsp;</div>
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

		$("#form_addSimkimbanLoan").submit(function(event) {          
              event.preventDefault();
             
            });

		$("select[name='deductionType']").change(function(){
	        var deductionType = $(this).val();

	        if (deductionType == ""){
	            //$("div[id='errorDeduction']").html("<span class='glyphicon glyphicon-remove'></span> Please select <b>Deduction Type</b> first.");
	            $("input[name='deduction']").val("");
	            $("input[name='opt_deductedPayrollDate']").attr("disabled","disabled");
	            $("input[name='opt_deductedPayrollDate']").removeAttr("checked",false);
	        }
	        else {

	          if (deductionType != "" && (deductionType != "Semi-monthly" && deductionType != "Monthly")){
	            $("div[id='error_message']").html("<span class='glyphicon glyphicon-remove' style='color:#CB4335;'></span> There's an error during submitting information please refresh the page.");
	          }
	          else {
	            if (deductionType == "Semi-monthly") {
	                $("input[name='opt_deductedPayrollDate']").attr("disabled","disabled");
	                $("input[name='opt_deductedPayrollDate']").removeAttr("checked",false);
	            }


	            if (deductionType == "Monthly") {
	                $("input[name='opt_deductedPayrollDate']").removeAttr("disabled");
	            }

	            var totalMonths = $("select[name='totalMonths']").val();


	            var deductionType = $("select[name='deductionType']").val();



	            if (deductionType == "Semi-monthly" ) {
	              totalMonths = parseInt(totalMonths) * 2;

	            }

	            var amountLoan = $("input[name='amountLoan']").val();

	            if (amountLoan != ""){


	               $.ajax({
	                    type: "POST",
	                    url: "ajax/script_check_exist_loan.php",
	                    //data: datastring,
	                    cache: false,
	                    success: function (data) {



	                      if (data == "exist" || $("input[name='amountLoan']").val() != $("input[name='totalPayment']").val()) {

	                      	$interest_rate = .036;
	                      	if ($("select[name='totalMonths']").val() == 1 || $("select[name='totalMonths']").val() == 0){
	                      		$interest_rate = 0;
	                      	}

	                        interest = (parseFloat(amountLoan) * $interest_rate) * (parseFloat($("select[name='totalMonths']").val()));   
	                                                            
	                      }

	                      else {
	                        
	                        interest  = (parseFloat(amountLoan) * 0) * (parseFloat($("select[name='totalMonths']").val()));
	                      }


	                      var totalPayment = parseFloat(amountLoan) + parseFloat(interest);
	                      var deduction = parseFloat(totalPayment) / parseFloat(totalMonths);

	                      // for 2 decimal places
	                      totalPayment = totalPayment.toString().split('e');
	                      totalPayment = Math.round(+(totalPayment[0] + 'e' + (totalPayment[1] ? (+totalPayment[1] + 2) : 2)));

	                      totalPayment = totalPayment.toString().split('e');
	                      final_totalPayment =  (+(totalPayment[0] + 'e' + (totalPayment[1] ? (+totalPayment[1] - 2) : -2))).toFixed(2);
	                      $("input[name='totalPayment']").val(final_totalPayment);
	              

	                      // for 2 decimal places
	                      deduction = deduction.toString().split('e');
	                      deduction = Math.round(+(deduction[0] + 'e' + (deduction[1] ? (+deduction[1] + 2) : 2)));

	                      deduction = deduction.toString().split('e');
	                      final_deduction =  (+(deduction[0] + 'e' + (deduction[1] ? (+deduction[1] - 2) : -2))).toFixed(2);

	                      $("input[name='deduction']").val(final_deduction);
	                    }
	              });

	             
	            }
	          }
	         
	       }
	    });


	    $("select[name='totalMonths']").change(function(){

		       //alert("HELLO WORLD!");

		         //alert("Wew");
		          /*var totalMonths = parseInt($(this).val()) + 1;

		    


		          // alert(totalMonths);

		          var currentDate = new Date($("input[name='dateFromFileSalaryLoan']").val());
		          var currentMonth = currentDate.setMonth(currentDate.getMonth() + totalMonths);

		          //alert(currentMonth);
		          var month = currentDate.getMonth();

		          //alert(month);

		          // for december
		          if (month == 0){
		              month = 12;
		             
		          }




		          

		          //alert(getMonth);

		          var currentYear = currentDate.getFullYear();

		          if (month == 0){
		              currentYear = currentYear - 1;
		          }

		          var currentDay = currentDate.getDate();

		          var newDate = month + "/" + currentDay + "/" + currentYear;
		          $("input[name='dateTo']").val(newDate);
		        */
		        var totalMonths = parseFloat($(this).val());

		      	// alert(totalMonths);

		        if (totalMonths != 0) {

		          var dateFrom = $("select[name='dateFromMonth']").val() + "/"+$("select[name='dateFromDay']").val() +"/"+$("select[name='dateFromYear']").val();

		         // alert(dateFrom);

		          //alert(totalMonths);
		          var nextMonth = addMonths(new Date(dateFrom), totalMonths);

		         // var newYear = addMonths(new Date($("input[name='dateFromFileSalaryLoan']").val()), $(this).val());

		          //alert(nextMonth);


		          var currentMonth = nextMonth.getMonth() + 1;
		          //alert(currentMonth);
		          //alert(currentMonth);
		          if (currentMonth == 0){
		              currentMonth= 12;
		          }

		          //alert("Hello World!");
		         // alert(nextMonth);







		          var currentDate = new Date(dateFrom);
		          var currentDay = currentDate.getDate();

		          var currentYear = nextMonth.getFullYear();

		        
		        if ($("select[name='deductionType']").val() == "Semi-monthly") {
		          if (currentDay == 30){
		              currentDay = 15;
		           }

		           else if (currentDay == 15){
		              currentDay = 30;
		              currentMonth = currentMonth - 1;
		           }      
		         }

		         else {
		         	currentMonth = currentMonth - 1;
		         }


		          if (currentMonth == 2 && currentDay == 30){
		                currentDay = 28;
		           }



		         if (currentDay == 28){
		            currentDay = 30;
		         }


		          //alert(currentYear);

		          //if (currentDay)

		          if (currentMonth == 0){
		          	currentMonth = 12;
		          	currentYear = currentYear - 1;
		          }

		          var newDate = currentMonth + "/" + currentDay + "/" + currentYear;

		          

		          $("input[name='dateTo']").val(newDate);
		        }

		        else {
		          currentMonth = $("select[name='dateFromMonth']").val();
		          currentDay = $("select[name='dateFromDay']").val();
		          currentYear = $("select[name='dateFromYear']").val();

		           var newDate = currentMonth + "/" + currentDay + "/" + currentYear;
		           $("input[name='dateTo']").val(newDate);
		        }

		          $("input[name='amountLoan']").removeAttr("readonly"); 

		          // if may value
		          var amountLoan = $("input[name='amountLoan']").val();
		          if (amountLoan != ""){

		             var totalMonths = $("select[name='totalMonths']").val();

		             var deductionType = $("select[name='deductionType']").val();

		            if (deductionType != "" && deductionType != "Semi-monthly" && deductionType != "Monthly"){
		              $("div[id='error_message']").html("<span class='glyphicon glyphicon-remove' style='color:#CB4335;'></span> There's an error during submitting information please refresh the page.");
		            }

		            else if (totalMonths == 0){
		              $("input[name='deduction']").val(amountLoan);
		            }

		            else {
		              if (deductionType == "Semi-monthly" ) {
		                  totalMonths = parseInt(totalMonths) * 2;
		              }

		              if (deductionType == "" ) {
		                  $("div[id='errorDeduction']").html("<span class='glyphicon glyphicon-remove'></span> Please select <b>Deduction Type</b> first.");
		                  $("input[name='deduction']").val("");
		              }

		              else {
		                //alert("wew");

		                 $.ajax({
		                    type: "POST",
		                    url: "ajax/script_check_exist_loan.php",
		                    //data: datastring,
		                    cache: false,
		                    success: function (data) {
		                  //    alert(data);

		                     if (data == "exist" || $("input[name='amountLoan']").val() != $("input[name='totalPayment']").val()) {

		                     	$interest_rate = .036;
		                      	if ($("select[name='totalMonths']").val() == 1 || $("select[name='totalMonths']").val() == 0){
		                      		$interest_rate = 0;
		                      	}

		                        interest = (parseFloat(amountLoan) * $interest_rate) * (parseFloat($("select[name='totalMonths']").val()));   
		                                                            
		                      }

		                      else {
		                        
		                        interest  = (parseFloat(amountLoan) * 0) * (parseFloat($("select[name='totalMonths']").val()));
		                      }


		                    var totalPayment = parseFloat(amountLoan) + parseFloat(interest);
		                    var deduction = parseFloat(totalPayment) / parseFloat(totalMonths);

		                    // for 2 decimal places
		                    totalPayment = totalPayment.toString().split('e');
		                    totalPayment = Math.round(+(totalPayment[0] + 'e' + (totalPayment[1] ? (+totalPayment[1] + 2) : 2)));

		                    totalPayment = totalPayment.toString().split('e');
		                    final_totalPayment =  (+(totalPayment[0] + 'e' + (totalPayment[1] ? (+totalPayment[1] - 2) : -2))).toFixed(2);
		                    $("input[name='totalPayment']").val(final_totalPayment);

		                     // for 2 decimal places
		                    deduction = deduction.toString().split('e');
		                    deduction = Math.round(+(deduction[0] + 'e' + (deduction[1] ? (+deduction[1] + 2) : 2)));

		                    deduction = deduction.toString().split('e');
		                    final_deduction =  (+(deduction[0] + 'e' + (deduction[1] ? (+deduction[1] - 2) : -2))).toFixed(2);

		                     $("input[name='deduction']").val(final_deduction);

		                }
		              });
		            }
		          }
		        }

		    });


		$("select[name='totalMonths']").focus(function(){
     // alert("wew");

	          var dateformat = /^(0[1-9]|1[012])[\/\-](0[1-9]|[12][0-9]|3[01])[\/\-]\d{4}$/;
	          
	          var dateFromMonth = $("input[name='dateFromMonth']").val();
	          var dateFromDay = $("input[name='dateFromDay']").val();
	          var dateFromYear = $("input[name='dateFromYear']").val();

	          if (dateFromMonth == "" || dateFromDay == "" || dateFromYear){
	             $(this).html("");
	              $("div[id='errorTotalMonths']").html("<span class='glyphicon glyphicon-remove'></span> Please provide a <b>Date From</b> first");
	          }

	         /* else if (!dateFrom.match(dateformat)){
	              $(this).html("");
	              $("div[id='errorTotalMonths']").html("<span class='glyphicon glyphicon-remove'></span> Please provide a <b> Valid Date From</b>");
	          }*/

	          else {
	              $("div[id='errorTotalMonths']").html("");
	              //var option = "<option value=''></option>";

	              var option0 = "";
	              if ($("select[name='deductionType']").val() == "" || $("select[name='deductionType']").val() == "Monthly"){
	                //var option0 = "<option value='0'>0</option>";
	                var option1 = "<option value='1'>1</option>";
	                var option2 = "<option value='2'>2</option>";
	                var option3 = "<option value='3'>3</option>";
	                var option4 = "<option value='4'>4</option>";
	                var option5 = "<option value='5'>5</option>";
	                var option6 = "<option value='6'>6</option>";
	                var option7 = "<option value='7'>7</option>";
	                var option8 = "<option value='8'>8</option>";
	                var option9 = "<option value='9'>9</option>";
	                var option10 = "<option value='10'>10</option>";
	                var option11 = "<option value='11'>11</option>";
	                var option12 = "<option value='12'>12</option>";
	                var option13 = "<option value='13'>13</option>";
	                var option14 = "<option value='14'>14</option>";
	                var option15 = "<option value='15'>15</option>";
	                var option16 = "<option value='16'>16</option>";
	                var option17 = "<option value='17'>17</option>";
	                var option18 = "<option value='18'>18</option>";
	                var option19 = "<option value='19'>19</option>";
	                var option20 = "<option value='20'>20</option>";
	                var option21 = "<option value='21'>21</option>";
	                var option22 = "<option value='22'>22</option>";
	                var option23 = "<option value='23'>23</option>";
	                var option24 = "<option value='24'>24</option>";
	              }
	              else {
	                var option0 = "<option value='0'>0</option>";
	                var option1 = "<option value='1'>1</option>";
	                var option2 = "<option value='2'>2</option>";
	                var option3 = "<option value='3'>3</option>";
	                var option4 = "<option value='4'>4</option>";
	                var option5 = "<option value='5'>5</option>";
	                var option6 = "<option value='6'>6</option>";
	                var option7 = "<option value='7'>7</option>";
	                var option8 = "<option value='8'>8</option>";
	                var option9 = "<option value='9'>9</option>";
	                var option10 = "<option value='10'>10</option>";
	                var option11 = "<option value='11'>11</option>";
	                var option12 = "<option value='12'>12</option>";
	                var option13 = "<option value='13'>13</option>";
	                var option14 = "<option value='14'>14</option>";
	                var option15 = "<option value='15'>15</option>";
	                var option16 = "<option value='16'>16</option>";
	                var option17 = "<option value='17'>17</option>";
	                var option18 = "<option value='18'>18</option>";
	                var option19 = "<option value='19'>19</option>";
	                var option20 = "<option value='20'>20</option>";
	                var option21 = "<option value='21'>21</option>";
	                var option22 = "<option value='22'>22</option>";
	                var option23 = "<option value='23'>23</option>";
	                var option24 = "<option value='24'>24</option>";
	              
	              }

	              var allOption =option0+ option1+option2+option3+option4+option5+option6+option7+option8+option9+option10+option11+option12+option13+option14+option15+option16+option17+option18+option19+option20+option21+option22+option23+option24;

	              $(this).html(allOption);


	            
	          }


	    });



	    // for changing amount loan amountLoan
	    $("input[name='amountLoan']").change(function(){
	        var totalMonths = $("select[name='totalMonths']").val();
	          
	      //  alert("wew");
	        //alert(totalMonths);

	        var amountLoan = $(this).val();
	        var interest  = 0;
	        // .php
	       // var user_id = $("input[name='user_id']").val();
	       //// alert(user_id);
	        $.ajax({
	          type: "POST",
	          url: "ajax/script_check_exist_loan.php",
	          //data: datastring,
	          cache: false,
	          success: function (data) {
	               // alert(data);
	            
	                if (amountLoan == ""){
	                   $("input[name='deduction']").val("");
	                }

	                else if (totalMonths == 0){
	                  $("input[name='deduction']").val(amountLoan);
	                }
	                else {

	                  var deductionType = $("select[name='deductionType']").val();
	                  if (deductionType != "" && deductionType != "Semi-monthly" && deductionType != "Monthly"){
	                    $("div[id='error_message']").html("<span class='glyphicon glyphicon-remove' style='color:#CB4335;'></span> There's an error during submitting information please refresh the page.");
	                  }
	                  else {

	                  
	                    if (deductionType == "Semi-monthly" ) {
	                      totalMonths = parseInt(totalMonths) * 2;
	                    }

	                    if (deductionType == "" ) {
	                      $("div[id='errorTotalPayment']").html("<span class='glyphicon glyphicon-remove'></span> Please select <b>Deduction Type</b> first.");
	                      $("input[name='totalPayment']").val("");


	                      $("div[id='errorDeduction']").html("<span class='glyphicon glyphicon-remove'></span> Please select <b>Deduction Type</b> first.");
	                      $("input[name='deduction']").val("");
	                    }

	                    else {
	                      // alert(totalMonths);

	                      // if (data == "exist") {

	                      	$interest_rate = .036;
	                      	if ($("select[name='totalMonths']").val() == 1 || $("select[name='totalMonths']").val() == 0){
	                      		$interest_rate = 0;
	                      	}

	                        interest = (parseFloat(amountLoan) * $interest_rate) * (parseFloat($("select[name='totalMonths']").val()));   
	                                                            
	                      /*}

	                      else {
	                        
	                        interest  = (parseFloat(amountLoan) * 0) * (parseFloat($("select[name='totalMonths']").val()));
	                      }*/




	                      var totalPayment = parseFloat(amountLoan) + parseFloat(interest);
	                      var deduction = parseFloat(totalPayment) / parseFloat(totalMonths);

	                      // for 2 decimal places
	                      totalPayment = totalPayment.toString().split('e');
	                      totalPayment = Math.round(+(totalPayment[0] + 'e' + (totalPayment[1] ? (+totalPayment[1] + 2) : 2)));

	                      totalPayment = totalPayment.toString().split('e');
	                      final_totalPayment =  (+(totalPayment[0] + 'e' + (totalPayment[1] ? (+totalPayment[1] - 2) : -2))).toFixed(2);
	                      $("input[name='totalPayment']").val(final_totalPayment);

	                      // for 2 decimal places
	                      deduction = deduction.toString().split('e');
	                      deduction = Math.round(+(deduction[0] + 'e' + (deduction[1] ? (+deduction[1] + 2) : 2)));

	                      deduction = deduction.toString().split('e');
	                      final_deduction =  (+(deduction[0] + 'e' + (deduction[1] ? (+deduction[1] - 2) : -2))).toFixed(2);
	                      $("input[name='deduction']").val(final_deduction);
	                    }
	                  }
	                }
	            
	          
	            
	          }
	       });


	        

	    });



	    // error_message
	    $("button[id='file_salary_btn']").on("click",function(){
	       // alert("Wew");
	       	 var item = $("input[name='item']").val();
	         var deductionType = $("select[name='deductionType']").val();
	         var dateFrom = $("input[name='dateFromFileSalaryLoan']").val();
	         var totalMonths = $("input[name='totalMonths']").val();
	         var dateTo = $("input[name='dateTo']").val();
	         var amountLoan = $("input[name='amountLoan']").val();
	         var deduction = $("input[name='deduction']").val(); // totalPayment
	         var totalPayment = $("input[name='totalPayment']").val(); 
	         //var remarks = $("textarea[name='remarks']").val();

	         //alert(deductionType);

	         // for please up all fields
	         if (deductionType == "" || dateFrom == "" || totalMonths == "" || dateTo== "" || amountLoan == "" || totalPayment == "" || deduction == "" || item == ""){
	            $("div[id='error_message']").html("<span class='glyphicon glyphicon-remove' style='color:#CB4335;'></span> Please fill up all fields.");
	         }

	         else if (deductionType == "Monthly" && !$("input[name='opt_deductedPayrollDate']").is(':checked')){
	            $("div[id='error_message']").html("<span class='glyphicon glyphicon-remove' style='color:#CB4335;'></span> Please choose date payroll to be deducted.");
	         }

	         // if edited in the inspect element
	         else if (deductionType != "" && deductionType != "Semi-monthly" && deductionType != "Monthly"){
	            $("div[id='error_message']").html("<span class='glyphicon glyphicon-remove' style='color:#CB4335;'></span> There's an error during submitting information please refresh the page.");
	         }

	         else {
	           $("#form_addSimkimbanLoan").attr("action","php script/add_simkimban.php?file_loan_id=<?php  echo $file_loan_id; ?>");
	           $("#form_addSimkimbanLoan").unbind().submit();
	         }
	    });



	     // for next year
	    function isLeapYear(year) { 
	        return (((year % 4 === 0) && (year % 100 !== 0)) || (year % 400 === 0)); 
	    }

	    function getDaysInMonth(year, month) {
	        return [31, (isLeapYear(year) ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][month];
	    }

	    function addMonths(date, value) {
	        var d = new Date(date),
	            n = date.getDate();
	        d.setDate(1);
	        d.setMonth(d.getMonth() + value);
	        d.setDate(Math.min(n, getDaysInMonth(d.getFullYear(), d.getMonth())));
	        return d;
	    }
	});

</script>
<?php
}	

?>

<?php
	}

	else {
		header("Location:../MainForm.php");
	}
	
?>