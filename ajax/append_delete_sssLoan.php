<?php
session_start();
include "../class/connect.php";
include "../class/sss_loan_class.php";
include "../class/emp_information.php";


if (isset($_POST["sss_loan_id"])){
	$sss_loan_id = $_POST["sss_loan_id"];

	//echo $pagibig_loan_id;

	$sss_loan_class = new SSSLoan;
	$emp_info_class = new EmployeeInformation;
	//$date_class = new date;
	//$money_class = new Money;

    if ($sss_loan_class->checkExistSSSLoanUpdate($sss_loan_id) == 1){
		$row = $sss_loan_class->getInfoBySSSLoanId($sss_loan_id);
		$row_emp = $emp_info_class->getEmpInfoByRow($row->emp_id);


		// values from db
		if ($row_emp->Middlename == ""){
			$full_name = $row_emp->Lastname . ", " . $row_emp->Firstname;
		}
		else {
			$full_name = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;
		}

		/*$dateFrom = $date_class->dateDefault($row->dateFrom);
		$dateTo = $date_class->dateDefault($row->dateTo);
		$amountLoan = $row->amountLoan;
		$deduction = $row->deduction;
		$remainingBalance = $row->remainingBalance;*/

?>
		
	<form class="" action="" method="post" id="form_deleteSSSLoan">
		<div class="container-fluid">
			<div style="text-align:center;">					
				<span><span class="glyphicon glyphicon-warning-sign" style="color:#CB4335;"></span> Are you sure you want to delete the <b>SSS Loan</b> of <b><?php echo $full_name; ?></b>?</b></span>
			</div>						
		</div>
	</form>



		<script>
			$(document).ready(function(){
				

			});

		</script>


		
<?php
	} // end of else if

	else {
		echo "Error";
	}
}

else {
	header("Location:../MainForm.php");
}

?>