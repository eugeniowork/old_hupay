<?php
include "../class/connect.php";
include "../class/cashbond_class.php";
include "../class/emp_information.php";
include "../class/money.php";

if (isset($_POST["file_cashbond_withdrawal_id"]) && isset($_POST["approve_stats"])){
	$cashbond_class = new Cashbond;
	// checkExistFileCashbondWithdrawalById
	$file_cashbond_withdrawal_id = $_POST["file_cashbond_withdrawal_id"];

	//echo $file_cashbond_withdrawal_id;
	if ($cashbond_class->checkExistFileCashbondWithdrawalById($file_cashbond_withdrawal_id == 0)){
		echo "Error";
	}
	else {
		//echo 
		$approve_stats = $_POST["approve_stats"];
		$emp_info_class = new EmployeeInformation;
		$money_class = new Money;

		$row = $cashbond_class->getInfoByFileCashbondWithdrawalId($file_cashbond_withdrawal_id);

		$row_emp = $emp_info_class->getEmpInfoByRow($row->emp_id);

		$file_name = $row_emp->Firstname . " " . $row_emp->Lastname;


?>
	<form class="" action="" method="post" id="form_approve_file_cashbond_withdrawal">
		<div class="container-fluid">
			<div style="text-align:center;">					
				<span><span class="glyphicon glyphicon-info-sign" style="color:#CB4335;"></span> Are you sure you want to <b><?php echo $approve_stats; ?></b> the <b>file cashbond withdrawal</b> of <b><?php echo $file_name; ?></b> amounting of <b>Php <?php echo $money_class->getMoney($row->amount_withdraw); ?>?</b></span>
			</div>
			<br/>
			<div class="form-group">
				<center>
					<button type="button" class="btn btn-primary btn-sm" id="btn_approve_file_salary_loan"><?php echo $approve_stats; ?></button>
				</center>
			</div>						
		</div>
	</form>

	<script>
		$(document).ready(function(){

			$("button[id='btn_approve_file_salary_loan']").on("click",function(){
				

				$("form[id='form_approve_file_cashbond_withdrawal']").append("<input type='hidden' name='file_cashbond_withdrawal_id' value='<?php echo $file_cashbond_withdrawal_id; ?>'/>");
				$("form[id='form_approve_file_cashbond_withdrawal']").append("<input type='hidden' name='approve_stats' value='<?php echo $approve_stats; ?>'/>");
				$("form[id='form_approve_file_cashbond_withdrawal']").attr("action","php script/script_approve_file_cashbond_withdrawal.php");
				$("form[id='form_approve_file_cashbond_withdrawal']").submit();
				//window.location = "php script/script_approve_file_cashbond_withdrawal.php?";

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