<?php
session_start();
include "../class/connect.php";
include "../class/emp_information.php";
include "../class/allowance_class.php";
include "../class/cut_off.php";
include "../class/date.php";
include "../class/Payroll.php";

// employeeID
if (isset($_POST["employeeName"]) && isset($_POST["regularOT"]) && isset($_POST["holidayOT"]) && isset($_POST["restdayOT"]) && isset($_POST["holidayRestdayOT"])
	&& isset($_POST["tardiness"]) && isset($_POST["absent"]) && isset($_POST["adjustment"]) && isset($_POST["totalGrossIncome"]) && isset($_POST["sssContribution"])
	&& isset($_POST["philhealthContribution"]) && isset($_POST["pagibigContribution"]) && isset($_POST["sssLoan"])
	&& isset($_POST["pagibigLoan"]) && isset($_POST["cashAdvance"]) && isset($_POST["cashBond"]) && isset($_POST["taxableIncome"]) && isset($_POST["tax"])
	&& isset($_POST["netPay"]) && isset($_POST["employeeID"])){

	$emp_id = $_POST["employeeID"];
	$emp_name_form = $_POST["employeeName"];

	$emp_info_class = new EmployeeInformation;
	$row = $emp_info_class->getEmpInfoByRow($emp_id);
	
	//if change and not exist to the database
	if ($emp_info_class->checkExistEmpId($emp_id) == 0){
		$_SESSION["save_payroll_error_msg"]  =  "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> There's an error during saving of data.</center>";
	}

	// if exist id but not equal to the emp name so error din
	else if ($emp_info_class->checkEmpName($emp_id,$emp_name_form) == 0){
		$_SESSION["save_payroll_error_msg"]  =  "<center><span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> There's an error during saving of data.</center>";

	}

	// success then add
	else {
		$allowance_class = new Allowance;
		$cut_off_class = new CutOff;
		$date_class = new date;
		$payroll_class = new Payroll;

		$dept_id = $row->dept_id;
		$cut_off_period = "semi-monthly";
		$salary = $row->Salary;
		$regularOT = substr($_POST["regularOT"],4);

		$holidayOT = substr($_POST["holidayOT"],4);
		$restdayOT = substr($_POST["restdayOT"],4);
		$holidayRestdayOT = substr($_POST["holidayRestdayOT"],4);
		$tardiness = substr($_POST["tardiness"],4);
		$absences = substr($_POST["absent"],4);
		$totalGrossIncome = substr($_POST["totalGrossIncome"],4);
		$allowance =  round((($allowance_class->getAllowanceInfoByEmpId($emp_id)) / 2),2);
		$sssContribution = substr($_POST["sssContribution"],4);
		$philhealthContribution = substr($_POST["philhealthContribution"],4);
		$pagibigContribution = substr($_POST["pagibigContribution"],4);
		$sssLoan = $_POST["sssLoan"];
		$pagibigLoan = $_POST["pagibigLoan"];
		$cashAdvance = $_POST["cashAdvance"];
		$cashBond = substr($_POST["cashBond"],4);
		$adjustment = $_POST["adjustment"];

		$totalDeduction = round(($sssContribution + $philhealthContribution + $pagibigContribution + $sssLoan + $pagibigLoan + $cashAdvance + $cashBond),2);

		$taxableIncome = round(($totalGrossIncome - $totalDeduction),2);

		$tax = substr($_POST["tax"],4);

		$netPay = substr($_POST["netPay"],4);
		$datePayroll = $cut_off_class->getDatePayroll();

		// getDate
		$current_date = $date_class->getDate();



		// if has no data yet so insert lang
		if ($payroll_class->existPayroll($emp_id,$datePayroll) == 0) {
			$payroll_class->insertPayroll($emp_id,$dept_id,$cut_off_period,$salary,$regularOT,$holidayOT,
									  $restdayOT,$holidayRestdayOT,$tardiness,$absences,$adjustment,$totalGrossIncome,
									  $allowance,$sssContribution,$philhealthContribution,$pagibigContribution,
									  $sssLoan,$pagibigLoan,$cashAdvance,$cashBond,$totalDeduction,$taxableIncome,
									  $tax,$netPay,$datePayroll,$current_date);
		}

		// if has exist data update
		else {
			$payroll_class->updatePayroll($emp_id,$datePayroll,$salary,$regularOT,$holidayOT,
								  $restdayOT,$holidayRestdayOT,$tardiness,$absences,$adjustment,$totalGrossIncome,
								  $allowance,$sssContribution,$philhealthContribution,$pagibigContribution,
								  $sssLoan,$pagibigLoan,$cashAdvance,$cashBond,$totalDeduction,$taxableIncome,
								  $tax,$netPay,$current_date);

		}

		$date_create = date_create($datePayroll);
		$date_format = date_format($date_create, 'F d, Y');

		$_SESSION["save_payroll_success_msg"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> The Payroll of <b>$emp_name_form</b> for the payroll dated <b>$date_format</b> is successfully saved. </center>";

		
		/*echo "Success" . "<br/>";


		echo $emp_id ."<br/>";
		echo $dept_id ."<br/>";
		echo $cut_off_period ."<br/>";
		echo $salary ."<br/>";
		echo $regularOT ."<br/>";
		echo $holidayOT ."<br/>";
		echo $restdayOT ."<br/>";
		echo $holidayRestdayOT ."<br/>";
		echo $tardiness ."<br/>";
		echo $absences ."<br/>";
		echo $totalGrossIncome ."<br/>";
		echo $allowance ."<br/>";
		echo $sssContribution ."<br/>";
		echo $philhealthContribution ."<br/>";
		echo $pagibigContribution ."<br/>";
		echo $sssLoan ."<br/>";
		echo $pagibigLoan ."<br/>";
		echo $cashAdvance ."<br/>";
		echo $totalDeduction ."<br/>";
		echo $taxableIncome ."<br/>";
		echo $tax ."<br/>";
		echo $netPay ."<br/>";
		echo $datePayroll ."<br/>";
		echo $current_date ."<br/>";*/




		//echo "Success";

	}

	header("Location:../createPayroll.php");




}

// if edited in the inspect element
else {
	header("Location:../MainForm.php");
}


?>