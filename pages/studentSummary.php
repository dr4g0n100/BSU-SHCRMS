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
        <title>Vaccine List</title>
          
        <link rel = "icon" href = "../images/BSU-Logo.webp" type = "image/x-icon">
        <!-- Bootstrap CSS -->
        
        <link rel="stylesheet" href="../dist/dataTables.bootstrap.min.css" />
        <link rel="stylesheet" href="../dist/bootstrap2.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        
        <link rel="stylesheet" href="../dist/jquery-confirm.min.css">
        <link rel="stylesheet" type="text/css" href="../dist/dataTable/jquery.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="../dist/dataTable/buttons.dataTables.min.css">

        <link rel="stylesheet" href="../css/studentSummary.css">

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->

        <script type="text/javascript" src="../dist/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script type="text/javascript" src="../dist/jquery-3.6.0.min.js"></script>
        <script type="text/javascript" src="../dist/jquery-confirm.min.js"></script>
        <script src="../dist/jquery-ui.js"></script>

        <script type="text/javascript" src="../dist/dataTable/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="../dist/dataTable/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="../dist/dataTable/jszip.min.js"></script>
        <script type="text/javascript" src="../dist/dataTable/pdfmake.min.js"></script>
        <script type="text/javascript" src="../dist/dataTable/vfs_fonts.js"></script>
        <script type="text/javascript" src="../dist/dataTable/buttons.html5.min.js"></script>
        <script type="text/javascript" src="../dist/dataTable/buttons.print.min.js"></script>

        
        

        <script src="../dist/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="../dist/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="../dist/jspdf.debug.js"></script>
        <script src="../dist/jspdf.min.js"></script>
        <script src="../dist/html2pdf.bundle.min.js"></script>

       

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
    <nav class="navbar navbar-expand navbar-light bg-light">
        <li class="nav-item userFN">
        <span id="userFullname"><b><?php echo ucwords($_SESSION['homePosDisp']) . " ";
        $tempNAME = strtolower($_SESSION['fullname']);
        echo ucwords($tempNAME); 
        ?></b></span>
        </li>
        <div class="mr-auto"></div>
        <ul class="navbar-nav">
            <li class="nav-item mx-1">
            <a class="nav-link" href="Homepage/index.php">Home</a>
            </li>

            </li>
             <li class="nav-item dropdown mx-1 admin-nav">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-display="static" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Census
              </a>
              <div class="dropdown-menu dropdown-menu-lg-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="studentSummary.php">Student Summary</a>
                <a class="dropdown-item" href="Homepage/index.php">Dashboard</a>
              </div>
            </li>

            <li class="nav-item mx-1 " id="userlistID">
            <a class="nav-link admin-nav" href="userList.php?type=checkRecords">User List</a>
            </li>
            <li class="nav-item dropdown mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-display="static" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Records
            </a>
            <div class="dropdown-menu dropdown-menu-lg-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="Student/index.php?type=checkRecords">Student Records</a>
                <a class="dropdown-item" href="Consultation/index.php?type=checkRecords">Consultation Records</a>
                <!-- <a class="dropdown-item" href="Followup/index.php?type=checkRecords">Follow-up Consultation</a> -->
                <a class="dropdown-item" href="MedicalCertificate/index.php?type=checkMC">Medical Certificate</a>
            </div>
            </li>
            <li class="nav-item dropdown mx-1 admin-nav" id="archivedID">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-display="static" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Archived Records
            </a>
            <div class="dropdown-menu dropdown-menu-lg-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="Student/index.php?type=checkArchivedStudent">Archived Student Records</a>
                <a class="dropdown-item" href="Consultation/index.php?type=checkArchivedConsultation">Archived Consultation Records</a>
                <a class="dropdown-item" href="Followup/index.php?type=checkArchivedFollowUp">Archived Follow-up Records</a>
                <a class="dropdown-item" href="MedicalCertificate/index.php?type=checkArchivedMC">Archived Medical Certificates</a>
                <a class="dropdown-item" href="userList.php?type=checkArchivedStaff">Archived Staff Accounts</a>
                <a class="dropdown-item" href="logs.php?type=checkArchivedLogs">Archived System Logs</a>
            </div>
            </li>
            <li class="nav-item dropdown mx-1 admin-nav active" id="maintenanceID">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-display="static" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Maintenance
            </a>
            <div class="dropdown-menu dropdown-menu-lg-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="logs.php?type=checkRecords">Logs</a>
                <a class="dropdown-item" href="degreeList.php">Degree List</a>
                <a class="dropdown-item" href="vaccineList.php">Vaccine List</a>
                <a class="dropdown-item" href="backup.php">Backup</a>
                <a class="dropdown-item" href="restore.php">Restore</a>
            </div>
            </li>
            <li class="nav-item mx-1">
            <a class="nav-link" href="help.php?type=<?php echo $_SESSION['accesslevel']; ?>">Help</a>
            </li>
            <li class="nav-item mx-1">
            <a class="nav-link" href="#" onclick="logout()">Logout</a>
            </li>

        </ul>

   
  </nav>     
        <div>
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
        <div class="cont container">
            
            <div class="tabs">
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
                                                <a class="viewBTN btn btn-primary btn-sm" href="Student/pages/newRecord.php?id=<?php echo $RowStudent['StudentIDNumber']; ?>&type=viewRecord">View</a>
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
                                                <a class="viewBTN btn btn-primary btn-sm"  href="Consultation/pages/newConsultation.php?num=<?php echo $RowCons['Num']; ?>&type=viewCons">View</a>
                                                <a class="viewBTN btn btn-primary btn-sm"  href="Followup/index.php?id=<?php echo $RowCons['IdNumb']; ?>&date=<?php echo $RowCons['Dates']; ?>&time=<?php echo $RowCons['Times']; ?>&type=checkRelFU">Follow-ups</a>
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
                                                <a class="viewBTN btn btn-primary btn-sm" href="MedicalCertificate/page/medicalCertificate.php?studentID=<?php echo $RowMC['student_id']; ?>&id=<?php echo $RowMC['mc_id_num']; ?>&type=viewMC">View</a>
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
