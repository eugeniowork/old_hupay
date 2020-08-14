<?php
session_start();
include "../class/connect.php";
include "../class/simkimban_class.php";
include "../class/emp_information.php";


if (isset($_POST["simkimban_id"])){
	$simkimban_id = $_POST["simkimban_id"];

	//echo $pagibig_loan_id;

	$simkimban_class = new Simkimban;
	$emp_info_class = new EmployeeInformation;
	//$date_class = new date;
	//$money_class = new Money;

    if ($simkimban_class->checkExistSimkimbanUpdate($simkimban_id) == 1){
		$row = $simkimban_class->getInfoBySimkimbaId($simkimban_id);
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
		
	<form class="" action="" method="post" id="form_deleteSimkimban">
		<div class="container-fluid">
			<div style="text-align:center;">					
				<span><span class="glyphicon glyphicon-warning-sign" style="color:#CB4335;"></span> Are you sure you want to delete the <b>Simkimban</b> of <b><?php echo $full_name; ?></b>?</b></span>
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