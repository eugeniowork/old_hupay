<?php
include "../class/connect.php";
include "../class/emp_information.php";

if (isset($_POST["emp_id"])){

	$emp_info_class = new EmployeeInformation;

	$emp_id = $_POST["emp_id"];

	if ($emp_info_class->checkExistEmpId($emp_id) == 0){
		echo "Error";
	}

	else {
?>
	<div class="container-fluid">
		<form class="form-horizontal" method="post" id="form_increase_salary">
			<div class="form-group">
				<div class="col-md-12">
					<label><span class="glyphicon glyphicon-ruble" style="color:#27ae60"></span>&nbsp;New Salary:</label>
					<input type="text" class="form-control" name="new_salary" id="float_only" required="required"/>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-12">
					<label><span class="glyphicon glyphicon-calendar" style="color:#27ae60"></span>&nbsp;Date Increase:</label>
					<input type="text" class="form-control" name="date_increase" id="input_payroll" required="required"/>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-12">
					<input type="submit" class="btn btn-primary btn-sm pull-right" value="Submit" id="submit_increase"/>
				</div>
			</div>
		</form>
	</div>
	<script>
		$(document).ready(function(){
			//alert("HELLO WORLD!");
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


		      //  input_payroll
		      $("input[id='input_payroll']").keydown(function (e) {
		      //  return false;
		        if(e.keyCode != 116) {
		            return false;
		        }
		      });


		      $("input[name='date_increase']").dcalendarpicker();


		    

		      $("input[id='submit_increase']").on("click",function(){
		      	  $("#form_increase_salary" ).submit(function(event) {          
	                  event.preventDefault();
	                 
	               });

		      	  if ($("input[name='new_salary']").val() != "" && $("input[name='date_increase']").val() != ""){
		      	  	$("form[id='form_increase_salary']").append("<input type='hidden' value='<?php echo $emp_id; ?>' name='emp_id'/>");
		      	  	$("form[id='form_increase_salary']").attr("action","php script/increase_salary.php");
		      	  	$("#form_increase_salary" ).unbind().submit();
		      	  }
		      });


		});

	</script>
<?php
	}

}
else {
	header("Location:../dashboard.php");
}




?>