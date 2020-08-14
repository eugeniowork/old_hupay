<?php
class YearTotalDeduction extends Connect_db{


	public function getInfoByYtdId($ytd_id){
		$connect = $this->connect();
		$ytd_id = mysqli_real_escape_string($connect,$ytd_id);

		$select_qry = "SELECT * FROM tb_year_total_deduction WHERE ytd_id = '$ytd_id'";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		return $row;
	}

	public function insertYTD($emp_id,$ytdGross,$ytdAllowance,$ytdTax,$Year,$dateCreated){
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$ytdGross = mysqli_real_escape_string($connect,$ytdGross);
		$ytdAllowance = mysqli_real_escape_string($connect,$ytdAllowance);
		$ytdTax = mysqli_real_escape_string($connect,$ytdTax);
		$Year = mysqli_real_escape_string($connect,$Year);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$insert_qry = "INSERT INTO tb_year_total_deduction (ytd_id,emp_id,ytd_Gross,ytd_Allowance,ytd_Tax,Year,DateCreated)
								VALUES ('','$emp_id','$ytdGross','$ytdAllowance','$ytdTax','$Year','$dateCreated')";
		$sql = mysqli_query($connect,$insert_qry);

	}

	public function getYTDInfoToTable(){
		$connect = $this->connect();

		$year = date("Y");

		$select_qry = "SELECT * FROM tb_year_total_deduction WHERE Year='$year'";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){

				$emp_id = $row->emp_id;
				$select_emp_qry = "SELECT * FROM tb_employee_info WHERE emp_id = '$emp_id'";
				$result_emp = mysqli_query($connect,$select_emp_qry);
				$row_emp = mysqli_fetch_object($result_emp);

				// values from db
				if ($row_emp->Middlename == ""){
					$full_name = $row_emp->Lastname . ", " . $row_emp->Firstname;
				}
				else {
					$full_name = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;
				}

				echo "<tr id='".$row->ytd_id."'>";
					echo "<td>".$full_name."</td>";
					echo "<td>Php ".$this->getMoney($row->ytd_Gross)."</td>";
					echo "<td>Php ".$this->getMoney($row->ytd_Allowance)."</td>";
					echo "<td>Php ".$this->getMoney($row->ytd_Tax)."</td>";
					echo "<td><center>";
						echo "<span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span> <a href='#' id='edit_ytd' class='action-a'>Edit</a>";
					echo "</center></td>";
				echo "</tr>";
			}
		}



	}


	// check if exist ytd_id
	public function checkExistYtd($ytd_id){
		$connect = $this->connect();
		$ytd_id = mysqli_real_escape_string($connect,$ytd_id);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_year_total_deduction WHERE ytd_id = '$ytd_id'"));
		return $num_rows;
	}



	// if no changes was made
	public function sameYtdInfo($ytd_id,$ytdGross,$ytdAllowance,$ytdTax){
		$connect = $this->connect();

		$ytd_id = mysqli_real_escape_string($connect,$ytd_id);
		$ytdGross = mysqli_real_escape_string($connect,$ytdGross);
		$ytdAllowance = mysqli_real_escape_string($connect,$ytdAllowance);
		$ytdTax = mysqli_real_escape_string($connect,$ytdTax);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_year_total_deduction WHERE ytd_Gross = '$ytdGross' AND ytd_Allowance = '$ytdAllowance' 
														AND ytd_Tax = '$ytdTax' AND ytd_id = '$ytd_id'"));
		return $num_rows;

	}


	// for update
	public function updateYtd($ytd_id,$ytdGross,$ytdAllowance,$ytdTax){
		$connect = $this->connect();

		$ytd_id = mysqli_real_escape_string($connect,$ytd_id);
		$ytdGross = mysqli_real_escape_string($connect,$ytdGross);
		$ytdAllowance = mysqli_real_escape_string($connect,$ytdAllowance);
		$ytdTax = mysqli_real_escape_string($connect,$ytdTax);

		$update_qry = "UPDATE tb_year_total_deduction SET ytd_Gross = '$ytdGross', ytd_Allowance = '$ytdAllowance', ytd_Tax = '$ytdTax' WHERE ytd_id = '$ytd_id'";
		$sql = mysqli_query($connect,$update_qry);



	}


    // for getting info by emp id
    public function getInfoByEmpId($emp_id){
        $connect = $this->connect();
        $emp_id = mysqli_real_escape_string($connect,$emp_id);

        $select_qry = "SELECT * FROM tb_year_total_deduction WHERE emp_id = '$emp_id'";
        $result = mysqli_query($connect,$select_qry);
        $row = mysqli_fetch_object($result);

        return $row;
    }



    // for adding to year total deduction
    public function addYTDcurrentYear($cutOffPeriod){
        $connect = $this->connect();

        $cutOffPeriod = mysqli_real_escape_string($connect,$cutOffPeriod);

      //  $year = date("Y");

       // $exist_year = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_year_total_deduction WHERE Year = '$year'"));

        $dateCreated = date("Y-m-d");

       /* // ibig sabihin exist
        if ($exist_year != 0){
             $select_qry = "SELECT * FROM tb_year_total_deduction WHERE Year = '$year'";
             if ($result = mysqli_query($connect,$select_qry)){
                while ($row = mysqli_fetch_object($result)){


                    // kasi wla siyang payroll ka pag ininactive na siya
                    $num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_payroll_info WHERE CutOffPeriod = '$cutOffPeriod' AND emp_id='$row->emp_id'"));

                    if ($num_rows != 0){
                        $select_payroll_qry = "SELECT * FROM tb_payroll_info WHERE CutOffPeriod = '$cutOffPeriod' AND emp_id='$row->emp_id'";
                        $result_payroll = mysqli_query($connect,$select_payroll_qry);
                        $row_payroll = mysqli_fetch_object($result_payroll);

                        $ytd_Gross = $row_payroll->ytdGross;
                        $ytd_Allowance = $row_payroll->ytdAllowance;
                        $ytd_Tax = $row_payroll->ytdWithTax;


                        //$ytd_Gross = $row->ytd_Gross + $row->deduction;

                        // so update qry for new remaining balance
                        $update_qry = "UPDATE tb_year_total_deduction SET ytd_Gross = '$ytd_Gross', ytd_Allowance = '$ytd_Allowance', ytd_Tax = '$ytd_Tax' WHERE emp_id = '$row->emp_id'";
                        $sql = mysqli_query($connect,$update_qry);
                    }

                }
            }
        }

        // ibig sabihin hindi pa exist
        else {
             $num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_payroll_info WHERE CutOffPeriod = '$cutOffPeriod'"));

            if ($num_rows != 0){
                $select_payroll_qry = "SELECT * FROM tb_payroll_info WHERE CutOffPeriod = '$cutOffPeriod'";
                if ($result_payroll = mysqli_query($connect,$select_payroll_qry)){
                    while($row_payroll = mysqli_fetch_object($result_payroll)){
                        $ytd_Gross = $row_payroll->ytdGross;
                        $ytd_Allowance = $row_payroll->ytdAllowance;
                        $ytd_Tax = $row_payroll->ytdWithTax;
                        $emp_id = $row_payroll->emp_id;


                        //$ytd_Gross = $row->ytd_Gross + $row->deduction;
                        $dates = date("Y-m-d H:i:s");
                        $date = date_create($dates);
                        //date_sub($date, date_interval_create_from_date_string('15 hours'));

                        // $current_date_time = date_format($date, 'Y-m-d H:i:s');
                      

                        // so update qry for new remaining balance
                        $insert_qry = "INSERT INTO tb_year_total_deduction (ytd_id,emp_id,ytd_Gross,ytd_Allowance,ytd_Tax,Year,DateCreated) VALUES ('','$emp_id','$ytd_Gross','$ytd_Allowance','$ytd_Tax','$year','$dateCreated')";
                        $sql = mysqli_query($connect,$insert_qry);
                    }
                }
               
            }   
        }*/


        $select_qry = "SELECT * FROM tb_payroll_info WHERE CutOffPeriod = '$cutOffPeriod'";
        if ($result = mysqli_query($connect,$select_qry)){
            while($row = mysqli_fetch_object($result)){

                 $year = date_format(date_create($row->DateCreated), 'Y');

                 $exist_year = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_year_total_deduction WHERE Year = '$year' AND emp_id = '$row->emp_id'"));


                 // if exist update
                 if ($exist_year != 0){
                        $select_ytd_qry = "SELECT * FROM tb_year_total_deduction WHERE Year = '$year' AND emp_id = '$row->emp_id'";
                        $result_ytd = mysqli_query($connect,$select_ytd_qry);
                        $row_ytd = mysqli_fetch_object($result_ytd);

                        $ytd_Gross = $row_ytd->ytd_Gross;// + $row->ytdGross;
                        $ytd_Allowance = $row_ytd->ytd_Allowance;// + $row->ytdAllowance;
                        $ytd_Tax = $row_ytd->ytd_Tax;// + $row->ytdWithTax;


                        //$ytd_Gross = $row->ytd_Gross + $row->deduction;

                        // so update qry for new remaining balance
                        $update_qry = "UPDATE tb_year_total_deduction SET ytd_Gross = '$ytd_Gross', ytd_Allowance = '$ytd_Allowance', ytd_Tax = '$ytd_Tax' WHERE emp_id = '$row->emp_id' AND year = '$year'";
                        $sql = mysqli_query($connect,$update_qry);
                 }

                 // if not exist add
                 else {
                      // so update qry for new remaining balance
                        $insert_qry = "INSERT INTO tb_year_total_deduction (ytd_id,emp_id,ytd_Gross,ytd_Allowance,ytd_Tax,Year,DateCreated) VALUES ('','$row->emp_id','$row->ytdGross','$row->ytdAllowance','$row->ytdWithTax','$year','$dateCreated')";
                        $sql = mysqli_query($connect,$insert_qry);
                 }

            }
        }



    }


	public function getMoney($value){

        if ($value < 0){
            $final_value = $value;
        }

        else if ($value == 0) { // if 0       
            
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