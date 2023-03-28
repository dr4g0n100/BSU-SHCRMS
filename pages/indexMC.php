<?php  
require '../php/centralConnection.php';
 session_start();
 if(empty($_SESSION['logged_in'])){
    header('Location: ../index.html');
 } 
 $type = "";

 if($_SERVER['REQUEST_METHOD'] == 'GET'){
        if($_GET["type"] == "checkMC"){
            $query ="SELECT * FROM medicalcertificate GROUP BY student_id ORDER BY date_requested DESC";  
            $result = mysqli_query($connect, $query);
        }else if($_GET["type"] == "checkArchivedMC"){
            $query ="SELECT * FROM archivemedcertificate"; 
            $result = mysqli_query($connect, $query);
        }else if($_GET["type"] == "checkRange"){
            $start = $_GET['start'];
            $end = $_GET['end'];
            $query ="SELECT * FROM medicalcertificate WHERE date_requested >= '$start' AND date_requested <= '$end'";  
            $result = mysqli_query($connect, $query);
        }else if($_GET["type"] == "checkMCId"){
            $id = $_GET["id"];
            $query ="SELECT * FROM medicalcertificate WHERE student_id ='$id'";  
            $result = mysqli_query($connect, $query);
        }   
    }
    $type = $_GET["type"];
 ?>  


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <?php
            if($_GET["type"] == "checkMC" || $type == 'checkRange') {
                echo "<title>Consultation Summary</title>";
            } else if($_GET["type"] == "checkArchivedMC") {
                echo "<title>Archived Medical Certificates</title>";
            }else if($_GET["type"] == "checkMCId"){
                echo "<title>Medical Certificates</title>";
            }
        ?>
          
        <link rel = "icon" href = "../images/BSU-Logo.webp" type = "image/x-icon">
        <?php include '../includes/dependencies0.php'; ?>

        <link rel="stylesheet" href="../css/medicalCertificateTable-style.css">

        <script>
        // ---------------------------start functions for System Logs---------------------------------------
            var act = "";
            var acttype = "";
            var globalAL = "";
            var tempVari = "";
            var fullname = "";

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

            function editTableNav(y,name){
                if(y == "checkArchived"){
                    document.getElementById('tab1').innerHTML = '&bull;&nbsp;Archived Medical Certificates&nbsp;&bull;';
                }else if(y == "checkRecord"){
                    document.getElementById('tab1').innerHTML = '&bull;&nbsp;Medical Certificates Summary&nbsp;&bull;';
                }else if(y == "checkRecordsId"){
                    document.getElementById('tab1').innerHTML = '&bull;&nbsp;Medical Certificate Requests of '+name +'&nbsp;&bull;';
                }
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

            //called to log user clicking "logs" tab
            function userCheckLogs(){
                act = "Checked User Activities."
                logAction(act);
            }

            function userViewCertificate(StudentID){
                act = "Checked Student ID:" +StudentID +" medical certficate."
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

            function userRestoreRecord(StudentID){
                acttype = "restoreMC";
                ID = StudentID;

                if(confirm("Are you sure you want to restore this medical certificate?")){
                    $.ajax({
                    url:"../php/restore.php",
                    method:"GET",
                    data:jQuery.param({ type: acttype, id:ID }),
                    success:function(xml){
                        $(xml).find('output').each(function()
                        {
                            var message = $(this).attr('Message');
                            logAction(message +" ID " +ID +"");
                            alert(message);
                        });
                        location.reload();
                        
                    }
                })
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
            
            var reportTitle = "";
            if(getType == 'checkMC'){
                reportTitle = "Medical Certificates Summary";
            }else if(getType == 'checkMCId'){
                reportTitle = "Medical Certificate Requests of " +fullname;
            }
            var table = $('#MC_data').DataTable({
                dom: 'fltpB',
                buttons: [                              
                    {
                        extend:'print',
                        text:'Print Report',
                        title:"<h1 style='text-align:center;'>"+reportTitle+"</h1>",
                        exportOptions: {
                            columns: [0,1,2]
                        }           
                    },
                    {
                       extend:'pdf',
                       text:'Export to PDF',
                       title:reportTitle,
                       exportOptions: {
                            columns: [0,1,2]
                        }  
                    },
                    {
                       extend:'excel',
                       text:'Export to Excel',
                       title:reportTitle,
                       exportOptions: {
                            columns: [0,1,2]
                        }   
                    },
                  ],
                "oLanguage": {
                "sSearch": "Filter results:"
                }
            });
            var length = table.page.info().recordsTotal;

            var span = document.getElementById("NumMedCert");
            span.textContent = "Total Number of Medical Certificate/s:   " + length.toString();

            //Hides Nav Items to Staff Account

                var acclvl = sessionStorage.getItem('isStandard');

                if(acclvl == "true"){
                    $(".admin-nav").hide();

                    document.getElementById("userFullname").style.width = "52%";
                    /*document.getElementById("nav2").style.width = "9.33%";
                    document.getElementById("nav3").style.width = "9.33%";
                    document.getElementById("nav4").style.width = "9.33%";
                    document.getElementById("nav5").style.width = "9.33%";
                    document.getElementById("nav7").style.width = "9.33%";
                    document.getElementById("nav8").style.width = "9.33%";*/
                }
        });  
        </script>  
        <!--
        <link rel="stylesheet" type="text/css" href="dist/jquery.dataTables.min.css" /> 
        <script src="dist/jquery-3.6.0.min.js"></script>
        <link rel="stylesheet" href="dist/jquery-confirm.min.css">
        <script src="dist/jquery-confirm.min.js"></script>
        <link href='dist/bootstrap.css' rel="stylesheet" type="text/css">
        <link href='dist/bootstrap-darky theme.css' rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="dist/buttons.dataTables.min.css" />  
        <script src="dist/jquery.min.js" type="text/javascript"></script>
        <script>window.jQuery || document.write('<script src="dist/jquery.min.js"><\/script>')</script>
        <script src="dist/jquery.dataTables.min.js" type="text/javascript"></script>
        <script src="dist/dataTables.buttons.min.js" type="text/javascript"></script>
        <script src="dist/buttons.flash.min.js" type="text/javascript"></script>
        <script src="dist/jszip.min.js" type="text/javascript"></script>
        <script src="dist/pdfmake.min.js" type="text/javascript"></script>
        <script src="dist/vfs_fonts.js" type="text/javascript"></script>
        <script src="dist/buttons.html5.min.js" type="text/javascript"></script>
        <script src="dist/buttons.print.min.js" type="text/javascript"></script>
        <script type="text/javascript">
            function searchPatient()
            {  
                $.ajax(
                {
                    type:"POST",
                    url:"php/Fetch.php",
                    cache: false,
                    data:jQuery.param({ IDNumber: $("#SearchIDNumber").val()}),
                    dataType: "xml",
                    success:function(xml)
                    {
                        alert(1);
                        var row = ''; 
                        $(xml).find('patients').each(function()
                        {  
                            var idno = $(this).attr('idno');
                            var lastname = $(this).attr('lastname');    
                            var firstname = $(this).attr('firstname'); 
                            var middlename = $(this).attr('middlename');                 
                            var age = $(this).attr('age');
                            var sex = $(this).attr('sex');
                            var contactNumStudent = $(this).attr('contactNumStudent');
                            var address = $(this).attr('address');
                            var namePG = $(this).attr('namePG');
                            var contactPG = $(this).attr('contactPG');

                            row += "<tr>" +
                                   "<td>" + idno + "</td>" +
                                   "<td>" + lastname + "</td>" + 
                                   "<td>" + firstname + "</td>" + 
                                   "<td>" + middlename + "</td>" + 
                                   "<td>" + age + "</td>" +
                                   "<td>" + sex + "</td>" + 
                                   "<td>" + contactNumStudent + "</td>" + 
                                   "<td>" + address + "</td>" +
                                   "<td>" + namePG + "</td>" + 
                                   "<td>" + contactPG + "</td>" +
                                   "</tr>";

                            $("#Rows").html(row);
                        });
                        GenerateReport(); 
                     },
                    error: function (e)
                    {
                        //Display Alert Box
                        $.alert(
                        {theme: 'modern',
                        content:'Failed to search informations due to errors',
                        title:'', 
                        buttons:{
                            Ok:{
                            text:'Ok',
                            btnClass: 'btn-red'
                        }}});
                    }
                });
            }

            function GenerateReport()
            {              
              $('#ListPatient').DataTable( {
                  dom: 'Bfrip',              
                  "searching":true,
                  "bDestroy": true,
                  buttons: [                              
                    {
                        extend:'print',
                        text:'Print Report',
                        pageSize : 'Legal',
                        orientation: 'portrait',
                        title:"<h1 style='text-align:center;'>List of Patients</h1>",
                        exportOptions:{
                            stripHtml:false
                        },
                        header:true,
                        footer:false                    
                    },
                    {
                       extend:'pdf',
                       text:'Export to PDF',
                       title:"List of Patients",
                       header:true,
                       footer:false 
                    },
                    {
                       extend:'copy',
                       text:'Copy',
                       title:"List of Patients",
                       header:true,
                       footer:false 
                    },
                    {
                       extend:'csv',
                       text:'Export to CSV',
                       title:"List of Patients",
                       header:true,
                       footer:false 
                    },
                    {
                       extend:'excel',
                       text:'Export to Excel',
                       title:"List of Patients",
                       header:true,
                       footer:false 
                    },
                  ],        
                  "bJQueryUI": false,
                  "oLanguage": 
                  {
                    "sSearch": "Filter:"
                  }                  
              });        
            }     

            $(document).ready(function() 
            {
                $("#BtnSearch").click(function(event)
                {
                    if ($.fn.DataTable.isDataTable("#example")) 
                    {                  
                        $('#ListPatient').DataTable().clear().destroy();                  
                    }
                    searchPatient(); 
                });             
                    
            }); 
        </script> -->
    </head>
    <body>
    <?php include '../includes/navbar.php'; ?>  
        <div class="cont container">
            <div class="tabs">
                <div class="tabs-head" style="display: inline-flex;">
                    <?php  
                        if ($_GET["type"] == "checkMCId"){
                            echo "
                            <div>
                            <a id='backButton' class='btn btn-light m-3 bg-transparent border-0' onclick='window.history.back();' role='button'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='30' height='30' fill='currentColor' class='bi bi-arrow-left-circle' viewBox='0 0 16 16'>
                                  <path fill-rule='evenodd' d='M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z'/>
                                </svg>
                            </a>
                            </div>";
                        }
                    ?>
                    <span id="tab1" class="tabs-toggle is-active">&bull;&nbsp;Medical Certificate History&nbsp;&bull;</span>
                </div>
                <div id="notif">
                    <?php if ($_GET["type"] == "checkMC" || $type == 'checkRange' || $type == 'checkMCId'){
                        echo "
                        <a id='newConsultation' class='btn btn-primary' href='newMC.php?type=newMC' role='button'>New Certificate</a>
                        <span id='NumMedCert'>Total Number of Certificate Requests/s: </span>
                        ";

                    } ?>
                    
                </div>
                <div class="tabs-body">
                    <div class="tabs-content is-active table-responsive">  
                    <table id="MC_data" class="table table-striped table-bordered">  
                          <thead>  
                               <tr>  
                                        <th>ID Number</th>
                                        <th>Full Name</th>
                                        <?php 
                                        if ($_GET["type"] == "checkMC"){
                                            echo "<th>Total</th>";
                                        }else{
                                            echo "<th>Staff</th>";
                                        } 
                                        ?>

                                        <?php 
                                        if ($_GET["type"] == "checkMCId"){
                                            echo "<th>Date Requested</th>";
                                        }else{
                                            echo "<th>Dates Requested</th>";
                                        } 
                                        ?>

                                        
                                        <?php 
                                        if ($_GET["type"] == "checkArchivedMC")
                                            echo "<th>Archive Reason</th>";
                                        ?>
                                        <th>Action</th>
                               </tr>  
                          </thead>  
                          <?php        
                          $Lastname = '';
                          $Firstname = '';
                          $Middlename = '';
                          $Extension = '';
                          while($row = mysqli_fetch_array($result))  
                          {  
                                /*$row = array_map('strtoupper', $row);*/
                                $Student_idNum = $row['student_id'];
                                $staff = ucwords(strtolower($row['mc_physician'])) ;
                                $queryArchive ="SELECT * FROM personalmedicalrecord WHERE StudentIDNumber = '$Student_idNum'";
                                $resultArchive = mysqli_query($connect, $queryArchive);

                                if(mysqli_num_rows($resultArchive) > 0){
                                    $rowArchive = mysqli_fetch_array($resultArchive);
                                    $Lastname = ucwords($rowArchive['Lastname']);
                                    $Firstname = ucwords($rowArchive['Firstname']);
                                    $Middlename = ucwords($rowArchive['Middlename']);
                                    $Extension = ucwords($rowArchive['Extension']);
                                }else{
                                    $queryArchive ="SELECT * FROM archivedstudent WHERE StudentIDNumber = '$Student_idNum'";
                                    $resultArchive = mysqli_query($connect, $queryArchive);
                                    if(mysqli_num_rows($resultArchive) > 0){
                                        $rowArchive = mysqli_fetch_array($resultArchive);
                                        $Lastname = ucwords($rowArchive['Lastname']);
                                        $Firstname = ucwords($rowArchive['Firstname']);
                                        $Middlename = ucwords($rowArchive['Middlename']);
                                        $Extension = ucwords($rowArchive['Extension']);
                                    }
                                    
                                }

                                $querydates ="SELECT date_requested FROM medicalcertificate WHERE student_id = '$row[student_id]' ORDER BY date_requested DESC";  
                                $resultdates = mysqli_query($connect, $querydates);

                                $querycount ="SELECT COUNT(mc_id_num) as count FROM medicalcertificate WHERE student_id = '$row[student_id]' ORDER BY date_requested DESC";  
                                $resultcount = mysqli_query($connect, $querycount);

                                /*else if(empty($Lastname) && $type == "checkArchivedMC"){
                                    $queryArchive ="SELECT * FROM archivemedcertificate LEFT JOIN archivedstudent ON archivemedcertificate.student_id = archivedstudent.StudentIDNumber";
                                    $resultArchive = mysqli_query($connect, $queryArchive);
                                    $rowArchive = mysqli_fetch_array($resultArchive);
                                    $Lastname = $rowArchive['Lastname'];
                                    $Firstname = $rowArchive['Firstname'];
                                    $Middlename = $rowArchive['Middlename'];
                                }*/
                                echo "  
                                <tr>
                                    <td>$row[student_id]</td>
                                    <td>$Lastname, $Firstname $Middlename $Extension</td>
                                ";

                                if ($_GET["type"] == "checkMC"){

                                    if(mysqli_num_rows($resultcount) > 0){
                                        echo "<td>";
                                        while($rowcount = mysqli_fetch_array($resultcount)){
                                            echo "$rowcount[count] <br>";
                                        }
                                        echo "</td>";

                                    }else{
                                        echo "<td></td>";
                                    }

                                    if(mysqli_num_rows($resultdates) > 0){
                                        $datesarray = array();
                                        echo "<td>";
                                        while($rowdates = mysqli_fetch_array($resultdates)){
                                            $datesarray[]= $rowdates['date_requested'];
                                        }
                                        $dates = implode(" , ", $datesarray);
                                        echo "$dates";
                                        echo "</td>";

                                    }else{
                                        echo "<td></td>";
                                    }
                                }else{
                                    echo "
                                    <td>$staff</td>
                                    <td>$row[date_requested]</td>
                                    ";
                                }

                                    if ($_GET["type"] == "checkArchivedMC")
                                    echo "<td>$row[mc_archive_reason]</td>";

                                        if ($type == 'checkMCId' || $type == 'checkRange'){
                                            echo "<td>
                                            <a class='viewBTN btn btn-primary btn-sm' href='newMC.php?studentID=$row[student_id]&id=$row[mc_id_num]&type=viewMC'>View</a>
                                            <a class='viewBTN btn btn-primary btn-sm' id='archiveBTN' onclick='userArchiveRecord($row[mc_id_num],\"archiveMC\")'>Archive</a>";
                                        }else if ($type == 'checkArchivedMC'){
                                            echo "<td>
                                            <a class='viewBTN btn btn-primary btn-sm' href='newMC.php?studentID=$row[student_id]&id=$row[mc_id_num]&type=viewArchivedMC'>View</a>
                                            <a class='viewBTN btn btn-primary btn-sm' id='archiveBTN' onclick='userRestoreRecord($row[mc_id_num])'>Restore</a>";
                                        }else if ($type == 'checkMC'){
                                            echo "<td>
                                            <a class='viewBTN btn btn-primary btn-sm' href='indexMC.php?type=checkMCId&id=$row[student_id]'>View All</a>
                                            <a class='viewBTN btn btn-primary btn-sm' id='archiveBTN' onclick='userArchiveRecord($row[student_id],\"archiveAllMC\")'>Archive All</a>";
                                        }

                                    echo "</td>
                                </tr>
                               ";  
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
<?php
    $tempo = $_SESSION['accesslevel'];
    $tempor =  "";
    $_SESSION["typed"] = $_GET["type"];

    if($_GET["type"] == 'checkArchivedMC'){
        $tempor = "checkArchived";
    }else if($_GET["type"] == 'checkMC'){
        $tempor = "checkRecord";
    }else if($_GET["type"] == 'checkMCId'){
        $tempor = "checkRecordsId";
    }

    $F1Name = substr($Firstname, 0, 1) . '.';

    echo "<script type='text/javascript'>
        globalAL = '$tempo';
        getType = '$type';
        fullname = '$Lastname, $F1Name';
        editTableNav('$tempor','$Lastname, $F1Name');
    </script>";
?>
 