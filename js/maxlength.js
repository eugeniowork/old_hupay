$(document).ready(function(){

	  // for handling security in bio ID
    $("input[name='bioId']").on('input', function(){
       if ($(this).attr("maxlength") != 4){
            if ($(this).val().length > 4){
                $(this).val($(this).val().slice(0,-1));
            }
           $(this).attr("maxlength","4");
       }

       if ($(this).val() != ""){
        $(this).val(parseInt($(this).val()) / 1);
      }
   });

    // for handling security in contactNo
    $("input[name='contactNo']").on('input', function(){
       if ($(this).attr("maxlength") != 11){
            if ($(this).val().length > 11){
                $(this).val($(this).val().slice(0,-1));
            }
           $(this).attr("maxlength","11");
       }

   });


    // for handling security in SSS No
    $("input[name='sssNo']").on('input', function(){
       if ($(this).attr("maxlength") != 10){
            if ($(this).val().length > 10){
                $(this).val($(this).val().slice(0,-1));
            }
           $(this).attr("maxlength","10");
       }

   });


     // for handling security in Pag-ibig No
    $("input[name='pagibigNo']").on('input', function(){
       if ($(this).attr("maxlength") != 12){
            if ($(this).val().length > 12){
                $(this).val($(this).val().slice(0,-1));
            }
           $(this).attr("maxlength","12");
       }

   });


     // for handling security in Pag-ibig No
    $("input[name='tinNo']").on('input', function(){
       if ($(this).attr("maxlength") != 9){
            if ($(this).val().length > 9){
                $(this).val($(this).val().slice(0,-1));
            }
           $(this).attr("maxlength","9");
       }

   });


    // for handling security in Philhealth No
    $("input[name='philhealthNo']").on('input', function(){
       if ($(this).attr("maxlength") != 12){
            if ($(this).val().length > 12){
                $(this).val($(this).val().slice(0,-1));
            }
           $(this).attr("maxlength","12");
       }

   });


     // for handling security in Salary
    $("input[name='salary']").on('input', function(){
       if ($(this).attr("maxlength") != 6){
            if ($(this).val().length > 6){
                $(this).val($(this).val().slice(0,-1));
            }
           $(this).attr("maxlength","6");
       }

   });




    // for sss Contribution compensationFrom
    $("input[name='compensationFrom']").on('input', function(){
       if ($(this).attr("maxlength") != 6){
            if ($(this).val().length > 6){
                $(this).val($(this).val().slice(0,-1));
            }
           $(this).attr("maxlength","6");
       }

   });

     // for sss Contribution compensationTo
    $("input[name='compensationTo']").on('input', function(){
       if ($(this).attr("maxlength") != 6){
            if ($(this).val().length > 6){
                $(this).val($(this).val().slice(0,-1));
            }
           $(this).attr("maxlength","6");
       }

   });
    
    // sssContribution
    $("input[name='sssContribution']").on('input', function(){
       if ($(this).attr("maxlength") != 6){
            if ($(this).val().length > 6){
                $(this).val($(this).val().slice(0,-1));
            }
           $(this).attr("maxlength","6");
       }

   });


    // float only
    $("input[id='float_only']").on('input', function(){
       if ($(this).attr("maxlength") != 10){
            if ($(this).val().length > 10){
                $(this).val($(this).val().slice(0,-1));
            }
           $(this).attr("maxlength","10");
       }

   });

    // percentage
    $("input[name='percentage']").on('input', function(){
       if ($(this).attr("maxlength") != 2){
            if ($(this).val().length > 2){
                $(this).val($(this).val().slice(0,-1));
            }
           $(this).attr("maxlength","2");
       }

   });



    // for date only
    $("input[id='date_only']").on('input', function(){
       if ($(this).attr("maxlength") != 10){
            if ($(this).val().length > 10){
                $(this).val($(this).val().slice(0,-1));
            }
           $(this).attr("maxlength","10");
       }

   });


     // float only
    $("input[id='year_only']").on('input', function(){
       if ($(this).attr("maxlength") != 4){
            if ($(this).val().length > 4){
                $(this).val($(this).val().slice(0,-1));
            }
           $(this).attr("maxlength","4");
       }

   });


    // float only
    $("input[id='number_only']").on('input', function(){
       if ($(this).attr("maxlength") != 10){
            if ($(this).val().length > 10){
                $(this).val($(this).val().slice(0,-1));
            }
           $(this).attr("maxlength","10");
       }

   });

});