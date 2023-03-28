<?php
    require_once '../php/Database.php';
    require '../php/centralConnection.php';
    date_default_timezone_set('Asia/Manila');
    session_start();
    if(empty($_SESSION['logged_in'])){
    header('Location: ../index.html');
    }

        $id = $_GET['idnumber'];
        $query ="SELECT * FROM PersonalMedicalRecord WHERE StudentIDNumber = '$id'";  
        $resultStudent = mysqli_query($connect, $query);

        $query ="SELECT * FROM consultationinfo WHERE IdNumb = '$id'";  
        $resultCons = mysqli_query($connect, $query);

        $query ="SELECT * FROM medicalcertificate WHERE student_id = '$id'";  
        $resultMC = mysqli_query($connect, $query);

        if(!empty($resultStudent)){
            $RowStudent = $resultStudent->fetch_array();

            $ID = $RowStudent['StudentIDNumber'];
            $Firstname = ucwords($RowStudent['Firstname']);
            $Middlename = ucwords($RowStudent['Middlename']);
            $Lastname = ucwords($RowStudent['Lastname']);
            $Extension = ucwords($RowStudent['Extension']);

            $Age = ucwords($RowStudent['Age']);
            $Sex = ucwords($RowStudent['Sex']);
            $ContactNumber = ucwords($RowStudent['StudentContactNumber']);
            
            $course = "";
            if($RowStudent['Course'] != ""){
                $course = $RowStudent['Course'];
            }else{
                $course = ucwords($RowStudent['StudentCategory']);
            }

            $year = $RowStudent['Year'];
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

            $('#TxtStudentIDNumber2').val('<?php echo $ID; ?>');
            $('#TxtLastName').val('<?php echo $Lastname; ?>');
            $('#TxtFirstName').val('<?php echo $Firstname; ?>');
            $('#TxtMiddleName').val('<?php echo $Middlename; ?>');
            $('#TxtExtension').val('<?php echo $Extension; ?>');
            $('#TxtAge').val('<?php echo $Age; ?>');
            $('#TxtSex').val('<?php echo $Sex; ?>');
            $('#TxtContactNumber').val('<?php echo $ContactNumber; ?>');
            $('#TxtCourseStrand').val('<?php echo $course; ?>');
            $('#TxtYear').val('<?php echo $year; ?>');

            var ConsTable = $('#cons_info').DataTable({
                "oLanguage": {
                "sSearch": "Filter results:"
                }  
            });

            var ConsLength = ConsTable.page.info().recordsTotal;

            /*var span = document.getElementById("ConsSpan");
            span.textContent = "Total Number of Record/s: " + ConsLength.toString();*/

            var MCTable = $('#mc_info').DataTable({ 
                "oLanguage": {
                "sSearch": "Filter results:"
                }  
            });

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
                <div class="form-row mr-4 mt-3">
                    <a id='backButton' class='btn btn-light m-3 bg-transparent border-0' onclick='window.history.back();' role='button'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='30' height='30' fill='currentColor' class='bi bi-arrow-left-circle' viewBox='0 0 16 16'>
                          <path fill-rule='evenodd' d='M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z'/>
                        </svg>
                    </a>
                    <span class="h3 student-title" style="margin:auto";>&bull;&nbsp;Student Information&nbsp;&bull;</span>
                </div>
                <div class="form-row mx-4 my-2">
                    <div class="col-md-6">
                        <label for="TxtStudentIDNumber2">ID Number</label> <span id="req">*</span>
                        <input name="TxtStudentIDNumber2" class="form-control" type="Number" id="TxtStudentIDNumber2" readonly>
                    </div>
                </div>

                <div class="form-row mx-4 my-2">
                    <div class="col-md-3">
                        <label for="TxtLastName">Last Name</label>
                        <input type="text" name="TxtLastName" class="form-control" id="TxtLastName" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="TxtFirstName">First Name</label>
                        <input type="text" name="TxtFirstName" class="form-control" id="TxtFirstName" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="TxtMiddleName">Middle Name</label>
                        <input type="text" name="TxtMiddleName" class="form-control" id="TxtMiddleName" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="TxtExtension">Extension</label>
                        <input type="text" name="TxtExtension" class="form-control" id="TxtExtension" readonly>
                    </div>
                </div>

                <div class="form-row mx-4 my-2">
                    <div class="col-md-3">
                        <label for="TxtAge">Age</label> 
                        <input type="number" class="form-control" name="TxtAge" id="TxtAge" readonly >
                    </div>
                    <div class="col-md-3">
                        <label for="TxtSex">Sex</label> 
                        <input type="text" class="form-control" name="TxtSex" id="TxtSex" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="TxtContactNumber">Contact Number</label> 
                        <input type="text" class="form-control" name="TxtContactNumber" id="TxtContactNumber" readonly>
                    </div>
                </div>

                <div class="form-row mx-4 my-2 mb-3">
                    <div class="col-md-8">
                        <label for="TxtCourseStrand">Course / Strand</label>
                        <input type="text" class="form-control" name="TxtCourseStrand" id="TxtCourseStrand" readonly minlength="2">
                    </div>
                    <div class="col-md-4">
                        <label for="TxtYear">Year</label>
                        <input type="text" class="form-control" name="TxtYear" id="TxtYear" readonly maxlength="3">
                    </div>
                </div>
                
                <div class="tabs-head" id="tabsTitle">
                    <span id="tab2" class="tabs-toggle is-active">&bull;&nbsp;Consultation Info&nbsp;&bull;</span>
                    <span id="tab3" class="tabs-toggle">&bull;&nbsp;Medical Certs&nbsp;&bull;</span>
                </div>

                <div class="tabs-body">
                    <div class="tabs-content is-active table-responsive">  
                        
                        <table id="cons_info" class="table table-striped table-bordered">  
                              <thead>  
                                   <tr>  
                                      <th>Date</th>
                                      <th>Time</th>
                                      <th>Diagnosis</th>
                                      <th>Treatment</th>
                                      <th>Staff</th>
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
                                            <td><?php echo $RowCons['Dates']; ?></td>
                                            <td><?php echo $RowCons['Times']; ?></td>
                                            <td><?php echo $RowCons['Diagnosis']; ?></td>
                                            <td><?php echo $RowCons['DiagnosticTestNeeded']; ?></td>
                                            <td><?php echo $Staff; ?></td>
                                            
                                            <td>
                                                <a class="viewBTN btn btn-primary btn-sm"  href="newConsultation.php?num=<?php echo $RowCons['Num']; ?>&type=viewCons">View</a>
                                                <a class="viewBTN btn btn-primary btn-sm"  href="indexFU.php?id=<?php echo $RowCons['IdNumb']; ?>&date=<?php echo $RowCons['Dates']; ?>&time=<?php echo $RowCons['Times']; ?>&type=checkRelFU">Follow-ups</a>
                                                <a class="viewBTN btn btn-primary btn-sm" id="archiveBTN" onclick='userArchiveRecord(<?php echo $RowCons['Num']; ?>,"archiveConsultation")'>Archive</a>
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
                                      <th>Date Requested</th>
                                      <th>Purpose</th>
                                      <th>Staff</th>
                                      
                                      <th>Action</th>
                                            
                                   </tr>  
                              </thead>  
                              <?php 

                              if(!empty($resultMC)){
                                while($RowMC = $resultMC->fetch_array()) 
                                  {  
                                    $staffMC = ucwords(strtolower($RowMC['mc_physician']));
                                    $purpose = '';
                                    
                                    if(!empty($RowMC['purpose_others'])){
                                        $purpose = $RowMC['purpose_others'];
                                    }else if(!empty($RowMC['purpose'])){
                                        $purpose = $RowMC['purpose'];
                                    }
                                        
                                    ?>
                                        
                                        <tr>
                                            <td><?php echo $RowMC['date_requested']; ?></td>
                                            <td><?php echo $purpose; ?></td>
                                            <td><?php echo $staffMC; ?></td>
                                            
                                            <td>
                                                <a class="viewBTN btn btn-primary btn-sm" href="newMC.php?studentID=<?php echo $RowMC['student_id']; ?>&id=<?php echo $RowMC['mc_id_num']; ?>&type=viewMC">View</a>
                                                <a class="viewBTN btn btn-primary btn-sm" id="archiveBTN" onclick="userArchiveRecord(<?php echo $RowMC['mc_id_num']; ?>,'archiveMC')">Archive</a>
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
