<?php
class BIR_Contribution extends Connect_db{



    // for gettinng the bir status
    public function getBIRStatus(){
        $connect = $this->connect();
        $select_qry = "SELECT * FROM tb_bir_status";
        if ($result = mysqli_query($connect,$select_qry)){
            while ($row = mysqli_fetch_object($result)){

               // $num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_bir_contrib_table WHERE Status = '$row->Status'"));

                // if already used
               // if ($num_rows == 0) {
                    echo "<option value='".$row->Status."'>";
                        echo $row->Status;
                    echo "</option>";
                //}
            }
        }

    }

    // for update status
     public function getUpdateBIRStatus($status){
        $connect = $this->connect();
        $select_qry = "SELECT * FROM tb_bir_status";

         $status = mysqli_real_escape_string($connect,$status);

        if ($result = mysqli_query($connect,$select_qry)){
            while ($row = mysqli_fetch_object($result)){

               // $num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_bir_contrib_table WHERE Status = '$row->Status'"));

                // if already used
               // if ($num_rows == 0) {
                $selected = "";
                if ($status == $row->Status) {
                    $selected = "selected=selected";
                }

                echo "<option ". $selected ." value='".$row->Status."'>";
                    echo $row->Status;
                echo "</option>";
                //}
            }
        }

    }

    // check what kind of status the taxable falls for Payroll
    public function getBIRStatusToPayroll($dependent_count){
        $connect = $this->connect();
        $dependent_count = mysqli_real_escape_string($connect,$dependent_count);
        $select_qry = "SELECT * FROM tb_bir_status WHERE Dependent = '$dependent_count'";
        $result = mysqli_query($connect,$select_qry);
        $row = mysqli_fetch_object($result);
        return $row;
    }


     // check what kind of status the taxable falls for Payroll
    public function getTax($status,$taxableIncome){
        $connect = $this->connect();

        $status = mysqli_real_escape_string($connect,$status);
        $taxableIncome = mysqli_real_escape_string($connect,$taxableIncome);

        $amount = array();
        $amount_count = 0;





        $select_qry = "SELECT * FROM tb_bir_contrib_table WHERE Status = '$status' ORDER BY amount ASC LIMIT 1";
        $result = mysqli_query($connect,$select_qry);
        $row = mysqli_fetch_object($result);
               

         if ($taxableIncome <= $row->amount) {
                $percentage = $row->percentage;
                $tax = $row->Contribution + (($taxableIncome) *($percentage/100));              
         }

         else {

             $select_new_qry = "SELECT * FROM tb_bir_contrib_table WHERE Status = '$status' ORDER BY amount ASC LIMIT 1";
             if ($result_new = mysqli_query($connect,$select_new_qry)){
                while ($row_new = mysqli_fetch_object($result_new)){
                     if ($taxableIncome >= $row_new->amount) {
                          $amount[] = $row_new->amount;
                          $amount_count++;
                       // $percentage = $row->percentage;
                      //  $tax = $row->Contribution; //+ (($taxableIncome - $row->amount) *($percentage/100));
                    }

                }
            }


              $counter = 0;
                $final_amount = 0;
                do {
                    if ($final_amount == 0){
                        $final_amount = $amount[$counter];
                    }
                    else {
                        if ($final_amount > $amount[$counter]){
                             $final_amount = $amount[$counter];
                        }
                    }

                    $counter++;
                }while($counter < $amount_count);


                 // for new query
                 $select_qry = "SELECT * FROM tb_bir_contrib_table WHERE Status = '$status' AND amount = '$final_amount'";
                 $result = mysqli_query($connect,$select_qry);
                 $row = mysqli_fetch_object($result);

                 $percentage = $row->percentage;
                 $tax = $row->Contribution + (($taxableIncome) *($percentage/100));  


            
        }
                        
           

       // echo $amount[0];

      



        // DITO AKO IMISTOP LOGIC FOR GETTING THE CONTRIBUTION
        return $tax;
       //  return $final_amount;*/
        
    }


    public function existBIRStatus($status){
        $connect = $this->connect();
        $status = mysqli_real_escape_string($connect,$status);
        $num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_bir_status WHERE Status = '$status'"));
        return $num_rows;
    }




    // get info by contribution id
    public function getInfoByContribId($bir_contrib_id){
        $connect = $this->connect();

        $bir_contrib_id = mysqli_real_escape_string($connect,$bir_contrib_id);

        $select_qry = "SELECT * FROM tb_bir_contrib_table WHERE bir_contrib_id = '$bir_contrib_id'";
        $result = mysqli_query($connect,$select_qry);
        $row = mysqli_fetch_object($result);

        return $row;

    }


	// for adding SSS Contribution
	public function insertBIRcontribution($status,$amount,$contribution,$percentage,$dateCreated){
		$connect = $this->connect();

		$amount = mysqli_real_escape_string($connect,$amount);
		$contribution = mysqli_real_escape_string($connect,$contribution);
		$percentage = mysqli_real_escape_string($connect,$percentage);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$insert_qry = "INSERT INTO tb_bir_contrib_table (bir_contrib_id,Status,amount,Contribution,percentage,DateCreated) VALUES ('','$status','$amount','$contribution','$percentage','$dateCreated')";
		$sql = mysqli_query($connect,$insert_qry);
	}


	// checking if exist
	public function existContribution($status,$amount,$contribution,$percentage){
		$connect = $this->connect();

		$status = mysqli_real_escape_string($connect,$status);
		$amount = mysqli_real_escape_string($connect,$amount);
		$contribution = mysqli_real_escape_string($connect,$contribution);
        $percentage = mysqli_real_escape_string($connect,$percentage);

		$num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_bir_contrib_table WHERE Status = '$status' AND amount = '$amount' AND contribution = '$contribution' AND percentage = '$percentage'"));
		return $num_rows;
	}




	// for showing all data
	public function getContributionToTable(){
		$connect = $this->connect();
		$select_qry = "SELECT * FROM tb_bir_contrib_table";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){


                 // for compensation from if 0
                /*if ($this->getMoney($row->compensationFrom) == "over") {
                    $compensationFrom = "Php 0.00";
                }
                else {
                    $compensationFrom = $this->getMoney($row->compensationFrom);
                }*/
                
				echo "<tr id='".$row->bir_contrib_id."'>";
                    echo "<td>" . $row->Status ."</td>";
					echo "<td>" . $this->getMoney($row->amount) ."</td>";
					echo "<td>" .$this->getMoney($row->Contribution)."</td>";
                    echo "<td>" .$row->percentage."%</td>";
					echo "<td>";
                             if ($_SESSION["role"] == 1){
    							echo "<span class='glyphicon glyphicon-pencil' style='color:#b7950b'></span> <a href='#' id='edit_bir_contrib' class='action-a'>Edit</a>";
    							echo "<span> | </span>";
    							echo "<span class='glyphicon glyphicon-trash' style='color:#515a5a'></span> <a href='#' id='delete_bir_contrib' class='action-a'>Delete</a>";
					         }
                             else {
                                echo "No action";
                             }
                    echo "</td>";
				echo "</tr>";
			}
		}
	}



    // for checking if exist sss contribution ID
   public function existContributionId($bir_contrib_id){
        $connect = $this->connect();

        $bir_contrib_id = mysqli_real_escape_string($connect,$bir_contrib_id);

        $num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_bir_contrib_table WHERE bir_contrib_id = '$bir_contrib_id'"));
        return $num_rows;
    }


     // for checking if exist sss contribution ID
   public function sameContributionInfo($bir_contrib_id,$status,$amount,$contribution,$percentage){
        $connect = $this->connect();

        $bir_contrib_id = mysqli_real_escape_string($connect,$bir_contrib_id);
        $status = mysqli_real_escape_string($connect,$status);
        $amount = mysqli_real_escape_string($connect,$amount);
        $contribution = mysqli_real_escape_string($connect,$contribution);
        $percentage = mysqli_real_escape_string($connect,$percentage);

        $num_rows = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM tb_bir_contrib_table WHERE Status = '$status' AND amount = '$amount' AND Contribution = '$contribution' AND percentage='$percentage' AND bir_contrib_id = '$bir_contrib_id'"));
        return $num_rows;
    }


    // for update contribution info
    public function updateContributionInfo($bir_contrib_id,$status,$amount,$contribution,$percentage){
        $connect = $this->connect();

        $bir_contrib_id = mysqli_real_escape_string($connect,$bir_contrib_id);
        $status = mysqli_real_escape_string($connect,$status);
        $amount = mysqli_real_escape_string($connect,$amount);
        $contribution = mysqli_real_escape_string($connect,$contribution);
        $percentage = mysqli_real_escape_string($connect,$percentage);

        $update_qry = "UPDATE tb_bir_contrib_table SET Status = '$status', amount='$amount', Contribution = '$contribution', percentage = '$percentage' WHERE bir_contrib_id='$bir_contrib_id'";
        $sql = mysqli_query($connect,$update_qry);
    }


    // delete contribution info
    public function deleteContributionInfo($bir_contrib_id){
        $connect = $this->connect();

        $bir_contrib_id = mysqli_real_escape_string($connect,$bir_contrib_id);
        $delete_qry = "DELETE FROM tb_bir_contrib_table WHERE bir_contrib_id='$bir_contrib_id'";
        $sql = mysqli_query($connect,$delete_qry);

    }




    



	// for money output with comma
	public function getMoney($value){

        if ($value == 0) { // if 0       
            
            $final_value = "Php " . $value;                   
       }


		if ($value >= 1 && $value < 10) { // for 1 digit
          
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