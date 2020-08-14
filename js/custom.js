 
$(document).ready(function(){


  // for declaring global variable

  // for pag-ibig loan
  var add_pagibigLoan_emp_id = "";
  var update_pagibigLoan_pagibigLoan_id = "";
  var delete_pagibigLoan_pagibigLoan_id = "";
  var adjust_pagibigLoan_id = "";
  //alert(global_emp_id);



  // for sss loan
  var add_ssssLoan_emp_id = "";
  var update_sssLoan_sssLoan_id = "";
  var delete_sssLoan_sssLoan_id = "";
  var adjust_sssLoan_id = "";

  // for salary loan 
  var add_salaryLoan_emp_id = "";
  var update_salaryLoan_salaryLoan_id = "";
  var delete_salaryLoan_sssLoan_id = "";
  var adjust_salaryLoan_id = "";


  // for salary loan 
  var add_simkimban_emp_id = "";
  var update_simkimban_id = "";
  var delete_simkimban_id = "";
  var adjust_simkimban_id = "";


  // for YTD Information
  var update_ytd_id = "";

  // for memo
  var add_memo_emp_id = ""; // if choose specific employee
  var add_memo_dept_id = ""; // if choose department 
  var update_memo_id = "";
  var delete_memo_id = "";


  // for generating payroll
  var emp_active_total_count = "";
  var emp_active_id_values = "";


  // for updating payroll info
  var update_payroll_info_emp_id = "";



  // for deleting working hours
  var delete_working_hours_id = "";



  // for uploading images
  var upload_201_files_emp_id = "";
  var update_201_files_description_id = "";
  var update_201_files_image_id = "";
  var delete_201_files_image_id = "";
  var view_201_files_image_id = "";


  // for approve/ disapprove file salary loan
  var disapprove_file_salary_loan_id = "";



  // for employee list pic-th


  // JQUERY CODES GOES HERE


  // HERE ALL THE ONLOAD VALUES
//  $("input[name='bioId']").attr("maxlength","4");
  //$("input[name='bioId']").keydown(function(){
   //    $("input[name='bioId']").attr("maxlength","3");
   //});


// for global count memo
 var memoRecipientCount = 1;


  // for enabling toolip
   $('[data-toggle="tooltip"]').tooltip(); 
   //alert("Hello World!");



 // for registration in department will output the position
    $("select[name='department']").change(function(){
       var datastring = "department_id="+$(this).val();
       $.ajax({
            type: "POST",
            url: "ajax/append_position.php",
            data: datastring,
            cache: false,
           // datatype: "php",
            success: function (data) {
              $("select[name='position']").html(data);
               // $('#update_modal_body').html(data);
              //  $("#update_info_modal").modal("show");
            }
        });

    });

     // for DEPARTMENT LIST
     // ONCLICK UPDATING
     $("a[id='edit_deptartment']").on("click", function () {
       var datastring = "department_id="+$(this).closest("tr").attr("id");
      $("#edit_modal_body").html("<center><div class='loader'></div>Loading Information</center>");
       $.ajax({
            type: "POST",
            url: "ajax/append_edit_department_modal.php",
            data: datastring,
            cache: false,
           // datatype: "php",
            success: function (data) {
              $("#edit_modal_body").html(data);
              $("#editModal").modal("show");
            }
        });
         
     });

      // ONCLICK DELETING
     $("a[id='delete_department']").on("click", function () {
       var datastring = "department_id="+$(this).closest("tr").attr("id");
       $("#delete_modal_body").html("<center><div class='loader'></div>Loading Information</center>");
      $.ajax({
            type: "POST",
            url: "ajax/append_delete_department_modal.php",
            data: datastring,
            cache: false,
           // datatype: "php",
            success: function (data) {     
              $("#delete_modal_body").html(data);

              // if edited by user
              if (data == "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There is an error during getting of data</center>"){
                $("#delete_modal_footer").remove();
              }

              $("#deleteDepartmentConfirmationModal").modal("show");

            }
        });
         
     }); 

     // for DELETING YES BUTTON
    $("#delete_yes_dept").on("click", function () {
          $(this).attr("href","php script/delete_department_script.php");

     }); 


    // for POSITION LIST
     // ONCLICK UPDATING
     $("a[id='edit_position']").on("click", function () {
       var datastring = "position_id="+$(this).closest("tr").attr("id");
        $("#edit_modal_body").html("<center><div class='loader'></div>Loading Information</center>");
       $.ajax({
            type: "POST",
            url: "ajax/append_edit_position_modal.php",
            data: datastring,
            cache: false,
           // datatype: "php",
            success: function (data) { 
                $("#edit_modal_body").html(data);
              $("#editModal").modal("show");
            }
        }); 
         
     });

      // ONCLICK DELETING
     $("a[id='delete_position']").on("click", function () {
       var datastring = "position_id="+$(this).closest("tr").attr("id");
       $("#delete_modal_body").html("<center><div class='loader'></div>Loading Information</center>");
       $.ajax({
            type: "POST",
            url: "ajax/append_delete_position_modal.php",
            data: datastring,
            cache: false,
           // datatype: "php",
            success: function (data) { 
             $("#delete_modal_body").html(data);
              // if edited by user
              if (data == "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There is an error during getting of data</center>"){
                $("#delete_modal_footer").remove();
              }
             $("#deletePositionConfirmationModal").modal("show");
            }
        }); 
         
     });

       // for DELETING YES BUTTON
    $("#delete_yes_position").on("click", function () {
          $(this).attr("href","php script/delete_position_script.php");

     }); 


    // for birthdate onpaste is false
       // for security purpose return false
     $("input[name='birthdate").on("paste", function(){
          return false;
     });



     // for DEPARTMENT LIST
     // ONCLICK UPDATING
     $("a[id='view_emp_profile']").on("click", function () {
       var datastring = "emp_id="+$(this).closest("tr").attr("id");

       var tr_id = $(this).closest("tr").attr("id");

       var id = $(this).attr("id"); 
      // alert($("#"+tr_id).children().next().next().next().next().next().children().next().next().next().next().next().next().next().html()););

      

      // check muna natin if nag eexist ung emp id sa database kapag nag eexist render ung page kapag hindi error message
       $.ajax({
            type: "POST",
            url: "ajax/script_emp_view_profile.php",
            data: datastring,
            cache: false,
           // datatype: "php",
            success: function (data) {
              //$("#edit_modal_body").html(data);
             // $("#editModal").modal("show");
             if (data == "Error" || data == 1){
                $("#errorModal").modal("show");
             }
             else {
              // alert(tr_id);
              // alert(id);
              // $("#"+tr_id).find("a[id='"+id+"']").attr("href","view_emp_profile.php");
              // window.location = $("#"+tr_id).find("a[id='"+id+"']").attr('href');
               $("#submit_form").html(
                   "<form id='view_profile_form' action='php script/view_emp_profile_script.php' method='post'><input type='text' value='"+data+"' name='emp_id'></form>"
                );
               $("#view_profile_form").submit();
               // window.location = $("#"+tr_id).find("a[id='"+id+"']").attr('href');

               // $("#"+tr_id).find("a[id='"+id+"']").html());
                //$("#"+id).attr("href","view_emp_profile.php");
             //  $(this).attr("href","view_emp_profile.php");
             }
            }
        }); 
         
     });


    // for uploading 201 file
    $("a[id='upload_201_file']").on("click", function () {
        var datastring =  "emp_id="+$(this).closest("tr").attr("id");
        var emp_id = $(this).closest("tr").attr("id");

       // alert("HELLO WORLD!");

         $.ajax({
            type: "POST",
            url: "ajax/script_emp_view_profile.php",
            data: datastring,
            cache: false,
           // datatype: "php",
            success: function (data) {
              //$("#edit_modal_body").html(data);
             // $("#editModal").modal("show");
             if (data == "Error" || data == 1){
                $("#errorModal").modal("show");
             }
             else {
                $("#201FileModal").modal("show");
                upload_201_files_emp_id = emp_id;

             }
            }
        }); 

    });


    
    // for upload profile picture
     $("div[class='change-profile-div']").on("click", function () {
            $("#change_profile_pic_modal").modal("show");        
    });


    $("input[name='profile_pic_file']").on("click", function () {
      
     });

    $("a[id='edit_emp_info']").on("click", function () {
        var datastring = "emp_id="+$(this).closest("tr").attr("id");
        $("#modal_body_update_info").html("<center><div class='loader'></div>Loading Information</center>");
        $.ajax({
            type: "POST",
            url: "ajax/script_update_information.php",
            data: datastring,
            cache: false,
           // datatype: "php",
            success: function (data) {

              //$("#edit_modal_body").html(data);
             // $("#editModal").modal("show");
             if (data == "Error" || data == 1){
                $("#errorModal").modal("show");
             }
             else {
                $("#modal_dialog_update_info").attr("class","modal-dialog modal-sm"); // for refreshing the size of modal
                $("#modal_body_update_info").html(data);
                $("#updadeEmpInfo").modal("show");
             }
            }
        }); 
     });


  
    // for profile uploading picture
     $("input[name='profile_pic_file']").change(function(evt){
       // var path = $(this).val();
      //  document.getElementById("profile_img").setAttribute("src",path);

        var tgt = evt.target || window.event.srcElement,
        files = tgt.files;

        // FileReader support
        if (FileReader && files && files.length) {
            var fr = new FileReader();
            fr.onload = function () {
              //  document.getElementById("profile_img").src = fr.result;

                 var valid_extensions = /(\.jpg|\.jpeg|\.png)$/i;
                 // if success
                
                 if (valid_extensions.test($("input[name='profile_pic_file']").val())){
                     // first append
                  $("#img_append").append('<img src="" class="" id="profile_img"/>');


                  $("#profile_img").attr("src", fr.result);
                  $("#profile_img").attr("class","img-temp-view ");
                  //document.getElementById("profile_img").
                 }
                 else {
                    $("#img_append").html(""); // sa image
                 }
            }
            fr.readAsDataURL(files[0]);
        }

        // Not supported
        else {
            // fallback -- perhaps submit the input to an iframe and temporarily store
            // them on the server until the user's session ends.
        }

      });  



        // submit_profile_pic change 
      $("button[id='submit_profile_pic']").on("click", function () {
         // $("#change_profile_form").ajaxSubmit(); 
         var valid_extensions = /(\.jpg|\.jpeg|\.png)$/i;
         // if failed
         if ($("input[name='profile_pic_file']").val() != ""){
           if (!valid_extensions.test($("input[name='profile_pic_file']").val())){
             
              $("#change_profile_msg").append('<div class="col-xs-12"><p style="color:#CB4335"><span class="glyphicon glyphicon-remove"></span> You must upload only image file</p></div>');

            }
          //  if success
          else {

            //  alert("Hello World!");

            //return false;
             $.ajax({
              type: "POST",
              url: "ajax/change_profile_pic_script.php",
              data: new FormData($('#change_profile_form')[0]),
              processData: false,
              contentType: false,
              success: function (data) {
                 // $("#change_profile_msg").html('<div class="col-xs-12"><p style="color:#196F3D"><span class="glyphicon glyphicon-ok"></span> You have successfully change your profile</p></div>');
                 // $("input[name='profile_pic_file']").val(""); // sa input type
                 // $("#img_append").html(""); // sa image

                 //alert(data);
                
                //  $("#div_image_display").html('<img src="'+data+'" class="profile-pic"/>');
                 location.reload(true); // for reloading the page
                   

              }


            });
           }
         }
         else {
             $("#change_profile_msg").html('<div class="col-xs-12"><p style="color:#CB4335"><span class="glyphicon glyphicon-remove"></span> Please upload image file</p></div>');
         }
       });


      // for restting values
      $("button[id='close_change_profile']").on("click", function () {
          $("#change_profile_msg").html(""); // sa msg
          $("input[name='profile_pic_file']").val(""); // sa input type
          $("#img_append").html(""); // sa image
      });


      // for inactive and active user
       $("a[id='status_emp_info']").on("click", function () {
           var datastring = "emp_id="+$(this).closest("tr").attr("id");
           var tr = $(this).closest("tr").attr("id");
           $("#active_stat_modal_body").html("<center><div class='loader'></div>Loading Information</center>");
           // if tr id is == 1
           if (tr == 1) {
                $("#errorModal").modal("show");
          }

          else {
            $.ajax({
                  type: "POST",
                  url: "ajax/append_active_status_modal.php",
                  data: datastring,
                  cache: false,
                 // datatype: "php",
                  success: function (data) {
                     $("#active_stat_modal_body").html(data);
                    // if edited by user
                    if (data == "<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There is an error during getting of data</center>"){
                      $("#active_inactive_stat_modal_footer").remove();
                    }
                      $("#active_inactive_emp_modal").modal("show");
                  }
              }); 
          }
       });

       // for active status
      $("a[id='active_stat_yes_emp_info']").on("click", function () {

          
          var resignation_date = "";
          if ($("input[name='resignation_date']").val() != undefined && $("input[name='resignation_date']").val() == ""){
              $("input[name='resignation_date']").focus();
              //$("input[name='resignation_date']").trigger("click");
              //$("input[name='resignation_date']").attr("required","required");
              //$("input[name='resignation_date']").closest("form").submit();
          }


          if ($("input[name='resignation_date']").val() == undefined){
            //alert("we");
            //alert("READY TO SUBMIT!");
            $(this).attr("href","php script/emp_active_status_script.php?resignation_date="+resignation_date);
            
          }


          if ($("input[name='resignation_date']").val() != ""){
              resignation_date = $("input[name='resignation_date']").val();
              $(this).attr("href","php script/emp_active_status_script.php?resignation_date="+resignation_date);
          }

          //$(this).attr("href","php script/emp_active_status_script.php");
      });


      // for updating BIO ID
      $("a[id='update_bio_id']").on("click", function () {
         var datastring = "emp_id="+$(this).closest("tr").attr("id");
         // append_update_bio_id_modal.php
         $("#modal_body_update_bio_id").html("<center><div class='loader'></div>Loading Information</center>");
          $.ajax({
              type: "POST",
              url: "ajax/append_update_bio_id_modal.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
                if (data == "Error"){
                  $("#update_errorModal").modal("show");
                }
                // if success
                else {
                  $("#modal_body_update_bio_id").html(data);
                  $("#updateBioIdModal").modal("show");
                }
                
              }
           });
      });


      // for Update attendance
       $("a[id='edit_bio_id']").on("click", function () {
         var datastring = "attendance_id="+$(this).closest("tr").attr("id");


         //alert("wew");

          $.ajax({
              type: "POST",
              url: "ajax/append_update_attendance.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
                if (data == "Error"){
                  $("#update_errorModal").modal("show");
                }
                // if success
                else {               
                  $("#modal_body_update_attendance").html(data);
                  $("#updateAttendanceModal").modal("show");
                }
                
              }
           });
        
      });


       // for approving request attendance 
      $("a[id='approve_request_attendance']").on("click", function () {
         var datastring = "attendance_notif_id="+$(this).closest("tr").attr("id") +"&approve=" + $(this).attr("title");
         $("#modal_body_update_request_attendance").html("<center><div class='loader'></div>Loading Information</center>");
          $.ajax({
              type: "POST",
              url: "ajax/script_update_request_attendance.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
               if (data == "Error") {
                  $("#update_errorModal").modal("show");
               }
               // if success
               else {
                  $("#modal_body_update_request_attendance").html(data);
                  $("#updateRequestUpdateAttendanceModal").modal("show");
               }

             }
           });
         
        
      });


      // for approving request attendance 
      $("a[id='approve_attendace_notification_page']").on("click", function () {
         var datastring = "attendance_notif_id="+$(this).closest("div").attr("id") +"&approve=" + $(this).attr("title");
         $("#modal_body_update_request_attendance").html("<center><div class='loader'></div>Loading Information</center>");
          $.ajax({
              type: "POST",
              url: "ajax/script_update_request_attendance.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
               if (data == "Error") {
                  $("#update_errorModal").modal("show");
               }
               // if success
               else {
                  $("#modal_body_update_request_attendance").html(data);
                  $("#updateRequestUpdateAttendanceModal").modal("show");
               }

             }
           });
         
        
      });


        // for approving request attendance 
      $("a[id='approve_request_leave_old']").on("click", function () {
         var datastring = "leave_id="+$(this).closest("tr").attr("id") +"&approve=" + $(this).attr("title");
         $("#modal_body_update_request_attendance").html("<center><div class='loader'></div>Loading Information</center>");
   
         $.ajax({
              type: "POST",
              url: "ajax/script_approve_file_leave.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
               if (data == "Error") {
                  $("#update_errorModal").modal("show");
               }
               // if success
               else {
                  $("#modal_body_update_request_attendance").html(data);
                  $("#updateRequestUpdateAttendanceModal").modal("show");
               }

             }
           });
         
        
      });


        // for approving request attendance 
      $("a[id='approve_request_leave']").on("click", function () {
         var datastring = "leave_id="+$(this).closest("div").attr("id") +"&approve=" + $(this).attr("title");
         $("#modal_body_update_request_attendance").html("<center><div class='loader'></div>Loading Information</center>");
   
         $.ajax({
              type: "POST",
              url: "ajax/script_approve_file_leave.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
               if (data == "Error") {
                  $("#update_errorModal").modal("show");
               }
               // if success
               else {
                  $("#modal_body_update_request_attendance").html(data);
                  $("#updateRequestUpdateAttendanceModal").modal("show");
               }

             }
           });
         
        
      });


      // 
      $("input[id='check_compen_to']").on("click", function () {

          // if check
          if ($(this).is(':checked')){
            $("input[name='compensationTo']").val("Over");
            $("input[name='compensationTo']").attr("readonly","readonly");
          }
          // if not check
          else {
            $("input[name='compensationTo']").val("");
            $("input[name='compensationTo']").removeAttr("readonly");
          }

      });

      // for edit sss contribution
       $("a[id='edit_sss_contrib']").on("click", function () {
         var datastring = "sss_contrib_id="+$(this).closest("tr").attr("id");
         $("#modal_body_update").html("<center><div class='loader'></div>Loading Information</center>");
         $.ajax({
              type: "POST",
              url: "ajax/append_update_sss_contribution.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
               if (data == "Error"){
                  $("#update_errorModal").modal("show");
                }
                // if success
                else {             
                  $("#modal_body_update").html(data);
                  $("#updateSSSContribModal").modal("show");
                }
                
              }
           });
          
        
      });

       // for deleting sss contribution
       $("a[id='delete_sss_contrib']").on("click", function () {
         var datastring = "sss_contrib_id="+$(this).closest("tr").attr("id");
         $("#modal_body_delete").html("<center><div class='loader'></div>Loading Information</center>");
         $.ajax({
              type: "POST",
              url: "ajax/append_delete_sss_contrib.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
               if (data == "Error"){
                  $("#update_errorModal").modal("show");
                }
                // if success
                else {             
                 $("#modal_body_delete").html(data);
                 $("#delete_modal").modal("show");
                }
                
              }
        });
          
        
      });

      // for delete contribution table     
      // for deleting sss contribution
       $("a[id='yes_contribution']").on("click", function () {
          $(this).attr("href","php script/delete_sss_contribution.php");
      });


      



        // for edit philhealth contribution
       $("a[id='edit_philhealth_contrib']").on("click", function () {
         var datastring = "philhealth_contrib_id="+$(this).closest("tr").attr("id");
         $("#modal_body_update").html("<center><div class='loader'></div>Loading Information</center>");
         $.ajax({
              type: "POST",
              url: "ajax/append_update_philhealth_contribution.php",
              data: datastring,
              cache: false,
              success: function (data) {

                // if has error 
               if (data == "Error"){
                  $("#update_errorModal").modal("show");
                }
                // if success
                else {             
                  $("#modal_body_update").html(data);
                  $("#updatePhilhealthContribModal").modal("show");
                }
                
              }
           }); 
          
        
      });

       // for deleting philhealth contribution
       $("a[id='delete_philhealth_contrib']").on("click", function () {
         var datastring = "philhealth_contrib_id="+$(this).closest("tr").attr("id");
         $("#modal_body_delete").html("<center><div class='loader'></div>Loading Information</center>");
         //alert(datastring);
         $.ajax({
              type: "POST",
              url: "ajax/append_delete_philhealth_contrib.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
               if (data == "Error"){
                  $("#update_errorModal").modal("show");
                }
                // if success
                else {             
                 $("#modal_body_delete").html(data);
                 $("#delete_modal").modal("show");
                }
                
              }
           });
          
        
      });

       //yes_delete_philhealth_contribution
        $("a[id='yes_delete_philhealth_contribution']").on("click", function () {
          $(this).attr("href","php script/delete_philhealth_contribution.php");
      });



          // for edit pag-ibig contribution
       $("a[id='edit_pagibig_contrib']").on("click", function () {
         var datastring = "pagibig_contrib_id="+$(this).closest("tr").attr("id");
         $("#modal_body_update").html("<center><div class='loader'></div>Loading Information</center>");
        // alert(datastring);
         $.ajax({
              type: "POST",
              url: "ajax/append_update_pagibig_contribution.php",
              data: datastring,
              cache: false,
              success: function (data) {

                // if has error 
               if (data == "Error"){
                  $("#update_errorModal").modal("show");
                }
                // if success
                else {             
                  $("#modal_body_update").html(data);
                  $("#updatePagibigContribModal").modal("show");
                }
                
              }
           }); 
          
        
      });


       // for update pag-ibig contribution 
       $("a[id='delete_pagibig_contrib']").on("click", function () {
         var datastring = "pagibig_contrib_id="+$(this).closest("tr").attr("id");
         $("#modal_body_delete").html("<center><div class='loader'></div>Loading Information</center>");
         //alert(datastring);
         $.ajax({
              type: "POST",
              url: "ajax/append_delete_pagibig_contrib.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
               if (data == "Error"){
                  $("#update_errorModal").modal("show");
                }
                // if success
                else {             
                 $("#modal_body_delete").html(data);
                 $("#delete_modal").modal("show");
                }
                
              }
           });
          
        
      });


        //yes_delete_pagibig_contribution
      $("a[id='yes_delete_pagibig_contribution']").on("click", function () {
          $(this).attr("href","php script/delete_pagibig_contribution.php");
      });
      


        // for FILE OT
        $("a[id='file_ot']").on("click", function () {
          var datastring = "attendance_id="+$(this).closest("tr").attr("id");
          // append_file_ot.php
           $.ajax({
              type: "POST",
              url: "ajax/append_file_ot.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
               if (data == "Error"){
                  $("#update_errorModal").modal("show");
                }
                // if success
                else {          
                  $("#modal_body_fileOT").html(data);
                  $("#fileOTModal").modal("show");
                }
                
              }
           });
      });


        // for selecting month
       $("select[name='holidayDate_month']").change(function(){
         var datastring = "month="+$(this).val();
         $("select[name='holidayDate_day']").html("<option value=''></option>");
         //alert(datastring);
         $.ajax({
              type: "POST",
              url: "ajax/append_month_date.php",
              data: datastring,
              cache: false,
             // datatype: "php",
              success: function (data) {
                // if has error
                if (data == "Error") {
                  $("#message").html("<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> There's an error during getting of data</center>");
                }
                // if success
                else {

                   $("select[name='holidayDate_day']").html(data);
                }
              }
          }); 

      });


        // for edit holiday
        $("a[id='edit_holiday']").on("click", function () {
          var datastring = "holiday_id="+$(this).closest("tr").attr("id");
          $("#modal_body_update_info").html("<center><div class='loader'></div>Loading Information</center>");
          // append_file_ot.php
          $.ajax({
              type: "POST",
              url: "ajax/append_update_holiday.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
                if (data == "Error"){
                  $("#update_errorModal").modal("show");
                }
                // if success
                else {       
                  $("#modal_body_update_info").html(data);
                  $("#updadeHolidayInfo").modal("show");
                }
                
              }
           });
      });


      // for delete holiday 
       $("a[id='delete_holiday']").on("click", function () {
          var datastring = "holiday_id="+$(this).closest("tr").attr("id");
           $("#delete_modal_body").html("<center><div class='loader'></div>Loading Information</center>");
           $.ajax({
              type: "POST",
              url: "ajax/append_delete_holiday.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
                if (data == "Error"){
                  $("#update_errorModal").modal("show");
                }
                // if success
                else {             
                 $("#delete_modal_body").html(data);
                 $("#deleteHolidayConfirmationModal").modal("show");
                }
                
              }
         });
      });


       // for delete delete_yes_holiday
      $("a[id='delete_yes_holiday']").on("click", function () {
          $(this).attr("href","php script/delete_holiday.php");
      });

     // $( "input[name='attendance_date_ot']").focusout(function() {
    /*   $("select[name='type_ot']").focus(function() {

        //alert("Hello World!");
        if ($("input[name='attendance_date_ot']").val() != ""){

            var datastring = "date="+$("input[name='attendance_date_ot']").val(); 
            $("select[name='type_ot']").html("<option value=''></option>");
            //$("select[name='type_ot']").html("<option id='loader'><div class=loader></div>Loading Information</center></option>");
            $.ajax({
                type: "POST",
                url: "ajax/append_type_overtime.php",
                data: datastring,
                cache: false,
                success: function(data) {
                  // if has error 
                  $("select[name='type_ot']").html(data);
                  
                }
           });
          }
      });


      // $( "input[name='attendance_date_ot']").focusout(function() {
       $("input[name='attendance_date_ot']").focusout(function() {
          var datastring = "date="+$(this).val();
         // alert("Hello World!");
       //   alert(datastring);
          $.ajax({
              type: "POST",
              url: "ajax/append_type_overtime.php",
              data: datastring,
              cache: false,
              success: function(data) {
                // if has error 
                $("select[name='type_ot']").html(data);
                
              }
         });
      });
      */

  


      /*
       // for changing attendance ot
      $("input[name='attendance_date_ot']").change(function() {
          var datastring = "date="+$(this).val();
          //alert("Hello World!");
          $.ajax({
              type: "POST",
              url: "ajax/append_type_overtime.php",
              data: datastring,
              cache: false,
              success: function(data) {

                $("textarea[name='remarks']").val(data);
               // alert(datastringta);
                // if has error 
                // $("select[name='type_ot']").html(data);
              

              }
         });
          
         // $("input[name='hour_time_in']").attr("disabled","disabled");
      });
*/


      // for approve attendance OT
        $("a[id='approve_ot_request']").on("click", function () {
         var datastring = "attendance_ot_id="+$(this).closest("tr").attr("id") + "&approve=" + $(this).attr("title");
        
         //alert("wew");
         $("#modal_body_update_request_attendance").html("<center><div class='loader'></div>Loading Information</center>");
          $.ajax({
              type: "POST",
              url: "ajax/append_approve_overtime.php",
              data: datastring,
              cache: false,
              success: function(data) {
              //  alert(data);
                // if has error 
                if (data == "Error") {
                  $("#update_errorModal").modal("show");
                }
                // if success
                else {
                
                  $("#modal_body_update_request_attendance").html(data);
                  $("#updateRequestUpdateAttendanceModal").modal("show");
                }
                
              }
         });
      });

      // for approve attendance OT at attendance notif page
      $("a[id='approve_attendace_notification_page']").on("click", function () {
         var datastring = "attendance_ot_id="+$(this).closest("div").attr("id") + "&approve=" + $(this).attr("title");
         $("#modal_body_update_request_attendance").html("<center><div class='loader'></div>Loading Information</center>");
          $.ajax({
              type: "POST",
              url: "ajax/append_approve_overtime.php",
              data: datastring,
              cache: false,
              success: function(data) {
              //  alert(data);
                // if has error 
                if (data == "Error") {
                  $("#update_errorModal").modal("show");
                }
                // if success
                else {
                
                  $("#modal_body_update_request_attendance").html(data);
                  $("#updateRequestUpdateAttendanceModal").modal("show");
                }
                
              }
         });
      });


      // createPayroll
       $("a[id='chooseEmployee']").on("click", function () {
           var datastring = "emp_id="+$(this).closest("tr").attr("id");
            $.ajax({
              type: "POST",
              url: "ajax/append_emp_info_payroll.php",
              data: datastring,
              cache: false,
              success: function(data) {
              //  alert(data);
                // if has error 
                $("#emp_list_modal").modal("hide");
                if (data == "Error") {
                  $("#update_errorModal").modal("show");
                }
                // if success
                else {
                  //alert(data);
                  var payroll_values = data.split("#");
                  $("input[name='employeeID']").val(payroll_values[0]);
                  $("input[name='employeeName']").val(payroll_values[1]);
                  $("input[name='empDepartment']").val(payroll_values[2]);
                  $("input[name='basicSalary']").val(payroll_values[3]);
                  $("input[name='regularOT']").val(payroll_values[4]);
                  $("input[name='holidayOT']").val(payroll_values[5]); 
                  $("input[name='restdayOT']").val(payroll_values[6]); // 
                  $("input[name='holidayRestdayOT']").val(payroll_values[7]); 
                  $("input[name='tardiness']").val(payroll_values[8]); 
                  $("input[name='absent']").val(payroll_values[9]); 
                  $("input[name='allowance']").val(payroll_values[10]);  // ililipat pla dapat standalone
                  $("input[name='totalGrossIncome']").val(payroll_values[11]); 
                  $("input[name='sssContribution']").val(payroll_values[12]); 
                  $("input[name='philhealthContribution']").val(payroll_values[13]); 
                  $("input[name='pagibigContribution']").val(payroll_values[14]); 
                  $("input[name='cashBond']").val(payroll_values[15]); 
                 // alert(data);
                 // $("#modal_body_update_request_attendance").html(data);
                  //$("#updateRequestUpdateAttendanceModal").modal("show");


                  // for refreshing the value
                  $("input[name='totalDeductions']").val(""); 
                  $("input[name='taxableIncome']").val(""); 
                  $("input[name='tax']").val(""); 
                  $("input[name='netPay']").val("");
                  $("#message").html("");
                }
                
              }
         });


      });

      // for choose employee 
      $("a[id='choose_employee']").on("click", function () {
          $("#emp_list_modal").modal("show");
      });

     // compute_payroll
      $("button[id='compute_payroll']").on("click", function () {
          var emp_name = $("input[name='employeeName']").val();
          var department = $("input[name='empDepartment']").val();
          var basicSalary = $("input[name='basicSalary']").val();
          var regularOT = $("input[name='regularOT']").val();
          var holidayOT = $("input[name='holidayOT']").val();
          var restdayOT = $("input[name='restdayOT']").val();
          var holidayRestdayOT = $("input[name='holidayRestdayOT']").val();
          var tardiness = $("input[name='tardiness']").val();
          var absent = $("input[name='absent']").val();
          var totalGrossIncome = $("input[name='totalGrossIncome']").val();
          var allowance = $("input[name='allowance']").val();
          var sssContribution = $("input[name='sssContribution']").val();
          var philhealthContribution = $("input[name='philhealthContribution']").val();
          var pagibigContribution = $("input[name='pagibigContribution']").val();
          var sssLoan = $("input[name='sssLoan']").val();
          var pagibigLoan = $("input[name='pagibigLoan']").val();
          var cashAdvance = $("input[name='cashAdvance']").val();
          //var totalDeductions = $("input[name='totalDeductions']").val();
          //var taxableIncome = $("input[name='taxableIncome']").val();
          //var tax = $("input[name='tax']").val();
          //var netPay = $("input[name='netPay']").val();

          if (emp_name == "" || department == "" || basicSalary == "" || regularOT == "" ||holidayOT == ""||restdayOT == "" ||holidayRestdayOT == "" || tardiness == "" ||absent == "" ||totalGrossIncome == "" || allowance == "" || sssContribution == "" ||philhealthContribution == "" || pagibigContribution == "" || sssLoan == "" ||pagibigLoan == ""||cashAdvance == ""){
               $("#message").html("<span class='glyphicon glyphicon-remove' style='color:#b03a2e'></span> Please fill up required fields!");
          }
          else {
              sssContribution =  sssContribution.slice(3);
              philhealthContribution =  philhealthContribution.slice(3);
              pagibigContribution =  pagibigContribution.slice(3);


              var totalDeductions = parseFloat(sssContribution) + parseFloat(philhealthContribution) + parseFloat(pagibigContribution) + parseFloat(sssLoan) + parseFloat(pagibigLoan) + parseFloat(cashAdvance);
              // for 2 decimal places
              totalDeductions = totalDeductions.toString().split('e');
              totalDeductions = Math.round(+(totalDeductions[0] + 'e' + (totalDeductions[1] ? (+totalDeductions[1] + 2) : 2)));

              totalDeductions = totalDeductions.toString().split('e');
              final_totalDeductions=  (+(totalDeductions[0] + 'e' + (totalDeductions[1] ? (+totalDeductions[1] - 2) : -2))).toFixed(2);



              totalGrossIncome =  totalGrossIncome.slice(3);



              $("input[name='totalDeductions']").val("Php " + final_totalDeductions);


             
              var taxableIncome = parseFloat(totalGrossIncome) - parseFloat(final_totalDeductions);
               // for 2 decimal places
              taxableIncome = taxableIncome.toString().split('e');
              taxableIncome = Math.round(+(taxableIncome[0] + 'e' + (taxableIncome[1] ? (+taxableIncome[1] + 2) : 2)));

              taxableIncome = taxableIncome.toString().split('e');
              final_taxableIncome =  (+(taxableIncome[0] + 'e' + (taxableIncome[1] ? (+taxableIncome[1] - 2) : -2))).toFixed(2);

              $("input[name='taxableIncome']").val("Php " + final_taxableIncome); 



              // reserve for checking if taxable so deduct tapos check if not so wla
              var datastring = "taxable_income=" + (parseFloat(totalGrossIncome) - parseFloat(final_totalDeductions)) +"&emp_id=" + $("input[name='employeeID']").val() + "&emp_name=" + $("input[name='employeeName']").val();
             // alert(datastring);
              $.ajax({
                type: "POST",
                url: "ajax/append_tax_value.php",
                data: datastring,
                cache: false,
                success: function(data) {
                    // if failed
                    if (data == "Error"){
                        location.reload();
                    }
                    // if success
                    else {
                      $("input[name='tax']").val(data);
                      var tax =  data.slice(3);
                      var allowance = $("input[name='allowance']").val().slice(3);
                      var cashBond = $("input[name='cashBond']").val().slice(3);
                      var adjustment = $("input[name='adjustment']").val();
                      var netPay = (parseFloat(allowance) + parseFloat(adjustment)) + (parseFloat(totalGrossIncome) - parseFloat(final_totalDeductions) - parseFloat(tax) - parseFloat(cashBond));

                       // for 2 decimal places
                      netPay = netPay.toString().split('e');
                      netPay = Math.round(+(netPay[0] + 'e' + (netPay[1] ? (+netPay[1] + 2) : 2)));

                      netPay = netPay.toString().split('e');
                      final_netPay =  (+(netPay[0] + 'e' + (netPay[1] ? (+netPay[1] - 2) : -2))).toFixed(2);

                      $("input[name='netPay']").val("Php " + final_netPay);
                      $("#message").html("<button type='submit' class='btn btn-primary pull-right' id='save_payroll'>Save Payroll</button>");
                    }
                   // alert(data);
                }
             }); 

          }
      });


       // for edit bir contribution
       $("a[id='edit_bir_contrib']").on("click", function () {
         var datastring = "bir_contrib_id="+$(this).closest("tr").attr("id");
         $("#modal_body_update").html("<center><div class='loader'></div>Loading Information</center>");
        // alert(datastring);
         $.ajax({
              type: "POST",
              url: "ajax/append_update_bir_contribution.php",
              data: datastring,
              cache: false,
              success: function (data) {

                // if has error 
               if (data == "Error"){
                  $("#update_errorModal").modal("show");
                }
                // if success
                else {       
                  $("#modal_body_update").html(data);
                  $("#updateBIRContribModal").modal("show");
                }
                
              }
           }); 
          
        
      });


       // 
        // for delete bir contribution 
       $("a[id='delete_bir_contrib']").on("click", function () {
         var datastring = "bir_contrib_id="+$(this).closest("tr").attr("id");
         $("#modal_body_delete").html("<center><div class='loader'></div>Loading Information</center>");
         //alert(datastring);
         $.ajax({
              type: "POST",
              url: "ajax/append_delete_bir_contrib.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
               if (data == "Error"){
                  $("#update_errorModal").modal("show");
                }
                // if success
                else {       
                  $("#modal_body_delete").html(data);
                  $("#delete_modal").modal("show");
                }
                
              }
           });
          
        
      });


        // for delete delete_yes_holiday
      $("a[id='delete_yes_birContribution']").on("click", function () {
          $(this).attr("href","php script/delete_bir_contrib.php");
      });



      // for submitting/ inserting payroll information add_payroll_info.php
       $("a[id='print_myPayslip']").on("click", function () {
          var datastring = "payroll_id="+$(this).closest("tr").attr("id");
          $.ajax({
              type: "POST",
              url: "ajax/script_print_payroll.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
               if (data == "Error"){
                  $("#update_errorModal").modal("show");
                }
                // if success
                else {       
                  //$("#modal_body_delete").html(data);
                  //$("#delete_modal").modal("show");
                  //$("#submit_div").html(data);
                  //$("#print_payslip_form").submit();
                  window.location = "my_payslip_reports.php";
                }
                
              }
           });
       });


       // for removing multiple attribute of file if accept only one
        $("input[name='image']").on("click", function () {
            $(this).removeAttr('multiple');
       });

        // profile_pic_file
        $("input[name='profile_pic_file']").on("click", function () {
            $(this).removeAttr('multiple');
       });


       // edit_events
       $("a[id='edit_events']").on("click", function () {
          var datastring = "events_id="+$(this).closest("tr").attr("id");
          $("#edit_modal_body").html("<center><div class='loader'></div>Loading Information</center>");
          $.ajax({
              type: "POST",
              url: "ajax/append_update_events.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
               if (data == "Error"){
                  $("#update_errorModal").modal("show");
                }
                // if success
                else {       
                  $("#edit_modal_body").html(data);
                  $("#editModal").modal("show");
                }
                
              }
           });
       });


       // for delete delete_events
       $("a[id='delete_events']").on("click", function(){
          var datastring = "events_id="+$(this).closest("tr").attr("id");
          $("#delete_modal_body").html("<center><div class='loader'></div>Loading Information</center>");
          //alert(datastring); 
          $.ajax({
              type: "POST",
              url: "ajax/append_delete_events.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
               if (data == "Error"){
                  $("#update_errorModal").modal("show");
                }
                // if success
                else {       
                  $("#delete_modal_body").html(data);
                  $("#deleteEventConfirmationModal").modal("show");
                }
                
              }
           });
      });

       // 
       // for DELETING YES BUTTON
      $("#delete_yes_event").on("click", function () {
          $(this).attr("href","php script/delete_events.php");
      }); 


        // for print information
    $("a[id='print_emp_info']").on("click", function () {
        var datastring =  "emp_id="+$(this).closest("tr").attr("id");

        var tr = $(this).closest("tr").attr("id");

        // 
        if (tr == 1){
          $("#errorModal").modal("show");
        }
        else {
           // alert(datastring);
             $.ajax({
                type: "POST",
                url: "ajax/script_print_emp_info.php",
                data: datastring,
                cache: false,
               // datatype: "php",
                success: function (data) {
                  //$("#edit_modal_body").html(data);
                 // $("#editModal").modal("show");
                 if (data == "Error" || data == 1){
                    $("#errorModal").modal("show");
                 }
                 else {

                    //$("#201FileModal").modal("show");
                    //$("input[name='emp_id']").val(data);
                    
                     window.location = "emp_info_reports.php";
                    //$("a[id='print_emp_info']").trigger("click");
                    //$("#print_emp_info_form").submit();
                    
                 }
                }
            }); 
         }

    });

    // for adding minimum wage 
     $("button[id='submit_min_wage']").on("click", function () {
        
        if ($("input[name='basicWage']").val() != "" && $("input[name='cola']").val() != "" && $("input[name='effectiveDate']").val() != ""){
            // php script/add_min_wage.php
            $("#min_wage_form" ).submit(function(event) {          
              event.preventDefault();
             
            });
             $("#active_stat_modal_body").html("<span class='glyphicon glyphicon-info-sign' style='color:#3498db '></span> Are you sure you want to add the latest <b>minimum wage</b> with <b>basic wage</b> of <b>Php "+$("input[name='basicWage']").val()+"</b>, <b>COLA</b> of <b>Php " +$("input[name='cola']").val()+ "</b> and <b>effect date</b> of <b>"+$("input[name='effectiveDate']").val() + "</b>?");
             $("#submitMinWageConfirmation").modal("show");
        }

    });

     // for submitting the form
      $("a[id='submit_yes_min_wage']").on("click", function () {
       // $("#min_wage_form").attr("action","php script/add_min_wage.php");
          $("#min_wage_form" ).unbind().submit();       

      });




     //edit_latest_min_wage
    $("a[id='edit_latest_min_wage']").on("click", function () {
        $("#updateLatestMinWage").html("<center><div class='loader'></div>Loading Information</center>");  
        $.ajax({
            type: "POST",
            url: "ajax/append_edit_latest_min_wage.php",
           // data: datastring,
            cache: false,
           // datatype: "php",
            success: function (data) {
             // alert(data);
             //$("tr[id='edit_min_wage_tr']").html(data);  
             $("#updateLatestMinWage").html(data);     
            }
        }); 

    });


    // remove_update_min_wage_form
    $("div[id='updateLatestMinWage']").on("click","a[id='remove_update_min_wage_form']", function () {
        $("#updateLatestMinWage").html(""); 
    });


    // for delete latest min wage
    $("a[id='delete_latest_min_wage']").on("click",function () {
        $("#updateLatestMinWage").html("");
        $("#deleteConfirmModal").modal("show");
    });


    // for delete latest min wage button yes 
     $("a[id='delete_yes_min_wage']").on("click",function () {
         window.location = "php script/delete_latest_min_wage.php";
    });


     // hovering an unfield entry in database  employee list
     $("label[id='unfill_fields']").mouseover(function(){

        $(this).after("<div id='unfields_info' class='unfill-fields-info'><center><div class=loader></div>Loading Information</center></div>"); // just commenting but if will live it will be comment out


        var datastring = "emp_id="+$(this).closest("tr").attr("id");
        var tr_id =$(this).closest("tr").attr("id");
          $.ajax({
            type: "POST",
            url: "ajax/append_unfillUp_fields.php",
            data: datastring,
            cache: false,
            async: true,
           // datatype: "php",
            success: function (data) {

              // if has an error
              if (data == "Error"){
                $("#errorModal").modal("show");
              }
              else {
               // alert("wew");
                $("div[id='unfields_info']").html(data);
              }

               //$("label[id='unfill_fields']").after();
            }
        }); 
      

        
         

       });

     $("label[id='unfill_fields']").mouseout(function(){

       //$("#pop-up").hide();
       $("div[id='unfields_info']").remove();

     });


     // for view employment history modal
     // 
     $("a[id='view_emp_history_info']").on("click",function () {
         var datastring = "emp_id="+$(this).closest("tr").attr("id");
         
         $("#lfc_history_modal_body").html("<center><div class='loader'></div>Loading Information</center>");

         var tr = $(this).closest("tr").attr("id");

         if (tr == 1){
            $("#errorModal").modal("show");
         }

         else {
             $.ajax({
                type: "POST",
                url: "ajax/append_view_lfc_emp_history.php",
                data: datastring,
                cache: false,
               // datatype: "php",
                success: function (data) {

                  // if has an error
                  if (data == "Error"){
                    $("#errorModal").modal("show");
                  }
                  else {
                    $("#lfc_history_modal_body").html(data);
                    $("#view_emp_lfc_history_modal").modal("show");
                  }

                   //$("label[id='unfill_fields']").after();
                }
             }); 
         }
    });

     // change_password_a , for refreshing values
    $("a[id='change_password_a']").on("click",function () {
        $("input[name='currentPassword']").val("");
        $("input[name='newPassword']").val("");
        $("input[name='confirmPassword']").val("");
         $("#change_password_message").html("");
     });

    // for submit change password btn 
    $("input[id='submit_change_password']").on("click",function () {

        if ($("input[name='currentPassword']").val() != "" && $("input[name='newPassword']").val() != "" && $("input[name='confirmPassword']").val() != ""){
          $("#form_change_password").submit(function(event) {          
              event.preventDefault();
             
          });

          // $("#change_password_message").html('<div class=loader></div>Loading Information'); // just commenting but if will live it will be comment out



          // for ajax purpose of getting the change password information , need na rin pla ng loader ngaun :D
          var currentPassword = $("input[name='currentPassword']").val();
          var newPassword = $("input[name='newPassword']").val();
          var confirmPassword = $("input[name='confirmPassword']").val();
           $("#change_password_message").html("<center><div class='loader'></div>Loading Information</center>");
          // datastring
         var datastring = "currentPassword=" + currentPassword + "&newPassword=" + newPassword + "&confirmPassword="+confirmPassword;
         $.ajax({
                type: "POST",
                url: "ajax/append_change_password_info.php",
                data: datastring,
                cache: false,
               // datatype: "php",
                success: function (data) {
                  $("#change_password_message").html(data);
                 
                }
             }); 
        }

    });

  // update_atm_record , for update atm records
    $("a[id='update_atm_record']").on("click",function () {
        var datastring = "emp_id="+$(this).closest("tr").attr("id");
        $("#atm_record_modal_body").html("<center><div class='loader'></div>Loading Information</center>");
         $.ajax({
                type: "POST",
                url: "ajax/append_update_atm_record.php",
                data: datastring,
                cache: false,
               // datatype: "php",
                success: function (data) {
                    
                  // if has an error
                  if (data == "Error" || data == 1){
                     $("#errorModal").modal("show");
                  }
                  // if success
                  else {
                    $("#atm_record_modal_body").html(data);
                    $("#updateATM_modal").modal("show");
                  }
                 
                }
             }); 
        

        


    });


    // if yes is click update_atm_stat_emp_yes
    $("input[id='update_atm_stat_emp_yes']").on("click",function () {


      

      if ($("input[name='atmNo']").val() != null){
          
          $("input[name='atmNo']").attr("required","required");
          $("#form_updateAtmRecord" ).submit(function(event) {          
           event.preventDefault();    
         });  


          if ($("input[name='atmNo']").val() == ""){
            
          }

          else if ($("input[name='atmNo']").val() != "" && $("input[name='atmNo']").val().length != 12) {
            $("div[id='errorMessage']").html('<span class="glyphicon glyphicon-remove" style="color:#CB4335"></span> Please enter a 12 digit account number');    
          }
          else {
              $("#form_updateAtmRecord").attr("action","php script/update_emp_atm_status.php");
              $("#form_updateAtmRecord" ).unbind().submit(); 
          }    
      }

      else {
          $("#form_updateAtmRecord").attr("action","php script/update_emp_atm_status.php");
          $("#form_updateAtmRecord" ).unbind().submit(); 
      }

       
       // $("#form_updateAtmRecord").submit();
        //if()

        //$(this).attr("href","php script/update_emp_atm_status.php");
    });


    // for choosing employee in pag-ibig existing loan choose_employee_pagibig_loan
    $("a[id='choose_employee_pagibig_loan']").on("click",function () {
        $("#emp_list_modal").modal("show");
    });


    // for choosing employee to sss loan
    $("a[id='choose_employee_sss_loan']").on("click",function () {
        $("#emp_list_modal").modal("show");
    });

    // for choosing employee to salary loan
    $("a[id='choose_employee_salary_loan']").on("click",function () {
        $("#emp_list_modal").modal("show");
    });


    // for choosing memo specific employee choose_employee_memo
    var emp_id_count = 0;
    $("div[id='choose1']").on("click","a[id='choose_employee_memo']",function () {
        $("#emp_list_modal").modal("show");

        emp_id_count = $(this).closest("div").attr("id").slice(6,7);
    });

    



   

    // for choosing employee name dynamic chooseEmployee
     $("a[id='chooseEmployee']").on("click",function () {
        // empName
        var datastring = "emp_id="+$(this).closest("tr").attr("id");

        var emp_id = $(this).closest("tr").attr("id");

        // datastring
          $.ajax({
                type: "POST",
                url: "ajax/append_emp_name.php",
                data: datastring,
                cache: false,
               // datatype: "php",
                success: function (data) {

                  $("#emp_list_modal").modal("hide");
                    
                  // if has an error
                  if (data == "Error" || data == 1){
                     $("#errorModal").modal("show");
                  }
                  // if success
                  else {
                    $("input[name='empName']").val(data);
                    add_pagibigLoan_emp_id = emp_id;
                   // alert(global_emp_id);
                   // $("#atm_record_modal_body").html(data);
                   // $("#updateATM_modal").modal("show");
                   
                  }
                 
                }
           }); 
    });


     // for choosing employee name dynamic chooseEmployee
     $("a[id='chooseEmployee']").on("click",function () {
        // empName
        var datastring = "emp_id="+$(this).closest("tr").attr("id");

        var emp_id = $(this).closest("tr").attr("id");

        // datastring
          $.ajax({
                type: "POST",
                url: "ajax/append_emp_name.php",
                data: datastring,
                cache: false,
               // datatype: "php",
                success: function (data) {

                  $("#emp_list_modal").modal("hide");
                    
                  // if has an error
                  if (data == "Error" || data == 1){
                     $("#errorModal").modal("show");
                  }
                  // if success
                  else {
                    $("input[name='empName']").val(data);
                    add_ssssLoan_emp_id = emp_id;
                   // alert(add_ssssLoan_emp_id);
                   // alert(global_emp_id);
                   // $("#atm_record_modal_body").html(data);
                   // $("#updateATM_modal").modal("show");
                   
                  }
                 
                }
           }); 
    });


  // for choosing employee name dynamic chooseEmployee for salary loan
     $("a[id='chooseEmployee']").on("click",function () {
        // empName
        var datastring = "emp_id="+$(this).closest("tr").attr("id");

        var emp_id = $(this).closest("tr").attr("id");

        // datastring
          $.ajax({
                type: "POST",
                url: "ajax/append_emp_name.php",
                data: datastring,
                cache: false,
               // datatype: "php",
                success: function (data) {

                  $("#emp_list_modal").modal("hide");
                    
                  // if has an error
                  if (data == "Error" || data == 1){
                     $("#errorModal").modal("show");
                  }
                  // if success
                  else {
                    $("input[name='empName']").val(data);
                    add_salaryLoan_emp_id = emp_id;
                   // alert(add_ssssLoan_emp_id);
                   // alert(global_emp_id);
                   // $("#atm_record_modal_body").html(data);
                   // $("#updateATM_modal").modal("show");
                   
                  }
                 
                }
           }); 
    });



    // for choosing employee name dynamic chooseEmployee for simkimban
     $("a[id='chooseEmployee']").on("click",function () {
        // empName
        var datastring = "emp_id="+$(this).closest("tr").attr("id");

        var emp_id = $(this).closest("tr").attr("id");

        // datastring
          $.ajax({
                type: "POST",
                url: "ajax/append_emp_name.php",
                data: datastring,
                cache: false,
               // datatype: "php",
                success: function (data) {

                  $("#emp_list_modal").modal("hide");
                    
                  // if has an error
                  if (data == "Error" || data == 1){
                     $("#errorModal").modal("show");
                  }
                  // if success
                  else {
                    $("input[name='empName']").val(data);
                    add_simkimban_emp_id = emp_id;
                   // alert(global_emp_id);
                   // $("#atm_record_modal_body").html(data);
                   // $("#updateATM_modal").modal("show");
                   
                  }
                 
                }
           }); 
    });
  

     // for choosing employee name dynamic chooseEmployee for memorandum
     $("a[id='chooseEmployee']").on("click",function () {
        // empName
        var datastring = "emp_id="+$(this).closest("tr").attr("id");
       // alert("");

        var emp_id = $(this).closest("tr").attr("id");

        // datastring
          $.ajax({
                type: "POST",
                url: "ajax/append_emp_name.php",
                data: datastring,
                cache: false,
               // datatype: "php",
                success: function (data) {

                  $("#emp_list_modal").modal("hide");
                    
                  // if has an error
                  if (data == "Error" || data == 1){
                     $("#errorModal").modal("show");
                  }
                  // if success
                  else {
                   // alert(emp_id_count);
                    $("input[name='to"+emp_id_count+"']").val(data);
                    //add_memo_emp_id = emp_id;
                    
                   // $("#atm_record_modal_body").html(data);
                   // $("#updateATM_modal").modal("show");
                   
                  }
                 
                }
           }); 
    });


  

    // for pag-ibig loan submit input submit submitPagibigLoan
     $("input[id='submitPagibigLoan']").on("click",function () {

        var dateformat = /^(0[1-9]|1[012])[\/\-](0[1-9]|[12][0-9]|3[01])[\/\-]\d{4}$/; 

      // for backing its required field
        $("input[name='empName']").attr("required","required");
        $("input[name='dateFrom']").attr("required","required");
        $("input[name='dateTo']").attr("required","required");
        $("input[name='amountLoan']").attr("required","required");
        $("input[name='decution']").attr("required","required"); // 
        $("input[name='remainingBalance']").attr("required","required");


        // empName
        if ($("input[name='empName']").val() != "" && $("input[name='dateFrom']").val() != "" && $("input[name='dateTo']").val() != "" && $("input[name='amountLoan']").val() != "" && $("input[name='decution']").val() != "" && $("input[name='remainingBalance']").val() != ""){
            // alert(global_emp_id);

            var dateFrom = $("input[name='dateFrom']").val();
           var dateTo = $("input[name='dateTo']").val();

            // if does not match either one of the two or both
             if (!dateFrom.match(dateformat) || !dateTo.match(dateformat)) {
                $("#error_modal_body").html("<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid Date From or Date To</center>");
                $("#errorModal").modal("show");
                $("#form_addPagibigLoan" ).submit(function(event) {          
                  event.preventDefault();
                 
                });
            }

            else {
               $("#form_addPagibigLoan" ).unbind().submit(); 
               $("input[name='empName']").after("<input type='hidden' name='empId' value='"+add_pagibigLoan_emp_id+"' />");
               $("#form_addPagibigLoan").attr("action","php script/add_pagibigLoan.php");
             }
        }

        

       
    });


    // for adding loan for sss loan button submit submitSSSLoan
    $("input[id='submitSSSLoan']").on("click",function () {

        var dateformat = /^(0[1-9]|1[012])[\/\-](0[1-9]|[12][0-9]|3[01])[\/\-]\d{4}$/; 

      // for backing its required field
        $("input[name='empName']").attr("required","required");
        $("input[name='dateFrom']").attr("required","required");
        $("input[name='dateTo']").attr("required","required");
        $("input[name='amountLoan']").attr("required","required");
        $("input[name='decution']").attr("required","required"); // 
        $("input[name='remainingBalance']").attr("required","required");


        // empName
        if ($("input[name='empName']").val() != "" && $("input[name='dateFrom']").val() != "" && $("input[name='dateTo']").val() != "" && $("input[name='amountLoan']").val() != "" && $("input[name='decution']").val() != "" && $("input[name='remainingBalance']").val() != ""){
            // alert(global_emp_id);
            var dateFrom = $("input[name='dateFrom']").val();
            var dateTo = $("input[name='dateTo']").val();

            // if does not match either one of the two or both
             if (!dateFrom.match(dateformat) || !dateTo.match(dateformat)) {
                $("#error_modal_body").html("<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid Date From or Date To</center>");
                $("#errorModal").modal("show");
                $("#form_addSSSLoan" ).submit(function(event) {          
                  event.preventDefault();
                 
                });
            }

            else {
              $("#form_addSSSLoan" ).unbind().submit();
              $("input[name='empName']").after("<input type='hidden' name='empId' value='"+add_ssssLoan_emp_id+"' />");
              $("#form_addSSSLoan").attr("action","php script/add_sssLoan.php");
           }
        }

        

       
    });


   // for adding loan for sss loan button submit submitSalaryLoan
    $("input[id='submitSalaryLoan']").on("click",function () {

      // for backing its required field
        $("input[name='empName']").attr("required","required");
        $("input[name='dateFrom']").attr("required","required");
        $("input[name='dateTo']").attr("required","required");
        $("input[name='amountLoan']").attr("required","required");
        $("input[name='decution']").attr("required","required"); // 
        $("input[name='remainingBalance']").attr("required","required");


        // empName
        if ($("input[name='empName']").val() != "" && $("input[name='dateFrom']").val() != "" && $("input[name='dateTo']").val() != "" && $("input[name='amountLoan']").val() != "" && $("input[name='decution']").val() != "" && $("input[name='remainingBalance']").val() != "" && $("textarea[name='remarks']").val() != ""){
            // alert(global_emp_id);
             $("input[name='empName']").after("<input type='hidden' name='empId' value='"+add_salaryLoan_emp_id+"' />");
             $("#form_addSalaryLoan").attr("action","php script/add_salaryLoan.php");
        }

        

       
    });


    // for adding loan for sss loan button submit submitSalaryLoan
    $("input[id='submitSimkimban']").on("click",function () {

      // for backing its required fields
        $("input[name='empName']").attr("required","required");
        $("input[name='dateFrom']").attr("required","required");
        $("input[name='dateTo']").attr("required","required"); //item
        $("input[name='item']").attr("required","required");
        $("input[name='amountLoan']").attr("required","required");
        $("input[name='decution']").attr("required","required"); // 
        $("input[name='remainingBalance']").attr("required","required");


        // empName
        if ($("input[name='empName']").val() != "" && $("input[name='dateFrom']").val() != "" && $("input[name='dateTo']").val() != "" && $("input[name='item']").val() != "" && $("input[name='amountLoan']").val() != "" && $("input[name='deductionType']").val() != "" && $("input[name='remainingBalance']").val() != ""){
            // alert(global_emp_id);
             $("input[name='empName']").after("<input type='hidden' name='empId' value='"+add_simkimban_emp_id+"' />");
             $("#form_addSimkimbanLoan").attr("action","php script/add_manual_simkimban_loan.php");
        }

        

       
    });



     // for adding loan for sss loan button submit submitSalaryLoan
      $("input[id='submitMemo']").on("click",function () {

          //alert("HELLO WORLD!");

          // for backing its required field
          $("input[name='optRecipient1']").attr("required","required");

          if ($("input[name='optRecipient1']:checked").val() == "Specific Employee" || $("input[name='optRecipient']:checked").val() == "Department"){
              $("input[name='to1']").attr("required","required");
          }
          $("input[name='subject']").attr("required","required");
          $("input[name='content']").attr("required","required");
          
          var valid_extensions = /(\.jpg|\.jpeg|\.png)$/i;

          if ($("input[name='optRecipient1']:checked").val() == "All") {
             if ($("input[name='subject']").val() != ""  && $("input[name='content']").val() != ""){
                // alert(global_emp_id);
                // $("input[name='empName']").after("<input type='hidden' name='empId' value='"+add_simkimban_emp_id+"' />");
                // if failed
         
                if (!valid_extensions.test($("input[name='memo_upload_img[]']").val()) && $("input[name='memo_upload_img[]']").val() != ""){
             
                  $("#error_msg").html('<div class="col-xs-12"><p style="color:#CB4335"><span class="glyphicon glyphicon-remove"></span> You must upload only image file</p></div>');

                }

                else {
            
                  $("#form_addMemo").append("<input type='hidden' name='count' value='"+memoRecipientCount+"' />");
                  $("#form_addMemo").attr("action","php script/add_memo.php");
                }
               // alert("Ready For Submition");
             }
          }

          if ($("input[name='optRecipient1']:checked").val() == "Specific Employee") {
               if ($("input[name='to1']").val() != "" && $("input[name='subject']").val() != "" &&  $("input[name='content']").val() != ""){
                 
                if (!valid_extensions.test($("input[name='memo_upload_img[]']").val()) && $("input[name='memo_upload_img[]']").val() != ""){
             
                  $("#error_msg").html('<div class="col-xs-12"><p style="color:#CB4335"><span class="glyphicon glyphicon-remove"></span> You must upload only image file</p></div>');

                }

                else {
                   // alert(global_emp_id);
                   //$("input[name='to']").after("<input type='hidden' name='empId' value='"+add_memo_emp_id+"' />");
                   $("#form_addMemo").append("<input type='hidden' name='count' value='"+memoRecipientCount+"' />");
                   $("#form_addMemo").attr("action","php script/add_memo.php");

                }
               // alert("Ready For Submition");
             }

          }


          // if 
           if ($("input[name='optRecipient1']:checked").val() == "Department") {
            //alert("HELLO WORLD!");
            // && $("input[name='effectiveDate']").val() != ""
               if ($("input[name='to1']").val() != "" && $("input[name='subject']").val() != "" && $("input[name='content']").val() != ""){
                if (!valid_extensions.test($("input[name='memo_upload_img[]']").val()) && $("input[name='memo_upload_img[]']").val() != ""){
             
                  $("#error_msg").html('<div class="col-xs-12"><p style="color:#CB4335"><span class="glyphicon glyphicon-remove"></span> You must upload only image file</p></div>');

                }

                else {
                // alert(global_emp_id);
                // $("input[name='to']").after("<input type='hidden' name='deptId' value='"+add_memo_dept_id+"' />");
                 $("#form_addMemo").append("<input type='hidden' name='count' value='"+memoRecipientCount+"' />");
                 $("#form_addMemo").attr("action","php script/add_memo.php");
               // alert("Ready For Submition");
               }
             }

          }
 

          

          

         
      });



     // for edit pagibig loan edit_pagibigLoan
     $("a[id='edit_pagibigLoan']").on("click",function () {
        var datastring = "pagibig_loan_id="+$(this).closest("tr").attr("id");

        var pagibig_loan_id = $(this).closest("tr").attr("id"); // update_pagibigLoan_pagibigLoan_id
        $("#update_pagibigForm_body").html("<center><div class='loader'></div>Loading Information</center>");

        // append_update_pagibigLoan.php

        // updateFormModal
        $.ajax({
              type: "POST",
              url: "ajax/append_update_pagibigLoan.php",
              data: datastring,
              cache: false,
             // datatype: "php",
              success: function (data) {

            
                // if has an error
                if (data == "Error"){
                   $("#errorModal").modal("show");
                }
                // if success
                else {
                 // $("input[name='empName']").val(data);
                  //global_emp_id = emp_id;
                  $("#update_pagibigForm_body").html(data);
                  $("#updateFormModal").modal("show");

                  update_pagibigLoan_pagibigLoan_id = pagibig_loan_id; // passing to global variable

                 // alert(update_pagibigLoan_pagibigLoan_id);

                 
                }
               
              }
         });
         


      });


      // for edit pagibig loan edit_sssLoan
     $("a[id='edit_sssLoan']").on("click",function () {
        var datastring = "sss_loan_id="+$(this).closest("tr").attr("id");

        var sss_loan_id = $(this).closest("tr").attr("id"); // update_pagibigLoan_pagibigLoan_id
        $("#update_sssForm_body").html("<center><div class='loader'></div>Loading Information</center>");

        // append_update_pagibigLoan.php

        // updateFormModal
        $.ajax({
              type: "POST",
              url: "ajax/append_update_sssLoan.php",
              data: datastring,
              cache: false,
             // datatype: "php",
              success: function (data) {

            
                // if has an error
                if (data == "Error"){
                   $("#errorModal").modal("show");
                }
                // if success
                else {
                 // $("input[name='empName']").val(data);
                  //global_emp_id = emp_id;
                  $("#update_sssForm_body").html(data);
                  $("#updateFormModal").modal("show");

                  update_sssLoan_sssLoan_id = sss_loan_id; // passing to global variable

                 // alert(update_pagibigLoan_pagibigLoan_id);

                 
                }
               
              }
         });
         


      });


      // for edit pagibig loan edit_salaryLoan
     $("a[id='edit_salaryLoan']").on("click",function () {
        var datastring = "salary_loan_id="+$(this).closest("tr").attr("id");

        var salary_loan_id = $(this).closest("tr").attr("id"); // update_pagibigLoan_pagibigLoan_id
        $("#update_salaryForm_body").html("<center><div class='loader'></div>Loading Information</center>");

        // append_update_pagibigLoan.php

        // updateFormModal
        $.ajax({
              type: "POST",
              url: "ajax/append_update_salaryLoan.php",
              data: datastring,
              cache: false,
             // datatype: "php",
              success: function (data) {

            
                // if has an error
                if (data == "Error"){
                   $("#errorModal").modal("show");
                }
                // if success
                else {
                 // $("input[name='empName']").val(data);
                  //global_emp_id = emp_id;
                  $("#update_salaryForm_body").html(data);
                  $("#updateFormModal").modal("show");

                  update_salaryLoan_salaryLoan_id = salary_loan_id; // passing to global variable

                 // alert(update_pagibigLoan_pagibigLoan_id);

                 
                }
               
              }
         });
         


      });

    

        // for edit pagibig loan edit_simkimban
     $("a[id='edit_simkimban']").on("click",function () {
        var datastring = "simkimban_id="+$(this).closest("tr").attr("id");

        var simkimban_id = $(this).closest("tr").attr("id"); // update_pagibigLoan_pagibigLoan_id

        $("#update_simkimbanForm_body").html("<center><div class='loader'></div>Loading Information</center>");

        // append_update_pagibigLoan.php

        // updateFormModal
        $.ajax({
              type: "POST",
              url: "ajax/append_update_simkimban.php",
              data: datastring,
              cache: false,
             // datatype: "php",
              success: function (data) {

            
                // if has an error
                if (data == "Error"){
                   $("#errorModal").modal("show");
                }
                // if success
                else {
                 // $("input[name='empName']").val(data);
                  //global_emp_id = emp_id;
                  $("#update_simkimbanForm_body").html(data);
                  $("#updateFormModal").modal("show");

                  update_simkimban_id = simkimban_id; // passing to global variable

                 // alert(update_pagibigLoan_pagibigLoan_id);

                 
                }
               
              }
         });
         


      });






     // updatePagibigLoan , for update btn
      $("#update_pagibigForm_body").on("click","input[id='updatePagibigLoan']",function () {

      // for backing its required field
        $("input[name='update_amountLoan']").attr("required","required");
        $("input[name='update_decution']").attr("required","required"); // 
        $("input[name='update_remainingBalance']").attr("required","required");


        // empName
        if ($("input[name='update_amountLoan']").val() != "" && $("input[name='update_decution']").val() != "" && $("input[name='update_remainingBalance']").val() != ""){
            // alert(global_emp_id);
             $("input[name='update_empName']").after("<input type='hidden' name='update_pagibigLoanId' value='"+update_pagibigLoan_pagibigLoan_id+"' />");
             $("#form_updatePagibigLoan").attr("action","php script/update_pagibigLoan.php");
        }
 
     });



      // update sssLoan , for update btn updateSSSLoan
      $("#update_sssForm_body").on("click","input[id='updateSSSLoan']",function () {

      // for backing its required field
        $("input[name='update_amountLoan']").attr("required","required");
        $("input[name='update_decution']").attr("required","required"); // 
        $("input[name='update_remainingBalance']").attr("required","required");


        // empName
        if ($("input[name='update_amountLoan']").val() != "" && $("input[name='update_decution']").val() != "" && $("input[name='update_remainingBalance']").val() != ""){
            // alert(global_emp_id);
             $("input[name='update_empName']").after("<input type='hidden' name='update_sssLoanId' value='"+update_sssLoan_sssLoan_id+"' />");
             $("#form_updateSSSLoan").attr("action","php script/update_sssLoan.php");
        }
 
     });


       // update sssLoan , for update btn updateSalaryLoan
      $("#update_salaryForm_body").on("click","input[id='updateSalaryLoan']",function () {

      // for backing its required field
        $("input[name='update_amountLoan']").attr("required","required");
        $("input[name='update_decution']").attr("required","required"); // 
        $("input[name='update_remainingBalance']").attr("required","required");


        // empName
        if ($("input[name='update_amountLoan']").val() != "" && $("input[name='update_decution']").val() != "" && $("input[name='update_remainingBalance']").val() != ""){
            // alert(global_emp_id);
             $("input[name='update_empName']").after("<input type='hidden' name='update_salaryLoanId' value='"+update_salaryLoan_salaryLoan_id+"' />");
             $("#form_updateSalaryLoan").attr("action","php script/update_salaryLoan.php");
        }
 
     });


      // update sssLoan , for update btn updateSalaryLoan
      $("#update_simkimbanForm_body").on("click","input[id='updateSimkimban']",function () {

      // for backing its required field
        $("input[name='update_item']").attr("required","required");
        $("input[name='update_amountLoan']").attr("required","required");
        $("input[name='update_decution']").attr("required","required"); // 
        $("input[name='update_remainingBalance']").attr("required","required");


        // empName
        if ($("input[name='update_item']").val() != "" && $("input[name='update_amountLoan']").val() != "" && $("input[name='update_decution']").val() != "" && $("input[name='update_remainingBalance']").val() != ""){
            // alert(global_emp_id);
             $("input[name='update_empName']").after("<input type='hidden' name='update_simkimbanId' value='"+update_simkimban_id+"' />");
             $("#form_updateSimkimban").attr("action","php script/update_simkimban.php");
        }
 
     });
     


      // for delete confirmation modal in pagibig loan
      $("a[id='delete_pagibigLoan']").on("click",function () {
          var datastring = "pagibig_loan_id="+$(this).closest("tr").attr("id");

          var pagibig_loan_id = $(this).closest("tr").attr("id"); // update_pagibigLoan_pagibigLoan_id
          $("#delete_modal_body").html("<center><div class='loader'></div>Loading Information</center>");


          // delete_pagibigLoan_pagibigLoan_id
          $.ajax({
              type: "POST",
              url: "ajax/append_delete_pagibigLoan.php",
              data: datastring,
              cache: false,
             // datatype: "php",
              success: function (data) {

            
                // if has an error
                if (data == "Error"){
                   $("#errorModal").modal("show");
                }
                // if success
                else {
                 // $("input[name='empName']").val(data);
                  //global_emp_id = emp_id;
                  $("#delete_modal_body").html(data);
                  $("#deletePagibigConfirmationModal").modal("show");

                  delete_pagibigLoan_pagibigLoan_id = pagibig_loan_id; // passing to global variable

                 // alert(update_pagibigLoan_pagibigLoan_id);
            
                }
               
              }
         });
        


      });



     // for delete confirmation modal in sss loan
      $("a[id='delete_sssLoan']").on("click",function () {
          var datastring = "sss_loan_id="+$(this).closest("tr").attr("id");

          var sss_loan_id = $(this).closest("tr").attr("id"); // update_pagibigLoan_pagibigLoan_id

          $("#delete_modal_body").html("<center><div class='loader'></div>Loading Information</center>");

          // delete_pagibigLoan_pagibigLoan_id
          $.ajax({
              type: "POST",
              url: "ajax/append_delete_sssLoan.php",
              data: datastring,
              cache: false,
             // datatype: "php",
              success: function (data) {

            
                // if has an error
                if (data == "Error"){
                   $("#errorModal").modal("show");
                }
                // if success
                else {
                 // $("input[name='empName']").val(data);
                  //global_emp_id = emp_id;
                  $("#delete_modal_body").html(data);
                  $("#deletePagibigConfirmationModal").modal("show");

                  delete_sssLoan_sssLoan_id = sss_loan_id; // passing to global variable

                 // alert(update_pagibigLoan_pagibigLoan_id);
            
                }
               
              }
         });
        


      });


     // for delete confirmation modal in sss loan
      $("a[id='delete_salaryLoan']").on("click",function () {
          var datastring = "salary_loan_id="+$(this).closest("tr").attr("id");

          var salary_loan_id = $(this).closest("tr").attr("id"); // update_pagibigLoan_pagibigLoan_id
          $("#delete_modal_body").html("<center><div class='loader'></div>Loading Information</center>");


          // delete_pagibigLoan_pagibigLoan_id
          $.ajax({
              type: "POST",
              url: "ajax/append_delete_salaryLoan.php",
              data: datastring,
              cache: false,
             // datatype: "php",
              success: function (data) {

            
                // if has an error
                if (data == "Error"){
                   $("#errorModal").modal("show");
                }
                // if success
                else {
                 // $("input[name='empName']").val(data);
                  //global_emp_id = emp_id;
                  $("#delete_modal_body").html(data);
                  $("#deletePagibigConfirmationModal").modal("show");

                  delete_salaryLoan_sssLoan_id = salary_loan_id; // passing to global variable

                 // alert(update_pagibigLoan_pagibigLoan_id);
            
                }
               
              }
         });
        


      });




    // for delete confirmation modal in sss loan
      $("a[id='delete_simkimban']").on("click",function () {
          var datastring = "simkimban_id="+$(this).closest("tr").attr("id");

          var simkimban_id = $(this).closest("tr").attr("id"); // update_pagibigLoan_pagibigLoan_id
          $("#delete_modal_body").html("<center><div class='loader'></div>Loading Information</center>");


          // delete_pagibigLoan_pagibigLoan_id
          $.ajax({
              type: "POST",
              url: "ajax/append_delete_simkimban.php",
              data: datastring,
              cache: false,
             // datatype: "php",
              success: function (data) {

            
                // if has an error
                if (data == "Error"){
                   $("#errorModal").modal("show");
                }
                // if success
                else {
                 // $("input[name='empName']").val(data);
                  //global_emp_id = emp_id;
                  $("#delete_modal_body").html(data);
                  $("#deletePagibigConfirmationModal").modal("show");

                  delete_simkimban_id = simkimban_id; // passing to global variable

                 // alert(update_pagibigLoan_pagibigLoan_id);
            
                }
               
              }
         });
        


      });



     // for yes form_deletePagibigLoan
      $("#delete_modal_footer").on("click","a[id='delete_yes_pagibig']",function () {
          $("#form_deletePagibigLoan").append("<input type='hidden' name='delete_pagibigLoanId' value='"+delete_pagibigLoan_pagibigLoan_id+"' />");
          $("#form_deletePagibigLoan").attr("action","php script/delete_pagibigLoan.php");
          $("#form_deletePagibigLoan").submit();

      });


      // for yes form_deleteSSSLoan
      $("#delete_modal_footer").on("click","a[id='delete_yes_sss']",function () {
          $("#form_deleteSSSLoan").append("<input type='hidden' name='delete_sssLoanId' value='"+delete_sssLoan_sssLoan_id+"' />");
          $("#form_deleteSSSLoan").attr("action","php script/delete_sssLoan.php");
          $("#form_deleteSSSLoan").submit();

      });


       // for yes form_deleteSAlaryLoan
      $("#delete_modal_footer").on("click","a[id='delete_yes_salary']",function () {
          $("#form_deleteSalaryLoan").append("<input type='hidden' name='delete_salaryLoanId' value='"+delete_salaryLoan_sssLoan_id+"' />");
          $("#form_deleteSalaryLoan").attr("action","php script/delete_salaryLoan.php");
          $("#form_deleteSalaryLoan").submit();

      });



    // for yes form_deleteSimkimban
      $("#delete_modal_footer").on("click","a[id='delete_yes_simkimban']",function () {
          $("#form_deleteSimkimban").append("<input type='hidden' name='delete_simkimbanId' value='"+delete_simkimban_id+"' />");
          $("#form_deleteSimkimban").attr("action","php script/delete_simkimban.php");
          $("#form_deleteSimkimban").submit();

      });

      // for search attendance searchAttendance
      $("input[id='searchAttendance']").on("click",function () {

       // alert("wew");
          var searchOption = $("input[name='optionSearch']:checked").val();
         

           var dateformat = /^(0[1-9]|1[012])[\/\-](0[1-9]|[12][0-9]|3[01])[\/\-]\d{4}$/; 

          if (searchOption == "All" || searchOption == "Current Cut off" || searchOption == "Specific Date") {
            


             var dateFrom = $("input[name='dateFrom']").val();
             var dateTo = $("input[name='dateTo']").val();

             //alert(dateFrom + " " + dateTo);
            // alert(dateFrom.getFullYear());
             if (searchOption == "Specific Date" && (dateFrom == "" ||dateTo == "")) {
                $("#searchAttendance_body").html("<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Please provide Date From and Date To</center>");
                $("#searchAttendanceModal").modal("show");
             }

             /*
            else if (searchOption == "Specific Date" && (dateFrom > dateTo )) {
                $("#searchAttendance_body").html("<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Date From must be below from Date To</center>");
                $("#searchAttendanceModal").modal("show");
            }*/

            else if (searchOption == "Specific Date" && (!dateFrom.match(dateformat) || !dateTo.match(dateformat))){
               $("#searchAttendance_body").html("<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid Date From or Date To</center>");
               $("#searchAttendanceModal").modal("show");
            }


             else {
               // to be uncomment when live
               // $("#attendance_table").html("<div class='col-sm-12' style='margin-top:50px;border:1px solid #BDBDBD;'><div id='unfields_info' class=''><center><div class=loader></div>Loading Information ...</center></div></div>"); // just commenting but if will live it will be comment out
                
                 var datastring = "searchOption=" + searchOption + "&dateFrom="+dateFrom+ "&dateTo="+dateTo;
                 
                 $("#attendance_table").html("<center><div class='loader' style='margin-top:120px;'></div>Loading Information</center>");
                 
                 $.ajax({
                    type: "POST",
                    url: "ajax/append_search_my_attendance.php",
                    data: datastring,
                    cache: false,
                   // datatype: "php",
                    success: function (data) {

                  
                      // if has an error
                      if (data == "Error"){
                         $("#errorModal").modal("show");
                      }
                      // if success
                      else {

                         $("#attendance_table").html(data);
                  
                      }
                     
                    }
                 });
               // alert(datastring);
             }
          }

          else {
              $("#searchAttendanceModal").modal("show");
          }

          //if ()

      });


       $("#attendance_table").on("click","a[id='edit_bio_id']", function () {
           var datastring = "attendance_id="+$(this).closest("tr").attr("id");
          // alert(datastring);
           $("#modal_body_update_attendance").html("<center><div class='loader'></div>Loading Information</center>");
            $.ajax({
                type: "POST",
                url: "ajax/append_update_attendance.php",
                data: datastring,
                cache: false,
                success: function (data) {
                  // if has error 
                  if (data == "Error"){
                    $("#update_errorModal").modal("show");
                  }
                  // if success
                  else {               
                    $("#modal_body_update_attendance").html(data);
                    $("#updateAttendanceModal").modal("show");
                  }
                  
                }
             });

       });


      // edit_ytd
      $("a[id='edit_ytd']").on("click",function () {
           var datastring = "ytd_id="+$(this).closest("tr").attr("id");

           var ytd_id = $(this).closest("tr").attr("id");
           $("#update_ytdForm_body").html("<center><div class='loader'></div>Loading Information</center>");


           $.ajax({
                type: "POST",
                url: "ajax/append_update_ytd_info.php",
                data: datastring,
                cache: false,
               // datatype: "php",
                success: function (data) {

              
                  // if has an error
                  if (data == "Error"){
                     $("#errorModal").modal("show");
                  }
                  // if success
                  else {


                    $("#update_ytdForm_body").html(data);
                    $("#updateFormModal").modal("show");
                    update_ytd_id = ytd_id;
              
                  }
                 
                }
             });

           
       });


      // updateYTDinfo
       $("#update_ytdForm_body").on("click","input[id='updateYTDinfo']",function () {
          // for backing its required field
          $("input[name='update_ytdGross']").attr("required","required");
          $("input[name='update_ytdAllowance']").attr("required","required");
          $("input[name='update_ytdTax']").attr("required","required"); // 


          // empName
          if ($("input[name='update_ytdGross']").val() != "" && $("input[name='update_ytdAllowance']").val() != "" && $("input[name='update_ytdTax']").val() != ""){
              // alert(global_emp_id);
               $("input[name='update_empName']").after("<input type='hidden' name='update_ytdId' value='"+update_ytd_id+"' />");
               $("#form_updateYtd").attr("action","php script/update_ytd.php");
          }
          

      });


     // optRecipient
     $("input[name='optRecipient1']").on("click",function () {
          var recipientType = $(this).val();

         // alert(recipientType);
          //alert(memoRecipientCount);

          // success
          if (recipientType == "All"|| recipientType == "Specific Employee" || recipientType == "Department"){
              if (recipientType == "Specific Employee") {
                 //var recipient_count = recipientType.slice();
                  $("input[name='to1']").removeAttr("disabled");
                  $("div[id='choose1']").html("<a href='#' id='choose_employee_memo'>Choose</a>");
                  $("input[name='to1']").attr("required","required");
                  $("input[name='to1']").val("");
                  $("input[name='to1']").attr("placeholder","Employee ..");
                  $("button[id='add_recipient']").removeAttr("disabled");

              }

              if (recipientType == "All"){
                $("input[name='to1']").attr("disabled","disabled");
                $("input[name='to1']").removeAttr("required");
                 $("div[id='choose1']").html("");
                 $("input[name='to1']").val("");
                 $("input[name='to1']").attr("placeholder","");

                 // for add recipient button
                 $("button[id='add_recipient']").attr("disabled","disabled");

                 $("#div_recipient").html("");

                // for resetting global variable value
                add_memo_emp_id = "";
                add_memo_dept_id = "";

              }


              if (recipientType == "Department"){
                $("input[name='to1']").removeAttr("disabled");
                $("input[name='to1']").attr("required","required");
                $("div[id='choose1']").html("<a href='#' id='choose_department_memo'>Choose</a>");
                $("input[name='to1']").attr("placeholder","Department ..");
                $("input[name='to1']").val("");

                $("button[id='add_recipient']").removeAttr("disabled");
                // for resetting global variable value
                

              }
          }   

      });



     // for edit memo edit_memorandum

      $("div[id='edit_memorandum']").on("click",function () {
          var datastring = "memo_id="+$(this).closest("tr").attr("id");

          var memo_id = $(this).closest("tr").attr("id");

          $("#update_memoForm_body").html("<center><div class='loader'></div>Loading Information</center>");

          $.ajax({
                type: "POST",
                url: "ajax/append_update_memo.php",
                data: datastring,
                cache: false,
               // datatype: "php",
                success: function (data) {

              
                  // if has an error
                  if (data == "Error"){
                     $("#errorModal").modal("show");
                  }
                  // if success
                  else {


                    $("#update_memoForm_body").html(data);
                    $("#updateFormModal").modal("show");
                    update_memo_id = memo_id;
              
                  }
                 
                }
             });


       });

      


       // delete_memorandum
      $("a[id='delete_memorandum']").on("click",function () {
          var datastring = "memo_id="+$(this).closest("tr").attr("id");

          var memo_id = $(this).closest("tr").attr("id"); // update_pagibigLoan_pagibigLoan_id

          $("#delete_modal_body").html("<center><div class='loader'></div>Loading Information</center>");

          // delete_pagibigLoan_pagibigLoan_id
          $.ajax({
              type: "POST",
              url: "ajax/append_delete_memo.php",
              data: datastring,
              cache: false,
             // datatype: "php",
              success: function (data) {

            
                // if has an error
                if (data == "Error"){
                   $("#errorModal").modal("show");
                }
                // if success
                else {
                 // $("input[name='empName']").val(data);
                  //global_emp_id = emp_id;
                  $("#delete_modal_body").html(data);
                  $("#deleteMemoConfirmationModal").modal("show");
                  
                  delete_memo_id = memo_id; // passing to global variable

                 // alert(update_pagibigLoan_pagibigLoan_id);
            
                }
               
              }
         });
        


      });

       // for yes delete_yes_memorandum
      $("#delete_modal_footer").on("click","a[id='delete_yes_memorandum']",function () {
          $("#form_deleteMemo").append("<input type='hidden' name='delete_memoId' value='"+delete_memo_id+"' />");
          $("#form_deleteMemo").attr("action","php script/delete_memo.php");
          $("#form_deleteMemo").submit();

      });



      // view_memo_info
      $("a[id='view_memo_info']").on("click",function () {
          var datastring = "memo_id="+$(this).closest("tr").attr("id");

          var memo_id = $(this).closest("tr").attr("id"); // update_pagibigLoan_pagibigLoan_id
          $("#memo_info_body").html("<center><div class='loader'></div>Loading Information</center>");


          // form_viewMemo
           $.ajax({
              type: "POST",
              url: "ajax/append_view_memo_info.php",
              data: datastring,
              cache: false,
             // datatype: "php",
              success: function (data) {

            
                // if has an error
                if (data == "Error"){
                   $("#errorModal").modal("show");
                }
                // if success
                else {
                  $("#memo_info_body").html(data);
                  $("#memoInfoModal").modal("show");
            
                }
               
              }
         });

      });


      // for choose department in memo choose_department_memo
      var dept_id_count = 0;
      $("div[id='choose1']").on("click","a[id='choose_department_memo']",function () {
          $("#dept_list_modal").modal("show");

           dept_id_count = $(this).closest("div").attr("id").slice(6,7);

          //alert(id_count);

      });



     // for choosing employee name dynamic chooseDepartment for memorandum
     $("a[id='chooseDepartment']").on("click",function () {
        // empName
        var datastring = "dept_id="+$(this).closest("tr").attr("id");

        var dept_id = $(this).closest("tr").attr("id");

        //alert();


        // datastring
          $.ajax({
                type: "POST",
                url: "ajax/append_department_value.php",
                data: datastring,
                cache: false,
               // datatype: "php",
                success: function (data) {

                  $("#dept_list_modal").modal("hide");
                    
                  // if has an error
                  if (data == "Error" || data == 1){
                     $("#errorModal").modal("show");
                  }
                  // if success
                  else {
                    //alert(dept_id_count);
                    $("input[name='to"+dept_id_count+"']").val(data);
                    //add_memo_dept_id = dept_id;
                    
                   // $("#atm_record_modal_body").html(data);
                   // $("#updateATM_modal").modal("show");
                   
                  }
                 
                }
           }); 
    });




    // for update memo
    $("a[id='chooseEmployee']").on("click",function () {
        // empName
        var datastring = "emp_id="+$(this).closest("tr").attr("id");

        var emp_id = $(this).closest("tr").attr("id");

        // datastring
          $.ajax({
                type: "POST",
                url: "ajax/append_emp_name.php",
                data: datastring,
                cache: false,
               // datatype: "php",
                success: function (data) {

                  $("#emp_list_modal").modal("hide");
                    
                  // if has an error
                  if (data == "Error" || data == 1){
                     $("#errorModal").modal("show");
                  }
                  // if success
                  else {
                    $("input[name='to']").val(data);
                    update_memo_emp_id = emp_id;
                    
                    
                   // $("#atm_record_modal_body").html(data);
                   // $("#updateATM_modal").modal("show");
                   
                  }
                 
                }
           }); 
    });



   // for choosing employee name dynamic chooseDepartment for memorandum
   /*  $("a[id='chooseDepartment']").on("click",function () {
        // empName
        var datastring = "dept_id="+$(this).closest("tr").attr("id");

        var dept_id = $(this).closest("tr").attr("id");


        // datastring
          $.ajax({
                type: "POST",
                url: "ajax/append_department_value.php",
                data: datastring,
                cache: false,
               // datatype: "php",
                success: function (data) {

                  $("#dept_list_modal").modal("hide");
                    
                  // if has an error
                  if (data == "Error" || data == 1){
                     $("#errorModal").modal("show");
                  }
                  // if success
                  else {
                    $("input[name='to']").val(data);
                    update_memo_dept_id = dept_id;
                    
                    
                   // $("#atm_record_modal_body").html(data);
                   // $("#updateATM_modal").modal("show");
                   
                  }
                 
                }
           }); 
    });
    */



  


  

   // print_memorandum
     $("a[id='print_memorandum']").on("click",function () {

        var memo_id = $(this).closest("tr").attr("id");

        //window.open("memo_reports.php?memo_id="+memo_id);
        window.open("word_memo_reports.php?memo_id="+memo_id);

    });


    $("a[id='view_memo_img']").on("click",function () {

        var memo_id = $(this).closest("tr").attr("id");

        //alert("HELLO WORLD!");
        var datastring = "memo_id="+memo_id;
        $.ajax({
            type: "POST",
            url: "ajax/append_view_memo_images.php",
            data: datastring,
            cache: false,
           // datatype: "php",
            success: function (data) {
               //alert(data);
               $("#memoImgModal").find(".modal-body").html(data);
               $("#memoImgModal").modal("show");

            }
        });

    });


    $("a[id='add_memo_img']").on("click",function(){
      //alert("HELLO WORLD!");
      var memo_id = $(this).closest("tr").attr("id");

        //alert("HELLO WORLD!");
        var datastring = "memo_id="+memo_id;
        $.ajax({
            type: "POST",
            url: "ajax/append_add_memo_img.php",
            data: datastring,
            cache: false,
           // datatype: "php",
            success: function (data) {
               //alert(data);
               $("#memoImgModal").find(".modal-body").html(data);
               $("#memoImgModal").modal("show");

            }
        });
    });




    // request_update_btn
     $("input[id='file_vacationLeave']").on("click",function () {

        // for setting its required attr 
          $("select[name='leaveType']").attr("required","required");
          $("input[name='dateFrom_Leave']").attr("required","required");
          $("input[name='dateTo_Leave']").attr("required","required");
          $("textarea[name='remarks_Leave']").attr("required","required");

          if ($("select[name='leaveType']").val() != "" && $("input[name='dateFrom_Leave']").val() != "" && $("input[name='dateTo_Leave']").val() != ""  && $("textarea[name='remarks_Leave']").val() != ""){
             $("#fileLeave_Form").attr("action","php script/file_leave.php?FileLeaveType=Leave with pay");
          }

    });


     // for filing formal leave
     $("input[id='file_formalLeave']").on("click",function () {

        
        // for setting its required attr 
          $("select[name='formal_leaveType']").attr("required","required");
          $("input[name='formal_dateFrom_Leave']").attr("required","required");
          $("input[name='formal_dateTo_Leave']").attr("required","required");
          $("textarea[name='formal_remarks_Leave']").attr("required","required");
          // alert("Hello World!");
          //alert($("select[name='leaveType']").val());

          if ($("select[name='formal_leaveType']").val() != "" && $("input[name='formal_dateFrom_Leave']").val() != "" && $("input[name='formal_dateTo_Leave']").val() != ""  && $("textarea[name='formal_remarks_Leave']").val() != ""){
              //alert("Hello World!");
             $("#fileFormalLeave_Form").attr("action","php script/file_formal_leave.php?FileLeaveType=Leave without pay");
          }

    });


     // for filing half day leave
     $("input[id='file_halfdayLeave']").on("click",function () {

         // for setting its required attr 
          $("select[name='halfday_leave_type_leave']").attr("required","required");
          $("input[name='halfday_leave_date']").attr("required","required"); // 
          $("input[name='halfday_remarks_Leave']").attr("required","required");

          if ($("select[name='halfday_leave_type_leave']").val() != "" && $("input[name='halfday_leave_date']").val() != "" && $("input[name='halfday_remarks_Leave']").val() != ""){
              //alert("Hello World!");
             $("#fileHalfdaylLeave_Form").attr("action","php script/file_halfday_leave.php?FileLeaveType=Halfday Leave with pay");
          }


      });

     // generatePayroll
     var emp_active_total_count = 0;
      $("button[id='generatePayroll']").on("click",function () {
          
         var datastring = "generate_request=1";
          $.ajax({
              type: "POST",
              url: "ajax/append_total_count_active_emp.php",
              data: datastring,
              cache: false,
             // datatype: "php",
              success: function (data) {
                emp_active_total_count = data;
                //alert(emp_active_total_count);
                $("#generatePayrollModalConfirmation").modal("show");

              }
          });
      });


      // generate_payroll_yes

      $("a[id='generate_payroll_yes']").on("click",function () {
         $("#generatePayrollModalConfirmation").modal("hide");
         
         var datastring = "active_emp_count="+emp_active_total_count;
         $("#generating_values").html('<div class="panel panel-primary"><div class="panel-body"><div class="col-sm-12" id="message"><center><div class="loader"></div> Generating Payroll Please wait .... </center></div></div></div>');

        // alert("wew");

         //var emp_destrating = "emp_id="+values[counter];


          /*$.ajax({
              type: "POST",
              url: "ajax/append_total_count_active_emp.php",
              data: datastring,
              cache: false,
             // datatype: "php",
              success: function (data) {

                emp_active_total_count = data;*/

                /* $.ajax({
                    type: "POST",
                    url: "ajax/append_all_active_emp_id.php",
                    data: datastring,
                    cache: false,
                   // datatype: "php",
                    success: function (data) {


                     emp_active_id_values = data;
                     //alert(emp_active_id_values);


                      var values =  emp_active_id_values.split("#");

                       var counter = 0;
                      // alert(values);

                       do {

                        //  alert(values[counter] + "wew");
                          var emp_destrating = "emp_id="+values[counter];

                         // alert(emp_destrating);
                          ///alert(counter);

                       //  alert(values[counter] + "wew");
                          
                          // alert(counter);
                         // alert(emp_active_total_count - 1);
                           
                            
                    
                            
                          if (counter == (emp_active_total_count - 1)){
                                $("#message").html("<center><span class='glyphicon glyphicon-ok' style='color: #1d8348;'></span> <b>The Payroll Values Is Completely Finished.</b></center>");
                                $("#final_generation_values").append('<input type="button" class="btn btn-success btn-sm" style="margin-bottom:10px;" id="submit_payroll" value="Submit Payroll"/>');
                          }
                    */


                           $.ajax({
                              type: "POST",
                              url: "ajax/append_generating_payroll.php",
                              data: datastring,
                              cache: false,
                             // datatype: "php",
                              success: function (data) {
                                  //alert(data);
                               // alert(data);
                                  $("#final_generation_values").append(data);
                                  $("#message").html("<center><span class='glyphicon glyphicon-ok' style='color: #1d8348;'></span> <b>The Payroll Values Is Completely Finished.</b></center>");
                                  $("#final_generation_values").prepend('<input type="button" class="btn btn-success btn-sm" style="margin-bottom:10px;" id="submit_payroll" value="Submit Payroll"/>');
                                 // alert(counter);

                                  

                                  //if (counter == emp_active_total_count){
                                      
                                    //alert("Wew");
                                    // $("#message").html("<center><span class='glyphicon glyphicon-ok' style='color: #1d8348;'></span> <b>The Payroll Values Is Completely Finished.</b></center>");
                                     //$("#final_generation_values").append('<input type="button" class="btn btn-success btn-sm" style="margin-bottom:10px;" id="submit_payroll" value="Submit Payroll"/>');

                                
                                 // }
                              }
       
                           }); 

                          



                     /*     counter++;
                       } while(counter < emp_active_total_count);

                      //alert("wew");
                      
                       
                    }
                     
                    
                 }); */




               /*}
               
              
         });  */    

      });



      // for submitting payroll   $("div[id='updateLatestMinWage']").on("click","a[id='remove_update_min_wage_form']", function () {
      $("#final_generation_values").on("click","input[id='submit_payroll']",function () {
            $(this).attr("disabled","disabled");
            $("#final_generation_values").append('<input type="hidden" name="submit_payroll" value="1"/>'); // for security purpose
            $("#final_generation_values").attr("action","php script/submit_payroll.php");
            $("#final_generation_values").submit();

      });



      // for search attedance attendance list
      $("input[id='searchAllAttendance']").on("click",function () {

          var dateFrom = $("input[name='dateFrom']").val();
          var dateTo = $("input[name='dateTo']").val();

          var dateformat = /^(0[1-9]|1[012])[\/\-](0[1-9]|[12][0-9]|3[01])[\/\-]\d{4}$/; 



          if (dateFrom == "" ||dateTo == "") {
              $("#searchAttendance_body").html("<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Please provide Date From and Date To</center>");
              $("#searchAttendanceModal").modal("show");
           }


          // if does not match either one of the two or both
          else if (!dateFrom.match(dateformat) || !dateTo.match(dateformat)) {
              $("#searchAttendance_body").html("<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Invalid Date From or Date To</center>");
              $("#searchAttendanceModal").modal("show");
          }


          /*
          else if (dateFrom > dateTo) {
              $("#searchAttendance_body").html("<center><span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Date From must be below from Date To</center>");
              $("#searchAttendanceModal").modal("show");
          }
          */

         else {
             // to be uncomment when live
             // $("#attendance_table").html("<div class='col-sm-12' style='margin-top:50px;border:1px solid #BDBDBD;'><div id='unfields_info' class=''><center><div class=loader></div>Loading Information ...</center></div></div>"); // just commenting but if will live it will be comment out
              
              var datastring = "dateFrom="+dateFrom+ "&dateTo="+dateTo;
              $("#attendance_table").html("<center><div class='loader' style='margin-top:100px;'></div>Loading Information</center>");
              $.ajax({
                  type: "POST",
                  url: "ajax/append_search_all_attendance.php",
                  data: datastring,
                  cache: false,
                 // datatype: "php",
                  success: function (data) {

                
                    // if has an error
                    //if (data == "Error"){
                   //    $("#errorModal").modal("show");
                   // }
                    // if success
                   //else {

                     $("#attendance_table").html(data);
                
                  //  }
                   
                  }
               });
          } // end of else

      });




     // for approval
      // for approving request attendance 
     /* $("a[id='approve_payroll']").on("click", function () {

        // alert("HELLO WORLD!");

         var datastring = "approve_payroll_id="+$(this).closest("tr").attr("id") +"&approve=" + $(this).attr("title");
         $("#payrollApprove_body").html("<center><div class='loader'></div>Loading Information</center>");
        // alert(datastring);
          $.ajax({
              type: "POST",
              url: "ajax/script_approve_payroll.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
               if (data == "Error") {
                  $("#errorModal").modal("show");
               }
               // if success
               else {
                  $("#payrollApprove_body").html(data);
                  $("#payrollApproveModal").modal("show");
               }

             }
           });
         
        
      });*/


      // for print payroll reports print_payroll_reports
     /* $("a[id='print_payroll_reports']").on("click", function () {
         var datastring = "approve_payroll_id=" + $(this).closest("tr").attr("id");
        
           $.ajax({
              type: "POST",
              url: "ajax/script_check_exist_approve_payroll_id.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
               if (data == "Error") {
                  $("#errorModal").modal("show");
               }
               // if success
               else {
                 $("#select_type_reportsModal").modal("show");
                  //window.open("print_payroll_reports.php");
             
               }

             }
           });
         
         
        
      });*/


       // for print payroll reports print_payroll_reports
      $("a[id='print_adjustment_reports']").on("click", function () {
         var datastring = "cut_off_period=" + $(this).closest("tr").attr("id");
         //alert(datastring);

           $.ajax({
              type: "POST",
              url: "ajax/script_exist_cut_off_period_adjustment.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
               if (data == "Error") {
                  $("#errorModal").modal("show");
               }
               // if success
               else {
                 // alert(data);
                  window.open("print_adjustment_reports.php");
             
               }

             }
           });
         
         
        
      });


      // adjust_pagibigLoan
      $("a[id='adjust_pagibigLoan']").on("click", function () {
           var datastring = "pagibig_loan_id="+$(this).closest("tr").attr("id");
           //alert(datastring);
            
           var pagibig_loan_id = $(this).closest("tr").attr("id"); // update_pagibigLoan_pagibigLoan_id

           $.ajax({
              type: "POST",
              url: "ajax/append_adjust_pagibigLoan.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
               if (data == "Error") {
                  $("#errorModal").modal("show");
               }
               // if success
               else {
                 $("#adjust_pagibigForm_body").html(data);
                 $("#adjustLoanModal").modal("show");
                 adjust_pagibigLoan_id = pagibig_loan_id;
                // alert(adjust_pagibigLoan_id);
               }

             }
           });
         
         
        
      });


      // for adjustment BTN
      $("#adjust_pagibigForm_body").on("click","input[id='adjustPagibigLoan']",function () {

      // for backing its required field
        $("input[name='adjust_datePayment']").attr("required","required");
        $("input[name='adjust_remainingBalance']").attr("required","required");
        $("input[name='adjust_cashPayment']").attr("required","required"); // 
        $("input[name='adjust_newRemainingBalance']").attr("required","required");
        $("input[name='adjust_remarks']").attr("required","required");


        // empName
        if ($("input[name='adjust_datePayment']").val() != "" && $("input[name='adjust_remainingBalance']").val() != "" && $("input[name='adjust_cashPayment']").val() != "" && $("input[name='adjust_newRemainingBalance']").val() != "" && $("input[name='adjust_remarks']").val() != ""){
            // alert(global_emp_id);
             $("input[name='adjust_empName']").after("<input type='hidden' name='adjust_pagibigLoanId' value='"+adjust_pagibigLoan_id+"' />");
             $("#form_adjustPagibigLoan").attr("action","php script/adjust_pagibigLoan.php");
        }
 
     });


      // for onchange ng cashpayment compute the new outstanding balance adjust_cashPayment
      $("#adjust_pagibigForm_body").change("input[name='adjust_cashPayment']",function(){
          var currentBalance = $("input[name='adjust_remainingBalance']").val();
          var cashPayment = $("input[name='adjust_cashPayment']").val();

          if (cashPayment != ""){
            var newBalance = parseFloat(currentBalance) - parseFloat(cashPayment);
            // for 2 decimal places
            newBalance = newBalance.toString().split('e');
            newBalance = Math.round(+(newBalance[0] + 'e' + (newBalance[1] ? (+newBalance[1] + 2) : 2)));

            newBalance = newBalance.toString().split('e');
            newBalance=  (+(newBalance[0] + 'e' + (newBalance[1] ? (+newBalance[1] - 2) : -2))).toFixed(2);
            $("input[name='adjust_newRemainingBalance']").val(newBalance);

          }

          if (cashPayment == ""){
            $("input[name='adjust_newRemainingBalance']").val(""); // for refreshing the value
          }


     });


      // for sss loan adjust adjust_sssLoan
       $("a[id='adjust_sssLoan']").on("click", function () {
           var datastring = "sss_loan_id="+$(this).closest("tr").attr("id");
          // alert(datastring);
            
           var sss_loan_id = $(this).closest("tr").attr("id"); // update_pagibigLoan_pagibigLoan_id

           $.ajax({
              type: "POST",
              url: "ajax/append_adjust_sssLoan.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
               if (data == "Error") {
                  $("#errorModal").modal("show");
               }
               // if success
               else {
                 $("#adjust_sssForm_body").html(data);
                 $("#adjustLoanModal").modal("show");
                 adjust_sssLoan_id = sss_loan_id;
                 //alert(adjust_sssLoan_id);
               }

             }
           });
      });


        // for onchange ng cashpayment compute the new outstanding balance adjust_cashPayment
      $("#adjust_sssForm_body").change("input[name='adjust_cashPayment']",function(){
          var currentBalance = $("input[name='adjust_remainingBalance']").val();
          var cashPayment = $("input[name='adjust_cashPayment']").val();

          if (cashPayment != ""){
            var newBalance = parseFloat(currentBalance) - parseFloat(cashPayment);
            // for 2 decimal places
            newBalance = newBalance.toString().split('e');
            newBalance = Math.round(+(newBalance[0] + 'e' + (newBalance[1] ? (+newBalance[1] + 2) : 2)));

            newBalance = newBalance.toString().split('e');
            newBalance=  (+(newBalance[0] + 'e' + (newBalance[1] ? (+newBalance[1] - 2) : -2))).toFixed(2);
            $("input[name='adjust_newRemainingBalance']").val(newBalance);

          }

          if (cashPayment == ""){
            $("input[name='adjust_newRemainingBalance']").val(""); // for refreshing the value
          }


     });



       // for adjustment BTN
      $("#adjust_sssForm_body").on("click","input[id='adjustSSSLoan']",function () {

      // for backing its required field
        $("input[name='adjust_datePayment']").attr("required","required");
        $("input[name='adjust_remainingBalance']").attr("required","required");
        $("input[name='adjust_cashPayment']").attr("required","required"); // 
        $("input[name='adjust_newRemainingBalance']").attr("required","required");
        $("input[name='adjust_remarks']").attr("required","required");


        // empName
        if ($("input[name='adjust_datePayment']").val() != "" && $("input[name='adjust_remainingBalance']").val() != "" && $("input[name='adjust_cashPayment']").val() != "" && $("input[name='adjust_newRemainingBalance']").val() != "" && $("input[name='adjust_remarks']").val() != ""){
            // alert(global_emp_id);
             $("input[name='adjust_empName']").after("<input type='hidden' name='adjust_sssLoanId' value='"+adjust_sssLoan_id+"' />");
             $("#form_adjustSSSLoan").attr("action","php script/adjust_sssLoan.php");
        }
 
     });

      // adjust_salaryLoan
      // for salary loan adjust adjust_salaryLoan
       $("a[id='adjust_salaryLoan']").on("click", function () {
           var datastring = "salary_loan_id="+$(this).closest("tr").attr("id");
          // alert(datastring);
            
           var salary_loan_id = $(this).closest("tr").attr("id"); // update_pagibigLoan_pagibigLoan_id



           $.ajax({
              type: "POST",
              url: "ajax/append_adjust_salaryLoan.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
               if (data == "Error") {
                  $("#errorModal").modal("show");
               }
               // if success
               else {
                 $("#adjust_salaryForm_body").html(data);
                 $("#adjustLoanModal").modal("show");
                 adjust_salaryLoan_id = salary_loan_id;
                 //alert(adjust_sssLoan_id);
               }

             }
           });
      });


       // for onchange ng cashpayment compute the new outstanding balance adjust_cashPayment
      $("#adjust_salaryForm_body").change("input[name='adjust_cashPayment']",function(){
          var currentBalance = $("input[name='adjust_remainingBalance']").val();
          var cashPayment = $("input[name='adjust_cashPayment']").val();

          if (cashPayment != ""){
            var newBalance = parseFloat(currentBalance) - parseFloat(cashPayment);
            // for 2 decimal places
            newBalance = newBalance.toString().split('e');
            newBalance = Math.round(+(newBalance[0] + 'e' + (newBalance[1] ? (+newBalance[1] + 2) : 2)));

            newBalance = newBalance.toString().split('e');
            newBalance=  (+(newBalance[0] + 'e' + (newBalance[1] ? (+newBalance[1] - 2) : -2))).toFixed(2);
            $("input[name='adjust_newRemainingBalance']").val(newBalance);

          }

          if (cashPayment == ""){
            $("input[name='adjust_newRemainingBalance']").val(""); // for refreshing the value
          }


     });


        // for adjustment BTN
      $("#adjust_salaryForm_body").on("click","input[id='adjustSalaryLoan']",function () {

      // for backing its required field
        $("input[name='adjust_datePayment']").attr("required","required");
        $("input[name='adjust_remainingBalance']").attr("required","required");
        $("input[name='adjust_cashPayment']").attr("required","required"); // 
        $("input[name='adjust_newRemainingBalance']").attr("required","required");
        $("input[name='adjust_remarks']").attr("required","required");


        // empName
        if ($("input[name='adjust_datePayment']").val() != "" && $("input[name='adjust_remainingBalance']").val() != "" && $("input[name='adjust_cashPayment']").val() != "" && $("input[name='adjust_newRemainingBalance']").val() != "" && $("input[name='adjust_remarks']").val() != ""){
            // alert(global_emp_id);
             $("input[name='adjust_empName']").after("<input type='hidden' name='adjust_salaryLoanId' value='"+adjust_salaryLoan_id+"' />");
             $("#form_adjustSalaryLoan").attr("action","php script/adjust_salaryLoan.php");
        }
 
     });


      // for submitting/ inserting payroll information add_payroll_info.php
       $("a[id='print_loanAdjustmentReports']").on("click", function () {
          var datastring = "adjustment_loan_id="+$(this).closest("tr").attr("id");
          //alert(datastring);
          $.ajax({
              type: "POST",
              url: "ajax/script_print_adjustmentLoanReports.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
               if (data == "Error"){
                  $("#errorModal").modal("show");
                }
                // if success
                else {       
                  //alert(data);
                  window.open("print_adjustment_loan_reports.php");
                }
                
              }
           });
       });

       // adjust_simkimban
       $("a[id='adjust_simkimban']").on("click", function () {
           var datastring = "simkimban_id="+$(this).closest("tr").attr("id");
           //alert(datastring);
            
           var simkimban_id = $(this).closest("tr").attr("id"); // update_pagibigLoan_pagibigLoan_id



           $.ajax({
              type: "POST",
              url: "ajax/append_adjust_simkimban.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
               if (data == "Error") {
                  $("#errorModal").modal("show");
               }
               // if success
               else {
                 $("#adjust_simkimbanForm_body").html(data);
                 $("#adjustLoanModal").modal("show");
                 adjust_simkimban_id = simkimban_id;
                // alert(adjust_simkimban_id);
               }

             }
           });
      });


         // for onchange ng cashpayment compute the new outstanding balance adjust_cashPayment
      $("#adjust_simkimbanForm_body").change("input[name='adjust_cashPayment']",function(){
          var currentBalance = $("input[name='adjust_remainingBalance']").val();
          var cashPayment = $("input[name='adjust_cashPayment']").val();

          if (cashPayment != ""){
            var newBalance = parseFloat(currentBalance) - parseFloat(cashPayment);
            // for 2 decimal places
            newBalance = newBalance.toString().split('e');
            newBalance = Math.round(+(newBalance[0] + 'e' + (newBalance[1] ? (+newBalance[1] + 2) : 2)));

            newBalance = newBalance.toString().split('e');
            newBalance=  (+(newBalance[0] + 'e' + (newBalance[1] ? (+newBalance[1] - 2) : -2))).toFixed(2);
            $("input[name='adjust_newRemainingBalance']").val(newBalance);

          }

          if (cashPayment == ""){
            $("input[name='adjust_newRemainingBalance']").val(""); // for refreshing the value
          }


     });


        // for adjustment BTN
      $("#adjust_simkimbanForm_body").on("click","input[id='adjustSimkimban']",function () {

      // for backing its required field
        $("input[name='adjust_datePayment']").attr("required","required");
        $("input[name='adjust_remainingBalance']").attr("required","required");
        $("input[name='adjust_cashPayment']").attr("required","required"); // 
        $("input[name='adjust_newRemainingBalance']").attr("required","required");
        $("input[name='adjust_remarks']").attr("required","required");


        // empName
        if ($("input[name='adjust_datePayment']").val() != "" && $("input[name='adjust_remainingBalance']").val() != "" && $("input[name='adjust_cashPayment']").val() != "" && $("input[name='adjust_newRemainingBalance']").val() != "" && $("input[name='adjust_remarks']").val() != ""){
            // alert(global_emp_id);
             $("input[name='adjust_empName']").after("<input type='hidden' name='adjust_simkimbanId' value='"+adjust_simkimban_id+"' />");
             $("#form_adjustSimkimban").attr("action","php script/adjust_simkimban.php");
        }
 
     });

// print_adjustment_simkimban_reports.php
   // for submitting/ inserting payroll information add_payroll_info.php
       $("a[id='print_simkimbanAdjustmentReports']").on("click", function () {
          var datastring = "adjustment_loan_id="+$(this).closest("tr").attr("id");
          //alert(datastring);
          $.ajax({
              type: "POST",
              url: "ajax/script_print_adjustmentLoanReports.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
               if (data == "Error"){
                  $("#errorModal").modal("show");
                }
                // if success
                else {       
                  //alert(data);
                  window.open("print_adjustment_simkimban_reports.php");
                }
                
              }
           });
       });



       // for updating payroll info
      $("div[id='update_payroll_info']").on("click", function () {
          var datastring = "emp_id="+$(this).closest("tr").attr("id");
          var emp_id = $(this).closest("tr").attr("id");
          $("#update_payrollInfo_body").html("<center><div class='loader'></div>Loading Information</center>");
          $.ajax({
              type: "POST",
              url: "ajax/script_check_exist_emp_id_payroll.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
               if (data == "Error"){
                  $("#errorModal").modal("show");
                }
                // if success
                else {       
                    $("#update_payrollInfo_body").html(data);
                    $("#updateFormModal").modal("show");
                    update_payroll_info_emp_id = emp_id;
                  //  alert(update_payroll_info_emp_id);
                }
                
              }
           });


      });

      // updatePayrollInfo
      $("#update_payrollInfo_body").on("click","input[id='updatePayrollInfo']",function () {
          var totalGrossIncome = $("input[name='totalGrossIncome']").val();
          var earningsAdjustment = $("input[name='earningsAdjustment']").val();

          var totalDeductions = $("input[name='totalDeductions']").val();
          var deductionAdjustment = $("input[name='deductionAdjustment']").val();

          var tax = $("input[name='tax']").val();
          
          var allowance = $("input[name='nontaxAllowance']").val();
          var adjustment = $("input[name='afterAdjustment']").val();

          var netPay = $("input[name='netPay']").val();

          if (totalGrossIncome != "" && earningsAdjustment!= "" && totalDeductions != "" && deductionAdjustment != "" && tax != "" && allowance != "" && adjustment!= "" && netPay!= ""){
             
              $("input[name='empName']").after("<input type='hidden' name='update_payrollEmpId' value='"+update_payroll_info_emp_id+"' />");
              $("#form_updatePayrollInfo").attr("action","php script/updatePayrollInfo.php");

              //$("#form_updatePayrollInfo").
          }
         
      });


      // edit_cashbond
      $("a[id='edit_cashbond']").on("click",function () {
          //alert("Hello World!");
         var datastring = "cashbond_id="+$(this).closest("tr").attr("id");
        
          $.ajax({
              type: "POST",
              url: "ajax/append_edit_cashbond.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
               if (data == "Error"){
                  $("#errorModal").modal("show");
                }
                // if success
                else {       
                    $("#update_cashbondForm_body").html(data);
                    $("#updateFormModal").modal("show");
                }
                
              }
           });
         
      });


      // for adjust cashbond
      $("a[id='adjust_cashbond']").on("click",function(){
           var datastring = "cashbond_id="+$(this).closest("tr").attr("id");
           //alert(datastring);
            $.ajax({
              type: "POST",
              url: "ajax/append_adjust_cashbond.php",
              data: datastring,
              cache: false,
              success: function (data) {
                 if (data == "Error"){
                    $("#errorModal").modal("show");
                  }
                  // if success
                  else {       
                      $("#adjust_cashbondForm_body").html(data);
                      $("#adjustFormModal").modal("show");
                  }
              }
            });
      });


       $("input[id='add_working_hours']").on("click",function () {

        // for setting its required attr 
          $("input[name='hour_time_in']").attr("required","required");
          $("input[name='min_time_in']").attr("required","required");
          $("input[name='sec_time_in']").attr("required","required");
          $("input[name='hour_time_out']").attr("required","required");
          $("input[name='min_time_out']").attr("required","required");
          $("input[name='sec_time_out']").attr("required","required");

          if ($("input[name='hour_time_in']").val != "" && $("input[name='min_time_in']").val() != "" && $("input[name='sec_time_in']").val() != ""  && $("input[name='hour_time_out']").val() != "" && $("input[name='min_time_out']").val() != "" && $("input[name='sec_time_out']").val() != ""){
             $("#form_addWorkingHours").attr("action","php script/add_working_hours.php");
          }

       });


       // for editing working hours 
       $("a[id='edit_working_hours']").on("click",function () {
            var datastring = "working_hours_id=" + $(this).closest("tr").attr("id");
            //alert(datastring);

             $.ajax({
              type: "POST",
              url: "ajax/append_edit_working_hours.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
                if (data == "Error"){
                  $("#errorModal").modal("show");
                }
                // if success
                else {       
                    $("#update_workingHoursForm_body").html(data);
                    $("#updateFormModal").modal("show");
                }
                
              }
           });

      });

      // for deleting working hours 
      $("a[id='delete_working_hours']").on("click",function () {
            var datastring = "working_hours_id=" + $(this).closest("tr").attr("id");
            //alert(datastring);

            var working_hours_id = $(this).closest("tr").attr("id");

             $.ajax({
              type: "POST",
              url: "ajax/append_delete_working_hours.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
               if (data == "Error"){
                  $("#errorModal").modal("show");
                }
                // if success
                else {                         
                    $("#delete_modal_body").html(data);
                    $("#deleteWorkingHoursConfirmationModal").modal("show");
                    delete_working_hours_id = working_hours_id;

                }
                
              }
           });

      });


      // for delete yes working hours 
      $("a[id='delete_yes_workingHours']").on("click",function () {
           // delete_working_hours.php
           $("#form_deleteWorkingHours").append("<input type='hidden' name='working_hours_id' value='"+delete_working_hours_id+"' />");
           $("#form_deleteWorkingHours").attr("action","php script/delete_working_hours.php");
           $("#form_deleteWorkingHours").submit();
      });

      // for uploading 201 FILES
      $("button[id='upload_201_files_btn']").on("click",function () {
        var valid_extensions = /(\.jpg|\.jpeg|\.png)$/i;
        var files_201_name =  $("input[name='files_201_name']").val(); 
         // if failed
         if ($("input[name='201_files_pic_file[]']").val() != "" && files_201_name != ""){
           if (!valid_extensions.test($("input[name='201_files_pic_file[]']").val())){
             
              $("#upload_201_files_profile_msg").append('<div class="col-xs-12"><p style="color:#CB4335"><span class="glyphicon glyphicon-remove"></span> You must upload only image file</p></div>');

            }
          //  if success
          else {
              //alert(upload_201_files_emp_id)
              //$("#form_201_files").append("<input type='hidden' name='files_201_name' value='"+files_201_name+"'/>");
              $("#form_201_files").append("<input type='hidden' name='201_files_emp_id' value='"+upload_201_files_emp_id+"'/>");
              $("#form_201_files").submit();
              //alert("Success!");
           }
         }
         else {

           

              if (files_201_name == ""){
                $("#upload_201_files_profile_msg").html('<div class="col-xs-12"><p style="color:#CB4335"><span class="glyphicon glyphicon-remove"></span> Please provide a 201 file name</p></div>');
              }

              else if ($("input[name='201_files_pic_file[]']").val() == ""){

                $("#upload_201_files_profile_msg").html('<div class="col-xs-12"><p style="color:#CB4335"><span class="glyphicon glyphicon-remove"></span> Please upload image file</p></div>');
              }
         }
      });


      // for description 
      $("button[id='description']").on("click",function () {

          var datastring = "files201_id=" + $("div[class='item active']").attr("id");

          var files201_image_id = $("div[class='item active']").attr("id");

            $.ajax({
              type: "POST",
              url: "ajax/append_update_files201_description.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
               if (data == "Error"){
                  $("#errorModal").modal("show");
                }
                // if success
                else {
                    $("#update_Form_body").html(data);                   
                    $("#updateFormModal").modal("show");
                    update_201_files_description_id = files201_image_id;
                }
                
              }
           });

      });


      // for 201 files saving description button 
      $("#update_Form_body").on("click","input[id='save_files_201_btn']",function () {
            $("input[name='description']").attr("required","required");

            if ($("input[name='description']") != ""){       
                $("#form_updateDescription_files201").attr("action","php script/update_description_files201.php");
                $("#form_updateDescription_files201").append("<input type='hidden' value='"+update_201_files_description_id+"' name='files201_id' />");
                $("#form_updateDescription_files201").submit();
            }


      });

      // for updating files 201 image 
      $("button[id='update_image']").on("click",function () {
          var datastring = "files201_id=" + $("div[class='item active']").attr("id");

          var files201_image_id = $("div[class='item active']").attr("id");

            $.ajax({
              type: "POST",
              url: "ajax/append_update_files201_image.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
               if (data == "Error"){
                  $("#errorModal").modal("show");
                }
                // if success
                else {  
                    $("span[id='update_header_files201_image']").html("Update 201 File - " + data);              
                    $("#update_201FileModal").modal("show");
                    update_201_files_image_id = files201_image_id;
                }
                
              }
           });

      });


      // update_files201_image
      $("button[id='update_files201_image']").on("click",function () {
         var valid_extensions = /(\.jpg|\.jpeg|\.png)$/i;
         // if failed
         if ($("input[name='201_files_pic_file']").val() != ""){
           if (!valid_extensions.test($("input[name='201_files_pic_file']").val())){
             
              $("#upload_201_files_profile_msg").append('<div class="col-xs-12"><p style="color:#CB4335"><span class="glyphicon glyphicon-remove"></span> You must upload only image file</p></div>');

            }
          //  if success
          else {
              //alert(upload_201_files_emp_id)
              $("#form_update_images_201_files").append("<input type='hidden' name='201_files_id' value='"+update_201_files_image_id+"'/>");
              $("#form_update_images_201_files").submit();
              //alert("Success!");
           }
         }
         else {
             $("#upload_201_files_profile_msg").html('<div class="col-xs-12"><p style="color:#CB4335"><span class="glyphicon glyphicon-remove"></span> Please upload image file</p></div>');
         }

      });


      // for removing image 
      $("button[id='remove_image']").on("click",function () {
          var datastring = "files201_id=" + $("div[class='item active']").attr("id");

          var files201_image_id = $("div[class='item active']").attr("id");

            $.ajax({
              type: "POST",
              url: "ajax/append_update_files201_image.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
               if (data == "Error"){
                  $("#errorModal").modal("show");
                }
                // if success
                else {  
                    $("span[id='delete_header_files201_image']").html("Delete 201 File - " + data);              
                    $("#deleteFiles201ImageConfirmationModal").modal("show");
                    delete_201_files_image_id = files201_image_id;

                }
                
              }
           });

      });


      // for continuing deleting 
      $("a[id='delete_yes_files201_image']").on("click",function () {
           $("#form_deleteFiles201").attr("action","php script/delete_image_files201.php");
           $("#form_deleteFiles201").append("<input type='hidden' value='"+delete_201_files_image_id+"' name='201_files_id'/>");
           $("#form_deleteFiles201").submit();

      });


      // for viewing full image 
      $("img[id='view_full_files201_image']").on("click",function () {
          var datastring = "files201_id=" + $("div[class='item active']").attr("id");

          var files201_image_id = $("div[class='item active']").attr("id");

            $.ajax({
              type: "POST",
              url: "ajax/append_view_files201_image.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
               if (data == "Error"){
                  $("#errorModal").modal("show");
                }
                // if success
                else {  
                    //alert(data);
                    $("div[id='modal_content_view']").html(data);  
                    $("#view_files201Modal").modal("show");
                                
                    //$("#deleteFiles201ImageConfirmationModal").modal("show");
                    //delete_201_files_image_id = files201_image_id;

                }
                
              }
           });
      });


      // for filing of salary loan 
      $("div[id='file_salary_loan']").on("click",function () {
         window.location = "file_salary_loan.php";
      });




      $("div[id='view_file_ot_attendance']").on("click",function(){
          $("#viewFileOTAddAttendanceModal").modal("show");
      });

      // for click attendance notification
      $("div[id='notif-li']").on("click",function(){
          var attendance_notification_id = $(this).closest("li").attr("id");
          var datastring = "attendance_notification_id=" + attendance_notification_id;
          //alert(datastring);

          $.ajax({
              type: "POST",
              url: "ajax/script_check_exist_attendance_notification_id.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
               if (data == "Error"){
                  alert("There's an error during getting of data information please reload the page");
                }
                // if success
                else {  
                    var type = data;
                    //alert(type);
                    if (type == "Upload Attendance") {
                      window.location = "view_attendance.php";
                    }
                    else {
                      window.location = "attendance_notification_page.php?type="+type+"&id="+attendance_notification_id; 
                    }
                                    
                }
                
              }
           });
      });


     /* $("input[name='dateFromFileSalaryLoan']").change(function(){

          var totalMonths = $("select[name='totalMonths']").val();

          alert(totalMonths);

          var currentDate = new Date($(this).val());
          var currentMonth = currentDate.setMonth(currentDate.getMonth() + 7);

          //alert(currentMonth);
          var month = currentDate.getMonth();
          //alert(getMonth);

          var currentYear = currentDate.getFullYear();
          var currentDay = currentDate.getDate();
          var newDate = month + "/" + currentDay + "/" + currentYear;
          $("input[name='dateTo']").val(newDate);    
         
      });
    */

      // date
     $("select[name='totalMonths']").focus(function(){
     // alert("wew");

          var dateformat = /^(0[1-9]|1[012])[\/\-](0[1-9]|[12][0-9]|3[01])[\/\-]\d{4}$/;
          
          var dateFromMonth = $("input[name='dateFromMonth']").val();
          var dateFromDay = $("input[name='dateFromDay']").val();
          var dateFromYear = $("input[name='dateFromYear']").val();

          if (dateFromMonth == "" || dateFromDay == "" || dateFromYear){
             $(this).html("");
              $("div[id='errorTotalMonths']").html("<span class='glyphicon glyphicon-remove'></span> Please provide a <b>Date From</b> first");
          }

         /* else if (!dateFrom.match(dateformat)){
              $(this).html("");
              $("div[id='errorTotalMonths']").html("<span class='glyphicon glyphicon-remove'></span> Please provide a <b> Valid Date From</b>");
          }*/

          else {
              $("div[id='errorTotalMonths']").html("");
              //var option = "<option value=''></option>";

              var option0 = "";
              if ($("select[name='deductionType']").val() == "" || $("select[name='deductionType']").val() == "Monthly"){
                //var option0 = "<option value='0'>0</option>";
                var option1 = "<option value='1'>1</option>";
                var option2 = "<option value='2'>2</option>";
                var option3 = "<option value='3'>3</option>";
                var option4 = "<option value='4'>4</option>";
                var option5 = "<option value='5'>5</option>";
                var option6 = "<option value='6'>6</option>";
                var option7 = "<option value='7'>7</option>";
                var option8 = "<option value='8'>8</option>";
                var option9 = "<option value='9'>9</option>";
                var option10 = "<option value='10'>10</option>";
                var option11 = "<option value='11'>11</option>";
                var option12 = "<option value='12'>12</option>";
              }
              else {
                var option0 = "<option value='0'>0</option>";
                var option1 = "<option value='1'>1</option>";
                var option2 = "<option value='2'>2</option>";
                var option3 = "<option value='3'>3</option>";
                var option4 = "<option value='4'>4</option>";
                var option5 = "<option value='5'>5</option>";
                var option6 = "<option value='6'>6</option>";
                var option7 = "<option value='7'>7</option>";
                var option8 = "<option value='8'>8</option>";
                var option9 = "<option value='9'>9</option>";
                var option10 = "<option value='10'>10</option>";
                var option11 = "<option value='11'>11</option>";
                var option12 = "<option value='12'>12</option>";
              
              }

              var allOption =option0+ option1+option2+option3+option4+option5+option6+option7+option8+option9+option10+option11+option12;

              $(this).html(allOption);


            
          }


    });


    // for next year
    function isLeapYear(year) { 
        return (((year % 4 === 0) && (year % 100 !== 0)) || (year % 400 === 0)); 
    }

    function getDaysInMonth(year, month) {
        return [31, (isLeapYear(year) ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][month];
    }

    function addMonths(date, value) {
        var d = new Date(date),
            n = date.getDate();
        d.setDate(1);
        d.setMonth(d.getMonth() + value);
        d.setDate(Math.min(n, getDaysInMonth(d.getFullYear(), d.getMonth())));
        return d;
    }

  
    $("select[name='totalMonths']").change(function(){

       // alert("HELLO WORLD!");

         //alert("Wew");
          /*var totalMonths = parseInt($(this).val()) + 1;

    


          // alert(totalMonths);

          var currentDate = new Date($("input[name='dateFromFileSalaryLoan']").val());
          var currentMonth = currentDate.setMonth(currentDate.getMonth() + totalMonths);

          //alert(currentMonth);
          var month = currentDate.getMonth();

          //alert(month);

          // for december
          if (month == 0){
              month = 12;
             
          }




          

          //alert(getMonth);

          var currentYear = currentDate.getFullYear();

          if (month == 0){
              currentYear = currentYear - 1;
          }

          var currentDay = currentDate.getDate();

          var newDate = month + "/" + currentDay + "/" + currentYear;
          $("input[name='dateTo']").val(newDate);
        */
        var totalMonths = parseFloat($(this).val());

      //  alert(totalMonths);

        if (totalMonths != 0) {

          var dateFrom = $("select[name='dateFromMonth']").val() + "/"+$("select[name='dateFromDay']").val() +"/"+$("select[name='dateFromYear']").val();

         // alert(dateFrom);

          //alert(totalMonths);
          var nextMonth = addMonths(new Date(dateFrom), totalMonths);

         // var newYear = addMonths(new Date($("input[name='dateFromFileSalaryLoan']").val()), $(this).val());

          //alert(nextMonth);


          var currentMonth = nextMonth.getMonth() + 1;
          //alert(currentMonth);
          //alert(currentMonth);
          if (currentMonth == 0){
              currentMonth= 12;
          }

          //alert("Hello World!");
         // alert(nextMonth);







          var currentDate = new Date(dateFrom);
          var currentDay = currentDate.getDate();

          var currentYear = nextMonth.getFullYear();

        
        if ($("select[name='deductionType']").val() == "Semi-monthly") {
          if (currentDay == 30){
              currentDay = 15;
           }

           else if (currentDay == 15){
              currentDay = 30;
              currentMonth = currentMonth - 1;
           }      
         }


          if (currentMonth == 2 && currentDay == 30){
                currentDay = 28;
           }



         if (currentDay == 28){
            currentDay = 30;
         }


          //alert(currentYear);

          //if (currentDay)

          var newDate = currentMonth + "/" + currentDay + "/" + currentYear;

          //alert(newDate);

          $("input[name='dateTo']").val(newDate);
        }

        else {
          currentMonth = $("select[name='dateFromMonth']").val();
          currentDay = $("select[name='dateFromDay']").val();
          currentYear = $("select[name='dateFromYear']").val();

           var newDate = currentMonth + "/" + currentDay + "/" + currentYear;
           $("input[name='dateTo']").val(newDate);
        }

          $("input[name='amountLoan']").removeAttr("readonly"); 

          // if may value
          var amountLoan = $("input[name='amountLoan']").val();
          if (amountLoan != ""){

             var totalMonths = $("select[name='totalMonths']").val();

             var deductionType = $("select[name='deductionType']").val();

            if (deductionType != "" && deductionType != "Semi-monthly" && deductionType != "Monthly"){
              $("div[id='error_message']").html("<span class='glyphicon glyphicon-remove' style='color:#CB4335;'></span> There's an error during submitting information please refresh the page.");
            }

            else if (totalMonths == 0){
              $("input[name='deduction']").val(amountLoan);
            }

            else {
              if (deductionType == "Semi-monthly" ) {
                  totalMonths = parseInt(totalMonths) * 2;
              }

              if (deductionType == "" ) {
                  $("div[id='errorDeduction']").html("<span class='glyphicon glyphicon-remove'></span> Please select <b>Deduction Type</b> first.");
                  $("input[name='deduction']").val("");
              }

              else {
                //alert("wew");

                 $.ajax({
                    type: "POST",
                    url: "ajax/script_check_exist_loan.php",
                    //data: datastring,
                    cache: false,
                    success: function (data) {
                  //    alert(data);

                     if (data == "exist" || $("input[name='amountLoan']").val() != $("input[name='totalPayment']").val()) {
                        interest = (parseFloat(amountLoan) * .036) * (parseFloat($("select[name='totalMonths']").val()));   
                                                            
                      }

                      else {
                        
                        interest  = (parseFloat(amountLoan) * 0) * (parseFloat($("select[name='totalMonths']").val()));
                      }


                    var totalPayment = parseFloat(amountLoan) + parseFloat(interest);
                    var deduction = parseFloat(totalPayment) / parseFloat(totalMonths);

                    // for 2 decimal places
                    totalPayment = totalPayment.toString().split('e');
                    totalPayment = Math.round(+(totalPayment[0] + 'e' + (totalPayment[1] ? (+totalPayment[1] + 2) : 2)));

                    totalPayment = totalPayment.toString().split('e');
                    final_totalPayment =  (+(totalPayment[0] + 'e' + (totalPayment[1] ? (+totalPayment[1] - 2) : -2))).toFixed(2);
                    $("input[name='totalPayment']").val(final_totalPayment);

                     // for 2 decimal places
                    deduction = deduction.toString().split('e');
                    deduction = Math.round(+(deduction[0] + 'e' + (deduction[1] ? (+deduction[1] + 2) : 2)));

                    deduction = deduction.toString().split('e');
                    final_deduction =  (+(deduction[0] + 'e' + (deduction[1] ? (+deduction[1] - 2) : -2))).toFixed(2);

                     $("input[name='deduction']").val(final_deduction);

                }
              });
            }
          }
        }

    });

  
  // for changing date from month
    $("select[name='dateFromMonth']").change(function(){
        var dateFromMonth = $(this).val();
       // alert(dateFromMonth);
       if (dateFromMonth == 2){
          $("select[name='dateFromDay']").html("<option value='15'>15</option><option value='28'>28</option>");
       }
       else {
          $("select[name='dateFromDay']").html("<option value='15'>15</option><option value='30'>30</option>");
       }
    });



    
    // for changing amount loan amountLoan
    $("input[name='amountLoan']").change(function(){
        var totalMonths = $("select[name='totalMonths']").val();
          
      //  alert("wew");
        //alert(totalMonths);

        var amountLoan = $(this).val();
        var interest  = 0;
        // .php
       // var user_id = $("input[name='user_id']").val();
       //// alert(user_id);
        $.ajax({
          type: "POST",
          url: "ajax/script_check_exist_loan.php",
          //data: datastring,
          cache: false,
          success: function (data) {
               // alert(data);
            
                if (amountLoan == ""){
                   $("input[name='deduction']").val("");
                }

                else if (totalMonths == 0){
                  $("input[name='deduction']").val(amountLoan);
                }
                else {

                  var deductionType = $("select[name='deductionType']").val();
                  if (deductionType != "" && deductionType != "Semi-monthly" && deductionType != "Monthly"){
                    $("div[id='error_message']").html("<span class='glyphicon glyphicon-remove' style='color:#CB4335;'></span> There's an error during submitting information please refresh the page.");
                  }
                  else {

                  
                    if (deductionType == "Semi-monthly" ) {
                      totalMonths = parseInt(totalMonths) * 2;
                    }

                    if (deductionType == "" ) {
                      $("div[id='errorTotalPayment']").html("<span class='glyphicon glyphicon-remove'></span> Please select <b>Deduction Type</b> first.");
                      $("input[name='totalPayment']").val("");


                      $("div[id='errorDeduction']").html("<span class='glyphicon glyphicon-remove'></span> Please select <b>Deduction Type</b> first.");
                      $("input[name='deduction']").val("");
                    }

                    else {
                      // alert(totalMonths);

                      // if (data == "exist") {
                        interest = (parseFloat(amountLoan) * .036) * (parseFloat($("select[name='totalMonths']").val()));   
                                                            
                      /*}

                      else {
                        
                        interest  = (parseFloat(amountLoan) * 0) * (parseFloat($("select[name='totalMonths']").val()));
                      }*/




                      var totalPayment = parseFloat(amountLoan) + parseFloat(interest);
                      var deduction = parseFloat(totalPayment) / parseFloat(totalMonths);

                      // for 2 decimal places
                      totalPayment = totalPayment.toString().split('e');
                      totalPayment = Math.round(+(totalPayment[0] + 'e' + (totalPayment[1] ? (+totalPayment[1] + 2) : 2)));

                      totalPayment = totalPayment.toString().split('e');
                      final_totalPayment =  (+(totalPayment[0] + 'e' + (totalPayment[1] ? (+totalPayment[1] - 2) : -2))).toFixed(2);
                      $("input[name='totalPayment']").val(final_totalPayment);

                      // for 2 decimal places
                      deduction = deduction.toString().split('e');
                      deduction = Math.round(+(deduction[0] + 'e' + (deduction[1] ? (+deduction[1] + 2) : 2)));

                      deduction = deduction.toString().split('e');
                      final_deduction =  (+(deduction[0] + 'e' + (deduction[1] ? (+deduction[1] - 2) : -2))).toFixed(2);
                      $("input[name='deduction']").val(final_deduction);
                    }
                  }
                }
            
          
            
          }
       });


        

    });

    // error_message
    $("button[id='file_salary_btn']").on("click",function(){
       // alert("Wew");
         var deductionType = $("select[name='deductionType']").val();
         var dateFrom = $("input[name='dateFromFileSalaryLoan']").val();
         var totalMonths = $("input[name='totalMonths']").val();
         var dateTo = $("input[name='dateTo']").val();
         var amountLoan = $("input[name='amountLoan']").val();
         var deduction = $("input[name='deduction']").val(); // totalPayment
         var totalPayment = $("input[name='totalPayment']").val(); 
         var remarks = $("textarea[name='remarks']").val();

         //alert(deductionType);

         // for please up all fields
         if (deductionType == "" || dateFrom == "" || totalMonths == "" || dateTo== "" || amountLoan == "" || totalPayment == "" || deduction == "" || remarks == ""){
            $("div[id='error_message']").html("<span class='glyphicon glyphicon-remove' style='color:#CB4335;'></span> Please fill up all fields.");
         }

         else if (deductionType == "Monthly" && !$("input[name='opt_deductedPayrollDate']").is(':checked')){
            $("div[id='error_message']").html("<span class='glyphicon glyphicon-remove' style='color:#CB4335;'></span> Please choose date payroll to be deducted.");
         }

         // if edited in the inspect element
         else if (deductionType != "" && deductionType != "Semi-monthly" && deductionType != "Monthly"){
            $("div[id='error_message']").html("<span class='glyphicon glyphicon-remove' style='color:#CB4335;'></span> There's an error during submitting information please refresh the page.");
         }

         else {
            $("#form_fileSalaryLoan").attr("action","php script/script_file_salary_loan.php");
            $("#form_fileSalaryLoan").submit();
         }
    });


    // for selecting deduction type
    $("select[name='deductionType']").change(function(){
        var deductionType = $(this).val();

        if (deductionType == ""){
            //$("div[id='errorDeduction']").html("<span class='glyphicon glyphicon-remove'></span> Please select <b>Deduction Type</b> first.");
            $("input[name='deduction']").val("");
            $("input[name='opt_deductedPayrollDate']").attr("disabled","disabled");
            $("input[name='opt_deductedPayrollDate']").removeAttr("checked",false);
        }
        else {

          if (deductionType != "" && (deductionType != "Semi-monthly" && deductionType != "Monthly")){
            $("div[id='error_message']").html("<span class='glyphicon glyphicon-remove' style='color:#CB4335;'></span> There's an error during submitting information please refresh the page.");
          }
          else {
            if (deductionType == "Semi-monthly") {
                $("input[name='opt_deductedPayrollDate']").attr("disabled","disabled");
                $("input[name='opt_deductedPayrollDate']").removeAttr("checked",false);
            }


            if (deductionType == "Monthly") {
                $("input[name='opt_deductedPayrollDate']").removeAttr("disabled");
            }

            var totalMonths = $("select[name='totalMonths']").val();


            var deductionType = $("select[name='deductionType']").val();



            if (deductionType == "Semi-monthly" ) {
              totalMonths = parseInt(totalMonths) * 2;

            }

            var amountLoan = $("input[name='amountLoan']").val();

            if (amountLoan != ""){


               $.ajax({
                    type: "POST",
                    url: "ajax/script_check_exist_loan.php",
                    //data: datastring,
                    cache: false,
                    success: function (data) {



                      if (data == "exist" || $("input[name='amountLoan']").val() != $("input[name='totalPayment']").val()) {
                        interest = (parseFloat(amountLoan) * .036) * (parseFloat($("select[name='totalMonths']").val()));   
                                                            
                      }

                      else {
                        
                        interest  = (parseFloat(amountLoan) * 0) * (parseFloat($("select[name='totalMonths']").val()));
                      }


                      var totalPayment = parseFloat(amountLoan) + parseFloat(interest);
                      var deduction = parseFloat(totalPayment) / parseFloat(totalMonths);

                      // for 2 decimal places
                      totalPayment = totalPayment.toString().split('e');
                      totalPayment = Math.round(+(totalPayment[0] + 'e' + (totalPayment[1] ? (+totalPayment[1] + 2) : 2)));

                      totalPayment = totalPayment.toString().split('e');
                      final_totalPayment =  (+(totalPayment[0] + 'e' + (totalPayment[1] ? (+totalPayment[1] - 2) : -2))).toFixed(2);
                      $("input[name='totalPayment']").val(final_totalPayment);
              

                      // for 2 decimal places
                      deduction = deduction.toString().split('e');
                      deduction = Math.round(+(deduction[0] + 'e' + (deduction[1] ? (+deduction[1] + 2) : 2)));

                      deduction = deduction.toString().split('e');
                      final_deduction =  (+(deduction[0] + 'e' + (deduction[1] ? (+deduction[1] - 2) : -2))).toFixed(2);

                      $("input[name='deduction']").val(final_deduction);
                    }
              });

             
            }
          }
         
       }
    });

    // for selecting deduction type in manual by payroll admin for those has exist salaryloan
    $("select[name='deductionTypeExist']").change(function(){
        var deductionType = $(this).val();

        if (deductionType == ""){
            //$("div[id='errorDeduction']").html("<span class='glyphicon glyphicon-remove'></span> Please select <b>Deduction Type</b> first.");
            $("input[name='deduction']").val("");
            $("input[name='opt_deductedPayrollDate']").attr("disabled","disabled");
            $("input[name='opt_deductedPayrollDate']").removeAttr("checked",false);
        }
        else {

          
            if (deductionType == "Semi-monthly") {
                $("input[name='opt_deductedPayrollDate']").attr("disabled","disabled");
                $("input[name='opt_deductedPayrollDate']").removeAttr("checked",false);
            }


            if (deductionType == "Monthly") {
                $("input[name='opt_deductedPayrollDate']").removeAttr("disabled");
            }

            
        }
         
       
    });


    // for approving the salary loan 
     $("span[id='approve_salaryLoan']").on("click",function(){
          var file_salary_loan_id = $(this).closest("tr").attr("id");
          var approve = $(this).attr("title");

          var datastring = "file_salary_loan_id=" +file_salary_loan_id + "&approve="+approve;
        

          $.ajax({
              type: "POST",
              url: "ajax/append_approve_file_salary_loan.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
               if (data == "Error"){
                  alert("There's an error during getting of data information please reload the page");
                }
                // if success
                else { 
                    //alert(data);
                    window.location = "approve_file_salary_loan.php?id="+file_salary_loan_id;
                              
                }
                
              }
           });
     });


     // error_message
    $("button[id='approve_file_salary_loan']").on("click",function(){
          
        
         var deductionType = $("select[name='deductionType']").val();
         var dateFromMonth = $("select[name='dateFromMonth']").val();
         var dateFromDay = $("select[name='dateFromDay']").val();
         var dateFromYear = $("select[name='dateFromYear']").val();
         //alert(dateFrom);

         var totalMonths = $("input[name='totalMonths']").val();
         var dateTo = $("input[name='dateTo']").val();
         var amountLoan = $("input[name='amountLoan']").val();
         var deduction = $("input[name='deduction']").val();
         var totalPayment = $("input[name='totalPayment']").val();
         var remarks = $("textarea[name='remarks']").val();
         //alert(deductionType);
         
         // for please up all fields
         if (deductionType == "" || totalMonths == "" || dateTo== "" || amountLoan == "" || totalPayment == "" || deduction == "" || remarks == ""){
            $("div[id='error_message']").html("<span class='glyphicon glyphicon-remove' style='color:#CB4335;'></span> Please fill up all fields.");
         }

         else if (deductionType == "Monthly" && !$("input[name='opt_deductedPayrollDate']").is(':checked')){
            $("div[id='error_message']").html("<span class='glyphicon glyphicon-remove' style='color:#CB4335;'></span> Please choose date payroll to be deducted.");
         }

         // if edited in the inspect element
         else if (deductionType != "" && deductionType != "Semi-monthly" && deductionType != "Monthly"){
            $("div[id='error_message']").html("<span class='glyphicon glyphicon-remove' style='color:#CB4335;'></span> There's an error during submitting information please refresh the page.");
         }

         else {
            $("#form_fileSalaryLoan").attr("action","php script/script_approve_file_salary_loan.php");
            //$("#form_fileSalaryLoan").append("");
            $("#form_fileSalaryLoan").submit();
         }
    });


    // for disapproving salary loan
    $("span[id='dis_approve_salaryLoan']").on("click",function(){
          var file_salary_loan_id = $(this).closest("tr").attr("id");

          var datastring = "file_salary_loan_id=" +file_salary_loan_id;


          $.ajax({
            type: "POST",
            url: "ajax/append_disapprove_file_salary_loan.php",
            data: datastring,
            cache: false,
            success: function (data) {
            // if has error 
              if (data == "Error"){
              alert("There's an error during getting of data information please reload the page");
              }
              // if success
              else { 
                  $("#disapprove_modal_body").html(data);
                  $("#disapproveFileSalaryLoanConfirmationModal").modal("show");
                  disapprove_file_salary_loan_id = file_salary_loan_id;                      
              }
            }
          });
    });



    // for disapproveing file salary loan
    $("a[id='disapprove_yes_fileSalaryLoan']").on("click",function(){
          $(this).attr("href","php script/script_disapprove_file_salary_loan.php?file_salary_loan_id="+disapprove_file_salary_loan_id);
    });


    // for accepting file salary loan accept_file_salary_loan
    $("button[id='accept_file_salary_loan']").on("click",function(){

        // alert("Hello World!");
        $.ajax({
          type: "POST",
          url: "ajax/append_accept_file_salary_loan.php",
          //data: datastring,
          cache: false,
          success: function (data) {
          // if has error 
            if (data == "Error"){
              alert("There's an error during getting of data information please reload the page");
            }
            // if success
            else { 
                $("#accept_modal_body").html(data);
                $("#acceptFileSalaryLoanConfirmationModal").modal("show");               
            }
          }
        });

        
    });



    // if click yes accept the new file salary loan
    $("a[id='accept_yes_fileSalaryLoan']").on("click",function(){
          window.location = "php script/script_accept_file_salary_loan.php";
    });


    // for declining
    $("button[id='decline_file_salary_loan']").on("click",function(){

        // alert("Hello World!");
        $.ajax({
          type: "POST",
          url: "ajax/append_decline_file_salary_loan.php",
          //data: datastring,
          cache: false,
          success: function (data) {
          // if has error 
            if (data == "Error"){
              alert("There's an error during getting of data information please reload the page");
            }
            // if success
            else { 
                $("#decline_modal_body").html(data);
                $("#declineFileSalaryLoanConfirmationModal").modal("show");               
            }
          }
        });

        
    });

    // for decline script 
    $("a[id='decline_yes_fileSalaryLoan']").on("click",function(){
          window.location = "php script/script_decline_file_salary_loan.php";
    });



    //$("input[name='searchEmployee']").on('input', function(){




    $("button[id='search']").on("click",function(){
        var empName = $("input[name='searchEmployee']").val();
        var cutOffPeriod = $("select[name='cutOffPeriod']").val();
        var year = $("input[name='year']").val();

        if (empName == "" || cutOffPeriod == "" || year == ""){
            $("div[id='errorMessage']").html("<span class='glyphicon glyphicon-remove' style='color:#CB4335'></span> Please fill up all fields.");
        }

        else {
            $("div[id='errorMessage']").html("<center><div class='loader'></div>Loading Information</center>");
            var datastring = "emp_name=" +empName+"&cutOffPeriod="+cutOffPeriod+"&year="+year;
            $.ajax({
              type: "POST",
              url: "ajax/append_search_employee_payroll_information.php",
              data: datastring,
              cache: false,
              success: function (data) {
                //alert(data);
              // if has error 
                $("div[id='errorMessage']").html(data);
              }
            });
        }


    });


   // view_leave_status_history
   $("div[id='view_leave_status_history']").on("click",function(){
        $("#viewFileLeaveHistoryModal").modal("show");
    });



   


    

    // for sending payroll reports 
    $("a[id='send_payroll_reports']").on("click" ,function () {
        var approve_payroll_id = $(this).closest("tr").attr("id");

         var datastring = "approve_payroll_id="+approve_payroll_id;

         $.ajax({
              type: "POST",
              url: "php script/script_send_payroll_reports.php",
              data: datastring,
              cache: false,
              success: function (data) {
                  
                  if (data == "Error"){
                    $("#errorModal").modal("show");
                  }
                  else {
                   // alert(data);
                    $("#send_successModal").modal("show");

                    $("#status"+approve_payroll_id).html("Already Sent");
                    $("#action"+approve_payroll_id).html("<a href='#' id='print_payroll_reports' class='action-a'><span class='glyphicon glyphicon-print' style='color: #2c3e50'></span> Print</a>");
                    //alert(approve_payroll_id);
                    // for print payroll reports print_payroll_reports
                    $("a[id='print_payroll_reports']").on("click", function () {
                       var datastring = "approve_payroll_id=" + $(this).closest("tr").attr("id");
                      
                         $.ajax({
                            type: "POST",
                            url: "ajax/script_check_exist_approve_payroll_id.php",
                            data: datastring,
                            cache: false,
                            success: function (data) {
                              // if has error 
                             if (data == "Error") {
                                $("#errorModal").modal("show");
                             }
                             // if success
                             else {
                               // window.open("print_payroll_reports.php");
                           
                             }

                           }
                         });
                       
                       
                      
                    });

                  }

              }
            });

    });



    // for focusing events on the button 
    $("button[id='show_password']").on("click",function () {
          var emp_id = $(this).closest("tr").attr("id");

          var datastring = "emp_id=" + emp_id;

          var title = $(this).attr("title");

          
          // for showing
          if (title == "click me to show password") {
            $.ajax({
                type: "POST",
                url: "ajax/append_show_password.php",
                data: datastring,
                cache: false,
                success: function (data) {
                  // if has error 
                 if (data == "Error") {
                    alert("There's an error during getting of data");
                 }
                 // if success
                 else {
                   // window.open("print_payroll_reports.php");
                    $("#password"+emp_id).html(data);
                         
                    $("button[id='show_password']").attr("title","click me to hide password");
               
                 }

               }
             });
         }

         // for hiding
         else {
            $.ajax({
                type: "POST",
                url: "ajax/append_hide_password.php",
                data: datastring,
                cache: false,
                success: function (data) {
                  // if has error 
                 if (data == "Error") {
                    alert("There's an error during getting of data");
                 }
                 // if success
                 else {
                   // window.open("print_payroll_reports.php");
                    $("#password"+emp_id).html(data);
                         
                    $("button[id='show_password']").attr("title","click me to show password");
               
                 }

               }
             });
         }
    });



    // print_cash_bond_reports
     $("div[id='print_cash_bond_reports']").on("click",function () {

        window.location = "cashbond_reports.php";



    });


     // for file leave 
    $("a[id='file_leave']").on("click",function () {
        $("#fileLeaveOption").modal("show");

    });


    // for file leave with pay
    $("div[id='leave_with_pay']").on("click",function() {
        $("#fileLeaveOption").modal("hide");
        $("#fileLeaveModal").modal("show");

    });


    // for file leave formal 
     $("div[id='formal_leave']").on("click",function() {
        $("#fileLeaveOption").modal("hide");
        $("#fileFormalLeaveModal").modal("show");

    });


     // for filing half day leave 
     $("div[id='halfday_leave']").on("click",function() {
        $("#fileLeaveOption").modal("hide");
        $("#fileHalfdayLeaveModal").modal("show");

    });


     // for print leave list history 
    $("div[id='print_leave_history_reports']").on("click",function() {
        window.location = "leave_list_history_reports.php";

    });


    // for print leave list for current cut off 
    $("div[id='print_leave_cut_off_reports']").on("click",function() {
        window.location = "leave_list_cut_off_reports.php";

    });


    // create message 
    $("button[id='send_message']").on("click",function() {
        //alert("Hello World!");
        $("input[name='message_to']").attr("required","required");
        $("input[name='subject']").attr("required","required");
        $("textarea[name='message']").attr("required","required");

        if ($("input[name='message_to']").val() != "" || $("input[name='subject']").val() != "" || $("textarea[name='message']").val() != ""){
            $("form[id='form_message']").attr("action","php script/send_message.php");
        }
    });



    // read_message
    $("div[id='read_message']").on("click",function() {
        var message_id = $(this).closest("tr").attr("id");

        var datastring = "message_id="+message_id;

        $.ajax({
              type: "POST",
              url: "ajax/append_read_message.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
               if (data == "Error") {
                  $("#errorModal").modal("show");
               }
               // if success
               else {
                 // window.open("print_payroll_reports.php");
                  //$("#password"+emp_id).html(data);
                       
                  //$("button[id='show_password']").attr("title","click me to show password");
                 // alert(data);
                  $("#read_modal_body").html(data);
                  $("#readMessageModal").modal("show");
               }

             }
           });

    });


      // for print leave list history 
    $("div[id='print_ot_history_reports']").on("click",function() {
        window.location = "ot_list_history_reports.php";

    });


    // print_ot_cut_off_reports ot
    $("div[id='print_ot_cut_off_reports']").on("click",function() {
        window.location = "ot_list_cut_off_reports.php";

    });


    // for editing atm record info 
    $("div[id='edit_atm_info']").on("click",function() {
        var emp_id = $(this).closest("tr").attr("id");

        var datastring = "emp_id="+emp_id;

        $.ajax({
              type: "POST",
              url: "ajax/append_edit_atm_record_from_table.php",
              data: datastring,
              cache: false,
              success: function (data) {
                // if has error 
               if (data == "Error") {
                  $("#errorModal").modal("show");
               }
               // if success
               else {
                  $("#update_salaryForm_body").html(data);
                  $("#updateFormModal").modal("show");
               }

             }
           });
   });


    // for print atm account no reports 
    $("div[id='print_atm_reports']").on("click",function() {
        window.location = "print_atm_account_number_reports.php";
      // alert("Hello World!");
    });


    // for editing leave count
    $("div[id='edit_leave_count']").on("click",function(){
        var emp_id = $(this).closest("tr").attr("id");

        var datastring = "emp_id="+emp_id;

        $.ajax({
            type: "POST",
            url: "ajax/append_edit_leave_count.php",
            data: datastring,
            cache: false,
            success: function (data) {
              // if has error 
             if (data == "Error") {
                $("#errorModal").modal("show");
             }
             // if success
             else {
                //alert(data);
                //$("#update_salaryForm_body").html(data);
                //$("#updateFormModal").modal("show");
                $("#update_salaryForm_body").html(data);
                $("#updateFormModal").modal("show");
             }
           }
         });
    });


    // for add multiple memo
   
    $("button[id='add_recipient']").on("click",function(){

      memoRecipientCount++;

      //var div = '</div>';
      var labelRecipient = '<label class="control-label col-sm-3 col-sm-offset-1"><b>Recipient:</b></label>';
     // var allOption = '<label class="radio-inline"><input required="required" type="radio" value="All" name="optRecipient'+memoRecipientCount+'">All</label>';
      var deptOption = '<label class="radio-inline"><input required="required" type="radio" value="Department" name="optRecipient'+memoRecipientCount+'">Department</label>';
      var specificOption = '<label class="radio-inline"><input required="required" type="radio" value="Specific Employee" name="optRecipient'+memoRecipientCount+'">Specific Employee</label>&nbsp;&nbsp;<button type="button" class="btn btn-danger btn-sm" id="remove_recipient'+memoRecipientCount+'">-</button>';
      var labelTo = '<label class="control-label col-sm-3 col-sm-offset-1"><b>To:</b></label>';
      var divTxt = '<div class="col-sm-6 txt-pagibig-loan" style="margin-right:-15px;">';
      var txt = '<input type="text" class="form-control" name="to'+memoRecipientCount+'" placeholder="" id="input_payroll" required="required" disabled="disabled" autocomplete="off"/>';
      var endDivTxt = '</div>';
      var labelChoose = '<label class="col-sm-1 control-label"><div id="choose'+memoRecipientCount+'"></div></label>';

      $("#div_recipient").append('<div id="recipient_mother_div'+memoRecipientCount+'"><div class="form-group">'+labelRecipient+deptOption+specificOption+'</div><div class="form-group">'+labelTo+divTxt+txt+endDivTxt+labelChoose+'</div></div>');
    

      // for function
      $("input[name='optRecipient"+memoRecipientCount+"']").on("click",function () {
          var recipientType = $(this).val();





         // alert(recipientType);
          //alert(memoRecipientCount);

          // success
          if (recipientType == "Specific Employee" || recipientType == "Department"){
              if (recipientType == "Specific Employee") {
                  $("input[name='to"+$(this).attr("name").slice(12,13)+"']").removeAttr("disabled");
                  $("div[id='choose"+$(this).attr("name").slice(12,13)+"']").html("<a href='#' id='choose_employee_memo'>Choose</a>");
                  $("input[name='to"+$(this).attr("name").slice(12,13)+"']").attr("required","required");
                  $("input[name='to"+$(this).attr("name").slice(12,13)+"']").val("");
                  $("input[name='to"+$(this).attr("name").slice(12,13)+"']").attr("placeholder","Employee ..");
              }

            

              if (recipientType == "Department"){
                $("input[name='to"+$(this).attr("name").slice(12,13)+"']").removeAttr("disabled");
                $("input[name='to"+$(this).attr("name").slice(12,13)+"']").attr("required","required");
                $("div[id='choose"+$(this).attr("name").slice(12,13)+"']").html("<a href='#' id='choose_department_memo'>Choose</a>");
                $("input[name='to"+$(this).attr("name").slice(12,13)+"']").attr("placeholder","Department ..");
                $("input[name='to"+$(this).attr("name").slice(12,13)+"']").val("");


                // for resetting global variable value
                

              }
          }   

      });

  
      $("div[id='choose"+memoRecipientCount+"']").on("click","a[id='choose_department_memo']",function () {
          $("#dept_list_modal").modal("show");

          dept_id_count = $(this).closest("div").attr("id").slice(6,7);
         // alert(id_count);

      }); 


       $("div[id='choose"+memoRecipientCount+"']").on("click","a[id='choose_employee_memo']",function () {
        $("#emp_list_modal").modal("show");

        emp_id_count = $(this).closest("div").attr("id").slice(6,7);
    });


       // for removing recipient
       $("button[id='remove_recipient"+memoRecipientCount+"']").on("click",function(){

         

         $("div[id='recipient_mother_div"+$(this).attr("id").slice(16,17)+"']").remove();
          //alert(memoRecipientCount);
          //  $("recipient_mother_div"+memoRecipientCount)



       });


    });


    // for add add attendance manual
    //var global_add_attendance_emp_id = 0;
    $("div[id='add_attendance_no_bio']").on("click",function(){
        var emp_id = $(this).closest("tr").attr("id");

        var datastring = "emp_id="+emp_id;
        $.ajax({
            type: "POST",
            url: "ajax/append_add_attendance.php",
            data: datastring,
            cache: false,
            success: function (data) {
              // if has error 
             if (data == "Error") {
                $("#errorModal").modal("show");
             }
             // if success
             else {
             // alert(data);
                $("span[id='emp_name']").html(data);
                $("#attAttendanceModal").modal("show");
                // 
                $("#form_add_attendance").attr("action","php script/payroll_add_attendance.php?emp_id="+emp_id);
               // global_add_attendance_emp_id = emp_id;
             }
           }
         });

        
    });


    // for create bio create_bio_id
    $("a[id='create_bio_id']").on("click",function(){
        var emp_id = $(this).closest("tr").attr("id");

        var datastring = "emp_id="+emp_id;

        $.ajax({
            type: "POST",
            url: "ajax/append_create_bio.php",
            data: datastring,
            cache: false,
            success: function (data) {
              // if has error 
               if (data == "Error"){
                  $("#update_errorModal").modal("show");
                }
                // if success
                else {
                  $("#modal_body_update_bio_id").html(data);
                  $("#updateBioIdModal").modal("show");
                }
           }
         });

    });



    /* print salary loan */
    $("a[id='printSalaryLoan']").on("click",function(){
        var salary_loan_id = $(this).closest("tr").attr("id");

        window.open("report_file_salary_loan.php?salary_loan_id="+salary_loan_id);
    });
    
    
    // cashbond withdrawal
    $("div[id='file_cashbond_withdrawal']").on("click",function(){
        $("#fileCashwithdrawalModal").modal("show");
    });


    // for filing of cashwithdrawal
    $("button[id='btn_file_withdrawal']").on("click",function(){
        var amount_withraw = $("input[name='amount_withdraw']").val();

        if (amount_withraw == ""){
            $("label[id='file_withdraw_message']").html("<span class='glyphicon glyphicon-remove' style='color:#c0392b;'></span> Please provide an amount first.");
        }

        else {

            // check natin kung ung remaining balance na inimput nya is mas malaki sa available
            $("label[id='file_withdraw_message']").html("<center><div class='loader' style='float:left'></div>&nbsp;Filing Cashbond Withdrawal please wait ...</center>");
            var datastring = "check_request=1";
             $.ajax({
              type: "POST",
              url: "ajax/append_check_totalcashbond_amount.php",
              data: datastring,
              cache: false,
              success: function (data) {
                 // alert(data);
                  //alert(amount_withraw);
                  if (parseFloat(amount_withraw) > parseFloat(data)){
                      //alert("Error");
                      $("label[id='file_withdraw_message']").html("<span class='glyphicon glyphicon-remove' style='color:#c0392b;'></span> The amount you want to withdraw must be not higher than your available withdraw cashbond amount of <b>Php "+data+"</b>.");
                  }
                  else {
                      $("#form_file_cashbond_withdrawal").attr("action","php script/script_file_cashbond_withdrawal.php");
                      $("#form_file_cashbond_withdrawal").submit();
                  }


             }
           });
        }
    });

  

    // for approving file cashbond withdrawal
    $("div[id='approve_file_cashbond_withdrawal']").on("click",function(){
        var file_cashbond_withdrawal_id = $(this).closest("tr").attr("id");

        var approve_stats = $(this).attr("title");


        if (approve_stats != "Approve" && approve_stats != "Disapprove"){
            $("#errorModal").modal("show");
        }
        else {
          var datastring = "file_cashbond_withdrawal_id="+file_cashbond_withdrawal_id + "&approve_stats="+approve_stats;
          //alert(datastring);
          $.ajax({
                type: "POST",
                url: "ajax/append_check_exist_file_salary_loan_id.php",
                data: datastring,
                cache: false,
                success: function (data) {
                   
                    if (data == "Error"){
                      $("#errorModal").modal("show");
                    }
                    else {
                      $("#approve_confirmation_body").html(data);
                      $("#approveConfirmationModal").modal("show");
                    }
                    

                 }
           });
        }
    });


   // for printing salary info
   $("a[id='print_salary_info']").on("click",function(){
      var approve_payroll_id = $(this).closest("tr").attr("id");
      //alert(approve_payroll_id);
      window.location = "print_salary_info_reports.php?approve_payroll_id="+approve_payroll_id;
   });

   // for viewing cashbond history
   $("a[id='view_cashbond_reports']").on("click",function(){
        var cashbond_id = $(this).closest("tr").attr("id");
        var datastring = "cashbond_id="+cashbond_id;
         $.ajax({
                type: "POST",
                url: "ajax/append_view_cashbond_history.php",
                data: datastring,
                cache: false,
                success: function (data) {
                   
                    if (data == "Error"){
                      $("#errorModal").modal("show");
                    }
                    else {
                      $("#cashbond_history_body").html(data);
                      $("#cashbondHistoryModal").modal("show");
                    }
                    

                 }
           });
       // alert(emp_id);
       
   });


   $("div[id='print_employee_list_reports']").on("click",function(){
      //alert("HELLO WORLD!");
      window.location = "employee_list_reports.php";

   });

   $("span[id='edit_file_attendance_updates']").on("click",function(){
      var attendance_notif_id = $(this).closest("tr").attr("id");
     // alert(attendance_notif_id);


      var datastring = "attendance_notif_id="+attendance_notif_id;
       $.ajax({
              type: "POST",
              url: "ajax/append_update_file_attendance.php",
              data: datastring,
              cache: false,
              success: function (data) {
                 // alert(data);
                  if (data == "Error"){
                    $("#errorModal").modal("show");
                  }
                  else {
                   $("#update_file_attendance_info_body").html(data);
                   $("#updateFileAttendanceInfoModal").modal("show");
                   
                  }
                  
                  $("#viewFileOTAddAttendanceModal").modal("hide");
               }
               
         });
     


   });


   // for canceling file attendance updates
   $("span[id='cancel_file_attendance_updates']").on("click",function(){
      var attendance_notif_id = $(this).closest("tr").attr("id");


      var datastring = "attendance_notif_id="+attendance_notif_id;
       $.ajax({
          type: "POST",
          url: "ajax/append_cancel_attendance_updates.php",
          data: datastring,
          cache: false,
          success: function (data) {
             // alert(data);
              if (data == "Error"){
                $("#errorModal").modal("show");
              }
              else {
               $("#cancel_modal_body").html(data);
               $("#cancelAttendanceUpdatesModal").modal("show");
              
              }
              $("#viewFileOTAddAttendanceModal").modal("hide");
              

           }
       });
   });
   

   $("span[id='edit_file_overtime']").on("click",function(){
      var attendance_ot_id = $(this).closest("tr").attr("id");

      var datastring = "attendance_ot_id="+attendance_ot_id;
       $.ajax({
          type: "POST",
          url: "ajax/append_edit_file_ot.php",
          data: datastring,
          cache: false,
          success: function (data) {
             // alert(data);
              if (data == "Error"){
                $("#errorModal").modal("show");
              }
              else {
               $("#update_file_ot_info_body").html(data);
               $("#updateFileOTModal").modal("show");
              
              }
              $("#viewFileOTAddAttendanceModal").modal("hide");

           }
       });

   });


   $("span[id='cancel_file_overtime']").on("click",function(){
      var attendance_ot_id = $(this).closest("tr").attr("id");

      var datastring = "attendance_ot_id="+attendance_ot_id;
      //alert(datastring);
      $.ajax({
          type: "POST",
          url: "ajax/append_cancel_file_ot.php",
          data: datastring,
          cache: false,
          success: function (data) {
             // alert(data);
              if (data == "Error"){
                $("#errorModal").modal("show");
              }
              else {
               $("#cancel_fileot_modal_body").html(data);
               $("#cancelFileOTModal").modal("show");
               
              }
              $("#viewFileOTAddAttendanceModal").modal("hide");

           }
       });

   });


   $("span[id='edit_file_leave']").on("click",function(){
      //alert("HELLO WORLD!");
      var leave_id = $(this).closest("tr").attr("id");
      //alert(leave_id);
       var datastring = "leave_id="+leave_id;
      //alert(datastring);
      $.ajax({
          type: "POST",
          url: "ajax/append_edit_file_leave.php",
          data: datastring,
          cache: false,
          success: function (data) {
             // alert(data);
              if (data == "Error"){
                $("#errorModal").modal("show");
              }
              else {
               $("#update_file_leave_info_body").html(data);
               $("#updateFileLeaveModal").modal("show");
               
              }
              $("#viewFileLeaveHistoryModal").modal("hide");
              

           }
       });
   });



  $("span[id='cancel_file_cashbond_withdrawal']").on("click",function(){

    $("#cancelConfirmationModal").modal("show");
  });

  //alert("HELLO WORLD!");
  $("a[id='cancel_yes_cashbond_withdrawal']").on("click",function(){
    window.location="php script/cancel_file_cashbond_withdrawal.php";  
  });

 // alert("HELLO WORLD!");
  $("span[id='edit_file_cashbond_withdrawal']").on("click",function(){

      var datastring = "append=1";
      $.ajax({
          type: "POST",
          url: "ajax/append_update_file_cashbond_withdrawal.php",
          data: datastring,
          cache: false,
          success: function (data) {

            $("#modal_body_file_cashbond_withdraw").html(data);
            $("#updatefileCashwithdrawalModal").modal("show");

          }

      });


      
  });




  // for adding increase
  $("a[id='add_increase_info']").on("click",function(){
    //alert("HELLO WORLD!");

    // append_increase_salary.php
     var datastring = "emp_id="+$(this).closest("tr").attr("id");

      $.ajax({
          type: "POST",
          url: "ajax/append_increase_salary.php",
          data: datastring,
          cache: false,
          success: function (data) {

              if (data == "Error"){
                $("#errorModal").modal("show");
              }
              else {
                $("#modal_body_increase_salary").html(data);
                $("#addIncreaseInfoModal").modal("show");
              }

          }

      });
  });


  $("a[id='view_salaryLoan_history']").on("click",function(){
    var datastring = "salary_loan_id="+$(this).closest("tr").attr("id");

    $.ajax({
          type: "POST",
          url: "ajax/append_view_salary_loan_history.php",
          data: datastring,
          cache: false,
          success: function (data) {

              if (data == "Error"){
                $("#errorModal").modal("show");
              }
              else {
                $("#view_salaryLoan_body").html(data);
                $("#view_salary_loan_history").modal("show");
              }

          }

      });

  });

  //view_simkimban_history
  $("a[id='view_simkimban_history']").on("click",function(){
    var datastring = "simkimban_id="+$(this).closest("tr").attr("id");
  //  alert("HELLO WORLD!");
    $.ajax({
          type: "POST",
          url: "ajax/append_view_simkimban_loan_history.php",
          data: datastring,
          cache: false,
          success: function (data) {

              if (data == "Error"){
                $("#errorModal").modal("show");
              }
              else {
                $("#view_simkimbanLoan_body").html(data);
                $("#view_simkimban_loan_history").modal("show");
              }

          }

      });

  });


  $("select[name='education_attain']").on("change",function(){
    //alert("HELLO WORLD!");
    var html = "";

    // for secondary
    html +='<div class="form-group">';
      html +='<div class="col-sm-12">';
        html += '<label style="color: #27ae60 "><i>Secondary Information</i></label>';
       html +='</div>';
      html +='<div class="col-sm-4">';
        html +='<label class="control-label"><span class="glyphicon glyphicon-home" style="color:#2E86C1;"></span> School Name:&nbsp;<span class="red-asterisk">*</span></label>';
        html +='<input type="text" name="school_name[]" class="form-control" required="required"/>';
      html +='</div>';
      html +='<div class="col-sm-2">';
        html +='<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year From:&nbsp;<span class="red-asterisk">*</span></label>';
        html +='<input type="text" name="year_from[]" class="form-control" required="required" id="year_only" placeholder="year from"/>';
      html +='</div>';
      html +='<div class="col-sm-2">';
        html +='<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year To:&nbsp;<span class="red-asterisk">*</span></label>';
        html +='<input type="text" name="year_to[]" class="form-control" required="required" id="year_only" placeholder="year to"/>';
      html +='</div>';
      html +='<div class="col-sm-3" style="display:none">';
        html +='<label class="control-label"><span class="glyphicon glyphicon-education" style="color:#2E86C1;"></span> Course:&nbsp;<span class="red-asterisk">*</span></label>';
        html +='<textarea class="form-control" name="course[]"></textarea>';
      html +='</div>';
    html +='</div>';
    // for tertiary
    html +='<div class="form-group">';
       html +='<div class="col-sm-12">';
        html += '<label style="color: #27ae60 "><i>Tertiary Information</i></label>';
       html +='</div>';
       html +='<div class="col-sm-4">';
          html +='<label class="control-label"><span class="glyphicon glyphicon-home" style="color:#2E86C1;"></span> School Name:&nbsp;<span class="red-asterisk">*</span></label>';
          html +='<input type="text" name="school_name[]" class="form-control" required="required"/>';
        html +='</div>';
        html +='<div class="col-sm-3">';
          html +='<label class="control-label"><span class="glyphicon glyphicon-education" style="color:#2E86C1;"></span> Course:&nbsp;<span class="red-asterisk">*</span></label>';
          html +='<textarea class="form-control" name="course[]" required="required"></textarea>';
        html +='</div>';
        html +='<div class="col-sm-2">';
          html +='<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year From:&nbsp;<span class="red-asterisk">*</span></label>';
          html +='<input type="text" name="year_from[]" class="form-control" required="required"/>';
        html +='</div>';
        html +='<div class="col-sm-2">';
          html +='<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year To:&nbsp;<span class="red-asterisk">*</span></label>';
          html +='<input type="text" name="year_to[]" class="form-control" required="required"/>';
        html +='</div>';
        html +='<div class="col-md-1">';
          html +='<button id="add_education_attain" class="btn btn-primary btn-sm" type="button" style="margin-top:30px"><span class="glyphicon glyphicon-plus"></span></button>';
        html +='</div>';


      html +='</div>';

      var education_attain = $(this).val();

      if (education_attain == ""){
          $("#append_tertiary_education").html("");
      }

      else if (education_attain == "Secondary"){
          html = "";
          html +='<div class="form-group">';
            html +='<div class="col-sm-12">';
              html += '<label style="color: #27ae60 "><i>Secondary Information</i></label>';
             html +='</div>';
            html +='<div class="col-sm-4">';
              html +='<label class="control-label"><span class="glyphicon glyphicon-home" style="color:#2E86C1;"></span> School Name:&nbsp;<span class="red-asterisk">*</span></label>';
              html +='<input type="text" name="school_name[]" class="form-control" required="required"/>';
            html +='</div>';
            html +='<div class="col-sm-2">';
              html +='<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year From:&nbsp;<span class="red-asterisk">*</span></label>';
              html +='<input type="text" name="year_from[]" class="form-control" required="required" id="year_only" placeholder="year from"/>';
            html +='</div>';
            html +='<div class="col-sm-2">';
              html +='<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year To:&nbsp;<span class="red-asterisk">*</span></label>';
              html +='<input type="text" name="year_to[]" class="form-control" required="required" id="year_only" placeholder="year to"/>';
            html +='</div>';
          html +='</div>';
          $("#append_tertiary_education").html(html); 


      }
      else if(education_attain == "Tertiary"){
          $("#append_tertiary_education").html(html);       
      }


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


      // float only
      $("input[id='year_only']").on('input', function(){
         if ($(this).attr("maxlength") != 4){
              if ($(this).val().length > 4){
                  $(this).val($(this).val().slice(0,-1));
              }
             $(this).attr("maxlength","4");
         }

     });

      
  });


  $("#append_tertiary_education").on("click","#add_education_attain",function(){
      //lert("HELLO WORLD!");
      var html =  "";
      html +='<div class="form-group">';
       html +='<div class="col-sm-4">';
          html +='<label class="control-label"><span class="glyphicon glyphicon-home" style="color:#2E86C1;"></span> School Name:&nbsp;<span class="red-asterisk">*</span></label>';
          html +='<input type="text" name="school_name[]" class="form-control" required="required"/>';
        html +='</div>';
        html +='<div class="col-sm-3">';
          html +='<label class="control-label"><span class="glyphicon glyphicon-education" style="color:#2E86C1;"></span> Course:&nbsp;<span class="red-asterisk">*</span></label>';
          html +='<textarea class="form-control" name="course[]"></textarea>';
        html +='</div>';
        html +='<div class="col-sm-2">';
          html +='<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year From:&nbsp;<span class="red-asterisk">*</span></label>';
          html +='<input type="text" name="year_from[]" class="form-control" required="required" placeholder="year from" id="year_only"/>';
        html +='</div>';
        html +='<div class="col-sm-2">';
          html +='<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year To:&nbsp;<span class="red-asterisk">*</span></label>';
          html +='<input type="text" name="year_to[]" class="form-control" required="required" placeholder="year to" id="year_only"/>';
        html +='</div>';
        html +='<div class="col-md-1">';
          html +='<button id="remove_education_attain" class="btn btn-danger btn-sm" type="button" style="margin-top:30px"><span class="glyphicon glyphicon-remove"></span></button>';
        html +='</div>';


      html +='</div>';
      $("#append_tertiary_education").append(html);   


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


        // float only
        $("input[id='year_only']").on('input', function(){
           if ($(this).attr("maxlength") != 4){
                if ($(this).val().length > 4){
                    $(this).val($(this).val().slice(0,-1));
                }
               $(this).attr("maxlength","4");
           }

       });
  });

  $("#append_tertiary_education").on("click","#remove_education_attain",function(){

    $(this).closest("div").parent("div").remove();
  }); 


  // for add work expirience
  $("#add_work_xp").on("click",function(){
    //alert("HELLO WORLD!");
    var html = "";
    html += '<div class="form-group">';
      html += '<div class="col-md-12">';
        html += '<button class="btn btn-danger btn-xs pull-right" id="remove_work_xp" type="button" style="margin-top:30px"><span class="glyphicon glyphicon-remove"></span>&nbsp;Remove';
        html += '</button>';
      html += '</div>';
    html += '</div>';
    html +='<div class="form-group">'; 
      html +='<div class="col-sm-2">';            
        html +='<label class="control-label"><span class="glyphicon glyphicon-user" style="color:#2E86C1;"></span> Position&nbsp;<span class="red-asterisk">*</span></label>';
        html +='<input type="text" name="work_position[]" id="txt_only" class="form-control" placeholder="Enter Last Name" required="required">';
      html +='</div>';

      html +='<div class="col-sm-3">';            
        html +='<label class="control-label"><span class="glyphicon glyphicon-blackboard" style="color:#2E86C1;"></span> Company Name&nbsp;<span class="red-asterisk">*</span></label>';
        html +='<input type="text" name="company_name[]" id="txt_only" value="" class="form-control" placeholder="Enter First Name" required="required">';
      html +='</div>';
      html +='<div class="col-sm-3">';            
        html +='<label class="control-label"><span class="glyphicon glyphicon-info-sign" style="color:#2E86C1;"></span> Job Description</label>';
        html +='<textarea class="form-control" name="job_description[]" placeholder="Job Description" required="required"></textarea>';
      html +='</div>';
      html +='<div class="col-sm-2">';
            html +='<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year From:&nbsp;<span class="red-asterisk">*</span></label>';
            html +='<input type="text" name="work_year_from[]"  class="form-control" required="required" placeholder="year from" id="year_only"/>';
          html +='</div>';
          html +='<div class="col-sm-2">';
            html +='<label class="control-label"><span class="glyphicon glyphicon-calendar" style="color:#2E86C1;"></span> Year To:&nbsp;<span class="red-asterisk">*</span></label>';
            html +='<input type="text" name="work_year_to[]"  class="form-control" required="required" placeholder="year to" id="year_only"/>';
          html +='</div>';
    html +='</div>';

    $("#append_work_experience").append(html);

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


      // float only
      $("input[id='year_only']").on('input', function(){
         if ($(this).attr("maxlength") != 4){
              if ($(this).val().length > 4){
                  $(this).val($(this).val().slice(0,-1));
              }
             $(this).attr("maxlength","4");
         }

     });

  });


  $("#append_work_experience").on("click","button[id='remove_work_xp']",function(){
     $(this).closest("div").parent("div").next("div").remove();
      $(this).closest("div").parent("div").remove();
  });


  // for adjust cashbond
    $("a[id='add_cashbond_deposit']").on("click",function(){
         var datastring = "cashbond_id="+$(this).closest("tr").attr("id");
         //alert(datastring);
          $.ajax({
            type: "POST",
            url: "ajax/append_add_cashbond_deposit.php",
            data: datastring,
            cache: false,
            success: function (data) {
               if (data == "Error"){
                  $("#errorModal").modal("show");
                }
                // if success
                else {       
                    $("#deposit_cashbondForm_body").html(data);
                    $("#addDepositModal").modal("show");
                }
            }
          });
    });


     // generate_code
    $("a[id='generate_code']").on("click", function () {
        var datastring =  "emp_id="+$(this).closest("tr").attr("id");
        var emp_id = $(this).closest("tr").attr("id");

        //alert("HELLO WORLD!");

         $.ajax({
            type: "POST",
            url: "ajax/append_emp_generate_code.php",
            data: datastring,
            cache: false,
           // datatype: "php",
            success: function (data) {
              //$("#edit_modal_body").html(data);
             // $("#editModal").modal("show");
             if (data == "Error" || data == 1){
                $("#errorModal").modal("show");
             }
             else {
                $("#generate_code_modal_body").html(data);
                $("#generateCodeModal").modal("show");
                //generate_code_emp_id = emp_id;

             }
            }
        }); 

    });


    
    $("a[id='view_generated_code']").on("click", function () {
        var datastring =  "emp_id="+$(this).closest("tr").attr("id");
        var emp_id = $(this).closest("tr").attr("id");

       // alert("HELLO WORLD!");

         $.ajax({
            type: "POST",
            url: "ajax/append_emp_generate_code.php",
            data: datastring,
            cache: false,
           // datatype: "php",
            success: function (data) {
              //$("#edit_modal_body").html(data);
             // $("#editModal").modal("show");
             if (data == "Error" || data == 1){
                $("#errorModal").modal("show");
             }
             else {
                $("#generate_code_modal_body").html(data);
                $("#generateCodeModal").modal("show");
                //generate_code_emp_id = emp_id;

             }
            }
        }); 

    });


    

});




