<?php
    require_once '../php/Database.php';
    require '../php/centralConnection.php';
    date_default_timezone_set('Asia/Manila');
    session_start();
    if(empty($_SESSION['logged_in'])){
    header('Location: ../index.html');
    }

    $type = $_GET["type"];

    $ClinicRecordsDB = new Database($Server,$User,$DBPassword);

    if ($ClinicRecordsDB->Connect()==true)
    {
        $Result = $ClinicRecordsDB->SelectDatabase($Database);
                      

        if($Result == true)
        {     
            if($_SERVER['REQUEST_METHOD'] == 'GET'){
                if($_GET["type"] == "checkRecords"){
                    $sql = "SELECT * FROM USERACCOUNTS";          
                    $ClinicRecordsQuery = $ClinicRecordsDB->GetRows($sql);
                }else if($_GET["type"] == "checkArchivedStaff"){
                    $sql = "SELECT * FROM ARCHIVEDSTAFF";          
                    $ClinicRecordsQuery = $ClinicRecordsDB->GetRows($sql);
                }  
            }  
                   
        }
    }

    if(empty($_SESSION['logged_in'])){
        header('Location: ../index.html');
    }

?>  

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <?php
             if($_GET["type"] == "checkRecords"){
                echo "<title>User List</title>";
             }else if($_GET["type"] == "checkArchivedStaff"){
                echo "<title>Archived User List</title>";
             }
        ?>
          
        <link rel = "icon" href = "../images/BSU-Logo.webp" type = "image/x-icon">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="Staff/dist/bootstrap2.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="../css/userList.css">
        
        <link rel="stylesheet" href="Staff/dist/dataTables.bootstrap.min.css" />

        <link rel="stylesheet" type="text/css" href="../dist/dataTable/jquery.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="../dist/dataTable/buttons.dataTables.min.css">
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="Staff/dist/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="Staff/dist/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="Staff/dist/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="Staff/dist/jquery.min.js"></script> 
        <script src="Staff/dist/dataTables.bootstrap.min.js"></script>   
        <script type="text/javascript" src="../dist/dataTable/jquery-3.5.1.js"></script>
        <script type="text/javascript" src="../dist/dataTable/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="../dist/dataTable/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="../dist/dataTable/jszip.min.js"></script>
        <script type="text/javascript" src="../dist/dataTable/pdfmake.min.js"></script>
        <script type="text/javascript" src="../dist/dataTable/vfs_fonts.js"></script>
        <script type="text/javascript" src="../dist/dataTable/buttons.html5.min.js"></script>
        <script type="text/javascript" src="../dist/dataTable/buttons.print.min.js"></script>

        

       

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

            function editTableNav(y){
                if(y == "checkArchived"){
                    document.getElementById('tab1').innerHTML = '&bull;&nbsp;ARCHIVED USER LIST&nbsp;&bull;';
                    document.getElementById('userlistID').classList.remove('active');
                    document.getElementById('archivedID').classList.add('active');
                    document.getElementById('maint').classList.add("active");
                    document.getElementById('maint').style.color = "white";
                }else{
                    document.getElementById('tab1').innerHTML = '&bull;&nbsp;USER LIST&nbsp;&bull;';
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

            function openManual(){
                if(globalAL == "admin"){
                    window.open("../files/ManualAdmin.pdf");
                }else if(globalAL == "superadmin"){
                    window.open("../files/ManualSuperadmin.pdf");
                }else{
                    window.open("../files/ManualStandard.pdf");                }
            }

            function userViewRecord(StaffID){
                act = "Checked Staff ID:" +StaffID +" Information."
                logAction(act);
            }

            function userArchiveRecord(StaffID){
                acttype = "archiveStaff";
                ID = StaffID;
                var reason = '';
                if(reason = window.prompt("Specify a reason for archiving?")){
                    $.ajax({
                    url:"../php/archive.php",
                    method:"GET",
                    data:jQuery.param({ type: acttype, id:ID, archReason:reason }),
                    success:function(xml){
                        $(xml).find('output').each(function()
                        {
                            var message = $(this).attr('Message');
                            logAction(message +" ID " +ID +"");
                            alert(message);
                        });
                        window.location.href = 'userList.php?type=checkRecords';
                        
                    }
                })
                }else if(reason == ''){
                    alert('Please specify a reason');
                }


            }

            function userRestoreRecord(StaffID){
                acttype = "restoreStaff";
                ID = StaffID;

                if(confirm("Are you sure you want to restore this staff account?")){
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
                        window.location.href = 'userList.php?type=checkArchivedStaff';
                        
                    }
                })
                }


            }


        // ---------------------------end functions for System Logs---------------------------------------
        function checkNameLength(name){

                var nameVal = name.value.trim();

                if(nameVal.length < 3){
                    $.alert(
                        {theme: 'modern',
                        content:'Name should be atleast 3 characters',
                        title:'', 
                        useBootstrap: false,
                        buttons:{
                            Ok:{
                            text:'Ok',
                            btnClass: 'btn-red'
                        }}});
                    name.value = '';
                }else{
                    name.value = nameVal.trim();
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
                dom: 'fltpB',
                buttons: [                              
                    {
                        extend:'print',
                        text:'Print Report',
                        title:"<h1 style='text-align:center;'>User List</h1>",
                        exportOptions: {
                            columns: [0,1,2,3,4,5]
                        }            
                    },
                    {
                       extend:'pdf',
                       text:'Export to PDF',
                       title:"User List",
                       exportOptions: {
                            columns: [0,1,2,3,4,5]
                        }  
                    },
                    {
                       extend:'excel',
                       text:'Export to Excel',
                       title:"User List",
                       exportOptions: {
                            columns: [0,1,2,3,4,5]
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
                    /*document.getElementById("nav1").style.width = "64%";
                    document.getElementById("nav2").style.width = "8%";
                    document.getElementById("nav5").style.width = "8%";
                    document.getElementById("nav6").style.width = "14%";
                    document.getElementById("nav7").style.width = "5%";*/
                    document.getElementById("line").style.width = "4.5%";
                    document.getElementById("line").style.left = "66%";

                    document.getElementById("nav5").addEventListener("mouseover", function(){
                        document.getElementById("line").style.width = "7.8%";
                        document.getElementById("line").style.left = "72.3%";
                    });
                    document.getElementById("nav5").addEventListener("mouseout", function(){
                        document.getElementById("line").style.width = "4.5%";
                        document.getElementById("line").style.left = "66%";
                    });

                    document.getElementById("nav6").addEventListener("mouseover", function(){
                        document.getElementById("line").style.width = "9.5%";
                        document.getElementById("line").style.left = "82.3%";
                    });
                    document.getElementById("nav6").addEventListener("mouseout", function(){
                        document.getElementById("line").style.width = "4.5%";
                        document.getElementById("line").style.left = "66%";
                    });

                    document.getElementById("nav7").addEventListener("mouseover", function(){
                        document.getElementById("line").style.width = "4.3%";
                        document.getElementById("line").style.left = "94.5%";
                    });

                    document.getElementById("nav7").addEventListener("mouseout", function(){
                        document.getElementById("line").style.width = "4.5%";
                        document.getElementById("line").style.left = "66%";
                    });

                    document.getElementById("navani").setAttribute("width", "55vw");
                    document.getElementById("navani").setAttribute("viewBox", "0 0 1060 85");
                    document.getElementById("navani").setAttribute("enable-background", "new 0 0 1000 60");
                    document.getElementById("polani").setAttribute("points", "0,45.486 38.514,45.486 44.595,33.324 50.676, 45.486 57.771,45.486 62.838,55.622 71.959,9 80.067,63.729 84.122,45.486 97.297, 45.486 103.379, 20.324 110.07, 60.45 117.07, 35 121, 45 160, 45 167, 55 173, 25 180, 68 189, 8 196, 48 205, 48  210, 56 216, 35 223, 66 229, 48 270, 48 276, 35 284, 72 289, 50 297, 50 304, 20 312, 60 320, 40 325, 48 370, 48 379, 8 387, 58 393, 38 399, 69 408, 22 414, 48 421, 48 428, 62 432, 48 485, 48 492, 78 500, 27 507, 48 515, 48 521, 65 528, 48, 570, 48 579, 9 587, 75 595, 38 604, 38 610, 17 618, 48 627, 48 632, 64 639, 22 645, 48 700, 48 708, 63 716, 48 723, 48 729, 35 738, 75 746, 17 754, 48 767, 48 773, 63 780, 27 787, 48 832, 48 839, 57 846, 15 853, 58 860, 40 868, 80 875, 52 883, 52 891, 25 898, 48 945, 48 952, 36 959, 59 966, 16 972, 48 979, 48 985, 77 993, 23 1000, 58 1008, 48 1060, 48");

                    document.getElementById("contani").setAttribute("class", "rt-containerSTAFF");
                    document.getElementById("hrani").setAttribute("class", "heart-rateSTAFF");
                    document.getElementById("fiani").setAttribute("class", "fade-inSTAFF");
                    document.getElementById("foani").setAttribute("class", "fade-outSTAFF");
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
            <li class="nav-item mx-1 active" id="userlistID">
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
            <li class="nav-item dropdown mx-1 admin-nav" id="maintenanceID">
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
        <div class="cont container">
            <div class="tabs">
                <div class="tabs-head">
                    <span id="tab1" class="tabs-toggle is-active">&bull;&nbsp;USER LIST&nbsp;&bull;</span>
                </div>
                <div id="notif">
                    <?php if ($_GET["type"] == "checkRecords"){
                        echo "
                        <a id='newRecord' class='btn btn-primary' href='Staff/index.php?type=newRecord' role='button'>New User</a>
                        <span id='NumRecord'>Total Number of Record/s: </span>
                        ";

                    } ?>
                    
                    
                </div>
                <div class="tabs-body">
                    <div class="tabs-content is-active table-responsive">  
                    <table id="user_data" class="table table-striped table-bordered">  
                          <thead>  
                               <tr>  
                                        <th>ID No.</th>
                                        <th>Email</th>
                                        <th>User name</th>
                                        <th>Full Name</th>
                                        <th>Contact Number</th>
                                        <th>Position</th>
                                        <th>Account Status</th>
                                        <th>Code</th>
                                        <?php 
                                        if ($_GET["type"] == "checkArchivedStaff")
                                        echo "<th>Archive Reason</th>";
                                         ?>
                                        <th>Action</th>
                               </tr>  
                          </thead>  
                          <?php        
                          while($Row = $ClinicRecordsQuery->fetch_array()) 
                          {  
                                /*$Row = array_map('strtoupper', $Row);*/
                                $Pos = ucwords($Row['Position']);
                                echo "  
                                <tr>
                                    <td>$Row[IdNum]</td>
                                    <td>$Row[Email]</td>
                                    <td>$Row[Username]</td>
                                    <td>$Row[LastName], $Row[FirstName] $Row[MiddleName]</td>
                                    <td>$Row[ContactNum]</td>
                                    <td>$Pos</td>
                                    <td>$Row[AccStatus]</td>
                                    <td>$Row[code]</td>";

                                    if ($_GET["type"] == "checkArchivedStaff")
                                    echo "<td>$Row[user_archive_reason]</td>";


                                    echo "<td>";

                                        if(stripslashes($Row['AccessLevel']) == "superadmin" && $type == 'checkRecords'){
                                            if ($_SESSION['accesslevel'] == "superadmin"){
                                                echo "
                                                <a class='viewBTN btn btn-primary btn-sm' href='Staff/index.php?id=$Row[IdNum]&type=viewRecord'>View</a>";
                                            }
                                        }else if(stripslashes($Row['AccessLevel']) == "admin" && $type == 'checkRecords'){
                                            if ($_SESSION['accesslevel'] == "superadmin"){
                                                echo "
                                                <a class='viewBTN btn btn-primary btn-sm' href='Staff/index.php?id=$Row[IdNum]&type=viewRecord'>View</a>
                                                <a class='viewBTN btn btn-primary btn-sm' id='archiveBTN' onclick='userArchiveRecord($Row[user_id])'>Archive</a>";
                                            }else{
                                                echo "
                                                <a class='viewBTN btn btn-primary btn-sm' href='Staff/index.php?id=$Row[IdNum]&type=viewRecord'>View</a>"; 
                                            }
                                        }else if (stripslashes($Row['AccessLevel']) == "standard" && $type == 'checkRecords'){
                                            echo "
                                            <a class='viewBTN btn btn-primary btn-sm' href='Staff/index.php?id=$Row[IdNum]&type=viewRecord'>View</a>
                                            <a class='viewBTN btn btn-primary btn-sm' id='archiveBTN' onclick='userArchiveRecord($Row[user_id])'>Archive</a>";
                                        }else if (stripslashes($Row['AccessLevel']) == "standard" && $type == 'checkArchivedStaff'){
                                            echo "
                                            <a class='viewBTN btn btn-primary btn-sm' href='Staff/index.php?id=$Row[IdNum]&type=viewArchivedRecord'>View</a>
                                            <a class='viewBTN btn btn-primary btn-sm' id='archiveBTN' onclick='userRestoreRecord($Row[user_id])'>Restore</a>";
                                        }else{
                                            echo "
                                            <a class='viewBTN btn btn-primary btn-sm' href='Staff/index.php?id=$Row[IdNum]&type=viewRecord'>View</a>";
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

    if($_GET["type"] == 'checkArchivedStaff'){
        $tempor = "checkArchived";
    }else{
        $tempor = "checkRecord";
    }

    echo "<script type='text/javascript'>
        globalAL = '$tempo';
        editTableNav('$tempor');
    </script>";
?>
 