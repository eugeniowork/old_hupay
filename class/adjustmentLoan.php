<?php

class AdjustmentLoan extends Connect_db{

	public function insertAdjustmentLoan($emp_id,$datePayment,$pagibigLoanId,$sssLoanId,$salaryLoanId,$simkimbanId,
								$loanType,$cashPayment,$remainingBalance,$remarks,$dateCreated){

		$connect = $this->connect();


		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$datePayment = mysqli_real_escape_string($connect,$datePayment);
		$pagibigLoanId = mysqli_real_escape_string($connect,$pagibigLoanId);
		$sssLoanId = mysqli_real_escape_string($connect,$sssLoanId);
		$salaryLoanId = mysqli_real_escape_string($connect,$salaryLoanId);
		$simkimbanId = mysqli_real_escape_string($connect,$simkimbanId);
		$loanType = mysqli_real_escape_string($connect,$loanType);
		$cashPayment = mysqli_real_escape_string($connect,$cashPayment);
		$remainingBalance = mysqli_real_escape_string($connect,$remainingBalance);
		$remarks = mysqli_real_escape_string($connect,$remarks);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$insert_qry = "INSERT INTO tb_adjustment_loan (adjustment_loan_id,emp_id,datePayment,pagibig_loan_id,sss_loan_id,
													salary_loan_id,simkimban_id,loanType,cashPayment,
													remainingBalance,remarks,DateCreated)
									VALUES ('','$emp_id','$datePayment','$pagibigLoanId','$sssLoanId','$salaryLoanId','$simkimbanId','$loanType','$cashPayment',
											'$remainingBalance','$remarks','$dateCreated')";
		$sql = mysqli_query($connect,$insert_qry);

		//mysqli_close($connect);
	
		
	}


	// for putting info to table without simkimban
	public function adjustmentReportListToTable(){
		$connect = $this->connect();

		$select_qry = "SELECT * FROM tb_adjustment_loan WHERE loanType != 'Simkimban'"; // iba ung reporting pag sa simkimban
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$row->emp_id'";
				$result_emp = mysqli_query($connect,$select_emp_qry);
				$row_emp = mysqli_fetch_object($result_emp);

				$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;

				$date_create = date_create($row->datePayment);
				$datePayment = date_format($date_create, 'F d, Y');

			    echo "<tr id='".$row->adjustment_loan_id."'>";
					echo "<td>" .$fullName. "</td>";
					echo "<td>" .$datePayment. "</td>";
					echo "<td>" .$row->loanType. "</td>";
					echo "<td>" .$this->getMoney($row->cashPayment). "</td>";
					echo "<td>" .$this->getMoney($row->remainingBalance). "</td>";
					echo "<td>";
						echo "<span class='glyphicon glyphicon-print' style='color: #283747 '></span> <a href='#' id='print_loanAdjustmentReports' class='action-a'>Print Reports</a>";
					echo "</td>";
				echo "</tr>";

			}
		}
	}


	// for putting info to table only simkimban
	public function adjustmentSimkimbanReportListToTable(){
		$connect = $this->connect();

		$select_qry = "SELECT * FROM tb_adjustment_loan WHERE loanType = 'Simkimban'"; // iba ung reporting pag sa simkimban
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$row->emp_id'";
				$result_emp = mysqli_query($connect,$select_emp_qry);
				$row_emp = mysqli_fetch_object($result_emp);

				$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;

				$date_create = date_create($row->datePayment);
				$datePayment = date_format($date_create, 'F d, Y');

			    echo "<tr id='".$row->adjustment_loan_id."'>";
					echo "<td>" .$fullName. "</td>";
					echo "<td>" .$datePayment. "</td>";
					echo "<td>" .$row->loanType. "</td>";
					echo "<td>" .$this->getMoney($row->cashPayment). "</td>";
					echo "<td>" .$this->getMoney($row->remainingBalance). "</td>";
					echo "<td>";
						echo "<span class='glyphicon glyphicon-print' style='color: #283747 '></span> <a href='#' id='print_simkimbanAdjustmentReports' class='action-a'>Print Reports</a>";
					echo "</td>";
				echo "</tr>";

			}
		}
	}


	// check exist adjustment_loan_id
	public function checkExistAdjustmentLoanId($adjustment_loan_id){
		$connect = $this->connect();

		$adjustment_loan_id = mysqli_real_escape_string($connect,$adjustment_loan_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_adjustment_loan WHERE adjustment_loan_id = '$adjustment_loan_id'"));
		return $num_rows;
	}


	// for print reports
	public function LoanAdjustmentReports($adjustment_loan_id){
		$connect = $this->connect();

		$adjustment_loan_id = mysqli_real_escape_string($connect,$adjustment_loan_id);

		require ("reports/fpdf.php");

		$pdf = new PDF_MC_Table("l");
		$pdf->SetMargins("20","35"); // left top

		$pdf->AddPage();

		// for logo
	    $pdf->Image("img/logo/lloyds_report_logo.jpeg",118,10,65,25);// margin-left,margin-top,width,height
	    $pdf->SetFont("helvetica","B","7.5");
	    $pdf->Cell(0,3,"1255 Cardona St. Rizal Village, Makati City",0,1,"C");
	    $pdf->Cell(0,3,"897 66 44 - 46 / 897 62 76 - 77",0,1,"C");

	    $pdf->Cell(0,5,"",0,1,"C"); // for margin

	    $pdf->SetFont("helvetica","B","10");
	    $pdf->Cell(0,5,"LOAN ADJUSTMENT REPORTS",0,1,"C"); // for margin

	    $pdf->Cell(0,5,"",0,1,"C"); // for margin

	    $pdf->SetFont("helvetica","B","9");
	    $pdf->SetWidths(array(50,35,35,35,45,60));
		$pdf->SetAligns(array("C","C","C","C","C","C"));
		$pdf->Row(array("EMPLOYEE NAME","DATE PAYMENT","LOAN TYPE","CASH PAYMENT","OUTSTANDING BALANCE","REMARKS"));


		if ($this->checkExistAdjustmentLoanId($adjustment_loan_id) != 0){


			$select_qry = "SELECT * FROM tb_adjustment_loan WHERE adjustment_loan_id = '$adjustment_loan_id'";
			$result = mysqli_query($connect,$select_qry);
			$row = mysqli_fetch_object($result);

			$select_emp_qry = "SELECT * FROM tb_employee_info WHERe emp_id = '$row->emp_id'";
			$result_emp = mysqli_query($connect,$select_emp_qry);
			$row_emp = mysqli_fetch_object($result_emp);

			$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;

			$date_create = date_create($row->datePayment);
			$datePayment = date_format($date_create, 'F d, Y');


			$pdf->SetFont("helvetica","","9");
		    $pdf->SetWidths(array(50,35,35,35,45,60));
			$pdf->SetAligns(array("C","C","C","C","C","C"));
			$pdf->Row(array($fullName,$datePayment,$row->loanType,$this->getMoney($row->cashPayment),$this->getMoney($row->remainingBalance),nl2br(htmlspecialchars($row->remarks))));
		}



	    $pdf->output();
	}



	// for print reports
	public function SimkimbanAdjustmentReports($adjustment_loan_id){
		$connect = $this->connect();

		$adjustment_loan_id = mysqli_real_escape_string($connect,$adjustment_loan_id);

		require ("reports/fpdf.php");

		$pdf = new PDF_MC_Table("l");
		$pdf->SetMargins("20","35"); // left top

		$pdf->AddPage();

		// for logo
	    $pdf->Image("img/logo/lloyds_report_logo.jpeg",118,10,65,25);// margin-left,margin-top,width,height
	    $pdf->SetFont("helvetica","B","7.5");
	    $pdf->Cell(0,3,"1255 Cardona St. Rizal Village, Makati City",0,1,"C");
	    $pdf->Cell(0,3,"897 66 44 - 46 / 897 62 76 - 77",0,1,"C");

	    $pdf->Cell(0,5,"",0,1,"C"); // for margin

	    $pdf->SetFont("helvetica","B","10");
	    $pdf->Cell(0,5,"SIMKIMBAN ADJUSTMENT REPORTS",0,1,"C"); // for margin

	    $pdf->Cell(0,5,"",0,1,"C"); // for margin

	    $pdf->SetFont("helvetica","B","9");
	    $pdf->SetWidths(array(50,35,35,35,45,60));
		$pdf->SetAligns(array("C","C","C","C","C","C"));
		$pdf->Row(array("EMPLOYEE NAME","DATE PAYMENT","LOAN TYPE","CASH PAYMENT","OUTSTANDING BALANCE","REMARKS"));

		$select_qry = "SELECT * FROM tb_adjustment_loan WHERE adjustment_loan_id = '$adjustment_loan_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		$select_emp_qry = "SELECT * FROM tb_employee_info WHERe emp_id = '$row->emp_id'";
		$result_emp = mysqli_query($connect,$select_emp_qry);
		$row_emp = mysqli_fetch_object($result_emp);

		$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;

		$date_create = date_create($row->datePayment);
		$datePayment = date_format($date_create, 'F d, Y');


		$pdf->SetFont("helvetica","","9");
	    $pdf->SetWidths(array(50,35,35,35,45,60));
		$pdf->SetAligns(array("C","C","C","C","C","C"));
		$pdf->Row(array($fullName,$datePayment,$row->loanType,$this->getMoney($row->cashPayment),$this->getMoney($row->remainingBalance),nl2br(htmlspecialchars($row->remarks))));



	    $pdf->output();
	}




	// for money output with comma
	public function getMoney($value){

        if ($value == 0) { // if 0       
            
            $final_value = "Php 0.00";                   
        }


		else if ($value >= 1 && $value < 10) { // for 1 digit
          
          	$decimal = "";

          	$one = substr($value,0,1);

            if ($this->is_decima($value) == 1) {
            	$decimal = substr($value,1);
            	$final_value = "Php " . $one . $decimal;
            }

            else {
            	$final_value = "Php " . $one . ".00";
            }

            
        }

		else if ($value >= 10 && $value < 100) { // for 2 digits 
          
          	$decimal = "";
            $ten = substr($value,0,1);
            $one = substr(substr($value,1),0,1);

            if ($this->is_decima($value) == 1) {
            	$decimal = substr($value,2);
            	$final_value = "Php " . $ten . $one . $decimal;
            }
            else {
            	$final_value = "Php " . $ten . $one . ".00";
            }

            
        }


		else if ($value >= 100 && $value < 1000) { // for 3 digits 
          
          	$decimal = "";
            $hundred = substr($value,0,1);
            $ten = substr(substr($value,1),0,1);
            $one = substr(substr($value,2),0,1);

            if ($this->is_decima($value) == 1) {
            	$decimal = substr($value,3);
            	$final_value = "Php " . $hundred . $ten . $one . $decimal;
            }

            else {
            	 $final_value = "Php " . $hundred . $ten . $one . ".00";
            }

           
        }


        else if ($value >= 1000 && $value < 10000) { // for 4 digits 
          
          	$decimal = "";
            $thousand = substr($value,0,1);
            $hundred = substr(substr($value,1),0,1);
            $ten = substr(substr($value,2),0,1);
            $one = substr(substr($value,3),0,1);

            if ($this->is_decima($value) == 1) {
            	$decimal = substr($value,4);
            	$final_value = "Php " . $thousand . "," . $hundred . $ten . $one . $decimal;
            }

            else {
            	$final_value = "Php " . $thousand . "," . $hundred . $ten . $one . ".00";
            }

           
        }

        else if ($value >= 10000 && $value < 100000) { // for 5 digits
        	$ten_thousand = substr($value,0,1);
        	$thousand = substr(substr($value,1),0,1);
        	$hundred = substr(substr($value,2),0,1);
        	$ten = substr(substr($value,3),0,1);
        	$one = substr(substr($value,4),0,1);

        	$decimal = "";
        	 if ($this->is_decima($value) == 1) {
            	$decimal = substr($value,5);
            	$final_value = "Php " . $ten_thousand . "" . $thousand . "," . $hundred . $ten . $one . $decimal;
            }

            else {
            	$final_value = "Php " . $ten_thousand . "" . $thousand . "," . $hundred . $ten . $one . ".00";
            }

           
           
        }

        else if ($value>= 100000 && $value < 1000000) { // 6 digits
        	$hundred_thousand = substr($value,0,1);
            $ten_thousand = substr(substr($value,1),0,1);
        	$thousand = substr(substr($value,2),0,1);
        	$hundred = substr(substr($value,3),0,1);
        	$ten = substr(substr($value,4),0,1);
        	$one = substr(substr($value,5),0,1);

        	$decimal = "";
        	 if ($this->is_decima($value) == 1) {
            	$decimal = substr($value,6);
            	$final_value = "Php " . $hundred_thousand. $ten_thousand . "" . $thousand . "," . $hundred . $ten . $one . $decimal;
            }

            else {
            	$final_value = "Php " . $hundred_thousand. $ten_thousand . "" . $thousand . "," . $hundred . $ten . $one . ".00";
            }
        }

        return $final_value;
	}


	// if has decimal
	function is_decima($val)
	{
	    return is_numeric($val) && floor($val) != $val;
	}


}

?>