<?php
include "../class/connect.php";
include "../class/minimum_wage_class.php";
include "../class/date.php";

$min_wage_class = new MinimumWage;
$row = $min_wage_class->getLatestMinWageInfo();

$date_class = new date;
$effectiveDate = $date_class->dateDefault($row->effectiveDate);

?>

<div class="panel panel-info">
    <div class="panel-footer" style="border:1px solid #BDBDBD;">
    	<div class="" style="border-bottom:1px solid #BDBDBD"><b style="color: #2471a3 ">Update Minimum Wage</b> <a href="#" id="remove_update_min_wage_form"><span class="glyphicon glyphicon-remove pull-right" style="color:#cb4335;"></span></a></div>
		<div class="container-fluid">
			<form class="form-horizontal" action="php script/update_latest_min_wage.php" method="post">
				<div class="form-group">

					<div class="col-sm-4">
						<label class="control-label">Effective Date:</label>
						<input type="text" name="updateEffectiveDate" class="form-control" value="<?php echo $effectiveDate; ?>" required="required" placeholder="Input Effective Date"/>
					</div>

					<div class="col-sm-4">
						<label class="control-label">Basic Wage:</label>
						<input id="float_only" type="text" name="updateBasicWage" class="form-control" value="<?php echo $row->basicWage; ?>" required="required" placeholder="Input Basic Wage"/>
					</div>

					<div class="col-sm-4">
						<label class="control-label">COLA:</label>
						<input id="float_only" type="text" name="updateCOLA" class="form-control" value="<?php echo $row->COLA; ?>" required="required" placeholder="Input COLA"/>
					</div>
					
					<div class="col-sm-12" style="margin-top:10px;">
						<input type="submit" value="Update" class="btn btn-success btn-sm pull-right"/>
					</div>			

				</div>					
			</form>
			

		</div>
    </div>
</div>



<script>
	$(document).ready(function(){

		$("input[name='updateEffectiveDate']").dcalendarpicker();

		 // sssContribution
	    $("input[id='float_only']").on('input', function(){
	       if ($(this).attr("maxlength") != 9){
	            if ($(this).val().length > 9){
	                $(this).val($(this).val().slice(0,-1));
	            }
	           $(this).attr("maxlength","9");
	       }

	   });


	    // FOR DECIMAL POINT
      $("input[id='float_only']").keydown(function (e) {


        // for decimal pint
        if (e.keyCode == "190") {
            if ($(this).val().replace(/[0-9]/g, "") == ".") {
                return false;  
            }
        }


        // Allow: backspace, delete, tab, escape, enter , F5
        if ($.inArray(e.keyCode, [46,8, 9, 27, 13, 110,116,190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
	});
</script>