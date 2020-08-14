<?php
session_start();
include "../class/connect.php";
include "../class/cashbond_class.php";
include "../class/money.php";

if (isset($_POST["append"])){
	$id = $_SESSION["id"];
	$cashbond_class = new Cashbond;
	$money_class = new Money;

	$row_cashbond = $cashbond_class->getInfoByEmpId($id);
	//echo $row_cashbond->totalCashbond;
	$totalCashbond = $row_cashbond->totalCashbond;

	$row = $cashbond_class->getLastestFileCashbondWithdrawal($id);

	

?>	
	<form class="form-horizontal" id="form_update_file_cashbond_withdrawal" method="post">
		<div class="container-fluid">
			<div class="form-group">
				<div class="col-md-12">
					<?php
						
					?>
					<b>Note:</b> <i>Available amount that can withdraw <b>Php <?php echo $money_class->getMoney($totalCashbond - 5000); ?></b></i>
				</div>
			</div>
			

			<div class="form-group">
				<label class="control-label col-md-4" style="margin-right:-20px;"><b style="color: #2471a3 ">Amount Withdraw:</b></label>
				<div class="col-md-5">
					
					<input type="text" name="update_amount_withdraw" value="<?php echo $row->amount_withdraw; ?>" class="form-control" id="float_only"/>
				</div>
			</div>

			<div class="form-group">
				<div class="col-md-offset-4">
					<button type="button" class="btn btn-sm btn-success" id="btn_update_file_withdrawal">Update</button>
				</div>
			</div>

			<div class="form-group">
				<div class="col-md-offset-4">
					<label id="update_file_withdraw_message">&nbsp;</label>
				</div>
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


		     // float only
		    $("input[id='float_only']").on('input', function(){
		       if ($(this).attr("maxlength") != 10){
		            if ($(this).val().length > 10){
		                $(this).val($(this).val().slice(0,-1));
		            }
		           $(this).attr("maxlength","10");
		       }

		   });


		    // for filing of cashwithdrawal
		    $("button[id='btn_update_file_withdrawal']").on("click",function(){
		        var amount_withraw = $("input[name='update_amount_withdraw']").val();

		        if (amount_withraw == ""){
		            $("label[id='update_file_withdraw_message']").html("<span class='glyphicon glyphicon-remove' style='color:#c0392b;'></span> Please provide an amount first.");
		        }

		        else {

		            // check natin kung ung remaining balance na inimput nya is mas malaki sa available
		            $("label[id='update_file_withdraw_message']").html("<center><div class='loader' style='float:left'></div>&nbsp;Filing Cashbond Withdrawal please wait ...</center>");
		            var datastring = "check_request=1";
		             $.ajax({
		              type: "POST",
		              url: "ajax/append_check_totalcashbond_amount.php",
		              data: datastring,
		              cache: false,
		              success: function (data) {
		                 // alert(data);
		                  //alert(amount_withraw);
		                  if (parseFloat(amount_withraw) > parseFloat(data)){
		                      //alert("Error");
		                      $("label[id='update_file_withdraw_message']").html("<span class='glyphicon glyphicon-remove' style='color:#c0392b;'></span> The amount you want to withdraw must be not higher than your available withdraw cashbond amount of <b>Php "+data+"</b>.");
		                  }
		                  else {
		                  		//alert("READY FOR SUBMITTION");
		                      $("#form_update_file_cashbond_withdrawal").attr("action","php script/script_update_file_cashbond.php");
		                      $("#form_update_file_cashbond_withdrawal").submit();
		                  }


		             }
		           });
		        }
		    });
		});

	</script>
<?php
}
else {
	header("Location:../dashboard.php");
}

?>