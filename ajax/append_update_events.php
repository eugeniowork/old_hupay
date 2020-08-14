<?php
session_start();
include "../class/connect.php";
include "../class/events.php";


if (isset($_POST["events_id"])) {
	$events_id = $_POST["events_id"];

	$events_class = new Events;
	$date_class = new date;

	// if exist
	if ($events_class->existEventsId($events_id) == 0) {
		echo "Error";	
	}
	// if success
	else {
		$row = $events_class->getEventsInfoById($events_id);
		$_SESSION["update_events_id"] = $events_id;



?>
	<div class="container-fluid">
		<form class="form-horizontal" action="php script/update_events.php" method="post" enctype="multipart/form-data">
			<div class="col-sm-12">
				<div class="form-group">
					<label class="label-control col-sm-3"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Event:</label>
					<div class="col-sm-9">
						<textarea class="form-control event-txt" name="event" placeholder="Enter event .." rows="5" required="required"><?php echo $row->events_value;?></textarea>
					</div>
				</div>

				<div class="form-group">
					<label class="label-control col-sm-3"><span class="glyphicon glyphicon-credit-card" style="color:#2E86C1;"></span> Event Title:</label>
					<div class="col-sm-9">
						<input type="text" id="department_txt" class="form-control event-txt" value="<?php echo $row->events_title;?>" name="title" placeholder="Enter title .." required="required"/>
					</div>
				</div>

				<div class="form-group">
					<label class="label-control col-sm-3"><span class="glyphicon glyphicon-time" style="color:#2E86C1;"></span> Event Date:</label>
					<div class="col-sm-9" id="wew">
						<?php
							$events_date = $row->events_date;
							if ($events_date == "0000-00-00"){
								$events_date = "";
							}
							else {
								$events_date = $date_class->dateDefault($events_date);
							}
						?>
						<input type="text" class="form-control event-txt" value="<?php echo $events_date; ?>" name="date" placeholder="Enter date .."/>
					</div>
				</div>

				<div class="form-group">
					<label class="label-control col-sm-3"><span class="glyphicon glyphicon-picture" style="color:#2E86C1;"></span> Images</label>
					<div class="col-sm-9">
						<input type="file" class="event-txt" name="image[]" accept="image/*" multiple/>
					</div>
				</div>


				<?php

					$existImage = $events_class->checkExistImage($events_id);
					if ($existImage >= 1){
						$events_class->getEventsImagesById($events_id);
						//$path = $row_events->imagePath;

				?>
					

				<?php
					} // end of if
				?>


				
				<div class="form-group">
					<div class="col-sm-12">
						<input type="submit" value="Update Events" class="btn btn-success btn-sm pull-right add-event-btn"/>
					</div>
				</div>
			</div>
		</form>
	</div>

	<script>
		$(document).ready(function(){
			$("input[name='date']").dcalendarpicker();
			$("#today").trigger( "click" );
			$("input[name='image']").on("click",function(){
				$(this).removeAttr("multiple");
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