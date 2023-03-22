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
        }  

        $type = $_GET["type"];
    }  
 ?>  

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <?php 
            if($_GET["type"] == "checkRecords"){
                echo "<title>Student Records</title>";
            }else if($_GET["type"] == "checkArchivedStudent"){
                echo "<title>Archived Student Records</title>";
            }
         ?>
        <link rel = "icon" href = "images/BSU-Logo.webp" type = "image/x-icon">
        <script src="dist/jquery.min.js"></script> 
        <link rel="stylesheet" href="dist/bootstrap.min.css" />
        <script src="dist/dataTables.bootstrap.min.js"></script>   
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
        <script type="text/javascript" src="../../dist/dataTable/jquery-3.5.1.js"></script>
        <script type="text/javascript" src="../../dist/dataTable/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="../../dist/dataTable/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="../../dist/dataTable/jszip.min.js"></script>
        <script type="text/javascript" src="../../dist/dataTable/pdfmake.min.js"></script>
        <script type="text/javascript" src="../../dist/dataTable/vfs_fonts.js"></script>
        <script type="text/javascript" src="../../dist/dataTable/buttons.html5.min.js"></script>
        <script type="text/javascript" src="../../dist/dataTable/buttons.print.min.js"></script>


        <link rel="stylesheet" href="css/studentTable-style.css">
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
                        sessionStorage.clear();
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
                    document.getElementById('nav4').classList.remove("active");
                    document.getElementById('nav6').classList.add("active");
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

                if(confirm("Are you sure you want to archive this student record?")){
                    $.ajax({
                    url:"../../php/archive.php",
                    method:"GET",
                    data:jQuery.param({ type: acttype, id:ID }),
                    success:function(xml){
                        $(xml).find('output').each(function()
                        {
                            var message = $(this).attr('Message');
                            logAction(message +" ID " +ID +"");
                            alert(message);
                        });
                        window.location.href = 'index.php?type=checkRecords';
                        
                    }
                })
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
                    document.getElementById("nav2").style.width = "9.33%";
                    document.getElementById("nav3").style.width = "9.33%";
                    document.getElementById("nav4").style.width = "9.33%";
                    document.getElementById("nav5").style.width = "9.33%";
                    document.getElementById("nav7").style.width = "9.33%";
                    document.getElementById("nav8").style.width = "9.33%";
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
    <nav>
      <span id="userFullname"><b><?php echo ucwords($_SESSION['homePosDisp']) . " ";
      $tempNAME = strtolower($_SESSION['fullname']);
      echo ucwords($tempNAME); 
      ?></b></span>
      <a href="../Homepage/index.php" id="nav2" class="nav-pages">Home</a>
      <a href="../userList.php?type=checkRecords" id="nav3" class="nav-pages admin-nav">User List</a>
      <a href="../Student/index.php?type=checkRecords" id="nav4" class="nav-pages active">Student</a>
      <a href="../Consultation/index.php?type=checkRecords" id="nav5" class="nav-pages">Consultation</a>
      
      <div class="dropdown nav-pages admin-nav" id="nav6">
          <button id="maint" class="dropbtn">Maintenance</button>
                <div class="dropdown-content">
                    <a class="dropA" href="../logs.php?type=checkRecords" onclick="userCheckLogs()">Logs</a>
                    <a class="dropA" href="../Student/index.php?type=checkArchivedStudent">Archived Student Records</a>
                    <a class="dropA" href="../Consultation/index.php?type=checkArchivedConsultation">Archived Consultation Records</a>
                    <a class="dropA" href="../userList.php?type=checkArchivedStaff">Archived Staff Accounts</a>
                    <a class="dropA" href="../logs.php?type=checkArchivedLogs">Archived System Logs</a>
                    
                    <a class="dropA" href="../backup.php">Backup</a>
                    <a class="dropA" href="../restore.php">Restore</a>
                </div>
      </div>
      <a href="#" onclick="openManual()" id="nav8" class="nav-pages">Help</a>
      <a href="#" id="nav7" onclick="logout()" class="nav-pages">Logout</a>
    </nav>
        <div class="cont container">
            <div class="tabs">
                <div class="tabs-head">
                    <span id="tab1" class="tabs-toggle is-active">&bull;&nbsp;Students Record&nbsp;&bull;</span>
                </div>
                <div id="notif">
                    <?php if ($_GET["type"] == "checkRecords"){
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
                                        <th>Action</th>
                               </tr>  
                          </thead>  
                          <?php        
                          while($row = mysqli_fetch_array($result))  
                          {  
                                /*$row = array_map('strtoupper', $row);*/
                                echo "  
                                <tr>
                                    <td>$row[StudentIDNumber]</td>
                                    <td>$row[Lastname], $row[Firstname] $row[Middlename]</td>
                                    <td>$row[Course]</td>
                                    <td>$row[Age]</td>
                                    <td>$row[Sex]</td>
                                    <td>$row[StudentContactNumber]</td>
                                    <td>$row[Date]</td>
                                    <td>
                                        ";
                                        if ($type == 'checkRecords'){
                                            echo "
                                            <a class='viewBTN btn btn-primary btn-sm' onclick='userViewRecord($row[StudentIDNumber])' href='pages/newRecord.php?id=$row[StudentIDNumber]&type=viewRecord'>View</a>
                                            <a class='viewBTN btn btn-primary btn-sm' id='archiveBTN' onclick='userArchiveRecord($row[id_num])'>Archive</a>";
                                        }else if ($type == 'checkArchivedStudent'){
                                            echo "
                                            <a class='viewBTN btn btn-primary btn-sm' onclick='userViewRecord($row[StudentIDNumber])' href='pages/newRecord.php?id=$row[StudentIDNumber]&type=viewArchivedRecord'>View</a>
                                            <a class='viewBTN btn btn-primary btn-sm' id='archiveBTN' onclick='userRestoreRecord($row[id_num])'>Restore</a>";
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
 