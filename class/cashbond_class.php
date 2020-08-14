<?php

class Cashbond extends Connect_db {


	// for computing cashbond for new employee
	public function cashbondNewEmpFormula($salary){
		$connect = $this->connect();
		$salary = mysqli_real_escape_string($connect,$salary);
		$cashBond = ($salary * .02)/2;

		return $cashBond;
	}


	// for inserting values
	public function insertCashbond($emp_id,$cashbondValue,$dateCreated){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$cashbondValue = mysqli_real_escape_string($connect,$cashbondValue);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$insert_qry = "INSERT INTO tb_cashbond (cashbond_id,emp_id,cashbondValue,DateCreated) VALUES ('','$emp_id','$cashbondValue','$dateCreated')";
		$sql = mysqli_query($connect,$insert_qry);
	}

	// for updating cashbond if inupdate ung mga fields na may relate sa salary or allowance
	public function updateCashbond($emp_id,$cashbondValue,$total_cashbond){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$cashbondValue = mysqli_real_escape_string($connect,$cashbondValue);
		$total_cashbond = mysqli_real_escape_string($connect,$total_cashbond);

		//$update_qry = "UPDATE tb_cashbond SET cashbondValue = '$cashbondValue', totalCashbond = '$total_cashbond' WHERE emp_id = '$emp_id'";
		$update_qry = "UPDATE tb_cashbond SET cashbondValue = '$cashbondValue' WHERE emp_id = '$emp_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


	public function updateFileCashbondWithrawal($emp_id,$amount_withdraw){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$amount_withdraw = mysqli_real_escape_string($connect,$amount_withdraw);

		$row = $this->getLastestFileCashbondWithdrawal($emp_id);
		$file_cashbond_withdrawal_id = $row->file_cashbond_withdrawal_id;

		$update_qry = "UPDATE tb_file_cashbond_withdrawal SET amount_withdraw = '$amount_withdraw' WHERE file_cashbond_withdrawal_id = '$file_cashbond_withdrawal_id'";
		$sql = mysqli_query($connect,$update_qry);
	}

	// for showing info to table
	public function getCashbondInfoToTable(){
		$connect = $this->connect();

		$select_qry = "SELECT * FROM tb_cashbond";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){


				// for getting employee info
				$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$row->emp_id'";
				$result_emp = mysqli_query($connect,$select_emp_qry);
				$row_emp = mysqli_fetch_object($result_emp);


				if ($row_emp->ActiveStatus == '1'){

					$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;

					echo "<tr id='".$row->cashbond_id."'>";
						echo "<td>" .$fullName. "</td>";
						echo "<td>" .number_format($row->cashbondValue,2). "</td>";
						echo "<td>" .number_format($row->totalCashbond,2). "</td>";
						echo "<td><center>";
							echo "<a href='#' id='edit_cashbond' class='action-a'><span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span></a>";
							echo "&nbsp;|&nbsp;";
							echo "<a href='#' id='view_cashbond_reports' class='action-a'><span class='glyphicon glyphicon-eye-open' style='color: #2980b9 '></span></a>";
							echo "&nbsp;|&nbsp;";
							echo "<a href='#' id='add_cashbond_deposit' class='action-a'><span class='glyphicon glyphicon-plus-sign' style='color: #717d7e'></span></a>";
							echo "&nbsp;|&nbsp;";
							echo "<a href='#' id='adjust_cashbond' class='action-a'><span class='glyphicon glyphicon-adjust' style='color:#229954'></span></a>";
						echo "</center></td>";
					echo "</tr>";
				}
			}
		}

		return $row;

	}


	// this is for searching if the total cashbond value of the employee
	public function getTotalCashbond($emp_id){
		//$connect = $this->connect();

		//$select_qry = "SELECT * FROM "
	}


	// for checking if cashbond id is exist
	public function checkExistCashBond($cashbond_id){
		$connect = $this->connect();

		$cashbond_id = mysqli_real_escape_string($connect,$cashbond_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_cashbond WHERE cashbond_id = '$cashbond_id'"));

		return $num_rows;
	}

	public function checkExistCashBondByEmpId($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_cashbond WHERE emp_id = '$emp_id'"));

		return $num_rows;
	}

	// for checking if no changes were taken
	public function noChanges($cashbond_id,$cashbond_value,$total_cashbond){
		$connect = $this->connect();

		$cashbond_id = mysqli_real_escape_string($connect,$cashbond_id);
		$cashbond_value = mysqli_real_escape_string($connect,$cashbond_value);
		$total_cashbond = mysqli_real_escape_string($connect,$total_cashbond);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_cashbond WHERE cashbondValue = '$cashbond_value' AND totalCashbond = '$total_cashbond' AND cashbond_id = '$cashbond_id'"));

		return $num_rows;


	}

	// for getting information by rows
	public function getInfoByCashbondId($cashbond_id){
		$connect = $this->connect();

		$cashbond_id = mysqli_real_escape_string($connect,$cashbond_id);

		$select_qry = "SELECT * FROM tb_cashbond WHERE cashbond_id = '$cashbond_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		return $row;
	}


	public function getInfoByEmpId($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$select_qry = "SELECT * FROM tb_cashbond WHERE emp_id = '$emp_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		return $row;
	}

	// for update
	/*public function updateCashbond($cashbond_id,$cashbondValue){
		$connect = $this->connect();

		$cashbond_id = mysqli_real_escape_string($connect,$cashbond_id);
		$cashbondValue = mysqli_real_escape_string($connect,$cashbondValue);

		$update_qry = "UPDATE tb_cashbond SET cashbondValue = '$cashbondValue' WHERE cashbond_id = '$cashbond_id'";
		$sql = mysqli_query($connect,$update_qry);
	}*/


	public function getCashBondReports(){
		$connect = $this->connect();


		include "excel-report/PHPExcel/Classes/PHPExcel.php";

		$filename = "cashbond_reports";
		$objPHPExcel = new PHPExcel();
		/*********************Add column headings START**********************/
		$objPHPExcel->setActiveSheetIndex(0) 
					->setCellValue('A1', 'Emplyee Name')
					->setCellValue('B1', 'Cashbond Deduction')
					->setCellValue('C1', 'Total Cashbond');
					//->setCellValue('D1', 'Total count of login')
					//->setCellValue('E1', 'Facebook Login');
		/*********************Add column headings END**********************/
		
		// You can add this block in loop to put all ur entries.Remember to change cell index i.e "A2,A3,A4" dynamically 
		/*********************Add data entries START**********************/
		
		$count = 1;
		$select_emp_qry = "SELECT * FROM tb_employee_info WHERE (role_id != '1' OR dept_id != '1') ORDER BY Lastname ASC";
		if ($result_emp = mysqli_query($connect,$select_emp_qry)){
			while ($row_emp = mysqli_fetch_object($result_emp)){

				$select_qry = "SELECT * FROM tb_cashbond WHERE emp_id = '$row_emp->emp_id'";
				$result = mysqli_query($connect,$select_qry);
				$row = mysqli_fetch_object($result);

				$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;


				$count++;
				$objPHPExcel->setActiveSheetIndex(0) 
					->setCellValue('A'.$count, $fullName)
					->setCellValue('B'.$count, $row->cashbondValue)
					->setCellValue('C'.$count, $row->totalCashbond);
			}
		}

		
					//->setCellValue('D2', '5')
				//	->setCellValue('E2', 'No');
		/*********************Add data entries END**********************/
		
		/*********************Autoresize column width depending upon contents START**********************/
        foreach(range('A','C') as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
		}
		/*********************Autoresize column width depending upon contents END***********************/
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true); //Make heading font bold
		
		/*********************Add color to heading START**********************/
		$objPHPExcel->getActiveSheet()
					->getStyle('A1:C1')
					->getFill()
					->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
					->getStartColor()
					->setRGB('abb2b9');
		/*********************Add color to heading END***********************/
		
		$objPHPExcel->getActiveSheet()->setTitle('cashbond_list_reports'); //give title to sheet
		$objPHPExcel->setActiveSheetIndex(0);
		header('Content-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment;Filename=$filename.xls");
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;

	}



	// for adding the cashbond deduction to the current total cashbond
	public function addCashbondTotalValue(){
		$connect = $this->connect();

		$dates = date("Y-m-d H:i:s");
		$date = date_create($dates);
		//date_sub($date, date_interval_create_from_date_string('15 hours'));

		// $current_date_time = date_format($date, 'Y-m-d H:i:s');
		$current_date_time = date_format($date, 'Y-m-d');


		$year = date("Y");

		$minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
		$select_qry = "SELECT * FROM tb_cut_off";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){


				$date_from = date_format(date_create($row->dateFrom),'Y-m-d');
				if (date_format(date_create($row->dateFrom),'m-d') == "12-26"){
					//echo "wew";
					$prev_year = $year - 1;
					$date_from = $prev_year . "-" .date_format(date_create($row->dateFrom),'m-d');
					//echo $date_from . "sad";
					//$date_from = date_format(date_create($row->dateFrom),'Y-m-d');

				}
				$date_from = date_format(date_create($date_from),"Y-m-d");
				$date_to = date_format(date_create($row->dateTo),'Y-m-d');
				//$to =  date("Y-m-d",strtotime($date_to) + (86400 *5));
				//echo $to . "<br/>";


				$minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));

				
				if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
					$final_date_from = $date_from;
					$final_date_to = $date_to;
					$date_payroll = date_format(date_create($row->datePayroll),'Y-m-d');
				}


			}

		}

		$cut_off_period =  date_format(date_create($final_date_from),'F d, Y') . " - " . date_format(date_create($final_date_to),'F d, Y');

		$select_qry = "SELECT * FROM tb_cashbond";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){


				$is_active = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_employee_info WHERE emp_id = '$row->emp_id' AND ActiveStatus = '1'"));

				//echo $is_active;

				if ($is_active != 0) {

					$totalCashbond = $row->totalCashbond;

					//echo $cut_off_period . "<br/>";
					
					// for selecting the cashnond deduction to current payroll cut off
					$select_payroll_info = "SELECT * FROM tb_payroll_info WHERE CutOffPeriod = '$cut_off_period' AND emp_id = '$row->emp_id'";
					$result_payroll_info = mysqli_query($connect,$select_payroll_info);
					$row_payroll_info = mysqli_fetch_object($result_payroll_info);

					$payroll_info_cashbond = $row_payroll_info->CashBond;

					//echo $payroll_info_cashbond . "<br/>";

					$current_date_payroll = $row_payroll_info->datePayroll;

					//echo $current_date_payroll . " ";

					//if ()
					$num_rows_payroll_info = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_payroll_info WHERE emp_id = '$row->emp_id'"));

					if ($num_rows_payroll_info == 1){
						 $newTotalCashbond = $payroll_info_cashbond;
					}

					else {

						$counter = 1;
						$old_date_payroll = "";
						$select_old_date_payroll = "SELECT * FROM `tb_payroll_info` WHERE emp_id = '$row->emp_id' ORDER BY `datePayroll` DESC LIMIT 2";
						if ($result_old_date_payroll = mysqli_query($connect,$select_old_date_payroll)){
							while ($row_old_date_payroll = mysqli_fetch_object($result_old_date_payroll)){

								if ($counter == 1){
									$counter++;
								}
								else {
									$old_date_payroll = $row_old_date_payroll->datePayroll;
								}

							}
						}

						//echo $old_date_payroll . "<br/>";

						$current_date_payroll = strtotime($current_date_payroll);
						$old_date_payroll = strtotime($old_date_payroll);

						$secs = $current_date_payroll - $old_date_payroll;// == <seconds between the two times>
						$days = $secs / 86400;

						//echo $days . "<br/>";
						
						$interest = round(($days) * $payroll_info_cashbond * (.03/360),2); // fora adding old totalcashbond + cashbond deduction from current payroll

						//echo $interest . "<br/>";

						$newTotalCashbond = round($interest + $row->totalCashbond + $payroll_info_cashbond,2);



					}

					//echo $row->emp_id . " sad " . $newTotalCashbond . "<br/>";

					// for updating new total cashbond
					$update_qry = "UPDATE tb_cashbond SET totalCashbond = '$newTotalCashbond' WHERE emp_id = '$row->emp_id'";
					$sql = mysqli_query($connect,$update_qry);
				} // end of is active
			}

		}


		

	}



	// for inserting file cashbond withdrawal
	public function insertFileCashbondWithdrawal($emp_id,$amount,$dateCreated){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$amount = mysqli_real_escape_string($connect,$amount);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);


		$insert_qry = "INSERT INTO tb_file_cashbond_withdrawal (file_cashbond_withdrawal_id,emp_id,approver_id,amount_withdraw,approve_stats,dateApprove,dateCreated) VALUES ('','$emp_id','','$amount','0','','$dateCreated')";
		$sql = mysqli_query($connect,$insert_qry);
	}


	// check exist if there is a pending cashbond for approval
	public function checkExistFileCashbondWithdrawal($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_file_cashbond_withdrawal WHERE emp_id = '$emp_id' AND approve_stats = '0'"));

		return $num_rows;
	}


	// for getting the latest pending cashbond withdrawal
	public function getLastestFileCashbondWithdrawal($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$select_qry = "SELECT * FROM tb_file_cashbond_withdrawal WHERE emp_id = '$emp_id' AND approve_stats = '0' ORDER BY dateCreated DESC LIMIT 1";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		return $row;
	}


	// for checking the count of pending filing cashbond withdrawal
	public function getCountPendingCashbondWithdrawal(){
		$connect = $this->connect();

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_file_cashbond_withdrawal WHERE approve_stats = '0'"));

		return $num_rows;
	}

	// for getting file cashbond if exist
	public function checkExistFileCashbondWithdrawalById($file_cashbond_withdrawal_id){
		$connect = $this->connect();

		$file_cashbond_withdrawal_id = mysqli_real_escape_string($connect,$file_cashbond_withdrawal_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_file_cashbond_withdrawal WHERE file_cashbond_withdrawal_id = '$file_cashbond_withdrawal_id' AND approve_stats = '0'"));

		return $num_rows;
	}


	// for getting all the file cashbond withdrawal to be approve or disapprove
	public function getAllPendingFileCashbondWithdrawal(){
		$connect = $this->connect();

		$select_qry = "SELECT * FROM tb_file_cashbond_withdrawal WHERE approve_stats = '0'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$row->emp_id'";
				$result_emp = mysqli_query($connect,$select_emp_qry);
				$row_emp = mysqli_fetch_object($result_emp);

				$emp_name = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename; 
				if ($row_emp->Middlename == ""){
					$emp_name = $row_emp->Lastname . ", " . $row_emp->Firstname;
				}

				$dateFile = date_format(date_create($row->dateCreated), 'F d, Y');
		

				echo "<tr id='".$row->file_cashbond_withdrawal_id."'>";
					echo "<td>" . $emp_name. "</td>";
					echo "<td>" . $this->getMoney($row->amount_withdraw) . "</td>";
					echo "<td>". $dateFile . "</td>";
					echo "<td>";

						//if ($_SESSION["role"] == 1){
							echo "<div style='cursor:pointer;color:#158cba;float:left;' id='approve_file_cashbond_withdrawal' title='Approve'><span class='glyphicon glyphicon-ok' style='color: #229954 '></span> Approve</div>";
								echo "<div style='float:left;'>&nbsp;|&nbsp;</div>";
								echo "<div style='cursor:pointer;color:#158cba;float:left;' id='approve_file_cashbond_withdrawal' title='Disapprove'><span class='glyphicon glyphicon-remove' style='color: #c0392b '></span> Disapprove</div>";
							echo "</td>";
						/*}

						else if ($_SESSION["role"] == 3){
							echo "No action";
						echo "</td>";
						}*/

						
				echo "</tr>";

			}
		}
	}


	// for getting the information by file cashbond withdrawal
	public function getInfoByFileCashbondWithdrawalId($file_cashbond_withdrawal_id){
		$connect = $this->connect();

		$file_cashbond_withdrawal_id = mysqli_real_escape_string($connect,$file_cashbond_withdrawal_id);
		$select_qry = "SELECT * FROM tb_file_cashbond_withdrawal WHERE file_cashbond_withdrawal_id = '$file_cashbond_withdrawal_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		return $row;

	}


	// for approving file cashbond withdrawal
	public function approveFileCashbondWithdrawal($file_cashbond_withdrawal_id,$approver_id,$approve_stats,$dateApprove){
		$connect = $this->connect();

		$file_cashbond_withdrawal_id = mysqli_real_escape_string($connect,$file_cashbond_withdrawal_id);
		$approver_id = mysqli_real_escape_string($connect,$approver_id);
		$approve_stats = mysqli_real_escape_string($connect,$approve_stats);
		$dateApprove = mysqli_real_escape_string($connect,$dateApprove);

		$update_qry = "UPDATE tb_file_cashbond_withdrawal SET approver_id = '$approver_id', approve_stats = '$approve_stats', dateApprove = '$dateApprove' WHERE file_cashbond_withdrawal_id = '$file_cashbond_withdrawal_id'";
		$sql = mysqli_query($connect,$update_qry);

	}


	// for approving file cashbond withdrawal
	public function disapproveFileCashbondWithdrawal($file_cashbond_withdrawal_id,$approver_id,$approve_stats,$dateApprove){
		$connect = $this->connect();

		$file_cashbond_withdrawal_id = mysqli_real_escape_string($connect,$file_cashbond_withdrawal_id);
		$approver_id = mysqli_real_escape_string($connect,$approver_id);
		$approve_stats = mysqli_real_escape_string($connect,$approve_stats);
		$dateApprove = mysqli_real_escape_string($connect,$dateApprove);

		$update_qry = "UPDATE tb_file_cashbond_withdrawal SET approver_id = '$approver_id', approve_stats = '$approve_stats', dateApprove = '$dateApprove' WHERE file_cashbond_withdrawal_id = '$file_cashbond_withdrawal_id'";
		$sql = mysqli_query($connect,$update_qry);

	}

	// for updating cashbond value after approving file salary loan
	public function updateTotalCashbondApproveCashWithdrawal($emp_id,$totalCashbond){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$totalCashbond = mysqli_real_escape_string($connect,$totalCashbond);

		$update_qry = "UPDATE tb_cashbond SET totalCashbond = '$totalCashbond' WHERE emp_id = '$emp_id'";
		$sql = mysqli_query($connect,$update_qry);

	}


	// for getting cashbond 
	public function getOwnApproveFileCashbondHistory($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$select_qry = "SELECT * FROM tb_file_cashbond_withdrawal WHERE emp_id = '$emp_id' AND approve_stats = '1'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				$dateFile = date_format(date_create($row->dateCreated), 'F d, Y');
				$approveDate = date_format(date_create($row->dateApprove), 'F d, Y');


				echo "<tr>";
					echo "<td>".$dateFile."</td>";
					echo "<td>".$approveDate."</td>";
					echo "<td>".$this->getMoney($row->amount_withdraw)."</td>";
				echo "</tr>";
			}
		}


	}


	// for inserting in employee cashbond history
	public function insertEmpCashbondHistory($dateCreated){
		$connect = $this->connect();

		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$dates = date("Y-m-d H:i:s");
		$date = date_create($dates);
		//date_sub($date, date_interval_create_from_date_string('15 hours'));

		// $current_date_time = date_format($date, 'Y-m-d H:i:s');
		$current_date_time = date_format($date, 'Y-m-d');

		$year = date("Y");

		$minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));
		$select_qry = "SELECT * FROM tb_cut_off";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){


				$date_from = date_format(date_create($row->dateFrom),'Y-m-d');
				if (date_format(date_create($row->dateFrom),'m-d') == "12-26"){
					//echo "wew";
					$prev_year = $year - 1;
					$date_from = $prev_year . "-" .date_format(date_create($row->dateFrom),'m-d');
					//echo $date_from . "sad";
					//$date_from = date_format(date_create($row->dateFrom),'Y-m-d');

				}
				$date_from = date_format(date_create($date_from),"Y-m-d");
				$date_to = date_format(date_create($row->dateTo),'Y-m-d');
				//$to =  date("Y-m-d",strtotime($date_to) + (86400 *5));
				//echo $to . "<br/>";


				$minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));

				
				if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
					$final_date_from = $date_from;
					$final_date_to = $date_to;
					$date_payroll = date_format(date_create($row->datePayroll),'Y-m-d');
				}


			}

		}

		$cut_off_period =  date_format(date_create($final_date_from),'F d, Y') . " - " . date_format(date_create($final_date_to),'F d, Y');

		$select_qry = "SELECT * FROM tb_employee_info";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				//$

				$is_exist = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_payroll_info WHERE CutOffPeriod = '$cut_off_period' AND emp_id = '$row->emp_id'"));

				//echo $is_active;

				if ($is_exist != 0) {

					//echo $cut_off_period . "<br/>";
					
					// for selecting the cashnond deduction to current payroll cut off
					$select_payroll_info = "SELECT * FROM tb_payroll_info WHERE CutOffPeriod = '$cut_off_period' AND emp_id = '$row->emp_id'";
					$result_payroll_info = mysqli_query($connect,$select_payroll_info);
					$row_payroll_info = mysqli_fetch_object($result_payroll_info);
					$payroll_info_cashbond = $row_payroll_info->CashBond;
					$emp_id = $row->emp_id;
					$cashbond_deposit = $row_payroll_info->CashBond;
					$current_date_payroll = $row_payroll_info->datePayroll;

					$num_rows_payroll_info = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_payroll_info WHERE emp_id = '$row->emp_id'"));

					if ($num_rows_payroll_info == 1){
						 $newTotalCashbond = $payroll_info_cashbond;
						 $cashbond_deposit = $payroll_info_cashbond;
						 $interest = 0;
					}

					else {

						$counter = 1;
						$old_date_payroll = "";
						$select_old_date_payroll = "SELECT * FROM `tb_payroll_info` WHERE emp_id = '$row->emp_id' ORDER BY `datePayroll` DESC LIMIT 2";
						if ($result_old_date_payroll = mysqli_query($connect,$select_old_date_payroll)){
							while ($row_old_date_payroll = mysqli_fetch_object($result_old_date_payroll)){

								if ($counter == 1){
									$counter++;
								}
								else {
									$old_date_payroll = $row_old_date_payroll->DateCreated;
								}

							}
						}
					

					//echo $old_date_payroll . "<br/>";

					$current_date_payroll = strtotime(date("Y-m-d"));
					$old_date_payroll = strtotime($old_date_payroll);

					$secs = $current_date_payroll - $old_date_payroll;// == <seconds between the two times>
					$days = $secs / 86400;

					//echo $days . "<br/>";
					//echo $this->getCurrentEndingBalance($emp_id) . " ";

					$percentage = .05;
					if ($this->getCurrentEndingBalance($emp_id) >= 30000){
						$percentage = .07;
					}

					$interest = round(($days) * $this->getCurrentEndingBalance($emp_id) * ($percentage/360),2); // fora adding old totalcashbond + cashbond deduction from current payroll

					//echo $interest . "<br/>";

					$newTotalCashbond = round($interest + $this->getCurrentEndingBalance($emp_id)+ $payroll_info_cashbond,2);

					} // end of else


					
					$current_date_payroll2 = date("Y-m-d");
					$insert_qry = "INSERT INTO tb_emp_cashbond_history (emp_cashbond_history,emp_id,cashbond_deposit,interest,
									posting_date,amount_withdraw,cashbond_balance,interest_rate,dateCreated)
						VALUES ('','$emp_id','$cashbond_deposit','$interest','$current_date_payroll2','0',
								'$newTotalCashbond','3','$dateCreated')";


					$sql = mysqli_query($connect,$insert_qry);



					// for updating cashbond
					$update_qry = "UPDATE tb_cashbond SET totalCashbond = '$newTotalCashbond' WHERE emp_id = '$emp_id'";
					$sql_update = mysqli_query($connect,$update_qry);
					
					

					
				} // end of is active
			}

		}
		

		

		

	}


	// for inserting to cashbond history after approval of cashbond withdrawal
	public function insertCashbondHistoryAfterWithdraw($emp_id,$cashbond_deposit,$remarks,$interest,$posting_date,$amount_withdraw,$cashbond_balance,$interest_rate,$dateCreated){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$cashbond_deposit = mysqli_real_escape_string($connect,$cashbond_deposit);
		$remarks = mysqli_real_escape_string($connect,$remarks);
		$interest = mysqli_real_escape_string($connect,$interest);
		$posting_date = mysqli_real_escape_string($connect,$posting_date);
		$amount_withdraw = mysqli_real_escape_string($connect,$amount_withdraw);
		$cashbond_balance = mysqli_real_escape_string($connect,$cashbond_balance);
		$interest_rate = mysqli_real_escape_string($connect,$interest_rate);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$insert_qry = "INSERT INTO tb_emp_cashbond_history (emp_cashbond_history,emp_id,cashbond_deposit,remarks,interest,
									posting_date,amount_withdraw,cashbond_balance,interest_rate,dateCreated)
						VALUES ('','$emp_id','$cashbond_deposit','$remarks','$interest','$posting_date','$amount_withdraw',
								'$cashbond_balance','$interest_rate','$dateCreated')";

		echo $insert_qry;

		$sql = mysqli_query($connect,$insert_qry);
	}


	// for getting cashbond history info total credits
	public function getTotalCreditsCashbondHistory($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$totalCredits = 0;
		$select_qry = "SELECT * FROM tb_emp_cashbond_history WHERE emp_id = '$emp_id' ORDER BY posting_date DESC LIMIT 2";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				if ($totalCredits == 0){
					$totalCredits = $row->cashbond_balance;
				}

				else {
					$totalCredits = $totalCredits + $row->cashbond_balance;
				}
			}
		}

		return $this->getMoney($totalCredits);

	}


	public function getTotalEndingCredits($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$totalCredits = 0;

		$counter = 0;
		$select_qry = "SELECT * FROM tb_emp_cashbond_history WHERE emp_id = '$emp_id' ORDER BY posting_date DESC LIMIT 2";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				if ($counter == 1) {
					$totalCredits = $row->cashbond_balance;
				}
				$counter++;
			}
		}


		return $this->getMoney($totalCredits);

	}


	
	public function getTotalEndingBalance($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$endBalance = 0;
		$select_qry = "SELECT * FROM tb_emp_cashbond_history WHERE emp_id = '$emp_id' ORDER BY emp_cashbond_history DESC LIMIT 1";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		$endBalance = $row->cashbond_balance;
			
		return $this->getMoney($endBalance);

	}


	public function getCurrentEndingBalance($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$endBalance = 0;
		$select_qry = "SELECT * FROM tb_emp_cashbond_history WHERE emp_id = '$emp_id' ORDER BY 	emp_cashbond_history DESC LIMIT 1";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		$endBalance = $row->cashbond_balance;
			
		return $endBalance;
		//return $this->getMoney($endBalance);

	}

	// for getting cashbond history info total debits
	public function getTotalDebitsCashbondHistory($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$totalDebits = 0;
		$select_qry = "SELECT * FROM tb_emp_cashbond_history WHERE emp_id = '$emp_id'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				if ($totalDebits == 0){
					$totalDebits = $row->amount_withdraw;
				}

				else {
					$totalDebits = $totalDebits + $row->amount_withdraw;
				}
			}
		}

		return $this->getMoney($totalDebits);

	}


	// for getting cashbond history info total interest earned
	public function getTotalInterestEarnedCashbondHistory($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$totalInterestEarned = 0;
		$select_qry = "SELECT * FROM tb_emp_cashbond_history WHERE emp_id = '$emp_id'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				if ($totalInterestEarned == 0){
					$totalInterestEarned = $row->interest;
				}

				else {
					$totalInterestEarned = $totalInterestEarned + $row->interest;
				}
			}
		}

		return $this->getMoney($totalInterestEarned);

	}



	// for getting cashbond history by emp id
	public function getCashbondHistoryByEmpIdToTable($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$select_qry = "SELECT * FROM tb_emp_cashbond_history WHERE emp_id = '$emp_id' ORDER BY posting_date ASC";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				$posting_date = date_format(date_create($row->posting_date), 'F d, Y');



				echo "<tr id='".$row->emp_cashbond_history."'>";
					echo "<td>". $posting_date . "</td>";
					echo "<td>" . $this->getMoney($row->cashbond_deposit) . "</td>";
					echo "<td>" . $this->getMoney($row->interest) . "</td>";

					if ($row->amount_withdraw != 0){
						echo "<td>";
							echo $this->getMoney($row->amount_withdraw);
							echo "<br/>";

							echo "<span id='div_cashbond_ref_no'>";
								echo "<small style='background-color: #3498db;color:#fff'>";
								if ($row->reference_no != ""){
									echo $row->reference_no;
								}
								else {
									echo "No Ref No.";
								}
								echo "</small>";
							

							
								echo "<button id='edit_ref_no' class='btn btn-update btn-xs pull-right'><span class='glyphicon glyphicon-edit'></span></button>";
							echo "</span>";
						echo  "</td>";
					}
					else {
						echo "<td>" . $this->getMoney($row->amount_withdraw) . "</td>";
					}
					echo "<td>" . $this->getMoney($row->cashbond_balance) . "</td>";
				echo "</tr>";
			}
		}
	}



	// for cancelling cashbondwithdrawal
	public function cancelCashbondWithdrawal($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$update_qry = "UPDATE tb_file_cashbond_withdrawal SET approve_stats = '3' WHERE emp_id = '$emp_id' AND approve_stats = '0'";
		$sql = mysqli_query($connect,$update_qry);

	}


	// for updating payroll notification
	public function readAllNotif($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$update_qry = "UPDATE tb_memo_notif SET readStatus = '1' WHERE to_emp_id = '$emp_id'";
		$sql = mysqli_query($connect,$update_qry);
	}



	public function getEmpCashbondHistoryReports($emp_id,$current_date){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$current_date = mysqli_real_escape_string($connect,$current_date);

		include "excel-report/PHPExcel/Classes/PHPExcel.php";


		// FOR CURRENT DATE AND TIME PURPOSE

		// for getting employee info
		$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$emp_id'";
		$result_emp = mysqli_query($connect,$select_emp_qry);
		$row_emp = mysqli_fetch_object($result_emp);

		$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;
		
		$percentage = "5%";
		//$this->getTotalEndingCredits($emp_id)
		if (str_replace(",","",str_replace("Php","",$this->getTotalEndingCredits($emp_id))) >= 30000){
			$percentage = "7%";
		}


		$filename = "emp_cashbond_history_reports";
		$objPHPExcel = new PHPExcel();
		/*********************Add column headings START**********************/
		$objPHPExcel->setActiveSheetIndex(0) 
					->setCellValue('A1', 'Date')
					->setCellValue('B1', $current_date)
					->setCellValue('A2', 'Employee Name')
					->setCellValue('B2', $fullName)
					->setCellValue('A3', 'Interest Rate')
					->setCellValue('B3', $percentage)
					->setCellValue('A4', 'Total Credits') 
					->setCellValue('B4', $this->getTotalEndingCredits($emp_id)) // getTotalCreditsCashbondHistory
					->setCellValue('A5', 'Total Debits')
					->setCellValue('B5', $this->getTotalDebitsCashbondHistory($emp_id))
					->setCellValue('A6', 'Total Interest Earned')
					->setCellValue('B6', $this->getTotalInterestEarnedCashbondHistory($emp_id))
					->setCellValue('A7', 'Ending Balance')
					->setCellValue('B7', $this->getTotalEndingBalance($emp_id)); // $this->getTotalCreditsCashbondHistory($emp_id)
					//->setCellValue('D1', 'Total count of login')
					//->setCellValue('E1', 'Facebook Login');


		$objPHPExcel->setActiveSheetIndex(0) 
					->setCellValue('A9', 'Posting Date')
					->setCellValue('B9', 'Deposit')
					->setCellValue('C9', 'Compounded Interest')
					->setCellValue('D9', 'Withdrawal')
					->setCellValue('E9', 'Balance')
					->setCellValue('F9', 'Remarks')
					->setCellValue('G9', 'Reference No');
		/*********************Add column headings END**********************/
		
		// You can add this block in loop to put all ur entries.Remember to change cell index i.e "A2,A3,A4" dynamically 
		/*********************Add data entries START**********************/
		
			
		$counter = 10;
		$select_qry = "SELECT * FROM tb_emp_cashbond_history WHERE emp_id = '$emp_id' ORDER BY posting_date ASC";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				$posting_date = date_format(date_create($row->posting_date), 'm/d/Y');


				$objPHPExcel->setActiveSheetIndex(0) 
					->setCellValue('A'.$counter, $posting_date)
					->setCellValue('B'.$counter, $row->cashbond_deposit)
					->setCellValue('C'.$counter, $this->getMoney($row->interest))
					->setCellValue('D'.$counter, $row->amount_withdraw)
					->setCellValue('E'.$counter, $row->cashbond_balance)
					->setCellValue('F'.$counter, $row->remarks)
					->setCellValue('G'.$counter, $row->reference_no);
				
				$counter++;
			}
		}


		$counter = $counter + 3;
		$name_counter = $counter+1;
		$date_counter = $name_counter+1;
		// for checking if the last row is withdrawal

		//echo $emp_id;
		$select_withdrawal_qry = "SELECT * FROM tb_emp_cashbond_history WHERE emp_id = '$emp_id' ORDER BY posting_date DESC LIMIT 1";
		$result_withdrawal = mysqli_query($connect,$select_withdrawal_qry);
		$row_withdrawal = mysqli_fetch_object($result_withdrawal);



		// ibig sabihin withdrawal un
		if ($row_withdrawal->amount_withdraw != 0){

			$select_approve_withdrawal_qry = "SELECT * FROM tb_file_cashbond_withdrawal WHERE emp_id = '$emp_id' AND approve_stats = '1' ORDER BY dateCreated DESC LIMIT 1 ";
			$result_approve_withdrawal = mysqli_query($connect,$select_approve_withdrawal_qry);
			$row_approve_withdrawal = mysqli_fetch_object($result_approve_withdrawal);

			//echo $row_approve_withdrawal->approver_id;

			$select_approver_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$row_approve_withdrawal->approver_id'";
			$result_approver = mysqli_query($connect,$select_approver_qry);
			$row_approver = mysqli_fetch_object($result_approver);

			$fullNameApprover = $row_approver->Lastname . ", " . $row_approver->Firstname . " " . $row_approver->Middlename;

			$dateApprove = date_format(date_create($row_approve_withdrawal->dateApprove), 'm/d/Y');


			$objPHPExcel->setActiveSheetIndex(0) 
					->setCellValue('A'.$counter, "Approved By:")
					->setCellValue('A'.$name_counter, $fullNameApprover)
					->setCellValue('A'.$date_counter, $dateApprove);
		}


		
					//->setCellValue('D2', '5')
				//	->setCellValue('E2', 'No');
		/*********************Add data entries END**********************/
		
		/*********************Autoresize column width depending upon contents START**********************/
        foreach(range('A','F') as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
		}
		/*********************Autoresize column width depending upon contents END***********************/
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:A7')->getFont()->setBold(true); //Make heading font bold
		
		/*********************Add color to heading START**********************/
		$objPHPExcel->getActiveSheet()
					->getStyle('A9:F9')
					->getFill()
					->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
					->getStartColor()
					->setRGB('abb2b9');
		/*********************Add color to heading END***********************/
		
		$objPHPExcel->getActiveSheet()->setTitle('emp_cashbond_history_reports'); //give title to sheet
		$objPHPExcel->setActiveSheetIndex(0);
		header('Content-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment;Filename=$filename.xls");
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;

	}



	public function getLastCashbondHistory($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$endBalance = 0;
		$select_qry = "SELECT * FROM tb_emp_cashbond_history WHERE emp_id = '$emp_id' ORDER BY posting_date DESC LIMIT 1";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		return $row;
		//return $this->getMoney($endBalance);

	}


	// for updating cashbond if inupdate ung mga fields na may relate sa salary or allowance
	public function updateCashbondBalance($emp_id,$total_cashbond){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$total_cashbond = mysqli_real_escape_string($connect,$total_cashbond);

		$update_qry = "UPDATE tb_cashbond SET totalCashbond = '$total_cashbond' WHERE emp_id = '$emp_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


	public function updateCashbondWithdrawalRefNo($id,$reference_no){
		$connect = $this->connect();

		$id = mysqli_real_escape_string($connect,$id);
		$reference_no = mysqli_real_escape_string($connect,$reference_no);

		$update_qry = "UPDATE tb_emp_cashbond_history SET reference_no = '$reference_no' WHERE emp_cashbond_history = '$id'";
		$sql = mysqli_query($connect,$update_qry);
	}




	// for money output with comma
	public function getMoney($value){


       	 if ($value < 0) { // if 0       
            
            $final_value = "Php " .$value;                   
        }

        else if ($value == 0) { // if 0       
            
            $final_value = "Php 0.00";                   
        }

        else if ($value > 0 && $value < 1) { // if 0       
            
            $final_value = "Php " . $value;                   
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