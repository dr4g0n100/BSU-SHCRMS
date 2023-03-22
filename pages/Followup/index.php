<?php  
require '../../php/centralConnection.php';
 session_start();
 if(empty($_SESSION['logged_in'])){
    header('Location: ../../index.html');
 } 
 $type = "";

 if($_SERVER['REQUEST_METHOD'] == 'GET'){
        if($_GET["type"] == "checkRecords"){
            $query ="SELECT * FROM followup LEFT JOIN personalmedicalrecord ON followup.IdNumb = personalmedicalrecord.StudentIDNumber GROUP BY IdNumb ORDER BY Dates DESC";  
            $result = mysqli_query($connect, $query);
        }else if($_GET["type"] == "checkArchivedFollowUp"){
            $query ="SELECT * FROM archivedfollowup LEFT JOIN personalmedicalrecord ON archivedfollowup.IdNumb = personalmedicalrecord.StudentIDNumber";
            $result = mysqli_query($connect, $query);
        }else if($_GET["type"] == "checkRange"){
            $start = $_GET['start'];
            $end = $_GET['end'];
            $query ="SELECT * FROM followup LEFT JOIN personalmedicalrecord ON followup.IdNumb = personalmedicalrecord.StudentIDNumber WHERE Dates >= '$start' AND Dates <= '$end'";  
            $result = mysqli_query($connect, $query);
        }else if($_GET["type"] == "checkRecordsId"){
            $id = $_GET["id"];
            $query ="SELECT * FROM followup LEFT JOIN personalmedicalrecord ON followup.IdNumb = personalmedicalrecord.StudentIDNumber WHERE IdNumb ='$id' ";  
            $result = mysqli_query($connect, $query);
        }else if($_GET["type"] == "checkRelFU"){
            $id = $_GET["id"];
            $date = $_GET["date"];
            $time = $_GET["time"];
            $query ="SELECT * FROM followup LEFT JOIN personalmedicalrecord ON followup.IdNumb = personalmedicalrecord.StudentIDNumber WHERE IdNumb ='$id' AND cons_date = '$date' AND cons_time = '$time'";  
            $result = mysqli_query($connect, $query);
        }else{
            $result = '';
        }  
    }
    $type = $_GET["type"];
 ?>  


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <?php
            if($_GET["type"] == "checkRecords" || $type == 'checkRange') {
                echo "<title>Follow-Up Summary</title>";
            }else if($_GET["type"] == "checkArchivedFollowUp") {
                echo "<title>Archived Follow-Up Records</title>";
            }else if($_GET["type"] == "checkRecordsId" || $type = 'checkRelFU'){
                echo "<title>Follow-Up Records</title>";
            }
        ?>
          
        <link rel = "icon" href = "images/BSU-Logo.webp" type = "image/x-icon">
        <!-- CSS -->
        <link rel="stylesheet" href="dist/bootstrap2.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="css/consultationTable-style.css">
        <link rel="stylesheet" href="dist/dataTables.bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="../../dist/dataTable/jquery.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="../../dist/dataTable/buttons.dataTables.min.css">
        <!-- JavaScript -->
        <script src="dist/jquery.min.js"></script>
        <script src="dist/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="dist/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <!-- DataTables -->
        <script type="text/javascript" src="../../dist/dataTable/jquery-3.5.1.js"></script>
        <script type="text/javascript" src="../../dist/dataTable/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="../../dist/dataTable/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="../../dist/dataTable/jszip.min.js"></script>
        <script type="text/javascript" src="../../dist/dataTable/pdfmake.min.js"></script>
        <script type="text/javascript" src="../../dist/dataTable/vfs_fonts.js"></script>
        <script type="text/javascript" src="../../dist/dataTable/buttons.html5.min.js"></script>
        <script type="text/javascript" src="../../dist/dataTable/buttons.print.min.js"></script>
        <!-- Confirm Dialog -->
        <script type="text/javascript" src="../../dist/jquery-confirm.min.js"></script>

        

        <script>
        // ---------------------------start functions for System Logs---------------------------------------
            var act = "";
            var acttype = "";
            var getType = "";
            var globalAL = "";
            var fullname = "";
            var tempVari = "";

            //function called when logout tab pressed
            function logout(){
                act = "Logged out";
                logAction(act);
                  $.ajax({
                    url:"../../php/logout.php",
                    method:"POST",
                    data:"",
                    success:function(xml){
                        // sessionStorage.clear();
                        setTimeout(function(){
                            window.location.href = '../../index.html';
                        }, 100);
                    }
                  })
            }

            function editTableNav(y,name){
                if(y == "checkArchived"){
                    document.getElementById('tab1').innerHTML = '&bull;&nbsp;Archived Follow-up Consultation&nbsp;&bull;';
                    document.getElementById('consultationID').classList.remove('active');
                    document.getElementById('archivedID').classList.add('active');
                    document.getElementById('maint').classList.add("active");
                    document.getElementById('maint').style.color = "white";
                }else if(y == "checkRecord"){
                    document.getElementById('tab1').innerHTML = '&bull;&nbsp;Follow-up Summary&nbsp;&bull;';
                }else if(y == "checkRecordsId"){
                    document.getElementById('tab1').innerHTML = '&bull;&nbsp;Follow-up History of '+name +'&nbsp;&bull;';
                }
            }

            //main function for user activity logging
            function logAction(userAction){
                act = userAction;
                $.ajax({
                    url:"../../php/logAction.php",
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

            function userViewRecord(StudentID){
                act = "Checked Student ID:" +StudentID +" Follow-up Record."
                logAction(act);
            }

            function userArchiveRecord(StudentID){
                if(getType == 'checkRecordsId' || getType == 'checkRelFU'){
                    acttype = "archiveFollowUp";
                }else if(getType == 'checkRecords'){
                    acttype = "archiveAllFollowUp";
                }
                
                ID = StudentID;
                var reason = '';
                if (reason = window.prompt("Specify a reason for archiving?")){
                    
                    $.ajax({
                    url:"../../php/archive.php",
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
                acttype = "restoreFollowUp";
                ID = StudentID;

                if(confirm("Are you sure you want to restore this Follow-up Record?")){
                    $.ajax({
                    url:"../../php/restore.php",
                    method:"GET",
                    data:jQuery.param({ type: acttype, id:ID }),
                    success:function(xml){
                        $(xml).find('output').each(function()
                        {
                            var message = $(this).attr('Message');
                            logAction(message +" ID " +ID +"");
                            alert(message);
                        });
                        window.location.href = 'index.php?type=checkArchivedFollowUp';
                        
                    }
                })
                }


            }


        // ---------------------------end functions for System Logs---------------------------------------

        $(document).ready(function(){  

            if (sessionStorage.getItem("isLoggedIn") == null){

                alert('User has been logged out. Please login again');

                $.ajax({
                    url:"../../php/logout.php",
                    method:"POST",
                    data:"",
                    success:function(xml){
                        // sessionStorage.clear();
                        setTimeout(function(){
                            window.location.href = '../../index.html';
                        }, 100);
                    }
                  })
                }
            
            var reportTitle = "";
            if(getType == 'checkRecords'){
                reportTitle = "Follow-up Summary";
            }else if(getType == 'checkRecordsId' || getType == 'checkRelFU'){
                reportTitle = "Follow-up History of " +fullname;
            }
            var table = $('#followup_data').DataTable({
                dom: 'fltpB',
                buttons: [                              
                    {
                        extend:'print',
                        text:'Print Report',
                        title:"<h1 style='text-align:center;'>"+reportTitle+"</h1>",
                        exportOptions: {
                            columns: [0,1,2,3]
                        }           
                    },
                    {
                       extend:'pdf',
                       text:'Export to PDF',
                       title:reportTitle,
                       exportOptions: {
                            columns: [0,1,2,3]
                        }  
                    },
                    {
                       extend:'excel',
                       text:'Export to Excel',
                       title:reportTitle,
                       exportOptions: {
                            columns: [0,1,2,3]
                        }   
                    },
                  ]
            });
            var length = table.page.info().recordsTotal;

            var span = document.getElementById("NumFollowUp");
            span.textContent = "Total Number of Follow-up/s:   " + length.toString();

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
            <a class="nav-link" href="../Homepage/index.php">Home</a>
            </li>
            <li class="nav-item mx-1">
            <a class="nav-link admin-nav" href="../userList.php?type=checkRecords">User List</a>
            </li>
            <li class="nav-item dropdown mx-1 active" id="consultationID">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-display="static" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Records
            </a>
            <div class="dropdown-menu dropdown-menu-lg-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="../Student/index.php?type=checkRecords">Student Records</a>
                <a class="dropdown-item" href="../Consultation/index.php?type=checkRecords">Consultation Records</a>
                <!-- <a class="dropdown-item" href="index.php?type=checkRecords">Follow-up Consultation</a> -->
                <a class="dropdown-item" href="../MedicalCertificate/index.php?type=checkMC">Medical Certificate</a>
            </div>
            </li>
            <li class="nav-item dropdown mx-1 admin-nav" id="archivedID">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-display="static" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Archived Records
            </a>
            <div class="dropdown-menu dropdown-menu-lg-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="../Student/index.php?type=checkArchivedStudent">Archived Student Records</a>
                <a class="dropdown-item" href="../Consultation/index.php?type=checkArchivedConsultation">Archived Consultation Records</a>
                <a class="dropdown-item" href="index.php?type=checkArchivedFollowUp">Archived Follow-up Records</a>
                <a class="dropdown-item" href="../MedicalCertificate/index.php?type=checkArchivedMC">Archived Medical Certificates</a>
                <a class="dropdown-item" href="../userList.php?type=checkArchivedStaff">Archived Staff Accounts</a>
                <a class="dropdown-item" href="../logs.php?type=checkArchivedLogs">Archived System Logs</a>
            </div>
            </li>
            <li class="nav-item dropdown mx-1 admin-nav" id="maintenanceID">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-display="static" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Maintenance
            </a>
            <div class="dropdown-menu dropdown-menu-lg-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="../logs.php?type=checkRecords">Logs</a>
                <a class="dropdown-item" href="../degreeList.php">Degree List</a>
                <a class="dropdown-item" href="../vaccineList.php">Vaccine List</a>
                <a class="dropdown-item" href="../backup.php">Backup</a>
                <a class="dropdown-item" href="../restore.php">Restore</a>
            </div>
            </li>
            <li class="nav-item mx-1">
            <a class="nav-link" href="../help.php?type=<?php echo $_SESSION['accesslevel']; ?>">Help</a>
            </li>
            <li class="nav-item mx-1">
            <a class="nav-link" href="#" onclick="logout()">Logout</a>
            </li>

        </ul>

   
  </nav>  

        <div class="cont container">
            <div class="tabs">
                <div class="tabs-head" style="display: inline-block;">
                    <?php  
                        if ($_GET["type"] == "checkRecordsId"){
                            echo "
                            <div>
                            <a id='backButton' class='btn btn-primary' href='index.php?type=checkRecords' role='button'>Go Back</a>
                            </div>";
                        }else if ($_GET["type"] == "checkRelFU"){
                            echo "
                            <div>
                            <a id='backButton' class='btn btn-primary' href='../Consultation/index.php?id=$id&type=checkRecordsId' role='button'>Go Back</a>
                            </div>";
                        }
                    ?>
                    <span id="tab1" class="tabs-toggle is-active">&bull;&nbsp;Follow-Up Consultation&nbsp;&bull;</span>
                </div>
                <div id="notif">
                    <?php if ($_GET["type"] == "checkRecords" || $type == 'checkRange' || $type == 'checkRecordsId' || $type == 'checkRelFU'){
                        echo "
                        <a id='newFollowUp' class='btn btn-primary' href='pages/newFollowUp.php?type=newFollowUp' role='button'>New Follow-Up</a>
                        <span id='NumFollowUp' style='align:center'>Total Number of Follow-Up/s: </span>
                        ";

                    } ?>
                    
                </div>
                <div class="tabs-body">
                    <div class="tabs-content is-active table-responsive">  
                    <table id="followup_data" class="table table-striped table-bordered">  
                          <thead>  
                               <tr>  
                                        <th>ID Number</th>
                                        <th>Fullname</th>

                                        <?php 
                                        if ($_GET["type"] == "checkRecords"){
                                            echo "<th>Total</th>";
                                        }else{
                                            echo "<th>Consultation Date</th>";
                                        } 
                                        ?>

                                        
                                        <th>Follow-Up Date</th>
                                        <?php if ($_GET["type"] == "checkArchivedFollowUp")
                                                    echo "<th>Archive Reason</th>";
                                         ?>
                                        <th>Action</th>
                               </tr>  
                          </thead>  
                          <?php        
                          if($result != ''){
                            $Lastname = '';
                            $Firstname = '';
                            $Middlename = '';
                            while($row = mysqli_fetch_array($result)){  
                                /*$row = array_map('strtoupper', $row);*/
                                $Lastname = ucwords($row['Lastname']);
                                $Firstname = ucwords($row['Firstname']);
                                $Middlename = ucwords($row['Middlename']);
                                if(empty($Lastname) && ($type == "checkRecords" || $type == 'checkRange')){
                                    $queryArchive ="SELECT * FROM followup LEFT JOIN archivedstudent ON followup.IdNumb = archivedstudent.StudentIDNumber";
                                    $resultArchive = mysqli_query($connect, $queryArchive);
                                    $rowArchive = mysqli_fetch_array($resultArchive);
                                    $Lastname = ucwords($rowArchive['Lastname']);
                                    $Firstname = ucwords($rowArchive['Firstname']);
                                    $Middlename = ucwords($rowArchive['Middlename']);
                                }else if(empty($Lastname) && $type == "checkArchivedFollowUp"){
                                    $queryArchive ="SELECT * FROM archivedfollowup LEFT JOIN archivedstudent ON archivedfollowup.IdNumb = archivedstudent.StudentIDNumber";
                                    $resultArchive = mysqli_query($connect, $queryArchive);
                                    $rowArchive = mysqli_fetch_array($resultArchive);
                                    $Lastname = ucwords($rowArchive['Lastname']);
                                    $Firstname = ucwords($rowArchive['Firstname']);
                                    $Middlename = ucwords($rowArchive['Middlename']);
                                }
                                
                                $querydates ="SELECT Dates FROM followup WHERE IdNumb = '$row[IdNumb]' ORDER BY Dates DESC";  
                                $resultdates = mysqli_query($connect, $querydates);

                                $querycount ="SELECT COUNT(IdNumb) as count FROM followup WHERE IdNumb = '$row[IdNumb]' ORDER BY Dates DESC";  
                                $resultcount = mysqli_query($connect, $querycount);

                                echo "  
                                <tr>
                                    <td>$row[IdNumb]</td>
                                    <td>$Lastname, $Firstname $Middlename</td>
                                    
                                    ";

                                if ($_GET["type"] == "checkRecords"){

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
                                            $datesarray[]= $rowdates['Dates'];
                                        }
                                        $dates = implode(" , ", $datesarray);
                                        echo "$dates";
                                        echo "</td>";

                                    }else{
                                        echo "<td></td>";
                                    }
                                }else{
                                    echo "
                                    <td>$row[cons_date]</td>
                                    <td>$row[Dates]</td>
                                    ";
                                }

                                if ($_GET["type"] == "checkArchivedFollowUp")
                              echo "<td>$row[fu_archive_reason]</td>";

                                        if ($type == 'checkRecordsId' || $type == 'checkRange' || $type == 'checkRelFU'){
                                            echo "<td>
                                            <a class='viewBTN btn btn-primary btn-sm' href='pages/newFollowUp.php?num=$row[Num]&type=viewFollowUp'>View</a>
                                            <a class='viewBTN btn btn-primary btn-sm' id='archiveBTN' onclick='userArchiveRecord($row[Num])'>Archive</a>";
                                        }else if ($type == 'checkArchivedFollowUp'){
                                            echo "<td>
                                            <a class='viewBTN btn btn-primary btn-sm' href='pages/newFollowUp.php?num=$row[Num]&type=viewArchivedFollowUp'>View</a>
                                            <a class='viewBTN btn btn-primary btn-sm' id='archiveBTN' onclick='userRestoreRecord($row[Num])'>Restore</a>";
                                        }else if ($type == 'checkRecords'){
                                            echo "<td>
                                            <a class='viewBTN btn btn-primary btn-sm' href='index.php?type=checkRecordsId&id=$row[IdNumb]'>View All</a>
                                            <a class='viewBTN btn btn-primary btn-sm' id='archiveBTN' onclick='userArchiveRecord($row[IdNumb])'>Archive All</a>";
                                        }

                                    echo "</td>
                                </tr>
                               ";  
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
        <script src="js/script-tab.js"></script>
    </body>
</html>
<?php
    $tempo = $_SESSION['accesslevel'];
    $tempor =  "";
    $type = $_GET["type"];
    $_SESSION["typed"] = $_GET["type"];

    if($_GET["type"] == 'checkArchivedFollowUp'){
        $tempor = "checkArchived";
    }else if($_GET["type"] == 'checkRecords'){
        $tempor = "checkRecord";
    }else if($_GET["type"] == 'checkRecordsId'){
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
 