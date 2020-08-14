<?php

class PayrollNotif extends Connect_db {
	
	// for inserting payroll notification
	public function insertPayrollNotif($payroll_admin_id,$emp_id,$payroll_id,$approve_payroll_id,$file_salary_loan_id,$notif_type,$cutOffPeriod,$readStatus){
		$connect = $this->connect();

		$payroll_admin_id = mysqli_real_escape_string($connect,$payroll_admin_id);
		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$payroll_id = mysqli_real_escape_string($connect,$payroll_id);
		$approve_payroll_id = mysqli_real_escape_string($connect,$approve_payroll_id);
		$file_salary_loan_id = mysqli_real_escape_string($connect,$file_salary_loan_id);
		$notif_type = mysqli_real_escape_string($connect,$notif_type);
		$cutOffPeriod = mysqli_real_escape_string($connect,$cutOffPeriod);
		$readStatus = mysqli_real_escape_string($connect,$readStatus);
		//$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$insert_qry = "INSERT INTO tb_payroll_notif (payroll_notif_id,payroll_admin_id,emp_id,payroll_id,approve_payroll_id,file_salary_loan_id,notifType,cutOffPeriod,readStatus) 
					VALUES ('','$payroll_admin_id','$emp_id','$payroll_id','$approve_payroll_id','$file_salary_loan_id','$notif_type','$cutOffPeriod','$readStatus')";

		$sql = mysqli_query($connect,$insert_qry);
	}



	


	// for getting payroll notification where read status = 0
	public function unreadPayrollNotifCount($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_payroll_notif WHERE emp_id = '$emp_id' AND readStatus = '0'"));

		return $num_rows;
	}


	// for updating payroll notification
	public function readAllNotif($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$update_qry = "UPDATE tb_payroll_notif SET readStatus = '1' WHERE emp_id = '$emp_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


	// for getting all notification of current employee
	public function getAllPayrollNotif($emp_id){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$select_qry = "SELECT * FROM tb_payroll_notif WHERE emp_id = '$emp_id' ORDER BY dateCreated DESC";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){


				if ($row->notifType == 'Already Sent' || $row->notifType == 'Already Computed'){

				// for getting payroll admin name
				$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$row->payroll_admin_id'";
				$result_emp = mysqli_query($connect,$select_emp_qry);
				$row_emp = mysqli_fetch_object($result_emp);

				$payrollAdminName = $row_emp->Firstname . " " . $row_emp->Lastname;

				// for getting payroll admin position
				$select_position_qry = "SELECT * FROM tb_position WHERE position_id = '$row_emp->position_id'";
				$result_position = mysqli_query($connect,$select_position_qry);
				$row_position = mysqli_fetch_object($result_position);

				$dateCreated = date_format(date_create($row->dateCreated), 'F d, Y');

				$time = date_format(date_create($row->dateCreated), 'g:i A');


				echo '<li id="payroll_notif" class="'.$row->notifType.'">
						<div id="">
							<div class="container-fluid">
								<div clas="sm-2">
									<img src="'.$row_emp->ProfilePath.'">
								</div>
								<div class="col-sm-10">
									 <b>Payroll Information</b> for the cut off <b>'.$row->cutOffPeriod.'</b> is '.$row->notifType.' by <b> '.$payrollAdminName.' - '.$row_position->Position.' </b> on
									<b>'.$dateCreated.'</b> at <b>'.$time.'</b>
								</div>
							</div>
						</div>
					</li>';
				} // end of if $row->notifType
				else if ($row->notifType == "File Salary Loan") {
					//echo "Wew";

					// for getting select_file_salary_loan information
					//echo $row->file_salary_loan_id;s
					$select_file_salary_loan = "SELECT * FROM tb_file_salary_loan WHERE file_salary_loan_id = '$row->file_salary_loan_id'";
					$result_file_salary_loan = mysqli_query($connect,$select_file_salary_loan);
					$row_file_salary_loan = mysqli_fetch_object($result_file_salary_loan);

					$file_emp_id = $row_file_salary_loan->emp_id;

					//echo $file_emp_id;

					// for getting payroll admin name
					$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$file_emp_id'";
					$result_emp = mysqli_query($connect,$select_emp_qry);
					$row_emp = mysqli_fetch_object($result_emp);

					$filer_name = $row_emp->Firstname . " " . $row_emp->Lastname;

					$dateCreated = date_format(date_create($row->dateCreated), 'F d, Y');

					$time = date_format(date_create($row->dateCreated), 'g:i A');

					echo '<li id="file_salary_loan_notif" class="'.$row->file_salary_loan_id.'">
						<div id="">
							<div class="container-fluid">
								<div clas="sm-2">
									<img src="'.$row_emp->ProfilePath.'">
								</div>
								<div class="col-sm-10">
									 <b>'.$filer_name.'</b> '.$row->notifType.' with the amount of <b>Php '.$this->getMoney($row_file_salary_loan->amountLoan).'</b> for <b>'.$row_file_salary_loan->totalMonths.' months, '.$row_file_salary_loan->deductionType.'</b> on
									<b>'.$dateCreated.'</b> at <b>'.$time.'</b>
								</div>
							</div>
						</div>
					</li>';
				} // end of else if 

				else if ($row->notifType == "Approve Your File Salary Loan" || $row->notifType == "Disapprove Your File Salary Loan") {

					$select_file_salary_loan = "SELECT * FROM tb_file_salary_loan WHERE file_salary_loan_id = '$row->file_salary_loan_id'";
					$result_file_salary_loan = mysqli_query($connect,$select_file_salary_loan);
					$row_file_salary_loan = mysqli_fetch_object($result_file_salary_loan);

					$approver_id = $row_file_salary_loan->approver_id;

					// for getting payroll admin name
					$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$approver_id'";
					$result_emp = mysqli_query($connect,$select_emp_qry);
					$row_emp = mysqli_fetch_object($result_emp);


					$approver_name = $row_emp->Firstname . " " . $row_emp->Lastname;

					$dateCreated = date_format(date_create($row->dateCreated), 'F d, Y');

					$time = date_format(date_create($row->dateCreated), 'g:i A');

					echo '<li id="approve_file_salary_loan" class="'.$row->file_salary_loan_id.'">
						<div id="">
							<div class="container-fluid">
								<div clas="sm-2">
									<img src="'.$row_emp->ProfilePath.'">
								</div>
								<div class="col-sm-10">
									 <b>'.$approver_name.'</b> '.$row->notifType.' with the amount of <b>Php '.$this->getMoney($row_file_salary_loan->amountLoan).'</b> for <b>'.$row_file_salary_loan->totalMonths.' months, '.$row_file_salary_loan->deductionType.'</b> on
									<b>'.$dateCreated.'</b> at <b>'.$time.'</b>
								</div>
							</div>
						</div>
					</li>';

				}


			}
		}

		

	}

	// for getting payroll admin id
	public function getPayrollAdminIdByApprovePayrollId($approve_payroll_id){
		$connect = $this->connect();

		$approve_payroll_id = mysqli_real_escape_string($connect,$approve_payroll_id);

		$select_qry = "SELECT * FROM tb_payroll_notif WHERE approve_payroll_id = '$approve_payroll_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);
		return $row->payroll_admin_id;
	}


	// for getting the last id in database
	public function lastID(){
		$connect = $this->connect();
		$select_last_id_qry = "SELECT * FROM tb_payroll_notif ORDER BY payroll_notif_id DESC LIMIT 1";
		$result = mysqli_query($connect,$select_last_id_qry);
		$row = mysqli_fetch_object($result);
		$last_id = $row->payroll_notif_id;
		return $last_id;
	}


	// for updating payroll notification
	public function updateRefNoLastPayrollNotif($ref_no){
		$connect = $this->connect();

		$payroll_notif_id = $this->lastID();
		$ref_no = mysqli_real_escape_string($connect,$ref_no);

		$update_qry = "UPDATE tb_payroll_notif SET ref_no = '$ref_no' WHERE payroll_notif_id = '$payroll_notif_id'";
		$sql = mysqli_query($connect,$update_qry);
	}


	public function getMoney($value){

        if ($value == 0) { // if 0       
            
            $final_value = "0.00";                   
        }


        else if ($value >= 1 && $value < 10) { // for 1 digit
          
            $decimal = "";

            $one = substr($value,0,1);

            if ($this->is_decima($value) == 1) {
                $decimal = substr($value,1);
                $final_value = $one . $decimal;
            }

            else {
                $final_value = $one . ".00";
            }

            
        }

        else if ($value >= 10 && $value < 100) { // for 2 digits 
          
            $decimal = "";
            $ten = substr($value,0,1);
            $one = substr(substr($value,1),0,1);

            if ($this->is_decima($value) == 1) {
                $decimal = substr($value,2);
                $final_value = $ten . $one . $decimal;
            }
            else {
                $final_value = $ten . $one . ".00";
            }

            
        }


        else if ($value >= 100 && $value < 1000) { // for 3 digits 
          
            $decimal = "";
            $hundred = substr($value,0,1);
            $ten = substr(substr($value,1),0,1);
            $one = substr(substr($value,2),0,1);

            if ($this->is_decima($value) == 1) {
                $decimal = substr($value,3);
                $final_value = $hundred . $ten . $one . $decimal;
            }

            else {
                 $final_value =  $hundred . $ten . $one . ".00";
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
                $final_value = $thousand . "," . $hundred . $ten . $one . $decimal;
            }

            else {
                $final_value = $thousand . "," . $hundred . $ten . $one . ".00";
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
                $final_value = $ten_thousand . "" . $thousand . "," . $hundred . $ten . $one . $decimal;
            }

            else {
                $final_value = $ten_thousand . "" . $thousand . "," . $hundred . $ten . $one . ".00";
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
                $final_value = $hundred_thousand. $ten_thousand . "" . $thousand . "," . $hundred . $ten . $one . $decimal;
            }

            else {
                $final_value =  $hundred_thousand. $ten_thousand . "" . $thousand . "," . $hundred . $ten . $one . ".00";
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