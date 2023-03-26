<?php
    require_once '../php/Database.php';
    require '../php/centralConnection.php';
    date_default_timezone_set('Asia/Manila');
    session_start();
    if(empty($_SESSION['logged_in'])){
    header('Location: ../index.html');
    }

    $query ="SELECT * FROM db_degree_list";  
    $result = mysqli_query($connect, $query);

?>  

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Degree List</title>
          
        <link rel = "icon" href = "../images/BSU-Logo.webp" type = "image/x-icon">
        
        <?php include '../includes/dependencies0.php'; ?>

        <link rel="stylesheet" href="../css/userList.css">

        <script>  
        // ---------------------------start functions for System Logs---------------------------------------
            var act = "";
            var globalAL = "";

            //function called when logout tab pressed
            function logout(){
                act = "Logged out";
                logAction(act);
                  $.ajax({
                    url:"../php/logout.php",
                    method:"POST",
                    data:"",
                    success:function(xml){
                        // sessionStorage.clear();
                        setTimeout(function(){
                            window.location.href = '../index.html';
                        }, 100);
                    }
                  })
            }
            
            //main function for user activity logging
            function logAction(userAction){
                act = userAction;
                $.ajax({
                    url:"../php/logAction.php",
                    method:"POST",
                    data:jQuery.param({ action: act, isSuccess:"1" }),
                    dataType: "xml",
                    success:function(xml){

                    }
                  })
            }

            function userViewRecord(StaffID){
                act = "Checked Staff ID:" +StaffID +" Information."
                logAction(act);
            }


        // ---------------------------end functions for System Logs---------------------------------------

            function updateDegree(text, column, id){
                var form_data = new FormData(); 
                form_data.append("degree_name", text);  
                form_data.append("degree_col", column);
                form_data.append("degree_id", id);         
                    $.ajax(
                    {
                        url:"../php/updateDegree.php",
                        method:"POST",
                        data:form_data,
                        contentType: false,
                        processData: false,
                        cache: false,
                        dataType: "xml",
                        success:function(xml)
                        {
                            $(xml).find('output').each(function()
                            {
                                var Result = $(this).attr('Result');
                                var Error = $(this).attr('Error');

                                if(Error == "1"){
                                 
                                     $.alert({
                                        theme: 'modern',
                                        content:Result,
                                        title:"", 
                                        buttons:{
                                        Ok:{
                                            text:'Ok',
                                            btnClass: 'btn-red'
                                    }}});

                                }
                    
                            });
                         },
                        error: function (e)
                        {
                            $.alert(
                            {theme: 'modern',
                            content:'Failed to store information due to error',
                            title:'', 
                            buttons:{
                                Ok:{
                                text:'Ok',
                                btnClass: 'btn-red'
                            }}});
                        }
                    });
            }

            function deleteDegree(degree_id){

                if(confirm("Are you sure you want to this Degree from the list?")){
                    var form_data = new FormData();
                    form_data.append("id",degree_id);
                    $.ajax(
                        {
                            url:"../php/deleteDegree.php",
                            method:"POST",
                            data:form_data,
                            contentType: false,
                            processData: false,
                            cache: false,
                            dataType: "xml",
                            success:function(xml)
                            {
                                $(xml).find('output').each(function()
                                {
                                    var Result = $(this).attr('Result');
                                    var Error = $(this).attr('Error');

                                    if(Error == "1"){
                                     
                                        //alert(Result);
                                         $.alert({
                                            theme: 'modern',
                                            content:Result,
                                            title:"", 
                                            buttons:{
                                            Ok:{
                                                text:'Ok',
                                                btnClass: 'btn-red'
                                        }}});


                                    }else{
                                        
                                        Result="deleted a degree";

                                        //alert('Successfully Added new degree');
                                        $.alert({
                                            theme: 'modern',
                                            content:'Successfully '+Result,
                                            title:"", 
                                            buttons:{
                                            Ok:{
                                                text:'Ok',
                                                btnClass: 'btn-green'
                                            }}});

                                        setTimeout(function(){
                                            location.reload();
                                        }, 2000);
             
                                    }

                                });
                             },
                            error: function (e)
                            {
                                $.alert(
                                {theme: 'modern',
                                content:'Failed to store information due to error',
                                title:'', 
                                buttons:{
                                    Ok:{
                                    text:'Ok',
                                    btnClass: 'btn-red'
                                }}});
                            }
                        });
                }
                
            }


        $(document).ready(function(){  

            if (sessionStorage.getItem("isLoggedIn") == null){

                alert('User has been logged out. Please login again');

                $.ajax({
                    url:"../php/logout.php",
                    method:"POST",
                    data:"",
                    success:function(xml){
                        // sessionStorage.clear();
                        setTimeout(function(){
                            window.location.href = '../index.html';
                        }, 100);
                    }
                  })
                }
            
            var table = $('#user_data').DataTable({
                /*dom: 'fltpB',
                buttons: [                              
                    {
                        extend:'print',
                        text:'Print Report',
                        title:"<h1 style='text-align:center;'>User List</h1>",
                        exportOptions: {
                            columns: [0,1,2]
                        }            
                    },
                    {
                       extend:'pdf',
                       text:'Export to PDF',
                       title:"User List",
                       exportOptions: {
                            columns: [0,1,2]
                        }  
                    },
                    {
                       extend:'excel',
                       text:'Export to Excel',
                       title:"User List",
                       exportOptions: {
                            columns: [0,1,2]
                        }  
                    },
                  ]*/
            });

            $("#degree-form").submit(function(event)
                {    
                    event.preventDefault();
                    var form_data = new FormData(this);            
                    $.ajax(
                    {
                        url:"../php/addDegree.php",
                        method:"POST",
                        data:form_data,
                        contentType: false,
                        processData: false,
                        cache: false,
                        dataType: "xml",
                        success:function(xml)
                        {
                            $(xml).find('output').each(function()
                            {
                                var Result = $(this).attr('Result');
                                var Error = $(this).attr('Error');

                                if(Error == "1"){
                                 
                                    //alert(Result);
                                     $.alert({
                                        theme: 'modern',
                                        content:Result,
                                        title:"", 
                                        buttons:{
                                        Ok:{
                                            text:'Ok',
                                            btnClass: 'btn-red'
                                    }}});


                                }else{
                                    
                                    Result="Added new degree";

                                    //alert('Successfully Added new degree');
                                    $.alert({
                                        theme: 'modern',
                                        content:'Successfully '+Result,
                                        title:"", 
                                        buttons:{
                                        Ok:{
                                            text:'Ok',
                                            btnClass: 'btn-green'
                                        }}});


                                    $('#degree-category').val('');
                                    $('#degree-name').val('');
                                    $('#degree-acr').val('');

                                    $('#DegreeNewModal').modal('hide');
                                    setTimeout(function(){
                                        location.reload();
                                    }, 2000);

                                    

                                    
                                }



                                
                            });
                         },
                        error: function (e)
                        {
                            alert('error');
                            /*$.alert(
                            {theme: 'modern',
                            content:'Failed to store information due to error',
                            title:'', 
                            buttons:{
                                Ok:{
                                text:'Ok',
                                btnClass: 'btn-red'
                            }}});*/
                        }
                    });
                });

            var length = table.page.info().recordsTotal;

            var span = document.getElementById("NumRecord");
            span.textContent = "Total Number of Record/s: " + length.toString();
        
                var acclvl = sessionStorage.getItem('isStandard');

                if(acclvl == "true"){
                    $(".admin-nav").hide();
                }
                

            }); 

        </script>  
    </head>
    <body>
    <?php include '../includes/navbar.php'; ?>     

        <div class="modal fade" id="DegreeNewModal" tabindex="-1" role="dialog" aria-labelledby="DegreeNewModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="DegreeNewModalLabel">Add New Degree</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <form action="#" method="post" id="degree-form" autocomplete="off">
                    <div class="modal-body">
                                        
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label" >Category</label>
                            <!-- <input type="text" class="form-control" name="degree-category" id="degree-category" required> -->
                                    <select class="form-control" name="degree-category" id="degree-category" required>
                                <option value="" hidden>Select Category</option>
                                <option value="senior highschool">Senior Highschool</option>
                                <option value="college">College</option>
                                <option value="gradute">Graduate School</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label" >Degree</label>
                            <input type="text" class="form-control" name="degree-name" id="degree-name" required>
                         </div>
                         <div class="form-group">
                            <label for="message-text" class="col-form-label" >Degree Acronym</label>
                            <input type="text" class="form-control" name="degree-acr" id="degree-acr" required>
                        </div>
                                        
                    </div>
                                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="Submit" class="btn btn-primary">Add Degree</button>
                    </div>
                </form>
            </div>
            </div>
        </div>

        <div class="cont container">
            <div class="tabs">
                <div class="tabs-head">
                    <span id="tab1" class="tabs-toggle is-active">&bull;&nbsp;DEGREE LIST&nbsp;&bull;</span>
                </div>
                <div id="notif">

                    <button id='newRecord' data-toggle="modal" data-target="#DegreeNewModal" class='btn btn-primary' >New Degree</button>
                    <span id='NumRecord'>Total Number of Record/s: </span>
                    
                    
                </div>
                <div class="tabs-body">
                    <div class="tabs-content is-active table-responsive">  
                    <table id="user_data" class="table table-striped table-bordered">  
                          <thead>  
                               <tr>  
                                  <th>Degree Category</th>
                                  <th>Degree</th>
                                  <th>Degree Acronym</th>
                                  <th>Action</th>
                                        
                               </tr>  
                          </thead>  
                          <?php        
                          while($Row = $result->fetch_array()) 
                          {  
                            ?>
                                
                                <tr>
                                    <td contenteditable="true" onblur="updateDegree(this.innerHTML,'degree_category',<?php echo $Row['id']; ?>)" > <?php echo ucwords($Row['degree_category']); ?></td>
                                    <td contenteditable="true" onblur="updateDegree(this.innerHTML,'degree',<?php echo $Row['id']; ?>)" > <?php echo $Row['degree']; ?></td>
                                    <td contenteditable="true" onblur="updateDegree(this.innerHTML,'degree_acr',<?php echo $Row['id']; ?>)" > <?php echo $Row['degree_acr']; ?></td>
                                    <td align="center"><button id="deleteDegree" style="background: orangered; align-self: center;" class='viewBTN btn btn-primary btn-sm' onclick="deleteDegree(<?php echo $Row['id']; ?>)">Delete</button></td>
                                </tr>
                          <?php
                               
                          } 

                          ?>  
                     </table> 
                    </div>
                </div>
            </div>
        </div>
        <div>
            <button type="Submit" class="btnUp" id="btnUp" name="btnUp" onclick="window.scrollTo(0, 0)"/>
        </div>
        <script src="../js/script-tab.js"></script>
        
    </body>
</html>
