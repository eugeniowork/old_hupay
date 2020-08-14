$(document).ready(function(){

	 $("input[id='date_only']").keydown(function (e) {

	    //alert(e.keyCode);
	 	 //return false;
	 	 // Allow: backspace, delete, tab, escape, enter , F5 , backslash
        if ($.inArray(e.keyCode, [46,8, 9, 27, 13, 110,116,191]) !== -1 ||
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

     $("input[id='date_only']").keydown(function (e) {

     });


	 // for security purpose return false 
     $("input[id='date_only").on("paste", function(){
          return false;
     });



     $("input[id='date_only']").change(function(){
     	$("div[class='datepicker dropdown-menu']").attr("style","none");
       	//$("div[class='datepicker']").attr("style","display:none");
       //	alert("Hello World!");
 	 });




     /*
     // for attendance list date from
     $("input[name='dateFrom']").blur(function(){
         var dateformat = /^(0[1-9]|1[012])[\/\-](0[1-9]|[12][0-9]|3[01])[\/\-]\d{4}$/;
         var name = $(this).attr("name");

         //alert($(this).val());
         if ($(this).val() == ""){
            var div = $("input[name='"+name+"']").closest("div");
            div.removeClass("has-error has-success has-feedback");
            $("#glypcn" + name).remove();
         }

         else if ($(this).val().match(dateformat)){
            var div = $("input[name='"+name+"']").closest("div");
            div.removeClass("has-error");
            div.addClass("has-success has-feedback");
            $("#glypcn" + name).remove();
            div.append('<span id="glypcn' + name + '" class="glyphicon glyphicon-ok form-control-feedback"></span>');
         }

         else {
            var div = $("input[name='"+name+"']").closest("div");
            div.removeClass("has-success");
            div.addClass("has-error has-feedback");
            $("#glypcn" + name).remove();
            div.append('<span id="glypcn' + name + '" class="glyphicon glyphicon-remove form-control-feedback"></span>');
         }



    });


     // for attendance list date from
     $("input[name='dateTo']").blur(function(){
         var dateformat = /^(0[1-9]|1[012])[\/\-](0[1-9]|[12][0-9]|3[01])[\/\-]\d{4}$/;
         var name = $(this).attr("name");

         //alert($(this).val());
         if ($(this).val() == ""){
            var div = $("input[name='"+name+"']").closest("div");
            div.removeClass("has-error has-success has-feedback");
            $("#glypcn" + name).remove();
         }

         else if ($(this).val().match(dateformat)){
            var div = $("input[name='"+name+"']").closest("div");
            div.removeClass("has-error");
            div.addClass("has-success has-feedback");
            $("#glypcn" + name).remove();
            div.append('<span id="glypcn' + name + '" class="glyphicon glyphicon-ok form-control-feedback"></span>');
         }

         else {
            var div = $("input[name='"+name+"']").closest("div");
            div.removeClass("has-success");
            div.addClass("has-error has-feedback");
            $("#glypcn" + name).remove();
            div.append('<span id="glypcn' + name + '" class="glyphicon glyphicon-remove form-control-feedback"></span>');
         }

    });


    // for minimum wage
    $("input[name='effectiveDate']").blur(function(){
         var dateformat = /^(0[1-9]|1[012])[\/\-](0[1-9]|[12][0-9]|3[01])[\/\-]\d{4}$/;
         var name = $(this).attr("name");

         //alert($(this).val());
         if ($(this).val() == ""){
            var div = $("input[name='"+name+"']").closest("div");
            div.removeClass("has-error has-success has-feedback");
            $("#glypcn" + name).remove();
         }

         else if ($(this).val().match(dateformat)){
            var div = $("input[name='"+name+"']").closest("div");
            div.removeClass("has-error");
            div.addClass("has-success has-feedback");
            $("#glypcn" + name).remove();
            div.append('<span id="glypcn' + name + '" class="glyphicon glyphicon-ok form-control-feedback"></span>');
         }

         else {
            var div = $("input[name='"+name+"']").closest("div");
            div.removeClass("has-success");
            div.addClass("has-error has-feedback");
            $("#glypcn" + name).remove();
            div.append('<span id="glypcn' + name + '" class="glyphicon glyphicon-remove form-control-feedback"></span>');
         }

    });


    // for birthdate 
    $("input[name='birthdate']").blur(function(){

         var dateformat = /^(0[1-9]|1[012])[\/\-](0[1-9]|[12][0-9]|3[01])[\/\-]\d{4}$/;
         var name = $(this).attr("name");

         //alert($(this).val());
         if ($(this).val() == ""){
            var div = $("input[name='"+name+"']").closest("div");
            div.removeClass("has-error has-success has-feedback");
            $("#glypcn" + name).remove();
         }

         else if ($(this).val().match(dateformat)){
            var div = $("input[name='"+name+"']").closest("div");
            div.removeClass("has-error");
            div.addClass("has-success has-feedback");
            $("#glypcn" + name).remove();
            div.append('<span id="glypcn' + name + '" class="glyphicon glyphicon-ok form-control-feedback"></span>');
         }

         else {
            var div = $("input[name='"+name+"']").closest("div");
            div.removeClass("has-success");
            div.addClass("has-error has-feedback");
            $("#glypcn" + name).remove();
            div.append('<span id="glypcn' + name + '" class="glyphicon glyphicon-remove form-control-feedback"></span>');
        }

    });


     // for birthdate 
    $("input[name='dateHired']").blur(function(){
         var dateformat = /^(0[1-9]|1[012])[\/\-](0[1-9]|[12][0-9]|3[01])[\/\-]\d{4}$/;
         var name = $(this).attr("name");

         //alert($(this).val());
         if ($(this).val() == ""){
            var div = $("input[name='"+name+"']").closest("div");
            div.removeClass("has-error has-success has-feedback");
            $("#glypcn" + name).remove();
         }

         else if ($(this).val().match(dateformat)){
            var div = $("input[name='"+name+"']").closest("div");
            div.removeClass("has-error");
            div.addClass("has-success has-feedback");
            $("#glypcn" + name).remove();
            div.append('<span id="glypcn' + name + '" class="glyphicon glyphicon-ok form-control-feedback"></span>');
         }

         else {
            var div = $("input[name='"+name+"']").closest("div");
            div.removeClass("has-success");
            div.addClass("has-error has-feedback");
            $("#glypcn" + name).remove();
            div.append('<span id="glypcn' + name + '" class="glyphicon glyphicon-remove form-control-feedback"></span>');
         }

    });

    // date
    $("input[name='date']").blur(function(){
         var dateformat = /^(0[1-9]|1[012])[\/\-](0[1-9]|[12][0-9]|3[01])[\/\-]\d{4}$/;
         var name = $(this).attr("name");

         //alert($(this).val());
         if ($(this).val() == ""){
            var div = $("input[name='"+name+"']").closest("div");
            div.removeClass("has-error has-success has-feedback");
            $("#glypcn" + name).remove();
         }

         else if ($(this).val().match(dateformat)){
            var div = $("input[name='"+name+"']").closest("div");
            div.removeClass("has-error");
            div.addClass("has-success has-feedback");
            $("#glypcn" + name).remove();
            div.append('<span id="glypcn' + name + '" class="glyphicon glyphicon-ok form-control-feedback"></span>');
         }

         else {
            var div = $("input[name='"+name+"']").closest("div");
            div.removeClass("has-success");
            div.addClass("has-error has-feedback");
            $("#glypcn" + name).remove();
            div.append('<span id="glypcn' + name + '" class="glyphicon glyphicon-remove form-control-feedback"></span>');
         }

    });
    */




});