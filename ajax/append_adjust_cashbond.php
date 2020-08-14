<?php
session_start();
include "../class/connect.php";
include "../class/cashbond_class.php";

if (isset($_POST["cashbond_id"])){
	$cashbond_id = $_POST["cashbond_id"];

	$cashbond_class = new Cashbond;

	// check if d exist
	if ($cashbond_class->checkExistCashBond($cashbond_id) == 0){
		echo "Error";
	}
	// kapag exist tlga success
	else {
		$row = $cashbond_class->getInfoByCashbondId($cashbond_id);
?>

		<div class="container-fluid">
			<div class="row">
				<form class="form-horizontal" method="post" action="" id="form_adjustCashbond">
					
					<div class="form-group">
						<label class="control-label col-sm-3">Cashbond: </label>
						<div class="col-sm-9">
							<input type="text" id="readonly_txt" name="totalCashbond" value="<?php echo $row->totalCashbond; ?>" class="form-control" placeholder="Cashbond ..." required="required"/>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3">Adjust: </label>
						<div class="col-sm-9">
							<input type="text" id="float_only" name="adjust" value="" class="form-control" placeholder="Cashbond ..." required="required"/>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-3">Remarks: </label>
						<div class="col-sm-9">
							<textarea class="form-control" name="remarks" required="required"></textarea>
						</div>
					</div>

					<div class="form-group" style="text-align:center;">
						<input type="submit" value="Adjust" class="btn btn-success btn-sm" id="adjust_cashbond"/>
					</div>

					<div class="form-group">
						<span id="message"></span>
					</div>	
				</form>
			</div>	<!-- end of row -->
		</div> <!-- end of container-fluid -->


		<script>
			$(document).ready(function(){
				 // FOR DECIMAL POINT
				$("input[id='float_only']").keydown(function (e) {

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
				if ($.inArray(e.keyCode, [46,8, 9, 27, 13, 110,116,190,189,173]) !== -1 || //46,8, 9, 27, 13, 110,116,190,
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


				$("input[id='float_only']").on('input', function(){
					if ($(this).attr("maxlength") != 9){
					    if ($(this).val().length > 9){
					        $(this).val($(this).val().slice(0,-1));
					    }
					   $(this).attr("maxlength","9");
					}

				});



			    // for returning false
			     $(document).on('keypress', 'input[id="readonly_txt"]', function (event) {
			        return false;
			    });


				// for update button
				$("input[id='adjust_cashbond']").on("click",function () {
					if ($("input[name='adjust']").val() == "" && $("textarea[name='remarks']").val() == ""){					
						$("input[name='adjust']").attr("required","required");
						$("textarea[name='remarks']").attr("required","required");
						event.preventDefault();
					}
					else {
						$("#form_adjustCashbond").append("<input type='hidden' value='<?php echo $cashbond_id; ?>' name='cashbond_id'/>");
						$("#form_addSSSLoan" ).unbind().submit();
						$("#form_adjustCashbond").attr("action","php script/adjust_cashbond.php");
						
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