<?php
session_start();
include "../class/connect.php";
include "../class/SSS_Contribution.php";
include "../class/date.php";

if (isset($_POST["sss_contrib_id"])) {
	$sss_contrib_id = $_POST["sss_contrib_id"];

	$sss_contrib_class = new SSS_Contribution;

	// if exist
	if ($sss_contrib_class->existContributionId($sss_contrib_id) == 0) {
		echo "Error";	
	}
	// if success
	else {
		$row = $sss_contrib_class->getInfoByContribId($sss_contrib_id);
		$_SESSION["update_sss_contribution"] = $sss_contrib_id;

	
?>
	<div class="container-fluid">
		<form class="form-horizontal" action="php script/update_sss_contribution.php" method="post">		
			<div class="col-sm-12">

				<?php

					if ($sss_contrib_class->existOver() == 0 || $row->compensationTo == "0"){
				?>
				<div class="form-check">
				    <label class="form-check-label" style="margin-left:-15px;">
				      <input type="checkbox" <?php if ($row->compensationTo == "0") { echo "checked='checked'";}?> name="checkOver" class="form-check-input" id="check_compen_to"> Check if <b>Compensantion Range To</b> will be "over"
				    </label>
				</div>
				<?php
					}
				?>

				<div class="form-group">
					<label class="control-label"><span class="glyphicon glyphicon-bitcoin" style="color:#2E86C1;"></span> Compensantion From</label>
					<input type="text" id="float_only" value="<?php echo $row->compensationFrom; ?>" name="compensationFrom" autocomplete="off" placeholder="Enter Compensation From" class="form-control" required="required">									 													
				</div>	


				<div class="form-group">
					<label class="control-label"><span class="glyphicon glyphicon-bitcoin" style="color:#2E86C1;"></span> Compensation To</label>
					<input type="text" id="float_only" <?php if ($row->compensationTo == "0"){ echo "readonly='readonly'";} ?> value="<?php if ($row->compensationTo == "0"){ echo "Over";} else { echo $row->compensationTo;} ?>" name="compensationTo" autocomplete="off" placeholder="Enter Compensation To" class="form-control" required="required">									 													
				</div>	

				<div class="form-group">
					<label class="control-label"><span class="glyphicon glyphicon-bitcoin" style="color:#2E86C1;"></span> Contribution</label>
					<input type="text" id="float_only" value="<?php echo $row->Contribution; ?>" name="sssContribution" autocomplete="off" placeholder="Enter Contribution" class="form-control" required="required">									 													
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
		    $("input[name='compensationFrom']").on('input', function(){
		       if ($(this).attr("maxlength") != 9){
		            if ($(this).val().length > 9){
		                $(this).val($(this).val().slice(0,-1));
		            }
		           $(this).attr("maxlength","9");
		       }

		   });

		     // for sss Contribution compensationTo
		    $("input[name='compensationTo']").on('input', function(){
		       if ($(this).attr("maxlength") != 9){
		            if ($(this).val().length > 9){
		                $(this).val($(this).val().slice(0,-1));
		            }
		           $(this).attr("maxlength","9");
		       }

		   });
		    
		    // sssContribution
		    $("input[name='sssContribution']").on('input', function(){
		       if ($(this).attr("maxlength") != 9){
		            if ($(this).val().length > 9){
		                $(this).val($(this).val().slice(0,-1));
		            }
		           $(this).attr("maxlength","9");
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


			$("input[id='check_compen_to']").on("click", function () {

	          // if check
	          if ($(this).is(':checked')){
	            $("input[name='compensationTo']").val("Over");
	            $("input[name='compensationTo']").attr("readonly","readonly");
	          }
	          // if not check
	          else {
	            $("input[name='compensationTo']").val("");
	            $("input[name='compensationTo']").removeAttr("readonly");
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