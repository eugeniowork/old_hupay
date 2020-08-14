	<?php
	include "class/connect.php";
	include "class/cashbond_class.php";

	if (isset($_FILES["file"]["name"])){
			$file_tmp_name = $_FILES["file"]["tmp_name"];
			$base_name = basename($_FILES["file"]["name"]);
			$file_type = pathinfo($base_name,PATHINFO_EXTENSION);
			//$file_name = "dtr_dat_files" . "." . $file_type;


			$cashbond_class = new Cashbond;
		

		    //echo "Stored in: " . $_FILES["file"]["tmp_name"];
			$a = $_FILES["file"]["tmp_name"];
			//echo $a;

			//your database name

			// path where your CSV file is located
			//define('CSV_PATH','C:/xampp/htdocs/');
			//<!-- C:\\xampp\\htdocs -->
			// Name of your CSV file
			$csv_file = $a;



			$counter = 0;
			$count = 0;
			$previous_date = "";
			$emp_id = "";
			$previous_ending_balance_amount = 0;
			if (($getfile = fopen($csv_file, "r")) !== FALSE) {
			         $data = fgetcsv($getfile, 1000, ",");
			   while (($data = fgetcsv($getfile, 1000, ",")) !== FALSE) {


			   		$result = $data;
			            
			            
		        	$str = implode("__!__", $result);
		        	$slice = explode("__!__", $str);


		        	$percentage = .03;
		        	$interest = 0;
		        	if ($count == 0){
		        		$interest = $slice[3];
		        		$previous_date = date_format(date_create($slice[4]),"Y-m-d");
		        		$previous_ending_balance_amount = $slice[6];

		        		$interest = $slice[3];

		        		$new_ending_balance =  $interest + $previous_ending_balance_amount;

		        		//echo $previous_ending_balance_amount;
		        	}

		        	else {


		        		$date1 = $previous_date;
		        		$date1= date_create($date1);

		        				
		        		$date2 = date_format(date_create($slice[4]),"Y-m-d");

						$date2= date_create($date2);

						$diff =date_diff($date1,$date2);
						$wew =  $diff->format("%R%a");
						$days = str_replace("+","",$wew);

						//$percentage = .03;

						if (date_format(date_create($previous_date),"Y-m-d") < date_format(date_create("2018-09-15"),"Y-m-d")){
							$percentage = .03;
						
						}

						else {

							$percentage = .05;
							if ($previous_ending_balance_amount >= 30000){
								$percentage = .07;
							}
						}


						if ($slice[5] == 0){

							//echo $days . " " . $previous_ending_balance_amount . " " . $percentage . "<br/>";
							$interest = round(($days) * $previous_ending_balance_amount * ($percentage/360),2);

							//$interest = round(($days) * $previous_ending_balance_amount * ,2);

							$new_ending_balance = $interest + $slice[1] + $previous_ending_balance_amount;

							
			        	}


			        	else {

			        		$interest = round(($days) * $previous_ending_balance_amount * ($percentage/360),2);

			        		$new_ending_balance = $previous_ending_balance_amount + $interest - $slice[5];

							//$previous_date = date_format(date_create($slice[4]),"Y-m-d");
			        		//$previous_ending_balance_amount = $new_ending_balance;
			        	}


			        	$previous_date = date_format(date_create($slice[4]),"Y-m-d");
		        		$previous_ending_balance_amount = $new_ending_balance;



		        	}

		        	
		        	echo $slice[0] . " " . $slice[1]. " " . "" . " " . $interest . " " . " " . $previous_date . " " . $slice[5] . " " . $percentage * 100 . " " . $new_ending_balance . "<br/>";

		        	++$count;
		        	//echo "wew";


		        	$cashbond_class->insertCashbondHistoryAfterWithdraw($slice[0],$slice[1]," ",$interest,$previous_date,$slice[5],$new_ending_balance,$percentage * 100,date("Y-m-d"));

		        	$emp_id = $slice[0];


		        	//echo $count . " " . $slice[0] . " " . $slice[1] . " " . $interest . " " . $previous_date.  "<br/>";


		        	// for inserting to history



		        	// for update latest cashbond

		   		}
		   	}

		   	$cashbond_class->updateCashbondBalance($emp_id,$new_ending_balance);
	   	}

	   	else {
	   		echo "Unread!";
	   	}

	?>