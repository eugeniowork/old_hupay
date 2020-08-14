<?php
session_start();
include "../class/connect.php";
include "../class/emp_information.php";
include "../class/dependent.php";
include "../class/BIR_Contribution.php";
include "../class/minimum_wage_class.php";

if (isset($_POST["taxable_income"]) &&isset($_POST["emp_id"]) && isset($_POST["last_total_gross_income"])
	&& isset($_POST["cutOff_day"]) && isset($_POST["sss_contribution"]) && isset($_POST["pagibig_contribution"]) && isset($_POST["philhealth_contribution"])){

	$emp_info_class = new EmployeeInformation;
	$min_wage_class = new MinimumWage;

	$emp_id = $_POST["emp_id"];
	//$emp_name = $_POST["emp_name"];
	$last_total_gross_income = $_POST["last_total_gross_income"];
	$cutOff_day = $_POST["cutOff_day"];
	$sss_contribution = $_POST["sss_contribution"];
	$pagibig_contribution = $_POST["pagibig_contribution"];
	$philhealth_contribution = $_POST["philhealth_contribution"];
	
	if ($emp_info_class->checkExistEmpId($emp_id) == 0){
		//$_SESSION["save_payroll_error_msg"]  =  "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> There's an error during getting of data.</center>";
		echo "Error";
	}
	
	/*else if ($emp_info_class->checkEmpName($emp_id,$emp_name) == 0){
		//$_SESSION["save_payroll_error_msg"]  =  "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> There's an error during getting of data.</center>";
		echo "Error";
		
	}*/

	
	else {
		//$row = $emp_info_class->getEmpInfoByRow($emp_id);

		//$taxable = $row->WithTax;

		$taxable_income = $_POST["taxable_income"] - ($sss_contribution + $pagibig_contribution + $philhealth_contribution);


		/*

		$min_wage = $min_wage_class->getMinimumWage();


		// ibig sabihin wlang tax
		//if ($taxable == 0 || $row->TinNo == "") {
		//	echo "Php 0";
		//}

		// ibig sabihin may tax
		//else {
			//$civilStatus = $row->CivilStatus;

			$row = $emp_info_class->getEmpInfoByRow($emp_id);

			$salary = $row->Salary;

			if ($row->TinNo != "") {

				if ($salary <= $min_wage){
					echo "0";
				}
				else {
					$dependent_class = new Dependent;
					$dependent_count = $dependent_class->existDependent($emp_id);

					$bir_contribution_class = new BIR_Contribution;
					$bir_status_row = $bir_contribution_class->getBIRStatusToPayroll($dependent_count);

					$status = $bir_status_row->Status; 

					$tax = $bir_contribution_class->getTax($status,$taxable_income);

					echo round($tax,2);
				}
			}
			else {

				echo "0";
			}


		//}
		*/

			/*if ($taxable_income <= 10417){
				$tax = 0;
			}

			else if ($taxable_income > 10417 && $taxable_income < 16667){
				$tax = round((($taxable_income - 10417) * .20),2);

			}

			else if ($taxable_income > 16667 && $taxable_income < 33333){
				$tax = round((($taxable_income - 16667) * .25) + 1250,2);
			}
			
			else if ($taxable_income > 33333 && $taxable_income < 83333){
				$tax = round((($taxable_income - 33333) * .30) + 5416.67,2);
			}

			else if ($taxable_income > 83333 && $taxable_income < 333333){
				$tax = round((($taxable_income - 83333) * .32) + 20416.67,2);
			}

			else if ($taxable_income >= 333333){
				$tax = round((($taxable_income - 333333) * .35) + 100416.67,2);
			}*/



			$tax = 0;
			$taxableIncome = $taxable_income;
			if ($cutOff_day == "30"){

				if (($taxableIncome + $last_total_gross_income) <= 20833){
					$tax = 0;
				}

				else if (($taxableIncome + $last_total_gross_income) > 20833 && ($taxableIncome + $last_total_gross_income) < 33333){
					$tax = round(((($taxableIncome + $last_total_gross_income) - 20833) * .20),2);

				}

				else if (($taxableIncome + $last_total_gross_income) > 33333 && $taxableIncome < 66667){
					$tax = round(((($taxableIncome + $last_total_gross_income) - 33333) * .25) + 2500,2);
				}
				
				else if (($taxableIncome + $last_total_gross_income) > 66667 && ($taxableIncome + $last_total_gross_income) < 166667){
					$tax = round(((($taxableIncome + $last_total_gross_income) - 66667) * .30) + 10833.33,2);
				}

				else if (($taxableIncome + $last_total_gross_income) > 166667 && ($taxableIncome + $last_total_gross_income) < 666667){
					$tax = round(((($taxableIncome + $last_total_gross_income) - 166667) * .32) + 40833.33,2);
				}

				else if (($taxableIncome + $last_total_gross_income) >= 666667){
					$tax = round(((($taxableIncome + $last_total_gross_income) - 666667) * .35) + 200833.33,2);
				}
			}

			echo $tax;
	}

	

}
else {
	header("Location:../MainForm.php");
}


?>