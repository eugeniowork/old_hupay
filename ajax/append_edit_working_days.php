<?php
	
include "../class/connect.php";
include "../class/working_days_class.php";
	
if (isset($_POST["working_days_id"])){
	$working_days_id = $_POST["working_days_id"];

	// checkExistWorkingDaysId($working_days_id)

	$working_days_class = new WorkingDays;

	if ($working_days_class->checkExistWorkingDaysId($working_days_id) == 0){
			echo "Error";
	}

	else {


		$row = $working_days_class->getWorkingDaysInfoById($working_days_id);
		$day_from = $row->day_from;
		$day_to = $row->day_to;

?>
	<div class="container-fluid">
	<form class="form-horizontal" action="" id="form_addWorkingDays" method="post">	<!-- ../php script/position_department_script.php -->	
		
		<div class="form-group">
			<div class="col-sm-12">
				<label class="control-label"><span class="glyphicon glyphicon-time" style="color:#2E86C1;"></span> Day From:&nbsp;<span class="red-asterisk">*</span></label>
			</div>
			<div class="col-sm-12">
				<select class="form-control" name="update_day_from" id="working_days_select">
					<option value="">Please select</option>
					<?php
						$day_of_the_week = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
						$day_of_the_week_value = [0,1,2,3,4,5,6];

						$count = count($day_of_the_week);

						$counter = 0;
						do {

							$selected = "";
							if ($day_of_the_week_value[$counter] == $day_from){
								$selected = "selected='selected'";
							}

					?>
					<option <?php echo $selected; ?> value="<?php echo $day_of_the_week[$counter]; ?>"><?php echo $day_of_the_week[$counter]; ?></option>
					<?php

							$counter++;
						}while($count > $counter);

					?>
					
				</select>
			</div>
		</div>


		<div class="form-group">
			<div class="col-sm-12">
				<label class="control-label"><span class="glyphicon glyphicon-time" style="color:#2E86C1;"></span> Day To:&nbsp;<span class="red-asterisk">*</span></label>
			</div>
			<div class="col-sm-12">
				<select class="form-control" name="update_day_to" id="working_days_select">
					<option value="">Please select</option>
					<?php
						$day_of_the_week = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
						$day_of_the_week_value = [0,1,2,3,4,5,6];
						$count = count($day_of_the_week);

						$counter = 0;
						do {

							$selected = "";
							if ($day_of_the_week_value[$counter] == $day_to){
								$selected = "selected='selected'";
							}

					?>
					<option <?php echo $selected; ?> value="<?php echo $day_of_the_week[$counter]; ?>"><?php echo $day_of_the_week[$counter]; ?></option>
					<?php

							$counter++;
						}while($count > $counter);

					?>
					
				</select>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-12">
				<label id="update_wd_error_msg"></label>
			</div>
		</div>
			

		<div class="form-group">
			<div style="text-align:center;">
				<input type="button" id="edit_working_days" value="Edit Working Days" class="btn btn-success"/>
			</div>
		</div>
	</form>
</div>

<script>
	$(document).ready(function(){

		//alert("HELLO WORLD!");

		var day_of_the_week = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
		var day_of_the_week_value = [0,1,2,3,4,5,6];
		$("input[id='edit_working_days']").on("click",function(){
	    	//alert("HELLO WORLD!");
	    	var day_from = $("select[name='update_day_from']").val();
			var day_to = $("select[name='update_day_to']").val(); 

			if (day_from == "" || day_to == ""){
				$("#update_wd_error_msg").html("<span class='color-red'>Please fill up all fields.</span>");
			}
			else {
				var validate_day_from = day_of_the_week.includes(day_from);
	    		var validate_day_to = day_of_the_week.includes(day_to);


	    		//alert(validate_day_from + " " + validate_day_to);


	    		if (validate_day_from == true && validate_day_to == true){
	    			//alert("ANOTHER LOGIC");
	    			var day_from_value = day_of_the_week_value[day_of_the_week.indexOf(day_from)]; // 1
	    			var day_to_value = day_of_the_week_value[day_of_the_week.indexOf(day_to)]; // 1

	    			//alert(day_from_value + " " + day_to_value);
	    			if (day_from_value > day_to_value ){
	    				$("#update_wd_error_msg").html("<span class='color-red'><b>Day From</b> must be not greater than <b>Day To</b></span>");
	    			}
	    			else if (day_from_value == day_to_value){
	    				$("#update_wd_error_msg").html("<span class='color-red'><b>Day From</b> must be not equal to <b>Day To</b></span>");
	    			}

	    			else {
	    				$(this).attr("disabled","disabled");
	    				$("#update_wd_error_msg").html("");
	    				//alert("READY FOR SUBMITTION");
	    				var datastring = "day_from="+day_from_value+"&day_to="+day_to_value+"&working_days_id="+"<?php echo $working_days_id; ?>";
	    				
	    				$("#update_wd_error_msg").html('<div class="loader"></div>Please wait ...');
	    				$.ajax({
				            type: "POST",
				            url: "php script/edit_working_days.php",
				            data: datastring,
				            cache: false,
				           // datatype: "php",
				            success: function (data) {
				              	//alert(data);
				              	if (data != "Success"){
				              		$("#update_wd_error_msg").html(data);
				              		$("input[id='edit_working_days']").removeAttr("disabled");
				              	}
				              	else {
				              		location.reload();
				              	}
				            }
				        });
	    			}
	    		}
			}
	    });
	});
	
</script>
<?php
	}
}

else {
	header("Location:../MainForm.php");
}


?>