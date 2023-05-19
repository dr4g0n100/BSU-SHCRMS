<?php
    require_once '../php/Database.php';
    require '../php/centralConnection.php';
    date_default_timezone_set('Asia/Manila');
    session_start();
    if(empty($_SESSION['logged_in'])){
    header('Location: ../index.html');
    }

    $query ="SELECT * FROM PersonalMedicalRecord";  
    $resultStudent = mysqli_query($connect, $query);


?>  

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Student Summary</title>
          
        <link rel = "icon" href = "../images/BSU-Logo.webp" type = "image/x-icon">
        
        <?php include '../includes/dependencies0.php'; ?>

        <link rel="stylesheet" href="../css/studentSummary.css">

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

            function userArchiveRecord(StudentID, acttype){
                ID = StudentID;
                var reason = '';
                if (reason = window.prompt("Specify a reason for archiving?")){
                    
                    $.ajax({
                    url:"../php/archive.php",
                    method:"GET",
                    data:jQuery.param({ type: acttype, id:ID, archReason:reason }),
                    success:function(xml){
                        $(xml).find('output').each(function()
                        {
                            var message = $(this).attr('Message');
                            logAction(message +" ID " +ID);
                            alert(message);
                        });
                        location.reload();
                    }
                    })
                }else if(reason == ''){
                    alert('Please specify a reason');
                }

            }

        // ---------------------------end functions for System Logs---------------------------------------

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
            
            var StudentInfoTable = $('#student_info').DataTable({
                dom: 'fltpB',
                buttons: [                              
                    {
                        extend:'print',
                        text:'Print Report',
                        title:"<h1 style='text-align:center;'>Students Record</h1>",
                        exportOptions: {
                            columns: [0,1,2,3,4,5,6]
                        }            
                    },
                    {
                       extend:'pdf',
                       text:'Export to PDF',
                       title:"Students Record",
                       exportOptions: {
                            columns: [0,1,2,3,4,5,6]
                        }  
                    },
                    {
                       extend:'excel',
                       text:'Export to Excel',
                       title:"Students Record",
                       exportOptions: {
                            columns: [0,1,2,3,4,5,6]
                        }  
                    },
                  ],
                "oLanguage": {
                "sSearch": "Filter results:"
                }        
            });

            var StudentInfoLength = StudentInfoTable.page.info().recordsTotal;
        
            var acclvl = sessionStorage.getItem('isStandard');

            if(acclvl == "true"){
                $(".admin-nav").hide();
            }
                

            }); 

        </script>  
    </head>
    <body>
    <?php include '../includes/navbar.php'; ?>    
        
        <div class="cont container">
            
            <div class="tabs">
                
                <div class="tabs-head" id="tabsTitle">
                    <span id="tab1" class="tabs-toggle">&bull;&nbsp;Student Summary&nbsp;&bull;</span>
                </div>

                <!-- <div class="search">
                    <form action="studentSummary.php" method="GET">
                        <div class="form-group">
                            <div class="search-input mt-3">
                                <input type="text" name="idnumber" id="idnumber" placeholder="ID Number">
                                <button class="btn btn-success btnSearch" type="Submit" value="Search" name="btnSearch" id="btnSearch">Search</button>
                            </div>
                            
                        </div>
                        
                    </form>
                    
                </div> -->

                <div id="notif">

                    <a id='newRecord' class='btn btn-primary' href='newStudent.php?type=newRecord' role='button'>New Record</a>
                        <span id='NumRecord'>Total Number of Record/s: </span>
                     
                </div>
                <div class="tabs-body">
                    <div class="tabs-content is-active table-responsive"> 

                        <table id="student_info" class="table table-striped table-bordered">  
                              <thead>  
                                   <tr>  
                                        <th>ID</th>
                                        <th>Full Name</th>
                                        <th>Degree / Strand</th>
                                        <th>Age</th>
                                        <th>Sex</th>
                                        <th>Contact Number</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                   </tr>  
                              </thead>  
                              <?php 

                              if(!empty($resultStudent)){
                                while($RowStudent = $resultStudent->fetch_array()) 
                                  {  
                                    $Sex = ucwords($RowStudent['Sex']);
                                    $course = "";
                                    $Lastname = ucwords($RowStudent['Lastname']);
                                    $FirstName = ucwords($RowStudent['Firstname']);
                                    $MiddleName = ucwords($RowStudent['Middlename']);
                                    $StudentName = "$Lastname, $FirstName $MiddleName";
                                        if($RowStudent['Course'] != ""){
                                            $course = $RowStudent['Course'];
                                        }else{
                                            $course = ucwords($RowStudent['StudentCategory']);
                                        }
                                    ?>
                                        <tr>
                                            <td><?php echo $RowStudent['StudentIDNumber']; ?></td>
                                            <td><a class="fullnameLabel" href="studentSummary.php?idnumber=<?php echo $RowStudent['StudentIDNumber']; ?>" ><?php echo "$StudentName"; ?></a></td>
                                            <td><?php echo "$course";?></td>
                                            <td><?php echo $RowStudent['Age'];?></td>
                                            <td><?php echo "$Sex";?></td>
                                            <td><?php echo $RowStudent['StudentContactNumber'];?></td>
                                            <td><?php echo $RowStudent['Date'];?></td>
                                            <td class="text-center">
                                                <a class="viewBTN btn-success btn-sm" role="button" href="newStudent.php?id=<?php echo $RowStudent['StudentIDNumber']; ?>&type=viewRecord" data-toggle="tooltip" data-placement="bottom" title="View Student Information">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                      <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                                      <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                                    </svg>
                                                </a>
                                                <br>
                                                <a class='viewBTN btn-success btn-sm' role="button" href="studentSummary.php?idnumber=<?php echo $RowStudent['StudentIDNumber']; ?>" data-toggle="tooltip" data-placement="bottom" title="View Student Records">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-folder-symlink" viewBox="0 0 16 16">
                                                <path d="m11.798 8.271-3.182 1.97c-.27.166-.616-.036-.616-.372V9.1s-2.571-.3-4 2.4c.571-4.8 3.143-4.8 4-4.8v-.769c0-.336.346-.538.616-.371l3.182 1.969c.27.166.27.576 0 .742z"/>
                                                <path d="m.5 3 .04.87a1.99 1.99 0 0 0-.342 1.311l.637 7A2 2 0 0 0 2.826 14h10.348a2 2 0 0 0 1.991-1.819l.637-7A2 2 0 0 0 13.81 3H9.828a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 6.172 1H2.5a2 2 0 0 0-2 2zm.694 2.09A1 1 0 0 1 2.19 4h11.62a1 1 0 0 1 .996 1.09l-.636 7a1 1 0 0 1-.996.91H2.826a1 1 0 0 1-.995-.91l-.637-7zM6.172 2a1 1 0 0 1 .707.293L7.586 3H2.19c-.24 0-.47.042-.683.12L1.5 2.98a1 1 0 0 1 1-.98h3.672z"/>
                                                </svg>
                                                </a>
                                                <br>
                                                <a class='viewBTNArchive btn-danger btn-sm' role="button" href="#" onclick='userArchiveRecord(<?php echo $RowStudent['StudentIDNumber']; ?>,"archiveStudent")' data-toggle="tooltip" data-placement="bottom" title="Archive">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-archive" viewBox="0 0 16 16">
                                                      <path d="M0 2a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 12.5V5a1 1 0 0 1-1-1V2zm2 3v7.5A1.5 1.5 0 0 0 3.5 14h9a1.5 1.5 0 0 0 1.5-1.5V5H2zm13-3H1v2h14V2zM5 7.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                  <?php
                                       
                                  } 
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
