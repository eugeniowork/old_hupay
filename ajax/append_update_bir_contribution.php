<?php
session_start();
include "../class/connect.php";
include "../class/BIR_Contribution.php";
include "../class/date.php";

if (isset($_POST["bir_contrib_id"])) {
	$bir_contrib_id = $_POST["bir_contrib_id"];

	$bir_contrib_class = new BIR_Contribution;

	// if exist
	if ($bir_contrib_class->existContributionId($bir_contrib_id) == 0) {
		echo "Error";	
	}
	// if success
	else {
		$row = $bir_contrib_class->getInfoByContribId($bir_contrib_id);
		$_SESSION["update_bir_contribution"] = $bir_contrib_id;

	
?>
	<div class="container-fluid">
		<form class="form-horizontal" action="php script/update_bir_contribution.php" method="post">		
			<div class="col-sm-12">

				<?php
					//if ($sssContribution_class->existOver() == 0){
				?>
				<!--<div class="form-check">
				    <label class="form-check-label" style="margin-left:-15px;">
				      <input type="checkbox" name="checkOver" class="form-check-input" id="check_compen_to"> Check if <b>Compensantion Range To</b> will be "over"
				    </label>
				</div> -->
				<?php
					//}
				?>

				<div class="form-group">
					<label class="control-label"><span class="glyphicon glyphicon-stats" style="color:#2E86C1;"></span> Status</label>
					<select name="status" class="form-control" required="required">
						<option value=""></option>
						<?php
							$bir_contrib_class->getUpdateBIRStatus($row->Status);
						?>
					</select>									 													
				</div>

				<div class="form-group">
					<label class="control-label"><span class="glyphicon glyphicon-bitcoin" style="color:#2E86C1;"></span> Amount</label>
					<input type="text" id="float_only" value="<?php echo $row->amount; ?>" name="amount" autocomplete="off" placeholder="Enter Amount" class="form-control" required="required">									 													
				</div>	


				<div class="form-group">
					<label class="control-label"><span class="glyphicon glyphicon-bitcoin" style="color:#2E86C1;"></span> Contribution</label>
					<input type="text" id="float_only" value="<?php echo $row->Contribution; ?>" name="contribution" autocomplete="off" placeholder="Enter Contribution" class="form-control" required="required">									 													
				</div>	

				<div class="form-group">
					<label class="control-label"><span class="glyphicon glyphicon-grain" style="color:#2E86C1;"></span> Percentage</label>
					<input type="text" id="number_only" value="<?php echo $row->percentage; ?>" name="percentage" autocomplete="off" placeholder="Enter Percentage" class="form-control" required="required">									 													
				</div>


				<div class="form-group">
					<div style="text-align:center;">
						<input type="submit" class="btn btn-success" value="Update Contribution">
					</div>									 													
				</div>

			</div>									
		</form>
	</div>

	<script>
		$(document).ready(function(){
				// for sss Contribution compensationFrom
		    $("input[id='float_only']").on('input', function(){
		       if ($(this).attr("maxlength") != 9){
		            if ($(this).val().length > 9){
		                $(this).val($(this).val().slice(0,-1));
		            }
		           $(this).attr("maxlength","9");
		       }

		   });

		     // for sss Contribution compensationTo
		    
		    // sssContribution
		    $("input[name='percentage']").on('input', function(){
		       if ($(this).attr("maxlength") != 2){
		            if ($(this).val().length > 2){
		                $(this).val($(this).val().slice(0,-1));
		            }
		           $(this).attr("maxlength","2");
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


		     $("input[id='number_only").on("paste", function(){
		          return false;
		    });


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

			

	    });
	</script>

<?php
	} // end if else
}
else {
	header("Location:../Mainform.php");
}
?>