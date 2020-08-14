<?php
include "../class/connect.php";
include "../class/memorandum_class.php";

//echo "HELLO WORLD!";
if (isset($_POST["memo_id"])){

	$memo_id = $_POST["memo_id"];

	$memorandum_class = new Memorandum;

	$row = $memorandum_class->getMemoInfoById($memo_id);

?>
	<div class="container-fluid">
		<div class="row">
			<form class="form-horizontal" method="post">
				<div class="form-group">
					<div class="col-md-12">
						<label><span class="glyphicon glyphicon-file" style="color: #2471a3 "></span>&nbsp;<b>Subject:</b></label> <?php echo $row->Subject; ?>
					</div>
					<?php
						$memorandum_class->getMemoImgList($memo_id);
					?>
				</div>
				<div class="form-group">
					<div class="col-md-10 col-md-offset-1">
						<button id="update_memo_image" class="btn btn-primary btn-xs pull-right" type="button">Update</button>
					</div>
				</div>
				<div class="form-group" style="margin-bottom:-10px;">
					<div class="col-md-10 col-md-offset-1">
						<label id="msg">&nbsp;</label>
					</div>
				</div>
			</form>
		</div>
	</div>

	<script>
		$(document).ready(function(){
			var remove_array = [];
			$("button[id='remove_memo_image']").on("click",function(){
				//alert("HELLO WORLD!");
				var memo_img_id = $(this).closest("div").attr("id");
				//alert(memo_img_id);
					
				remove_array.push(memo_img_id);

				$(this).closest("div").parent("div").remove();

				//alert(remove_array);
			});


			$("#update_memo_image").on("click",function(){
				//alert("HELLO WORLD!");
				if (remove_array == ""){
					//alert("NEED FOR VALIDATION");
					$("#msg").html("<span style='color: #c0392b '><span class='glyphicon glyphicon-remove'></span>&nbsp;Can't Update, No remove images was taken.</span>");

				}
				else {
					$("button[id='remove_memo_image']").attr("disabled","disabled");
					$(this).attr("disabled","disabled");
					$("#msg").html("<center><div class='loader' style='float:left'></div>&nbsp;Removing Memo Images please wait ...</center>");

					var interval = setInterval(function(){ 
        				
        				
						$.ajax({
					        type: "POST",
					        url: "php script/remove_memo_images.php?memo_id=<?php echo $memo_id; ?>",
					        data: { removeArray: JSON.stringify(remove_array)},
					        cache: false,
					        success: function (data) {
					        	clearInterval(interval); 
					        	//alert(data);
								//alert("HELLO WORLD!");
								//alert(data);
								location.reload();
				          	}
			          	});
		          	}, 1000); // set to 3 seconds
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