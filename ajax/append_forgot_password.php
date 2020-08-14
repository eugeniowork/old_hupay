

<style type="text/css">
	/* for loader */
	.loaders {
	  border: 5px solid #f3f3f3;
	  border-radius: 50%;
	  border-top: 5px solid #3498db;
	  width: 20px;
	  height: 20px;
	  -webkit-animation: spin .8s linear infinite;
	  animation: spin .8s linear infinite;
	  float:left;
	}

	@-webkit-keyframes spin {
	  0% { -webkit-transform: rotate(0deg); }
	  100% { -webkit-transform: rotate(360deg); }
	}

	@keyframes spin {
	  0% { transform: rotate(0deg); }
	  100% { transform: rotate(360deg); }
	}
</style>

<div class="container-fluid">
	<form class="form-horizontal" id="form_change_password">
		<div class="form-group">
			<div class="col-sm-12">
				<input type="text" name="f_username" class="form-control" placeholder="Username ...">
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-12">
				<input type="text" name="f_generated_code" class="form-control" placeholder="Generated Code ...">
			</div>
		</div>		
		<div id="change_password_div">

		</div>
		<div class="form-group">
			<div class="col-sm-12">
				<button id="submit_generated_code" type="button" class="btn btn-primary btn-sm col-sm-12">Submit</button>
			</div>
		</div>
		<div class="form-group" >
			<div class="col-sm-12" style="">
				<label id="f_message" style="display:none"></label>
			</div>
		</div>
	</form>
</div>


<script>
	$(document).ready(function(){


		var global_username = "";
		var ready_for_change_password = false;

		$("#submit_generated_code").on("click",function(){
			var username = $("input[name='f_username']").val();
			var generated_code = $("input[name='f_generated_code']").val();




			if (ready_for_change_password == false){

				global_username = "";

				//alert(username + " " + generated_code);

				if (username == "" || generated_code == ""){
					$("#f_message").html("<span>Please fill up all fields first.</span>");
					$("#f_message").removeAttr("style","");
					$("#f_message").closest("div").attr("style","color: #ff0000 ");
				}

				else {

					$("#f_message").closest("div").removeAttr("style");
					$("#f_message").html('<div class="loaders"></div> Please wait .... </center></div>');


					var datastring = "";
					datastring += "username="+username;
					datastring += "&generated_code="+generated_code;


					$("#submit_generated_code").attr("disabled","disabled");

					$.ajax({
			            type: "POST",
			            url: "ajax/script_check_can_forgot_password.php",
			            data: datastring,
			            cache: false,
			            success: function (data) {


			            	$("#submit_generated_code").removeAttr("disabled");

			            	//alert(data);
			            	if (data != "Success"){
			            		$("#f_message").html(data);
								$("#f_message").removeAttr("style","");
								$("#f_message").closest("div").attr("style","color: #ff0000 ");
			            	}

			            	else {

			            		global_username = username;

			            		$("input[name='f_username']").attr("readonly","readonly");
			            		$("input[name='f_generated_code']").attr("readonly","readonly");

			            		$("#f_message").attr("style","display:none");
			            		$("#f_message").html("");

			            		var html = "";


			            		html += '<div class="form-group">';
									html += '<div class="col-sm-12">';
										html += '<input type="password" name="new_password" class="form-control" placeholder="New Password ...">';
									html += '</div>';
								html += '</div>';
								html += '<div class="form-group">';
									html += '<div class="col-sm-12">';
										html += '<input type="password" name="confirm_password" class="form-control" placeholder="Confirm New Password ...">';
									html += '</div>';
								html += '</div>';		


								$("#change_password_div").html(html);


			            		ready_for_change_password = true;
			            	}
			            }
			        });
				}
			}

			else {
				//alert("READY FOR 2nd LOGIC");

				var new_password = $("input[name='new_password']").val();
				var confirm_password = $("input[name='confirm_password']").val();

				if (new_password == "" || confirm_password == ""){
					$("#f_message").html("<span>Please fill up all fields first.</span>");
					$("#f_message").removeAttr("style","");
					$("#f_message").closest("div").attr("style","color: #ff0000 ");
				}

				else {


					if (new_password != confirm_password){
						$("#f_message").html("<span>New Password And Confirm Password not match.</span>");
						$("#f_message").removeAttr("style","");
						$("#f_message").closest("div").attr("style","color: #ff0000 ");
					}

					else if (new_password.length < 8){
						$("#f_message").html("<span>New Password character length must be 8 or above.</span>");
						$("#f_message").removeAttr("style","");
						$("#f_message").closest("div").attr("style","color: #ff0000 ");
					}

					else {

						$("#f_message").closest("div").removeAttr("style");
						$("#f_message").html('<div class="loaders"></div> Please wait .... </center></div>');

						var datastring = "";
						datastring += "password="+new_password;
						datastring += "&username="+global_username;

						$("#submit_generated_code").attr("disabled","disabled");

						$.ajax({
				            type: "POST",
				            url: "php script/script_forgot_change_password.php",
				            data: datastring,
				            cache: false,
				            success: function (data) {

				            	if (data != "Success"){
				            		location.reload();
				            	}

				            	else {
				            		$("#f_message").removeAttr("style");
			            			$("#f_message").html("Successfully Change Password");
			            			$("#f_message").closest("div").attr("style","color: #1ff14f ");

			            			$("#change_password_div").html("");

			            			$("input[name='f_username'").removeAttr("readonly");
			            			$("input[name='f_username'").val("");

			            			$("input[name='f_generated_code'").removeAttr("readonly");
			            			$("input[name='f_generated_code'").val("");
				            	}

			            	}
			            });



					}



				}
			}
		});
	});

</script>