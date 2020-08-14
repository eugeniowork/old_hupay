<?php
include "class/connect.php";
include "class/versioning_class.php"; // fixed class

include "class/company_class.php";


session_start();
if (isset($_SESSION["id"])){
	header("Location:MainForm.php");
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Log In Form</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> <!-- for symbols -->
		<link rel="shortcut icon" href="img/logo/lloyds logo.png"> <!-- for logo -->

		<!-- css -->
		<link rel="stylesheet" href="css/lumen.min.css">
		<link rel="stylesheet" href="css/custom.css">
		<style type="text/css">
			body, html {   
			    width: 100%;
			    height: 100%;
			    display:table;
			}
			body {
			    display:table-cell;
			    vertical-align:middle;
			   /* background-color: #85c1e9 ;*/
			    background-image:url("img/background image/background_images_log_in_page2.jpg"); 
			    /*background-color:#e9ecf2; */
			    background-size:cover;

			}
			form,img {
			   /* display:table; shrinks to fit content */
			    margin:auto;
			}

		</style>

		<!-- js -->
		<script src="js/jquery-1.12.2.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		
		
	</head>
	<body>
		<div class="container">
			<div class="row">
				<center>
					<img src="img/logo/lloyds logo.png" style="width:60px;"/>
					<h3>Sign in to <span style="color:#145a32;"><i>HuPay System</i></span></h3>
				</center>
			</div>
		</div>

		<div class="container">
			<div class="row">
				<div class="col-xs-8 col-xs-offset-2 col-md-4 col-sm-6 col-sm-offset-3 col-md-offset-4">
					<form class="form-horizontal" action="php script/log_in_script.php" method="post">
						<div class="panel panel-info">
							<!-- <div class="panel-heading">
								<h3 class="panel-title" style="color:#17202a;">
									<span class="glyphicon glyphicon-lock"></span> LOG IN FORM
									
								</h3>
							</div> -->
							<div class="panel-body">
								<div class="col-sm-8 col-xs-10 col-xs-offset-1 col-sm-offset-2">

									<div class="form-group">

									</div>

									<div class="form-group">

									</div>

									<!-- for username -->
							  		<div class="form-group">
							  			<div class="input-group mb-2 mr-sm-2 mb-sm-0">
										    <div class="input-group-addon" style="border:1px solid #999999;"><span class="glyphicon glyphicon-user" style="color:#2471a3;"></div>
										    <input type="text" class="form-control" name="username" placeholder="Username" required="required">
									    </div>
						  			</div>
						  			<!-- for password -->
						  			<div class="form-group">
					  					<div class="input-group mb-2 mr-sm-2 mb-sm-0">
										    <div class="input-group-addon" style="border:1px solid #999999;"><span class="glyphicon glyphicon-lock" style="color:#2471a3;"></div>
										    <input type="password" class="form-control" name="password" placeholder="Password" required="required">
									    </div>
						  			</div>


						  			<!-- for company -->
						  			<div class="form-group">
					  					<div class="input-group mb-2 mr-sm-2 mb-sm-0">
										    <div class="input-group-addon" style="border:1px solid #999999;"><span class="glyphicon glyphicon-blackboard" style="color:#2471a3;"></div>
										    <select name="company" class="form-control" required="required">
										    	<option value=""></option>
										    	<?php
										    		$company_class = new Company;
										    		$company_class->getAllCompanyToDropdown();
										    	?>	
									    	</select>
									    </div>
						  			</div>


						  			<!-- for submit button -->
						  			<div class="form-group">
					  					<input type="submit" class="btn btn-success col-xs-12" value="LOG IN">
					  					<!-- for error -->
					  					
					  				</div>

					  				
					  				<div class="form-group">
					  					<div class="">
					  						<center>
					  							<b>
					  								<a href="#" style="color: #ff0036" id="forgot_password">Forgot Password?</a>
				  								</b>
				  							</center>
				  						</div>
				  					</div>
					  					
				  					
					  				
					  			</div> <!-- end of col-md-12 -->
					  			<div class="col-xs-12" style="margin-bottom:-15px;">					  				
					  				<center>
			  							<p style="color: #cb4335 ;">&nbsp;<?php if (isset($_SESSION["failed_log_in"])){ echo $_SESSION["failed_log_in"]; $_SESSION["failed_log_in"] = null;} ?></p>
			  						</center>
			  					</div>
						  </div>	
						 
						</div>
					</form>
				</div>


				<div class="col-xs-8 col-xs-offset-2 col-md-4 col-sm-6 col-sm-offset-3 col-md-offset-4">
					<?php
	  					$version_class = new SystemVersion	;
	  					$version = $version_class->getLatestVersion();
	  				?>					
					<div style="text-align:center;color:#333333">
						<img src="img/logo/lloyds logo.png" style="width:15px;"/> <strong><small>Copyright <span class="glyphicon glyphicon-copyright-mark"></span> <?php echo $version; ?></small></strong>
					</div>					  				
				</div>

			</div> <!-- end of row -->
		</div> <!-- end of container -->


		<?php
			include "layout/modal/modal.php";
		?>


		<script>
			$(document).ready(function(){
				
				$("#forgot_password").on("click",function(){
					//alert("HELLO WORLD!");
					var datastring = "append=1";
			        $.ajax({
			          type: "POST",
			          url: "ajax/append_forgot_password.php",
			          data: datastring,
			          cache: false,
			          success: function (data) {
			            //alert(data);

			            // for size
			            $("#info_modal_size").attr("class","modal-dialog modal-sm");
			            $("#info_modal_title").html('Change Password');
			            $("#info_modal_title").removeAttr("style");

			            $("#informationModal").modal("show");
			            $("#info_modal_body").html(data);



			         }

			      });

				});

			});
		</script>


	</body>

		
</html>
