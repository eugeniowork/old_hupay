<?php
include "../class/connect.php";
include "../class/emp_information.php";


if (isset($_POST["emp_id"])){
	$emp_id = $_POST["emp_id"];

	$emp_info_class = new EmployeeInformation;

	// check if exist ung emo id
	if ($emp_info_class->checkExistEmpId($emp_id) == 0){
		echo "Error";
	}

	else {
?>
	
	<form class="form-horizontal" method="post" id="create_bio_form">
		<div class="form-group">
			<div class="col-md-10 col-md-offset-1">
				<label>Created Bio ID:</label>
				<input type="text" name="created_bio_id" placeholder="Create Bio ID" class="form-control" required="required" id="number_only"> 
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-10 col-md-offset-1">
				<button type="submit" class="btn btn-primary pull-right btn-sm" id="submit_create_bio_id">Create</button>
			</div>
		</div>
	</form>

	<script>
		$(document).ready(function(){
			// create bio id submitting 
  		  $("button[id='submit_create_bio_id']").on("click",function(){
     		 	var emp_id = <?php echo $emp_id; ?>;
     			
     		 	$("input[name='created_bio_id']").attr("required","required");

     		 	if ($("input[name='created_bio_id']").val() != ""){
     		 		
     		 		$("#create_bio_form").append("<input type='hidden' value='"+emp_id+"' name='create_bio_emp_id'/>");
     		 		$("#create_bio_form").attr("action","php script/create_bio_id.php");
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
	    	$("input[name='created_bio_id']").on('input', function(){
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

}
else {
	header("Location:../MainForm.php");
}



?>