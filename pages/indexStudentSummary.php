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
                <!-- <div id="notif">

                    <button id='newRecord' data-toggle="modal" data-target="#VaccineNewModal" class='btn btn-primary' >New Vaccine</button>
                    <span id='StudentInfoSpan'>Total Number of Record/s: </span>
                     
                </div> -->
                <div class="tabs-body">
                    <div class="tabs-content is-active table-responsive"> 

                        <table id="student_info" class="table table-striped table-bordered">  
                              <thead>  
                                   <tr>  
                                        <th>ID</th>
                                        <th>Full Name</th>
                                        <th>Course / Strand</th>
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
                                    $StudentName = "$RowStudent[Lastname], $RowStudent[Firstname] $RowStudent[Middlename]";
                                        if($RowStudent['Course'] != ""){
                                            $course = $RowStudent['Course'];
                                        }else{
                                            $course = ucwords($RowStudent['StudentCategory']);
                                        }
                                    ?>
                                        <tr>
                                            <td><?php echo $RowStudent['StudentIDNumber']; ?></td>
                                            <td><a href="studentSummary.php?idnumber=<?php echo $RowStudent['StudentIDNumber']; ?>" ><?php echo "$StudentName"; ?></a></td>
                                            <td><?php echo "$course";?></td>
                                            <td><?php echo $RowStudent['Age'];?></td>
                                            <td><?php echo "$Sex";?></td>
                                            <td><?php echo $RowStudent['StudentContactNumber'];?></td>
                                            <td><?php echo $RowStudent['Date'];?></td>
                                            <td>
                                                <a class="viewBTN btn btn-primary btn-sm" href="newStudent.php?id=<?php echo $RowStudent['StudentIDNumber']; ?>&type=viewRecord">View</a>
                                                <a class='viewBTN btn btn-primary btn-sm' id='archiveBTN' onclick='userArchiveRecord(<?php echo $RowStudent['StudentIDNumber']; ?>,"archiveStudent")'>Archive</a>
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
