<?php
include "../class/connect.php";
include "../class/emp_information.php";

if (isset($_POST["emp_id"])){
	$emp_info_class = new EmployeeInformation;

	$emp_id = $_POST["emp_id"];

	// for checking if exist ung emp_id
	if ($emp_info_class->checkExistEmpId($emp_id) == 0){
		echo "Error";
	}
	else {
		$row = $emp_info_class->getEmpInfoByRow($emp_id);
?>
		<form class="form-horizontal" action="" method="post">
			<div class="container-fluid">								
				<label class="control-label"><span class="glyphicon glyphicon-credit-card" style="color:#2E86C1;"></span> Account No.</label>
				<div class="input-group">
					<input type="text" id="number_only" name="update_atmNo" value="<?php echo $row->atmAccountNumber; ?>" autocomplete="off" placeholder="Enter Account No" class="form-control">
				 	<span class="input-group-btn">
				    	<button class="btn btn-primary" id="btn_update_account_no" type="button"><span class="glyphicon glyphicon-pencil"></span></button>
				    </span>
				</div>	

				<br/>
				<div id="message">

				</div>							
			</div>
		</form>

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
		    $("input[name='update_atmNo']").on('input', function(){
		       if ($(this).attr("maxlength") != 12){
		            if ($(this).val().length > 12){
		                $(this).val($(this).val().slice(0,-1));
		            }
		           $(this).attr("maxlength","12");
		       }

		   });

		    // 
		     $("button[id='btn_update_account_no']").on("click",function() {
		        var accountNo = $("input[name='update_atmNo']").val();
		        var datastring = "emp_id="+<?php echo $emp_id; ?>+"&accountNo="+accountNo;
		        //alert(datastring);

		        if (accountNo == ""){
		        	 $("#message").html("<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Please enter a atm account number.");
		        }

		        else if (accountNo != "" && accountNo.length != 12){
		        	 $("#message").html("<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Please enter a 12 digit account number.");
		        }

		        else {
			        $("#message").html("<center><div class='loader'></div>Updating Process, please wait ...</center>");
			        $.ajax({
		              type: "POST",
		              url: "php script/update_atm_account_no.php",
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