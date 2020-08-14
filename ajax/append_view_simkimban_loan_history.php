<?php
include "../class/connect.php";
include "../class/simkimban_class.php";

if (isset($_POST["simkimban_id"])){
	$simkimban_id = $_POST["simkimban_id"];

	$simkimban_class = new Simkimban;

?>

	<div class="container-fluid">
		<small>
			<table id="emp_list_with_salary_loan" class="table table-bordered table-hover table-striped" style="border:1px solid #BDBDBD;">
				
				<thead>
					<tr style="background-color: #616a6b ">
						<th style="color:#fff"><small><span class="glyphicon glyphicon-calendar" ></span> Payroll Date</small></th> 	
						<th style="color:#fff"><small><span class="glyphicon glyphicon-ruble"></span> Deduction</small></th>
						<th style="color:#fff"><small><span class="glyphicon glyphicon-ruble"></span> Outstanding Balance</small></th>					
					</tr>
				</thead>
				<tbody>	
					<?php
						$simkimban_class->getSimkimbanLoanHistory($simkimban_id);
					?>
				</tbody>
			</table>
		</small>
	</div>
<?php
} // end of if
else {
	header("../MainForm.php");
} // end of else



?>