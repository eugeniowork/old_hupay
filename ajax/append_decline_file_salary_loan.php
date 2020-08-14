<?php
session_start();
include "../class/connect.php";
include "../class/salary_loan_class.php";
include "../class/date.php";
include "../class/money.php";


$id = $_SESSION["id"];

$salary_loan_class = new SalaryLoan;
$date_class = new date;
$money_class = new Money;


// last id
$file_salary_loan_id = $salary_loan_class->fileSalaryLoanLastId($id);

$row = $salary_loan_class->getFileSalaryLoanById($file_salary_loan_id);

$amountLoan = $money_class->getMoney($row->amountLoan);

$dateFrom = $date_class->dateFormat($row->dateFrom);
$dateTo = $date_class->dateFormat($row->dateTo);


?>
<form class="" action="" method="post" id="form_deleteSalaryLoan">
	<div class="container-fluid">
		<div style="text-align:center;">					
			<span><span class="glyphicon glyphicon-warning-sign" style="color:#CB4335;"></span> Are you sure you want to <b>decline</b> your <b>File Salary Loan</b> with the amount of <b>Php <?php echo $amountLoan; ?></b> for <b> <?php echo $row->totalMonths; ?> months</b> starting <b><?php echo $dateFrom; ?></b> to <b><?php echo $dateTo; ?></b>?</span>
		</div>						
	</div>
</form>