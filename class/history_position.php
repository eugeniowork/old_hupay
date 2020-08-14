<?php

class HistoryPosition extends Connect_db{


	// for inserting history of position
	public function insertHistoryPosition($emp_id,$dept_id,$position_id,$salary,$dateHired,$dateCreated){
		// DITO AKO TUMIGIL SA PAG INSERT NG DATE HIRED KAPAG CLICK HERE SA COMPANY INFORMATION
		$connect = $this->connect();

		$emp_id = mysqli_real_escape_string($connect,$emp_id);
		$dept_id = mysqli_real_escape_string($connect,$dept_id);
		$position_id = mysqli_real_escape_string($connect,$position_id);
		$salary = mysqli_real_escape_string($connect,$salary);
		$dateHired = mysqli_real_escape_string($connect,$dateHired);
		$dateCreated = mysqli_real_escape_string($connect,$dateCreated);

		$insert_qry = "INSERT INTO tb_history_position (history_position_id,emp_id,dept_id,position_id,Salary,DateHired,DateCreated) 
								VALUES ('','$emp_id','$dept_id','$position_id','$salary','$dateHired','$dateCreated')";
		$sql = mysqli_query($connect,$insert_qry);
	}



	// for viewing of employment history
	public function getHistoryEmploymentToTable($emp_id){
		$connect = $this->connect();
		$emp_id = mysqli_real_escape_string($connect,$emp_id);

		$select_qry = "SELECT * FROM tb_history_position WHERE emp_id = '$emp_id'";
		if ($result = mysqli_query($connect,$select_qry)){
			while ($row = mysqli_fetch_object($result)){

				// for getting its dept value by dept_id
				$select_dept_qry = "SELECT * FROM tb_department WHERE dept_id = '$row->dept_id'";
				$result_dept = mysqli_query($connect,$select_dept_qry);
				$row_dept = mysqli_fetch_object($result_dept);
				$department = $row_dept->Department;

				// for getting its position value by position_id
				$select_position_qry = "SELECT * FROM tb_position WHERE position_id = '$row->position_id'";
				$result_position = mysqli_query($connect,$select_position_qry);
				$row_position = mysqli_fetch_object($result_position);
				$position = $row_position->Position;

				$date_create = date_create($row->DateHired);
				$date_format = date_format($date_create, 'F d, Y');


				echo "<tr style='text-align:center;'>";
					echo "<td>" . $department . "</td>";
					echo "<td>" . $position . "</td>";
					echo "<td>Php " . $this->getMoney($row->Salary) . "</td>";
					echo "<td>" . $date_format . "</td>";
				echo "</tr>";
			}
		}
	}


    // for selecting the first registered 
    public function getLatestEmpHistory($emp_id){
        $connect = $this->connect();
        $emp_id = mysqli_real_escape_string($connect,$emp_id);
        $select_last_id_qry = "SELECT * FROM tb_history_position WHERE emp_id = '$emp_id' ORDER BY emp_id DESC LIMIT 1";
        $result = mysqli_query($connect,$select_last_id_qry);
        $row = mysqli_fetch_object($result);
        return $row;
    }


    // for updating history position
    public function updateEmpHistory($history_position_id,$dept_id,$position_id,$salary,$dateHired){
        $connect = $this->connect();
        
        $history_position_id = mysqli_real_escape_string($connect,$history_position_id);
        $dept_id = mysqli_real_escape_string($connect,$dept_id);
        $position_id = mysqli_real_escape_string($connect,$position_id);
        $salary = mysqli_real_escape_string($connect,$salary);
        $dateHired = mysqli_real_escape_string($connect,$dateHired);

        $update_qry = "UPDATE tb_history_position SET dept_id = '$dept_id', position_id = '$position_id', Salary = '$salary', DateHired = '$dateHired'
                        WHERE history_position_id = '$history_position_id'";
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