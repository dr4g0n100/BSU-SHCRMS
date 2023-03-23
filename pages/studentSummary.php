<?php
    require_once '../php/Database.php';
    require '../php/centralConnection.php';
    date_default_timezone_set('Asia/Manila');
    session_start();
    if(empty($_SESSION['logged_in'])){
    header('Location: ../index.html');
    }

    if(isset($_POST['idnumber'])){
        $id = $_POST['idnumber'];
        $query ="SELECT * FROM PersonalMedicalRecord WHERE StudentIDNumber = '$id'";  
        $resultStudent = mysqli_query($connect, $query);

        $query ="SELECT * FROM consultationinfo WHERE IdNumb = '$id'";  
        $resultCons = mysqli_query($connect, $query);

        $query ="SELECT * FROM medicalcertificate WHERE student_id = '$id'";  
        $resultMC = mysqli_query($connect, $query);
    }

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

            function userViewRecord(StaffID){
                act = "Checked Staff ID:" +StaffID +" Information."
                logAction(act);
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
            
            var StudentInfoTable = $('#student_info').DataTable({ });

            var StudentInfoLength = StudentInfoTable.page.info().recordsTotal;

            /*var span = document.getElementById("StudentInfoSpan");
            span.textContent = "Total Number of Record/s: " + StudentInfoLength.toString();*/

            var ConsTable = $('#cons_info').DataTable({ });

            var ConsLength = ConsTable.page.info().recordsTotal;

            /*var span = document.getElementById("ConsSpan");
            span.textContent = "Total Number of Record/s: " + ConsLength.toString();*/

            var MCTable = $('#mc_info').DataTable({ });

            var StudentInfoLength = MCTable.page.info().recordsTotal;

            /*var span = document.getElementById("MCSpan");
            span.textContent = "Total Number of Record/s: " + MCLength.toString();*/
        
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
                <div class="search">
                    <form action="studentSummary.php" method="POST">
                        <div class="form-group">
                            <div class="search-input">
                                <label for="idnumber" class="col-form-label">Search</label><br>
                                <input type="text" name="idnumber" id="idnumber" placeholder="ID Number">
                                <button class="btn btn-primary" type="Submit" value="Search" name="btnSearch" id="btnSearch">Search</button>
                            </div>
                            
                        </div>
                        
                    </form>
                    
                </div>
                <div class="tabs-head" id="tabsTitle">
                    <span id="tab1" class="tabs-toggle is-active">&bull;&nbsp;Student Info&nbsp;&bull;</span>
                    <span id="tab2" class="tabs-toggle">&bull;&nbsp;Consultation Info&nbsp;&bull;</span>
                    <span id="tab3" class="tabs-toggle">&bull;&nbsp;Medical Certs&nbsp;&bull;</span>
                </div>
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
                                            <td><?php echo "$StudentName"; ?></td>
                                            <td><?php echo "$course";?></td>
                                            <td><?php echo $RowStudent['Age'];?></td>
                                            <td><?php echo "$Sex";?></td>
                                            <td><?php echo $RowStudent['StudentContactNumber'];?></td>
                                            <td><?php echo $RowStudent['Date'];?></td>
                                            <td>
                                                <a class="viewBTN btn btn-primary btn-sm" href="newStudent.php?id=<?php echo $RowStudent['StudentIDNumber']; ?>&type=viewRecord">View</a>
                                                <a class='viewBTN btn btn-primary btn-sm' id='archiveBTN' onclick='userArchiveRecord(<?php echo $RowStudent['StudentIDNumber']; ?>)'>Archive</a>
                                            </td>
                                        </tr>
                                  <?php
                                       
                                  } 
                              } 

                                  

                              ?>  
                         </table> 
                    </div>
                    <div class="tabs-content table-responsive">  
                        
                        <table id="cons_info" class="table table-striped table-bordered">  
                              <thead>  
                                   <tr>  
                                      <th>ID</th>
                                      <th>Full name</th>
                                      <th>Diagnosis</th>
                                      <th>Treatment</th>
                                      <th>Staff</th>
                                      <th>Date</th>
                                      <th>Action</th>
                                            
                                   </tr>  
                              </thead>  
                              <?php 

                              if(!empty($resultCons)){
                                while($RowCons = $resultCons->fetch_array()) 
                                  {  
                                    $Staff = ucwords(strtolower($RowCons['Physician']));
                                    ?>
                                        
                                        <tr>
                                            <td><?php echo $RowCons['IdNumb']; ?></td>
                                            <td><?php echo "$StudentName"; ?></td>
                                            <td><?php echo $RowCons['Diagnosis']; ?></td>
                                            <td><?php echo $RowCons['DiagnosticTestNeeded']; ?></td>
                                            <td><?php echo $Staff; ?></td>
                                            <td><?php echo $RowCons['Dates']; ?></td>
                                            <td>
                                                <a class="viewBTN btn btn-primary btn-sm"  href="newConsultation.php?num=<?php echo $RowCons['Num']; ?>&type=viewCons">View</a>
                                                <a class="viewBTN btn btn-primary btn-sm"  href="indexFU.php?id=<?php echo $RowCons['IdNumb']; ?>&date=<?php echo $RowCons['Dates']; ?>&time=<?php echo $RowCons['Times']; ?>&type=checkRelFU">Follow-ups</a>
                                                <a class="viewBTN btn btn-primary btn-sm" id="archiveBTN" onclick='userArchiveRecord(<?php echo $RowCons['Num']; ?>)'>Archive</a>
                                            </td>

                                        </tr>
                                  <?php
                                       
                                  } 
                              } 

                                  

                              ?>  
                         </table> 
                    </div>
                    <div class="tabs-content table-responsive">
                         
                        <table id="mc_info" class="table table-striped table-bordered">  
                              <thead>  
                                   <tr>  
                                      <th>ID</th>
                                      <th>Full Name</th>
                                      <th>Staff</th>
                                      <th>Date Requested</th>
                                      <th>Action</th>
                                            
                                   </tr>  
                              </thead>  
                              <?php 

                              if(!empty($resultMC)){
                                while($RowMC = $resultMC->fetch_array()) 
                                  {  
                                    $staffMC = ucwords(strtolower($RowMC['mc_physician'])) ;
                                    ?>
                                        
                                        <tr>
                                            <td><?php echo $RowMC['student_id']; ?></td>
                                            <td><?php echo $StudentName; ?></td>
                                            <td><?php echo $staffMC; ?></td>
                                            <td><?php echo $RowMC['date_requested']; ?></td>
                                            <td>
                                                <a class="viewBTN btn btn-primary btn-sm" href="newMC.php?studentID=<?php echo $RowMC['student_id']; ?>&id=<?php echo $RowMC['mc_id_num']; ?>&type=viewMC">View</a>
                                                <a class="viewBTN btn btn-primary btn-sm" id="archiveBTN" onclick="userArchiveRecord(<?php echo $RowMC['mc_id_num']; ?>)">Archive</a>
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
