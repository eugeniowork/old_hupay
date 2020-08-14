<?php
class Pagibig_Contribution extends Connect_db{


    public function alreadyData(){
         $connect = $this->connect();
        $num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_pagibig_contrib_table"));
        return $num_rows;
    }


	// get info by contribution id
    public function getInfoByContribId($pagibig_contrib_id){
        $connect = $this->connect();

        $pagibig_contrib_id = mysqli_real_escape_string($connect,$pagibig_contrib_id);

        $select_qry = "SELECT * FROM tb_pagibig_contrib_table WHERE pagibig_contrib_id = '$pagibig_contrib_id'";
        $result = mysqli_query($connect,$select_qry);
        $row = mysqli_fetch_object($result);

        return $row;

    }


	// for adding SSS Contribution
	public function insertPagibigcontribution($compensationFrom,$compensationTo,$contribution,$dateCreated){
		$connect = $this->connect();

		$compensationFrom = mysqli_real_escape_string($connect,$compensationFrom);
		$compensationTo = mysqli_real_escape_string($connect,$compensationTo);
		$contribution = mysqli_real_escape_string($connect,$contribution);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$insert_qry = "INSERT INTO tb_pagibig_contrib_table (pagibig_contrib_id,compensationFrom,compensationTo,Contribution,DateCreated) VALUES ('','$compensationFrom','$compensationTo','$contribution','$dateCreated')";
		$sql = mysqli_query($connect,$insert_qry);
	}


	// for exist information
	public function existContribution($compensationFrom,$compensationTo,$contribution){
		$connect = $this->connect();

		$compensationFrom = mysqli_real_escape_string($connect,$compensationFrom);
		$compensationTo = mysqli_real_escape_string($connect,$compensationTo);
		$contribution = mysqli_real_escape_string($connect,$contribution);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_pagibig_contrib_table WHERE compensationFrom = '$compensationFrom' AND compensationTo = '$compensationTo' AND contribution = '$contribution'"));
		return $num_rows;
	}


	// for showing all data
	public function getContributionToTable(){
		$connect = $this->connect();
		$select_qry = "SELECT * FROM tb_pagibig_contrib_table ORDER BY Contribution ASC";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){


                // for compensation from if 0
                if ($this->getMoney($row->compensationFrom) == "over") {
                    $compensationFrom = "Php 0.00";
                }
                else {
                    $compensationFrom = $this->getMoney($row->compensationFrom);
                }



				echo "<tr id='".$row->pagibig_contrib_id."'>";
					echo "<td>" .$compensationFrom. " - ". $this->getMoney($row->compensationTo) ."</td>";
					echo "<td>" .$this->getMoney($row->Contribution)."</td>";
					echo "<td>";
                            if ($_SESSION["role"] == 1){
    							echo "<span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span> <a href='#' id='edit_pagibig_contrib' class='action-a'>Edit</a>";
    							echo "<span> | </span>";
    							echo "<span class='glyphicon glyphicon-trash' style='color:#515a5a'></span> <a href='#' id='delete_pagibig_contrib' class='action-a'>Delete</a>";
					       }
                           else {
                                echo "No action";
                           }
                    echo "</td>";
				echo "</tr>";
			}
		}
	}


	// check the last contribution to
     public function checkLastContributionTo(){
        $connect = $this->connect();

        // check if my data na pra maavoid ung error kapag may over pra makainsert agad
      
        $select_qry = "SELECT * FROM tb_pagibig_contrib_table ORDER BY compensationTo ASC LIMIT 1";
        $result = mysqli_query($connect,$select_qry);
        $row = mysqli_fetch_object($result);
        return $row;
        
    }





     // check if there is a compensationTo has 0
    public function existOver(){
        $connect = $this->connect();
        $num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_pagibig_contrib_table WHERE compensationTo = '0'"));
        return $num_rows;
    }


     // for checking if exist sss contribution ID
   public function existContributionId($pagibig_contrib_id){
        $connect = $this->connect();

        $pagibig_contrib_id = mysqli_real_escape_string($connect,$pagibig_contrib_id);

        $num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_pagibig_contrib_table WHERE pagibig_contrib_id = '$pagibig_contrib_id'"));
        return $num_rows;
    }


      // for checking if exist sss contribution ID
   public function sameContributionInfo($pagibig_contrib_id,$compensationFrom,$compensationTo,$contribution){
        $connect = $this->connect();

        $pagibig_contrib_id = mysqli_real_escape_string($connect,$pagibig_contrib_id);
        $compensationFrom = mysqli_real_escape_string($connect,$compensationFrom);
        $compensationTo = mysqli_real_escape_string($connect,$compensationTo);
        $contribution = mysqli_real_escape_string($connect,$contribution);

        $num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_pagibig_contrib_table WHERE compensationFrom = '$compensationFrom' AND compensationTo = '$compensationTo' AND contribution = '$contribution' AND pagibig_contrib_id='$pagibig_contrib_id'"));
        return $num_rows;
    }


     // for update contribution info
    public function updateContributionInfo($pagibig_contrib_id,$compensationFrom,$compensationTo,$contribution){
        $connect = $this->connect();

        $pagibig_contrib_id = mysqli_real_escape_string($connect,$pagibig_contrib_id);
        $compensationFrom = mysqli_real_escape_string($connect,$compensationFrom);
        $compensationTo = mysqli_real_escape_string($connect,$compensationTo);
        $contribution = mysqli_real_escape_string($connect,$contribution);

        $update_qry = "UPDATE tb_pagibig_contrib_table SET compensationFrom = '$compensationFrom', compensationTo='$compensationTo', Contribution = '$contribution' WHERE pagibig_contrib_id='$pagibig_contrib_id'";
        $sql = mysqli_query($connect,$update_qry);
    }



     // delete contribution info
    public function deleteContributionInfo($pagibig_contrib_id){
        $connect = $this->connect();

        $pagibig_contrib_id = mysqli_real_escape_string($connect,$pagibig_contrib_id);
        $update_qry = "DELETE FROM tb_pagibig_contrib_table WHERE pagibig_contrib_id='$pagibig_contrib_id'";
        $sql = mysqli_query($connect,$update_qry);

    }


     public function getContribution($salary){
        $connect = $this->connect();

        $salary = mysqli_real_escape_string($connect,$salary);

        date_default_timezone_set("Asia/Manila");
        //$date = date_create("1/1/1990");

        $dates = date("Y-m-d H:i:s");
        $date = date_create($dates);
        //date_sub($date, date_interval_create_from_date_string('15 hours'));

        // $current_date_time = date_format($date, 'Y-m-d H:i:s');
        $current_date_time = date_format($date, 'Y-m-d');

        //echo $current_date_time;
        $year = date("Y");

        // for cutoff
        $select_cutoff_qry = "SELECT * FROM tb_cut_off";
        if ($result_cutoff = mysqli_query($connect,$select_cutoff_qry)){
            while($row_cutoff = mysqli_fetch_object($result_cutoff)){
                //$date_to = $row_cutoff->dateTo . ", " .$year;

                $date_from = date_format(date_create($row_cutoff->dateFrom . ", " .$year),'Y-m-d');
                if (date_format(date_create($row_cutoff->dateFrom),'m-d') == "12-26"){
                    //echo "wew";
                    $prev_year = $year - 1;
                    $date_from = $prev_year . "-" .date_format(date_create($row_cutoff->dateFrom),'m-d');
                    //echo $date_from . "sad";
                    //$date_from = date_format(date_create($row->dateFrom),'Y-m-d');

                }
                $date_from = date_format(date_create($date_from),"Y-m-d");
                $date_to = date_format(date_create($row_cutoff->dateTo. ", " .$year),'Y-m-d');
                //$to =  date("Y-m-d",strtotime($date_to) + (86400 *5));
                //echo $to . "<br/>";


                $minus_five_day = date("Y-m-d",strtotime($current_date_time) - (86400 *5));

                
                if ($minus_five_day >= $date_from && $minus_five_day <= $date_to) {
                    $final_date_from = $date_from;
                    $final_date_to = $date_to;
                    $date_payroll = date_format(date_create($row_cutoff->datePayroll . ", " .$year),'d');
                }


            }

        }

        $contribution = 0;

        if ($date_payroll == "15") { 
            $select_qry = "SELECT * FROM tb_pagibig_contrib_table";
            if ($result = mysqli_query($connect,$select_qry)){
                while($row = mysqli_fetch_object($result)){
                   

                    if ($row->compensationTo != 0) {

                        if ($salary >= $row->compensationFrom && $salary <= $row->compensationTo) {
                            $contribution = $row->Contribution;
                        }
                    }

                    else {
                        if ($salary >= $row->compensationFrom) {
                            $contribution = $row->Contribution;
                     }

                    }
                }
            }
        }

        return $contribution;
    }


	// for money output with comma
	public function getMoney($value){

        if ($value == 0) { // if 0       
            
            $final_value = "over";                   
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