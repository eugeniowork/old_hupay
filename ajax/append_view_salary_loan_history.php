<?php
include "../class/connect.php";
include "../class/salary_loan_class.php";

if (isset($_POST["salary_loan_id"])){
	$salary_loan_id = $_POST["salary_loan_id"];

	$salary_loan_class = new SalaryLoan;

?>

	<div class="container-fluid">
		<small>
			<table id="emp_list_with_salary_loan" class="table table-bordered table-hover table-striped" style="border:1px solid #BDBDBD;">
				<thead>
					<tr style="background-color: #616a6b ">
						<th style="color:#fff"><small><span class="glyphicon glyphicon-calendar" ></span> Payroll Date</small></th> 	
						<th style="color:#fff"><small><span class="glyphicon glyphicon-ruble"></span> Deduction</small></th>
						<th style="color:#fff"><small><span class="glyphicon glyphicon-ruble"></span> Outstanding Balance</small></th>					
					</tr>
				</thead>
				<tbody>	
					<?php
						$salary_loan_class->getSalaryLoanHistory($salary_loan_id);
					?>
				</tbody>
			</table>
		</small>
	</div>
<?php
} // end of if
else {
	header("../MainForm.php");
} // end of else



?>