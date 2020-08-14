 $(document).ready(function(){

 	// for number only
     $("input[id='number_only']").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter , F5
        if ($.inArray(e.keyCode, [46,8, 9, 27, 13, 110,116]) !== -1 ||
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

     // for year only
     $("input[id='year_only']").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter , F5
        if ($.inArray(e.keyCode, [46,8, 9, 27, 13, 110,116]) !== -1 ||
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




     // for security purpose return false 
     $("input[id='number_only").on("paste", function(){
          return false;
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





        // for security purpose return false
     $("input[id='float_only").on("paste", function(){
          return false;
     });

     $("input[id='year_only").on("paste", function(){
          return false;
     });


     // for payroll
      //  input_payroll
      $("input[id='input_payroll']").keydown(function (e) {
      //  return false;
        if(e.keyCode != 116) {
            return false;
        }
      });

        // onpaste
     $("input[id='input_payroll").on("paste", function(){
          return false;
     });


     // onpaste
     $("input[name='sssLoan']").change(function(){
          if ($(this).val()==""){
             $(this).val("0"); 
          }
     });


     // onpaste
     $("input[name='pagibigLoan']").change(function(){
          if ($(this).val()==""){
             $(this).val("0"); 
          }
     });


     $("input[name='cashAdvance']").change(function(){
          if ($(this).val()==""){
             $(this).val("0"); 
          }
     });


     $("input[name='adjustment']").change(function(){
          if ($(this).val()==""){
             $(this).val("0"); 
          }
     });


});