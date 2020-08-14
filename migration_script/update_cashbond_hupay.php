<?php
$servername = 'localhost';
$username = 'root';
$password = '';
//$dbname = 'hupay_testing_final';
$dbname = 'live_db_hr_payroll';

$conn = mysqli_connect($servername,$username,$password,$dbname);



$select_qry = "SELECT * FROM tb_employee_info WHERE ActiveStatus = '1'";
if ($result = mysqli_query($conn,$select_qry)){
	while($row1 = mysqli_fetch_assoc($result)){

		$emp_id = $row1["emp_id"];


		$num_rows_d = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM tb_emp_cashbond_history WHERE emp_id = '$emp_id'"));


		
		if ($num_rows_d != 0){

			echo $emp_id . " ";
			echo $num_rows_d . "<br/>";
			$counter = 0;
			$cashbond_balance = 0;
			$prev_posting_date = "";

			$select_qry1 = "SELECT * FROM tb_emp_cashbond_history WHERE emp_id = '$emp_id' ORDER BY posting_date ASC";
			if ($result1 = mysqli_query($conn,$select_qry1)){
				while($row = mysqli_fetch_assoc($result1)){
					$emp_cashbond_history = $row["emp_cashbond_history"];

					
					

					if ($counter == 0){
						$cashbond_balance = $row["cashbond_balance"];
						$prev_posting_date = $row["posting_date"];
					}

					

					if ($counter > 0){
						$date1=date_create($prev_posting_date);
						$date2=date_create($row["posting_date"]);
						$diff=date_diff($date1,$date2);
						$days = $diff->format("%a");

						$interest_rate = .05;
						if ($row["posting_date"] > "2018-09-03" && $cashbond_balance >= 30000){
							$interest_rate	= .07;
						}

						echo $cashbond_balance . " ";

						$interest_posted = round(($days) * $cashbond_balance * ($interest_rate/360),2);
						$cashbond_balance += $row["cashbond_deposit"] + $interest_posted - $row["amount_withdraw"];


						// update interest
						$update_qry = "UPDATE tb_emp_cashbond_history SET interest = '$interest_posted', cashbond_balance = '$cashbond_balance' WHERE emp_cashbond_history = '$emp_cashbond_history'";

						echo $days . " " . $update_qry . "<br/>";


						$sql = mysqli_query($conn,$update_qry);

						$prev_posting_date = $row["posting_date"];
					}


					$counter++;
				}
			}	



			$update_qry = "UPDATE tb_cashbond SET totalCashbond = '$cashbond_balance' WHERE emp_id = '$emp_id'";
			$sql = mysqli_query($conn,$update_qry);

	
			
		}

		
	}
}

?>