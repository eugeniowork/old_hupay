<?php
include "../class/connect.php";
include "../class/memorandum_class.php";

//echo "HELLO WORLD!";
if (isset($_POST["memo_id"])){

	$memo_id = $_POST["memo_id"];

	//$memorandum_class = new Memorandum;

	//$row = $memorandum_class->getMemoInfoById($memo_id);

?>
	<div class="container-fluid">
		<div class="row">
			<form class="form-horizontal" method="post" id="form_add_memo_image" enctype="multipart/form-data">
					
				<div class="form-group">
	 				<label class="control-label col-sm-3 col-sm-offset-1"><b>Upload Image:</b></label>
 					<div class="col-sm-6 txt-pagibig-loan">
 						<input type="file" name="memo_upload_img[]" accept="image/*" multiple required="required">
						</div>
 				</div>
				<div class="form-group">
					<div class="col-sm-6 col-sm-offset-3">
						<label id="msg">&nbsp;</label>
					</div>
					<div class="col-md-2">
						<button id="add_memo_image" class="btn btn-primary btn-sm pull-right" type="submit">Add</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<script>
		$(document).ready(function(){

			$("button[id='add_memo_image']").on("click",function(){
				var valid_extensions = /(\.jpg|\.jpeg|\.png)$/i;

				if (!valid_extensions.test($("input[name='memo_upload_img[]']").val()) && $("input[name='memo_upload_img[]']").val() != ""){

             
                  $("#msg").html('<div class="col-xs-12"><p style="color:#CB4335"><span class="glyphicon glyphicon-remove"></span> You must upload only image file</p></div>');

                }

                else {
                	//alert("HELLO WORLD!");
                	$(this).attr("disabled","disabled");
					$("#form_add_memo_image").append("<input type='hidden' name='memo_id' value='<?php echo $memo_id; ?>' />");
                  	$("#form_add_memo_image").attr("action","php script/add_memo_image.php");
                }
			});

			
		});
	</script>
<?php
}
else {
	header("Location:../index.php");
}

?>