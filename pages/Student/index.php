<?php  
require '../../php/centralConnection.php';
session_start();
if(empty($_SESSION['logged_in'])){
 header('Location: ../../index.html');
} 

 if($_SERVER['REQUEST_METHOD'] == 'GET'){
        if($_GET["type"] == "checkRecords"){
            $query ="SELECT * FROM PersonalMedicalRecord";  
            $result = mysqli_query($connect, $query);
        }else if($_GET["type"] == "checkArchivedStudent"){
            $query ="SELECT * FROM ARCHIVEDSTUDENT";  
            $result = mysqli_query($connect, $query);
        }else if($_GET["type"] == "checkRange"){
            $start = $_GET['start'];
            $end = $_GET['end'];
            $query ="SELECT * FROM PersonalMedicalRecord WHERE Date >= '$start' AND Date <= '$end'";  
            $result = mysqli_query($connect, $query);
        }  

        $type = $_GET["type"];
    }  
 ?>  

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <?php 
            if($_GET["type"] == "checkRecords" || $type == 'checkRange'){
                echo "<title>Student Records</title>";
            }else if($_GET["type"] == "checkArchivedStudent"){
                echo "<title>Archived Student Records</title>";
            }
         ?>
        <link rel = "icon" href = "images/BSU-Logo.webp" type = "image/x-icon">
         <!-- Bootstrap CSS -->
         <link rel="stylesheet" href="dist/bootstrap2.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
         <link rel="stylesheet" href="css/studentTable-style.css">
        <link rel="stylesheet" href="dist/dataTables.bootstrap.min.css" />

        <!-- <link rel="stylesheet" type="text/css" href="dist/buttons.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="dist/jquery.dataTables.min.css">
        <script type="text/javascript" src="dist/jquery-3.5.1.js"></script>
        <script type="text/javascript" src="dist/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="dist/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="dist/jszip.min.js"></script>
        <script type="text/javascript" src="dist/pdfmake.min.js"></script>
        <script type="text/javascript" src="dist/vfs_fonts.js"></script>
        <script type="text/javascript" src="dist/buttons.html5.min.js"></script>
        <script type="text/javascript" src="dist/buttons.print.min.js"></script> -->

        <link rel="stylesheet" type="text/css" href="../../dist/dataTable/jquery.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="../../dist/dataTable/buttons.dataTables.min.css">
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="dist/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="dist/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="dist/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="dist/jquery.min.js"></script> 
        <script src="dist/dataTables.bootstrap.min.js"></script> 
        <script type="text/javascript" src="../../dist/dataTable/jquery-3.5.1.js"></script>
        <script type="text/javascript" src="../../dist/dataTable/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="../../dist/dataTable/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="../../dist/dataTable/jszip.min.js"></script>
        <script type="text/javascript" src="../../dist/dataTable/pdfmake.min.js"></script>
        <script type="text/javascript" src="../../dist/dataTable/vfs_fonts.js"></script>
        <script type="text/javascript" src="../../dist/dataTable/buttons.html5.min.js"></script>
        <script type="text/javascript" src="../../dist/dataTable/buttons.print.min.js"></script>
        <script>  
        // ---------------------------start functions for System Logs---------------------------------------
            var act = "";
            var acttype = "";
            var globalAL = "";

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

            function editTableNav(y){
                if(y == "checkArchived"){
                    document.getElementById('tab1').innerHTML = '&bull;&nbsp;Archived Students Record&nbsp;&bull;';
                    document.getElementById('recordID').classList.remove('active');
                    document.getElementById('archivedID').classList.add('active');
                    document.getElementById('maint').classList.add("active");
                    document.getElementById('maint').style.color = "white";
                }else{
                    document.getElementById('tab1').innerHTML = '&bull;&nbsp;Students Record&nbsp;&bull;';
                }
            }

            function openManual(){
                if(globalAL == "admin"){
                    window.open("../../files/ManualAdmin.pdf");
                }else if(globalAL == "superadmin"){
                    window.open("../../files/ManualSuperadmin.pdf");
                }else{
                    window.open("../../files/ManualStandard.pdf");                }
            }

            //called to log user clicking "logs" tab
            function userCheckLogs(){
                act = "Checked User Activities." 
                logAction(act);
            }

            function userViewRecord(StudentID){
                act = "Checked Student ID: " +StudentID +" record";
                logAction(act);
            }

            function userArchiveRecord(StudentID){
                acttype = "archiveStudent";
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
                        window.location.href = 'index.php?type=checkRecords';
                    }
                    })
                }else if(reason == ''){
                    alert('Please specify a reason');
                }


            }

            function userRestoreRecord(StudentID){
                acttype = "restoreStudent";
                ID = StudentID;

                if(confirm("Are you sure you want to restore this student record?")){
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
                        window.location.href = 'index.php?type=checkArchivedStudent';
                        
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
            
            var table = $('#student_data').DataTable({
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
                  ]
            });
            var length = table.page.info().recordsTotal;

            var span = document.getElementById("NumRecord");
            span.textContent = "Total Number of Record/s: " + length.toString();

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
            <li class="nav-item dropdown mx-1 active" id="recordID">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-display="static" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Records
            </a>
            <div class="dropdown-menu dropdown-menu-lg-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="../Student/index.php?type=checkRecords">Student Records</a>
                <a class="dropdown-item" href="../Consultation/index.php?type=checkRecords">Consultation Records</a>
                <!-- <a class="dropdown-item" href="../Followup/index.php?type=checkRecords">Follow-up Consultation</a> -->
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
                <a class="dropdown-item" href="../Followup/index.php?type=checkArchivedFollowUp">Archived Follow-up Records</a>
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
                <a class="dropdown-item" href="../logs.php?type=checkRecords" onclick="userCheckLogs()">Logs</a>
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
                <div class="tabs-head">
                    <span id="tab1" class="tabs-toggle is-active">&bull;&nbsp;Students Record&nbsp;&bull;</span>
                </div>
                <div id="notif">
                    <?php if ($_GET["type"] == "checkRecords" || $type == 'checkRange'){
                        echo "
                        <a id='newRecord' class='btn btn-primary' href='pages/newRecord.php?type=newRecord' role='button'>New Record</a>
                    <span id='NumRecord'>Total Number of Record/s: </span>
                        ";

                    } ?>
                    
                </div>
                <div class="tabs-body">
                    <div class="tabs-content is-active table-responsive">  
                    <table id="student_data" class="table table-striped table-bordered">  
                          <thead>  
                               <tr>  
                                        <th>ID</th>
                                        <th>Full Name</th>
                                        <th>Course / Strand</th>
                                        <th>Age</th>
                                        <th>Sex</th>
                                        <th>Contact Number</th>
                                        <th>Date</th>
                                        <?php 
                                        if ($_GET["type"] == "checkArchivedStudent")
                                        echo "<th>Archive Reason</th>";
                                        ?>
                                        <th>Action</th>
                               </tr>  
                          </thead>  
                          <?php        
                          while($row = mysqli_fetch_array($result))  
                          {  
                                /*$row = array_map('strtoupper', $row);*/
                                $Sex = ucwords($row['Sex']);
                                $course = "";
                                if($row['Course'] != ""){
                                    $course = $row['Course'];
                                }else{
                                    $course = ucwords($row['StudentCategory']);
                                }
                                echo "  
                                <tr>
                                    <td>$row[StudentIDNumber]</td>
                                    <td>$row[Lastname], $row[Firstname] $row[Middlename]</td>
                                    <td>$course</td>
                                    <td>$row[Age]</td>
                                    <td>$Sex</td>
                                    <td>$row[StudentContactNumber]</td>
                                    <td>$row[Date]</td>
                                    ";
                                    if ($_GET["type"] == "checkArchivedStudent")
                                    echo "<td>$row[pm_archive_reason]</td>";
                                    
                                        
                                        if ($type == 'checkRecords' || $type == 'checkRange'){
                                            echo "<td>
                                            <a class='viewBTN btn btn-primary btn-sm' href='pages/newRecord.php?id=$row[StudentIDNumber]&type=viewRecord'>View</a>
                                            <a class='viewBTN btn btn-primary btn-sm' id='archiveBTN' onclick='userArchiveRecord($row[StudentIDNumber])'>Archive</a>";
                                        }else if ($type == 'checkArchivedStudent'){
                                            echo "<td>
                                            <a class='viewBTN btn btn-primary btn-sm' href='pages/newRecord.php?id=$row[StudentIDNumber]&type=viewArchivedRecord'>View</a>
                                            <a class='viewBTN btn btn-primary btn-sm' id='archiveBTN' onclick='userRestoreRecord($row[StudentIDNumber])'>Restore</a>";
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
        <script src="js/script-tab.js"></script>
    </body>
</html>
<?php
    $tempo = $_SESSION['accesslevel'];
    $tempor =  "";
    $_SESSION["typed"] = $_GET["type"];

    if($_GET["type"] == 'checkArchivedStudent'){
        $tempor = "checkArchived";
    }else{
        $tempor = "checkRecord";
    }

    echo "<script type='text/javascript'>
        globalAL = '$tempo';
        editTableNav('$tempor');
    </script>";
?>
 