<?php

class MinimumWage extends Connect_db{


	// for getting minimum information 
	public function getMinWageEffectiveDate(){
		$connect = $this->connect();
		$select_qry = "SELECT * FROM tb_minimum_wage ORDER BY effectiveDate DESC LIMIT 1";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		$date_create = date_create($row->effectiveDate);
		$date_format = date_format($date_create, 'F d, Y');

		return $date_format;
	}


	// for getting minimum information 
	public function getLatestMinWageInfo(){
		$connect = $this->connect();
		$select_qry = "SELECT * FROM tb_minimum_wage ORDER BY effectiveDate DESC LIMIT 1";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		return $row;
	}


	// for insert minimum wage
	public function insertMinimumWage($basicWage,$cola,$effectiveDate,$currentDate){
		$connect = $this->connect();

		$basicWage = mysqli_real_escape_string($connect,$basicWage);
		$cola = mysqli_real_escape_string($connect,$cola);
		$effectiveDate = mysqli_real_escape_string($connect,$effectiveDate);
		$currentDate = mysqli_real_escape_string($connect,$currentDate);


		$insert_qry = "INSERT INTO tb_minimum_wage (min_wage_id,basicWage,COLA,effectiveDate,dateCreated) VALUES('','$basicWage','$cola','$effectiveDate','$currentDate')";
		$sql = mysqli_query($connect,$insert_qry);

	}



	// for outputing the latest minimum wage
	public function getLatestMinimumWage(){
		$connect = $this->connect();

		$select_qry = "SELECT * FROM tb_minimum_wage ORDER BY effectiveDate DESC LIMIT 1";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);


		if ($_SESSION["role"] == 1) {

			echo "<tr style='text-align:center;' id='edit_min_wage_tr'>
		        <td>".$this->getMoney($row->basicWage)."</td>
		        <td>".$this->getMoney($row->COLA)."</td>
		        <td>".$this->getMoney($row->basicWage + $row->COLA)."</td>
		        <td>
		        	<a href='#' id='edit_latest_min_wage' class='action-a' title='Edit Latest Minimum Wage'><span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span> Edit</a>
		        	|
		        	<a href='#' id='delete_latest_min_wage' class='action-a' title='Delete Latest Minimum Wage'><span class='glyphicon glyphicon-trash' style='color:#515a5a'></span> Delete</a>
		        </td>
	   		 </tr>";
   		 }

   		 else {
   		 	echo "<tr style='text-align:center;' id='edit_min_wage_tr'>
		        <td>".$this->getMoney($row->basicWage)."</td>
		        <td>".$this->getMoney($row->COLA)."</td>
		        <td>".$this->getMoney($row->basicWage + $row->COLA)."</td>
		        <td>No Action</td>
	   		 </tr>";
   		 }
	}


	// for outputing the latest minimum wage
	public function getLatestMinWageToUpdate(){
		$connect = $this->connect();

		$select_qry = "SELECT * FROM tb_minimum_wage ORDER BY effectiveDate DESC LIMIT 1";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		echo "<td><input id='float_only' type='text' name='updateBasicWage' class='form-control' value='".$row->basicWage."' style='height:30px;'/></td>
	        <td><input id='float_only' type='text' name='updateCola' class='form-control' value='".$row->COLA."' style='height:30px;'/></td>
	        <td><input id='float_only' type='text' class='form-control' name='updateCola' value='".($row->basicWage+$row->COLA)."' style='height:30px;'/></td>
	        <td><a href='#' id='edit_latest_min_wage' class='action-a' title='Edit Latest Minimum Wage'>Update</a></td>";
	}


	// for minimum Wage History
	public function getMinimumWageHistory(){
		$connect = $this->connect();

		$select_qry = "SELECT * FROM tb_minimum_wage ORDER BY effectiveDate DESC";
		if ($result = mysqli_query($connect,$select_qry)){
			while($row = mysqli_fetch_object($result)){
				$date_create = date_create($row->effectiveDate);
				$date_format = date_format($date_create, 'F d, Y');

			echo "<tr style='text-align:center;'>
				<td>".$date_format."</td>	
		        <td>".$this->getMoney($row->basicWage)."</td>
		        <td>".$this->getMoney($row->COLA)."</td>
		        <td>".$this->getMoney($row->basicWage + $row->COLA)."</td>  
		        <td><b>".$this->getMoney(($row->basicWage + $row->COLA)*26)."</b></td>      	      
	   		 </tr>";
			}
		}
	}




	// for checking if no changes was made in updating
	public function sameMinWageInfo($effectiveDate,$basicWage,$cola){
		$connect = $this->connect();

		$effectiveDate = mysqli_real_escape_string($connect,$effectiveDate);
		$basicWage = mysqli_real_escape_string($connect,$basicWage);
		$cola = mysqli_real_escape_string($connect,$cola);

		$select_qry = "SELECT * FROM tb_minimum_wage ORDER BY effectiveDate DESC LIMIT 1";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		$count = 0;
		if ($row->effectiveDate == $effectiveDate && $row->basicWage == $basicWage && $row->COLA == $cola){
			$count =  1;
		}
		return $count;
	}


	// for updating latest minimum wage
	public function updateLatestMinWage($effectiveDate,$basicWage,$cola){
		$connect = $this->connect();

		$effectiveDate = mysqli_real_escape_string($connect,$effectiveDate);
		$basicWage = mysqli_real_escape_string($connect,$basicWage);
		$cola = mysqli_real_escape_string($connect,$cola);

		$select_qry = "SELECT * FROM tb_minimum_wage ORDER BY effectiveDate DESC LIMIT 1";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		$min_wage_id = $row->min_wage_id;

		$update_qry = "UPDATE tb_minimum_wage SET effectiveDate = '$effectiveDate', basicWage = '$basicWage', COLA = '$cola' WHERE min_wage_id = '$min_wage_id'";
		$sql = mysqli_query($connect,$update_qry);


	}


	// delete minimum wage
	public function deleteLatestMinWage(){
		$connect = $this->connect();

		$select_qry = "SELECT * FROM tb_minimum_wage ORDER BY effectiveDate DESC LIMIT 1";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		$min_wage_id = $row->min_wage_id;

		$delete_qry = "DELETE FROM tb_minimum_wage WHERE min_wage_id = '$min_wage_id'";
		$sql = mysqli_query($connect,$delete_qry);
	}


	// for checking if has same info
	public function existSameInfo($effectiveDate,$basicWage,$cola){
		$connect = $this->connect();

		$effectiveDate = mysqli_real_escape_string($connect,$effectiveDate);
		$basicWage = mysqli_real_escape_string($connect,$basicWage);
		$cola = mysqli_real_escape_string($connect,$cola);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_minimum_wage WHERE effectiveDate = '$effectiveDate' AND basicWage='$basicWage' AND COLA = '$cola'"));
		return $num_rows;
	}

	// for checking if merong na tlgang min wave
	public function existMinWage(){
		$connect = $this->connect();

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_minimum_wage"));
		return $num_rows;
	}



	// for checking
	public function getMinimumWage(){
		$connect = $this->connect();

		$select_qry = "SELECT * FROM tb_minimum_wage ORDER BY effectiveDate DESC LIMIT 1";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		$minimumWage = ($row->basicWage + $row->COLA) * 26;

		return $minimumWage;
	} 


	// for getting the effectiveDAte of latest minimum wage
	public function getLatestEffectiveDate(){
		$connect = $this->connect();

		$select_qry = "SELECT * FROM tb_minimum_wage ORDER BY effectiveDate DESC LIMIT 1";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);

		return $row->effectiveDate;
	}


	// for checking if the latest minimum wage effectivity date is sakop ng current cut off
	public function checkMinWageEffectiveDateInCutOff($emp_id,$monthly_rate_with_allowance){
		$connect = $this->connect();


		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$monthly_rate_with_allowance = mysqli_real_escape_string($connect,$monthly_rate_with_allowance);

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
						$date_payroll = date_format(date_create($row_cutoff->datePayroll . ", " .$year),'Y-m-d');
					}


				}

			}


		$select_qry = "SELECT * FROM tb_minimum_wage ORDER BY effectiveDate DESC LIMIT 1";
		$result = mysqli_query($connect,$select_qry);
		$row = mysqli_fetch_object($result);


		$inCutOff = 0;
		if ($row->effectiveDate >= $final_date_from AND $row->effectiveDate <= $final_date_to){


			$monthly_min_wage = $this->getMinimumWage();

			if ($monthly_rate_with_allowance <= $monthly_min_wage) {
				$inCutOff = 1;
			}
			//echo $monthly_rate_with_allowance;	
		}
		return $inCutOff;

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