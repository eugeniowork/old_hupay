<?php
session_start();
include "../class/connect.php";
include "../class/message_class.php";
include "../class/emp_information.php";

//
if (isset($_POST["message_id"])){
	$message_class = new Message;

	$message_id = $_POST["message_id"];

	if ($message_class->checkExistMessageId($message_id) == 0){
		echo "Error";
	}

	else {

		// for getting the info
		$row = $message_class->getInfoByMessageId($message_id);

		
		// this facility
		// for checking if the mssage is sa kanya
		$my_id = $_SESSION["id"];
		$own_message = 0;
		if ($row->from_emp_id == $my_id){
			$own_message = 1;
		}

		$message_class->readMessage($message_id,$own_message);


		// for getting the profile images
		$emp_info_class = new EmployeeInformation; // kung cnu nagsend
		$row_emp = $emp_info_class->getEmpInfoByRow($row->from_emp_id);
		$profilePath = $row_emp->ProfilePath;

		$from_name = $row_emp->Firstname . " " . $row_emp->Lastname;

		///$date_create = ;
		$date = date_format(date_create($row->dateCreated), 'F d, Y');
		$time = date_format(date_create($row->dateCreated), 'g:i A');


?>
	<div class="container-fluid">
		<form method="post" id="form_replyMessage">
			<div class="col-md-10 col-md-offset-1" style="border:1px solid #BDBDBD;padding:2px;background-color:#BDBDBD;">
				<h4 style="color:#000;"><?php echo $row->subject; ?></h4>
			</div>
			<div class="col-md-10 col-md-offset-1" style="border:1px solid #BDBDBD;padding:5px;background-color:#fff">

				<div class="col-md-1">
					<img src="<?php echo $profilePath; ?>" class="events-profile-pic"/>
				</div>
				<div class="col-md-8">
					<div class="col-md-12">
						<b><?php echo $from_name; ?></b>
					</div>
					<div class="col-md-12">
						<span style="word-wrap: break-word" id="readmoreReply"><?php echo htmlspecialchars($row->message); ?></span>
					</div>
				</div>
				<div class="col-md-3" style="">
					<small style="color:#707b7c"><i><?php echo $date; ?> , <?php echo $time; ?></i></small>
				</div>
			</div>

			<?php
				$message_class->getReplyMessages($message_id);
			?>
			

			<div class="col-md-10 col-md-offset-1" style="border:1px solid #BDBDBD;padding:5px;background-color:#BDBDBD;">
				<textarea class="form-control" placeholder="Enter your reply" name="message_reply" required="required" rows="5"></textarea>
			</div>

			<div class="col-md-11" style="margin-top:10px;margin-left:15px;">
				
					<button class="btn btn-primary btn-sm pull-right" id="btn_reply" type="submit">Reply</button>
				
			</div>
		</form>

	</div>

	<script>
		//alert("Hello World!");
		var showCharReply = 40;  // How many characters are shown by default
	    var ellipsestextReply = "...";
	    var moretextReply = "Show more >";
	    var lesstextReply = "Show less";
	    
	    $("span[id='readmoreReply']").each(function() {

	        var content = $(this).html();

	 
	        if(content.length > showCharReply) {
	 
	            var c = content.substr(0, showCharReply);
	            var h = content.substr(showCharReply, content.length - showCharReply);
	 
	            var html = c + '<span class="moreellipses">' + ellipsestextReply+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretextReply + '</a></span>';
	 
	            $(this).html(html);
	        }
	 
	    });



	    $(".morelink").click(function(){
	        if($(this).hasClass("less")) {
	            $(this).removeClass("less");
	            $(this).html(moretextReply);
	        } else {
	            $(this).addClass("less");
	            $(this).html(lesstextReply);
	        }
	        $(this).parent().prev().toggle();
	        $(this).prev().toggle();
	        return false;
	    });

	    /*$("textarea[name='message_reply']").keydown(function (e) {
       
	    	//alert(e.keyCode);

	        // for decimal pint
	        if (e.keyCode == "13" && !e.shiftKey) {
	           
	            var reply = $(this).val();

	           //alert(reply);
	     		window.location = "php script/reply_message.php?message_id=<?php echo $message_id; ?>&reply="+reply;      
	        }

   		 });*/

		//reply
		$("button[id='btn_reply']").on("click",function(){
			
			$("textarea[name='message_reply']").attr("required","required");
			
			if ($("textarea[name='message_reply']").val() != ""){
				//var reply = $("textarea[name='message_reply']").val();
				$("#form_replyMessage").attr("action","php script/reply_message.php?message_id=<?php echo $message_id; ?>");
				$("#form_replyMessage").submit();

			}

		});


	</script>
<?php
	}

}
else {
	header("Location:../MainForm.php");
}





?>