<?php
session_start();
include "../class/connect.php";
include "../class/memorandum_class.php";
include "../class/emp_information.php";
include "../class/department.php";


if (isset($_POST["memo_id"])){
	$memo_id = $_POST["memo_id"];

	//echo $pagibig_loan_id;

	$memo_class = new Memorandum;
	$emp_info_class = new EmployeeInformation;
	$dept_class = new Department;
	//$date_class = new date;
	//$money_class = new Money;

    if ($memo_class->checkExistMemo($memo_id) == 1){
		$row = $memo_class->getMemoInfoById($memo_id);

		$subject= $row->Subject;

		/*$row_emp = $emp_info_class->getEmpInfoByRow($row->emp_id);


		if ($row->recipient == "Specific Employee"){
			// values from db
			if ($row_emp->Middlename == ""){
				$full_name = $row_emp->Lastname . ", " . $row_emp->Firstname;
			}
			else {
				$full_name = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;
			}
		} // end of if


		else if ($row->recipient == "Department"){
			$row_dept = $dept_class->getDepartmentValue($row->dept_id);
			$full_name = $row_dept->Department . " Department";
		}

		else {
			$full_name = "All Employee";
		}
		*/

		/*$dateFrom = $date_class->dateDefault($row->dateFrom);
		$dateTo = $date_class->dateDefault($row->dateTo);
		$amountLoan = $row->amountLoan;
		$deduction = $row->deduction;
		$remainingBalance = $row->remainingBalance;*/

?>
		
	<form class="" action="" method="post" id="form_deleteMemo">
		<div class="container-fluid">
			<div style="text-align:center;">					
				<span><span class="glyphicon glyphicon-warning-sign" style="color:#CB4335;"></span> Are you sure you want to delete the <b>Memorandum</b> of <b><?php echo $subject; ?></b>?</b></span>
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