 $(document).ready(function(){
  // FOR UPPERCASE
    // fucntion for first letter Upperletter
    $("input[id='txt_only']").on('input', function(){
        $(this).val($(this).val().charAt(0).toUpperCase() + $(this).val().slice(1));
       // document.getElementById(id).value = inputTxt.value.charAt(0).toUpperCase() + inputTxt.value.slice(1);
     }); 

    // for txt only
    $(document).on('keypress', 'input[id="txt_only"]', function (event) {


        var regex = new RegExp("^[0-9?!@#$%^&*()_+<>/]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);

        if (regex.test(key)) {
            event.preventDefault();
            return false;
        }
    });


       // for security purpose return false
     $("input[id='txt_only").on("paste", function(){
    
          return false;
     });


      // for handling security in contactNo
    $("input[id='txt_only']").on('input', function(){

       if ($(this).attr("maxlength") != 50){
            if ($(this).val().length > 50){
                $(this).val($(this).val().slice(0,-1));
            }
           $(this).attr("maxlength","50");
       }

   });


       // for handling security in contactNo
    $("input[id='email_address_txt']").on('input', function(){

       if ($(this).attr("maxlength") != 50){
            if ($(this).val().length > 50){
                $(this).val($(this).val().slice(0,-1));
            }
           $(this).attr("maxlength","50");
       }

   });


       // for handling security in contactNo
    $("input[name='subject']").on('input', function(){

       if ($(this).attr("maxlength") != 100){
            if ($(this).val().length > 100){
                $(this).val($(this).val().slice(0,-1));
            }
           $(this).attr("maxlength","100");
       }

   });


       // for handling security in contactNo
    $("input[id='account_info_txt']").on('input', function(){

       if ($(this).attr("maxlength") != 50){
            if ($(this).val().length > 50){
                $(this).val($(this).val().slice(0,-1));
            }
           $(this).attr("maxlength","50");
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


    // account_info_txt
    $(document).on('keypress', 'input[id="account_info_txt"]', function (event) {


        var regex = new RegExp("^[<>/?]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);

        if (regex.test(key)) {
            event.preventDefault();
            return false;
        }
    });


       // for security purpose return false
     $("input[id='account_info_txt").on("paste", function(){
    
          return false;
     });

     // email_address_txt
     // account_info_txt
    $(document).on('keypress', 'input[id="email_address_txt"]', function (event) {

        var regex = new RegExp("^[<>/?]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);

        if (regex.test(key)) {
            event.preventDefault();
            return false;
        }
    });


        // for security purpose return false
     $("input[id='email_address_txt").on("paste", function(){
    
          return false;
     });

        // for security purpose return false
    $("input[id='email_address_txt']").keydown(function (e) {
    
         if (e.keyCode == "32") {
             return false;
           }
     });


      // username
      $("input[name='username']").keydown(function (e) {
           if (e.keyCode == "32") {
             return false;
           }
      });



       // for txt only
    $(document).on('keypress', 'input[name="subject"]', function (event) {


        var regex = new RegExp("^[<>/]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);

        if (regex.test(key)) {
            event.preventDefault();
            return false;
        }
    });



        // for security purpose return false
     $("input[name='subject").on("paste", function(){
    
          return false;
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


    // for returning false
     $(document).on('keypress', 'input[id="readonly_txt"]', function (event) {
        return false;
    });






});