<?php
session_start();
include "class/connect.php";
include "class/emp_information.php";
include "class/department.php";
include "class/position_class.php";
include "class/money.php";
include "class/government_no_format.php";
include "class/date.php";

if (isset($_SESSION["print_emp_info_id"])){
	$emp_id = $_SESSION["print_emp_info_id"];

	$emp_info_class = new EmployeeInformation;
	$date_class = new date;
	$department_class = new Department;
	$position_class = new Position;
	$money_class = new Money;
	$govt_format_class = new GovernmentNoFormat;
	$row = $emp_info_class->getEmpInfoByRow($emp_id);

	require ("reports/fpdf.php");

	$pdf = new PDF_MC_Table();
	//$pdf->SetMargins("65","35");

	$pdf->AddPage();

	$pdf->Image("img/logo/lloyds_report_logo.jpeg",87,10,40,25);// margin-left,margin-top,width,height

	//$pdf->InsertText("");


	$pdf->Image($row->ProfilePath,155,42,40,25);// margin-left,margin-top,width,height

	$pdf->SetFont("Arial","B","10");
	$pdf->Multicell(0,20,"",0); // for multicell
	$pdf->Cell(0,15,"EMPLOYEE INFORMATION",0,1,"C");

	$pdf->SetFont("Arial","BU","10");
	$pdf->SetTextColor(255,0,0);
	$pdf->Cell(0,10,"BASIC INFORMATION",0,1);

 	// employee name
	$pdf->SetFont("Arial","B","9");
	$pdf->SetTextColor(0,0,255);
	$pdf->Cell(28,5,"Employee Name:",0,0,"R");
	$pdf->SetTextColor(0,0,0);
	$pdf->Cell(0,5,utf8_decode($row->Lastname . ", " . $row->Firstname . " " . $row->Middlename),0,1);

	// employee civil status
	$pdf->SetTextColor(0,0,255);
	$pdf->Cell(28,5,"Civil Status:",0,0,"R");
	$pdf->SetTextColor(0,0,0);
	$pdf->Cell(0,5,$row->CivilStatus,0,1);


	// employee civil status
	$pdf->SetTextColor(0,0,255);
	$pdf->Cell(28,5,"Address:",0,0,"R");
	$pdf->SetTextColor(0,0,0);
	$pdf->Cell(0,5,utf8_decode($row->Address),0,1);

	// employee birthdate
	$pdf->SetTextColor(0,0,255);
	$pdf->Cell(28,5,"Birthdate:",0,0,"R");
	$pdf->SetTextColor(0,0,0);
	$pdf->Cell(0,5,$date_class->dateFormat($row->Birthdate),0,1);


	// employee gender
	$pdf->SetTextColor(0,0,255);
	$pdf->Cell(28,5,"Gender:",0,0,"R");
	$pdf->SetTextColor(0,0,0);
	$pdf->Cell(0,5,$row->Gender,0,1);


	// employee contact number
	$pdf->SetTextColor(0,0,255);
	$pdf->Cell(28,5,"Contact No:",0,0,"R");
	$pdf->SetTextColor(0,0,0);

	$contactNo = $row->ContactNo;
	if ($contactNo == ""){
		$contactNo = "N/A";
	}

	$pdf->Cell(0,5,$contactNo,0,1);


	// employee email address
	$pdf->SetTextColor(0,0,255);
	$pdf->Cell(28,5,"Email Address:",0,0,"R");
	$pdf->SetTextColor(30,144,255);
	$pdf->SetFont("Arial","BU","10");

	$emailAddress = $row->EmailAddress;
	if ($emailAddress == ""){
		$emailAddress = "N/A";
	}

	$pdf->Cell(0,5,utf8_decode($emailAddress),0,1);

	$pdf->Cell(0,5,"","B",1); // FOR UNDERLINE


	$pdf->SetFont("Arial","BU","10");
	$pdf->SetTextColor(255,0,0);
	$pdf->Cell(0,10,"COMPANY INFORMATION",0,1);

	// employee department
	$pdf->SetFont("Arial","B","9");
	$pdf->SetTextColor(0,0,255);
	$pdf->Cell(28,5,"Department:",0,0,"R");
	$pdf->SetTextColor(0,0,0);
	$pdf->Cell(35,5,utf8_decode($department_class->getDepartmentValue($row->dept_id)->Department),0,0);

	$pdf->SetTextColor(0,0,255);
	$pdf->Cell(28,5,"Position:",0,0,"R");
	$pdf->SetTextColor(0,0,0);
	$pdf->Cell(35,5,utf8_decode($position_class->getPositionById($row->position_id)->Position),0,0);

	$pdf->SetTextColor(0,0,255);
	$pdf->Cell(28,5,"Salary:",0,0,"R");
	$pdf->SetTextColor(0,0,0);
	$pdf->Cell(35,5,"Php " . $money_class->getMoney($row->Salary),0,1);

	$pdf->SetTextColor(0,0,255);
	$pdf->Cell(28,5,"Date Hired:",0,0,"R");
	$pdf->SetTextColor(0,0,0);
	$pdf->Cell(35,5,date_format(date_create($row->DateHired),"F d, Y"),0,1);

	/*$pdf->SetTextColor(0,0,255);
	$pdf->Cell(28,5,"Position:",0,0,"R");
	$pdf->SetTextColor(0,0,0);
	$pdf->Cell(35,5,utf8_decode($position_class->getPositionById($row->position_id)->Position),0,0);

	$pdf->SetTextColor(0,0,255);
	$pdf->Cell(28,5,"Salary:",0,0,"R");
	$pdf->SetTextColor(0,0,0);
	$pdf->Cell(35,5,"Php " . $money_class->getMoney($row->Salary),0,1);*/

	$pdf->Cell(0,5,"","B",1); // FOR UNDERLINE

	$pdf->SetFont("Arial","BU","10");
	$pdf->SetTextColor(255,0,0);
	$pdf->Cell(0,10,"GOVERNMENT INFORMATION",0,1);

	$pdf->SetFont("Arial","B","9");
	$pdf->SetTextColor(0,0,255);
	$pdf->Cell(28,5,"SSS No:",0,0,"R");
	$pdf->SetTextColor(0,0,0);
	$sssNo = $govt_format_class->sssNoFormat($row->SSS_No);
	if ($sssNo == ""){
		$sssNo = "N/A";
	}
	$pdf->Cell(35,5,$sssNo,0,0);


	$pdf->SetFont("Arial","B","9");
	$pdf->SetTextColor(0,0,255);
	$pdf->Cell(28,5,"Pag-ibig No:",0,0,"R");
	$pdf->SetTextColor(0,0,0);
	$pagibigNo = $govt_format_class->pagibigNoFormat($row->PagibigNo);
	if ($pagibigNo == ""){
		$pagibigNo = "N/A";
	}
	$pdf->Cell(35,5,$pagibigNo,0,0);


	$pdf->SetFont("Arial","B","9");
	$pdf->SetTextColor(0,0,255);
	$pdf->Cell(28,5,"Tin No:",0,0,"R");
	$pdf->SetTextColor(0,0,0);
	$tinNo = $govt_format_class->tinNoFormat($row->TinNo);
	if ($tinNo == ""){
		$tinNo = "N/A";
	}
	$pdf->Cell(35,5,$tinNo,0,1);


	$pdf->SetFont("Arial","B","9");
	$pdf->SetTextColor(0,0,255);
	$pdf->Cell(28,5,"Philhealth No:",0,0,"R");
	$pdf->SetTextColor(0,0,0);
	$philhealthNo = $govt_format_class->philhealthNoFormat($row->PhilhealthNo);
	if ($philhealthNo == ""){
		$philhealthNo = "N/A";
	}
	$pdf->Cell(35,5,$philhealthNo,0,1);

	$pdf->Cell(0,5,"","B",1); // FOR UNDERLINE

	$pdf->SetFont("Arial","BU","10");
	$pdf->SetTextColor(255,0,0);
	$pdf->Cell(0,10,"SCHOOL INFORMATION",0,1);

	$pdf->SetFont("Arial","B","9");
	$pdf->SetTextColor(0,0,255);
	$pdf->Cell(28,5,"Educational Attain:",0,0,"R");
	$pdf->SetTextColor(0,0,0);
	$pdf->Cell(35,5,$row->highest_educational_attain,0,1);


	$servername = 'localhost';
	$username = 'root';
	$password = '';
	$dbname = 'live_db_hr_payroll';
	//$dbname = 'test_live_db_hr_payroll';
	// mysqli connect
	$connect = mysqli_connect($servername,$username,$password,$dbname);


	$is_first = 0;
	$select_qry = "SELECT * FROM tb_emp_education_attain WHERE emp_id='$emp_id'";
	if ($result = mysqli_query($connect,$select_qry)){
		while ($row_educ = mysqli_fetch_object($result)){



			//echo $row->type;
			if ($row_educ->type == 0){	


				$pdf->Cell(69,3,"",0,1); // for margin

				$pdf->SetFont("Arial","","9");
				$pdf->SetTextColor(0,0,255);
				$pdf->Cell(28,5,"",0,0,"R");
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(69,5,"SECONDARY",0,1);

				$pdf->SetFont("Arial","B","9");
				$pdf->SetTextColor(0,0,255);
				$pdf->Cell(28,5,"School Name:",0,0,"R");
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(69,5,utf8_decode($row_educ->school_name),0,1);

				$pdf->SetFont("Arial","I","9");
				$pdf->SetTextColor(0,0,255);
				$pdf->Cell(28,5,"",0,0,"R");
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(69,5,$row_educ->year_from . " -  " . $row_educ->year_to,0,1);


				
			}

			else {

				$pdf->Cell(69,3,"",0,1); // for margin
				if ($is_first == 0){
					$is_first = 1;

					$pdf->SetFont("Arial","","9");
					$pdf->SetTextColor(0,0,255);
					$pdf->Cell(28,5,"",0,0,"R");
					$pdf->SetTextColor(0,0,0);
					$pdf->Cell(69,5,"TERTIARY",0,1);
				}

				$pdf->SetFont("Arial","B","9");
				$pdf->SetTextColor(0,0,255);
				$pdf->Cell(28,5,"School Name:",0,0,"R");
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(69,5,utf8_decode($row_educ->school_name),0,1);

				$pdf->SetFont("Arial","B","9");
				$pdf->SetTextColor(0,0,255);
				$pdf->Cell(28,5,"",0,0,"R");
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(69,5,$row_educ->course,0,1);

				$pdf->SetFont("Arial","I","9");
				$pdf->SetTextColor(0,0,255);
				$pdf->Cell(28,5,"",0,0,"R");
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(69,5,$row_educ->year_from . " -  " . $row_educ->year_to,0,1);			
		        
			}

		}
	}


	$pdf->Cell(0,5,"","B",1); // FOR UNDERLINE

	$pdf->SetFont("Arial","BU","10");
	$pdf->SetTextColor(255,0,0);
	$pdf->Cell(0,10,"WORK EXPERIENCE",0,1);

	$select_qry = "SELECT * FROM tb_emp_work_experience WHERE emp_id='$emp_id'";
	if ($result = mysqli_query($connect,$select_qry)){
		while ($row_work = mysqli_fetch_object($result)){

			$pdf->SetFont("Arial","B","9");
			$pdf->SetTextColor(0,0,255);
			$pdf->Cell(28,5,"Position:",0,0,"R");
			$pdf->SetTextColor(0,0,0);
			$pdf->Cell(69,5,utf8_decode($row_work->position),0,1);

			$pdf->SetFont("Arial","B","9");
			$pdf->SetTextColor(0,0,255);
			$pdf->Cell(28,5,"",0,0,"R");
			$pdf->SetTextColor(0,0,0);
			$pdf->Cell(69,5,utf8_decode($row_work->company_name),0,1);

			$pdf->SetFont("Arial","","9");
			$pdf->SetTextColor(0,0,255);
			$pdf->Cell(28,5,"",0,0,"R");
			$pdf->SetTextColor(0,0,0);
			$pdf->Cell(69,5,utf8_decode($row_work->job_description),0,1);


			$pdf->SetFont("Arial","I","9");
			$pdf->SetTextColor(0,0,255);
			$pdf->Cell(28,5,"",0,0,"R");
			$pdf->SetTextColor(0,0,0);
			$pdf->Cell(69,5,$row_work->year_from . " -  " . $row_work->year_to,0,1);	


		}
	}



	$pdf->output();

}
else {
	header("Location:MainForm.php");
}
?>