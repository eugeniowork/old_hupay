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
				<form class="form-horizontal" method="post" action="" id="form_updateCashbond">
					<div class="form-group">
						<label class="control-label col-sm-3">Cashbond: </label>
						<div class="col-sm-9">
							<input type="text" id="float_only" name="updateCashbond" value="<?php echo $row->cashbondValue; ?>" class="form-control" placeholder="Cashbond ..." required="required"/>
						</div>
					</div>
					<!-- <div class="form-group">
						<label class="control-label col-sm-3">Total: </label>
						<div class="col-sm-9">
							<input type="text" id="float_only" name="totalCashbond" value="<?php echo $row->totalCashbond; ?>" class="form-control" placeholder="Cashbond ..." required="required"/>
						</div>
					</div> -->
					<div class="form-group" style="text-align:center;">
						<input type="button" value="Update" class="btn btn-success btn-sm" id="update_cashbond"/>
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


				$("input[id='float_only']").on('input', function(){
					if ($(this).attr("maxlength") != 9){
					    if ($(this).val().length > 9){
					        $(this).val($(this).val().slice(0,-1));
					    }
					   $(this).attr("maxlength","9");
					}

				});


				// for update button
				$("input[id='update_cashbond']").on("click",function () {
					var cashbond = $("input[name='updateCashbond']").val();
					if (cashbond == ""){					
						$("input[name='updateCashbond']").attr("required","required");
						event.preventDefault();
					}
					else {
						//$("#form_updateCashbond").attr("action","php script/update_cashbond.php?cashbond_id=<?php echo $cashbond_id;?>");
						var cashbond_id = <?php echo $cashbond_id; ?>;
						var cashbond_value = $("input[name='updateCashbond']").val();
						var totalCashbond = $("input[name='totalCashbond']").val();
						var datastring = "updateCashbond=" + cashbond_value + "&cashbond_id="+cashbond_id + "&totalCashbond="+totalCashbond;
						//alert(datastring);
						$.ajax({
			              type: "POST",
			              url: "php script/update_cashbond.php",
			              data: datastring,
			              cache: false,
			              success: function (data) {
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