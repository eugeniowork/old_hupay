<?php
session_start();
include "../class/connect.php";
include "../class/holiday_class.php";
include "../class/month_day.php";


if (isset($_POST["holiday_id"])) {
	$holiday_id = $_POST["holiday_id"];

	$holiday_class = new Holiday;
	$month_day_class = new MonthDay;

	// if exist
	if ($holiday_class->existHolidayId($holiday_id) == 0) {
		echo "Error";	
	}
	// if success
	else {
		$row = $holiday_class->getHolidayInfoByRow($holiday_id);
		$_SESSION["update_holiday_info"] = $holiday_id;


		$holiday_date = explode(" ",$row->holiday_date);

?>
	<div class="container-fluid">
		<form class="form-horizontal" action="php script/update_holiday.php" method="post">
			<div class="form-group">
				<div class="col-sm-7">
					<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Month:&nbsp;<span class="red-asterisk">*</span></label>
				
					<!-- for month -->
					<select name="holidayDate_month" class="form-control" required="required">
						<option value=""></option>
						<option value="January" <?php if ($holiday_date[0] == "January") { echo "selected=selected";} ?> >January</option>
						<option value="February" <?php if ($holiday_date[0] == "February") { echo "selected=selected";} ?> >February</option>
						<option value="March" <?php if ($holiday_date[0] == "March") { echo "selected=selected";} ?> >March</option>
						<option value="April" <?php if ($holiday_date[0] == "April") { echo "selected=selected";} ?>>April</option>
						<option value="May" <?php if ($holiday_date[0] == "May") { echo "selected=selected";} ?>>May</option>
						<option value="June" <?php if ($holiday_date[0] == "June") { echo "selected=selected";} ?>>June</option>
						<option value="July" <?php if ($holiday_date[0] == "July") { echo "selected=selected";} ?>>July</option>
						<option value="August" <?php if ($holiday_date[0] == "August") { echo "selected=selected";} ?>>August</option>
						<option value="September" <?php if ($holiday_date[0] == "September") { echo "selected=selected";} ?>>September</option>
						<option value="October" <?php if ($holiday_date[0] == "October") { echo "selected=selected";} ?>>October</option>
						<option value="November" <?php if ($holiday_date[0] == "November") { echo "selected=selected";} ?>>November</option>
						<option value="December" <?php if ($holiday_date[0] == "December") { echo "selected=selected";} ?>>December</option>
					</select>
				</div>

				<div class="col-sm-5">
					<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Day:&nbsp;<span class="red-asterisk">*</span></label>

					<!-- for month -->
					<select name="holidayDate_day" class="form-control" required="required">
						<option value=""></option>
						<?php
							$month_day_class->getDayOfMonthUpdate($holiday_date[0],$holiday_date[1]);

						?>
					</select>
				</div>
			</div>	

			

			<div class="form-group">
				<div class="col-sm-12">
					<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Holiday Name:&nbsp;<span class="red-asterisk">*</span></label>
					<input type="text" class="form-control" id="department_txt" name="holidayName" value="<?php echo $row->holiday_value; ?>" placeholder="Input Holiday Name" required="required"/>
				</div>
			</div>	


			<div class="form-group">
				<div class="col-sm-12">
					<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Holiday Type:&nbsp;<span class="red-asterisk">*</span></label>
					<!-- for month -->
					<select name="holidayDate_type" class="form-control" required="required">
						<option value=""></option>
						<option value="Regular Holiday" <?php if ($row->holiday_type == "Regular Holiday") { echo "selected=selected";} ?> >Regular Holiday</option>
						<option value="Special non-working day" <?php if ($row->holiday_type == "Special non-working day") { echo "selected=selected";} ?> >Special non-working day</option>
						<!--<option value="Unexpected Holiday">Unexpected Holiday</option> -->
					</select>
				</div>
			</div>	


			<div class="form-group">
				<div class="col-sm-12">
					<div style="text-align:center;">
						<input type="submit" class="btn btn-success" value="Update Holiday"/>
					</div>
				</div>
			</div>	

			<div class="form-group">
				<div class="col-sm-12">
					<div id="message">

					</div>
				</div>
			</div>					
		</form>
	</div>


	<script>

	 $(document).ready(function(){
		 // for selecting month
	       $("select[name='holidayDate_month']").change(function(){
	         var datastring = "month="+$(this).val();
	         $("select[name='holidayDate_day']").html("<option value=''></option>");
	         $.ajax({
	              type: "POST",
	              url: "ajax/append_month_date.php",
	              data: datastring,
	              cache: false,
	             // datatype: "php",
	              success: function (data) {
	                // if has error
	                if (data == "Error") {
	                  $("#message").html("<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There's an error during getting of data</center>");
	                }
	                // if success
	                else {
	                   $("select[name='holidayDate_day']").html(data);
	                }
	              }
	          }); 


	         

	      });


	        // department_txt
			     $("input[id='department_txt").on("paste", function(){
			    
			          return false;
			     });



			       // for txt only
			    $(document).on('keypress', 'input[id="department_txt"]', function (event) {


			        var regex = new RegExp("^[<>/?]+$");
			        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);

			        if (regex.test(key)) {
			            event.preventDefault();
			            return false;
			        }
			    });


			     $("input[id='department_txt']").on('input', function(){

			       if ($(this).attr("maxlength") != 50){
			            if ($(this).val().length > 50){
			                $(this).val($(this).val().slice(0,-1));
			            }
			           $(this).attr("maxlength","50");
			       }

			    });

	     });

	</script>
	

<?php
	} // end if else
}
else {
	header("Location:../Mainform.php");
}
?>