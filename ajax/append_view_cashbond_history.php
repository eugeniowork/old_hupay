<?php
include "../class/connect.php";
include "../class/date.php";
include "../class/emp_information.php";
include "../class/cashbond_class.php";

if (isset($_POST["cashbond_id"])) {
	$cashbond_id = $_POST["cashbond_id"];

	$emp_info_class = new EmployeeInformation;
	$cashbond_class = new Cashbond;

	if ($cashbond_class->checkExistCashBond($cashbond_id) == 0){
		echo "Error";
	}

	else {
	
		$row = $cashbond_class->getInfoByCashbondId($cashbond_id);

		$emp_id = $row->emp_id;

		$date_class = new date;
		
		$current_date = $date_class->dateFormat($date_class->getDate());

		$row_emp = $emp_info_class->getEmpInfoByRow($emp_id);

		$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname . " " . $row_emp->Middlename;
		if ($row_emp->Middlename == ""){
			$fullName = $row_emp->Lastname . ", " . $row_emp->Firstname;
		}

		
		//$row = $


?>
	<div class="container-fluid">
		<form class="form-horizontal">
			<div class="form-group">
				<div class="col-md-6">
					<label><span class="glyphicon glyphicon-calendar" style="color:#186a3b"></span> <b>Date:</b></label>
					<?php
						//$current_date
					?>
					<input type="text" class="form-control" id="input_payroll" value="<?php echo $current_date; ?>"/>
				</div>
				<div class="col-md-6">
					<label><span class="glyphicon glyphicon-user" style="color:#186a3b"></span> <b>Employee Name:</b></label>
					<input type="text" class="form-control" id="input_payroll" value="<?php echo $fullName; ?>" />
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-6">
					<label><span class="glyphicon glyphicon-equalizer" style="color:#186a3b"></span> <b>Interest Rate:</b></label>					
					<?php
						$percentage = "5%";
						//echo ;
						if (str_replace(",","",str_replace("Php","",$cashbond_class->getTotalEndingBalance($emp_id))) >= 30000){
							$percentage = "7%";
						}
					?>
					<input type="text" class="form-control" id="input_payroll" value="<?php echo $percentage; ?>"/>
				</div>
				<div class="col-md-6">
					<label><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> <b>Total Credits:</b></label>
					<input type="text" class="form-control" id="input_payroll" value="<?php echo $cashbond_class->getTotalEndingBalance($emp_id); ?>"/>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-6">
					<label><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> <b>Total Debits:</b></label>
					<input type="text" class="form-control" id="input_payroll" value="<?php echo $cashbond_class->getTotalDebitsCashbondHistory($emp_id); ?>"/>
				</div>
				<div class="col-md-6">
					<label><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> <b>Total Interest Earned:</b></label>
					<input type="text" class="form-control" id="input_payroll" value="<?php echo $cashbond_class->getTotalInterestEarnedCashbondHistory($emp_id); ?>"/>
				</div>
			</div>
		</form>

		<table class="table table-bordered table-hover table-striped">
			<thead>
				<tr>
					<th width="20%"><small><span class="glyphicon glyphicon-calendar" style="color:#186a3b"></span> <b>Posting Date</b></small></th>
					<th width="20%"><small><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> <b>Deposit</b></small></th>
					<th width="20%"><small><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> <b>Interest</b></small></th>
					<th width="20%"><small><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> <b>Withdrawal</b></small></th>
					<th width="20%"><small><span class="glyphicon glyphicon-ruble" style="color:#186a3b"></span> <b>Balance</b></small></th>
				</tr>
			</thead>
			<tbody>
				<?php
					$cashbond_class->getCashbondHistoryByEmpIdToTable($emp_id);
				?>
			</tbody>
		</table>

		<div style="cursor:pointer;color:#158cba" class="pull-right" id="print_emp_cashbond_history"><u><b><span class="glyphicon glyphicon-print" style=""></span> Print Cashbond History</b></u></div>
	</div>

	<script>
		$(document).ready(function(){
			$("input[id='input_payroll']").keydown(function (e) {
		      //  return false;
		        if(e.keyCode != 116) {
		            return false;
		        }
		      });

		        // onpaste
		     $("input[id='input_payroll").on("paste", function(){
		          return false;
		     });

		     $("div[id='print_emp_cashbond_history']").on("click",function(){
			      window.location = "print_emp_cashbond_history_report.php?emp_id="+<?php echo $emp_id ?>;
			   });


		     var global_id = 0;
		     var global_ref_no = "";
		     $("span[id='div_cashbond_ref_no']").on("click","button[id='edit_ref_no']",function(){
		     	var id = $(this).closest("tr").attr("id");
		     	global_id = id;
		     	//alert(id);
		     	var ref_no = $(this).closest("span").find("small").html();
		     	//alert(rf_no);
		     	global_ref_no = ref_no;

		     	if (ref_no == "No Ref No."){
		     		ref_no = "";
		     	}

		     	var html = "";
		     	html += "<input type='text' name='ref_no' value='"+ref_no+"' size='8'/>";
		     	//html += "<button type='button' class='btn btn-success btn-xs pull-right'><span class='glyphicon glyphicon-edit'></span></button>"
		     	$(this).closest("tr").find("span[id='div_cashbond_ref_no']").html(html);
		     	//alert(html);
		     	$("input[name='ref_no']").focus();

		     	// d na sinilect
			    

		     });


		     $("span[id='div_cashbond_ref_no']").focusout("input[name='ref_no']",function(){
			  	//alert($(this).val() + ' wew');

			  	var html = "";
			  	html += "<small style='background-color: #3498db;color:#fff'>";

			  		html += global_ref_no;
			  	html += "</small>";

			  	html += "<button id='edit_ref_no' class='btn btn-update btn-xs pull-right'><span class='glyphicon glyphicon-edit'></span></button>";

			  	$(this).closest("tr").find("span[id='div_cashbond_ref_no']").html(html);

			});





		     $("span[id='div_cashbond_ref_no']").keydown("input[name='ref_no']",function (e) {
		     	//alert($(this).val());

		     	//alert(e.keyCode);

		     	if (e.keyCode == 13){
		     		//alert("READY FOR LOGIC");
		     		var id = global_id;
		     		var ref_no = $("input[name='ref_no']").val();

		     		var datastring = "id="+id+"&ref_no="+ref_no;
		     		//\
		     		//alert(datastring);
		     		$.ajax({
			            type: "POST",
			            url: "ajax/append_update_cashbond_withdrawal_ref_no.php",
			            data: datastring,
			            cache: false,
			            success: function (data) {
			               //alert(data);
			               if (data == ""){
			               		var html = "";
							  	html += "<small style='background-color: #3498db;color:#fff'>";

							  		html += "No Ref No.";
							  	html += "</small>";

							  	html += "<button id='edit_ref_no' class='btn btn-update btn-xs pull-right'><span class='glyphicon glyphicon-edit'></span></button>";

							  	$("tr[id='"+id+"']").find("span[id='div_cashbond_ref_no']").html(html);
			               }

			               else {
			               		var html = "";
							  	html += "<small style='background-color: #3498db;color:#fff'>";

							  		html += data;
							  	html += "</small>";

							  	html += "<button id='edit_ref_no' class='btn btn-update btn-xs pull-right'><span class='glyphicon glyphicon-edit'></span></button>";

							  	$("tr[id='"+id+"']").find("span[id='div_cashbond_ref_no']").html(html);
			               }
			            }
			          });
		     	}
		     });


		     

	     });
	</script>
<?php
	} // end of else
} // end of if
else {
	header("Location:../MainForm.php");
}

?>