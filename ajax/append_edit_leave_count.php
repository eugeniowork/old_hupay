<?php
include "../class/connect.php";
include "../class/emp_information.php";

if (isset($_POST["emp_id"])){
	$emp_id = $_POST["emp_id"];

	$emp_info_class = new EmployeeInformation;
	
	//for checking if not exist ung emp_id
	if ($emp_info_class->checkExistEmpId($emp_id) == 0){
		echo "Error";
	}

	else {
		$row = $emp_info_class->getEmpInfoByRow($emp_id);
?>
		<form class="form-horizontal" action="" method="post">
			<div class="container-fluid">								
				<label class="control-label"><span class="glyphicon glyphicon-asterisk" style="color:#2E86C1;"></span> Leave Count.</label>
				<div class="input-group">
					<input type="text" id="float_only" name="update_leaveCount" value="<?php echo $row->leave_count; ?>" autocomplete="off" placeholder="Enter Leave Count" class="form-control">
				 	<span class="input-group-btn">
				    	<button class="btn btn-primary" id="btn_update_leave_count" type="button"><span class="glyphicon glyphicon-pencil"></span></button>
				    </span>
				</div>	

				<br/>
				<div id="message">

				</div>							
			</div>
		</form>


		<script>
		 $(document).ready(function(){
	    	 // for maxlength
		    $("input[name='update_leaveCount']").on('input', function(){
		       if ($(this).attr("maxlength") != 4){
		            if ($(this).val().length > 4){
		                $(this).val($(this).val().slice(0,-1));
		            }
		           $(this).attr("maxlength","4");
		       }

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

		     $("input[name='update_leaveCount']").on('input', function(){
			      	if ($(this).val() > 10){
			      		 $(this).val($(this).val().slice(0,-1));
			      	}

			   });

		    // 
		     $("button[id='btn_update_leave_count']").on("click",function() {
		        var leaveCount = $("input[name='update_leaveCount']").val();
		        var datastring = "emp_id="+<?php echo $emp_id; ?>+"&leaveCount="+leaveCount;
		        //alert(datastring);

		        if (leaveCount == ""){
		        	 $("#message").html("<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Please enter a leave count.");
		        }

		       // else if (accountNo != "" && accountNo.length > 2){
		        //	 $("#message").html("<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Please enter a ");
		       // }

		        else {
			        $("#message").html("<center><div class='loader'></div>Updating Process, please wait ...</center>");
			        $.ajax({
		              type: "POST",
		              url: "php script/update_leave_count.php",
		              data: datastring,
		              cache: false,
		              success: function (data) {
		                // if has error 
		              	$("#message").html(data);

		             }
		           });
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