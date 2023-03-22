<?php
    require_once '../php/Database.php';
    require '../php/centralConnection.php';
    date_default_timezone_set('Asia/Manila');
    session_start();
    if(empty($_SESSION['logged_in'])){
    header('Location: ../index.html');
    }

    $type = $_GET["type"];

    echo "<script type='text/javascript'>
        var globalAL = '';
        globalAL = '$type';
    </script>";


?>  

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">

        <title>Help Manual</title>

          
        <link rel = "icon" href = "../images/BSU-Logo.webp" type = "image/x-icon">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="Staff/dist/bootstrap2.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="../css/help.css">
        
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

        // ---------------------------end functions for System Logs---------------------------------------

            function openManual(type){
                var src = '';
                if(globalAL == "admin"){
                    src = "../files/ManualAdmin.pdf";
                }else if(globalAL == "superadmin"){
                    src = "../files/ManualSuperadmin.pdf";
                }else{
                    src = "../files/ManualStandard.pdf";                
                }

                var iframeDiv = document.getElementById("iframeCont");

                var ifrm = document.createElement("iframe");
                ifrm.setAttribute("src", src);
                ifrm.style.width = "100%";
                ifrm.style.height = "95%";
                ifrm.setAttribute("frameborder", "0");
                ifrm.setAttribute("allow", "fullscreen");
                //ifrm.setAttribute("sandbox", "allow-top-navigation");
                
                iframeDiv.appendChild(ifrm);
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

            openManual(globalAL);
        
            var acclvl = sessionStorage.getItem('isStandard');

            if(acclvl == "true"){
                $(".admin-nav").hide();
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
            <li class="nav-item mx-1" id="userlistID">
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
            <li class="nav-item dropdown mx-1 admin-nav " id="archivedID">
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
            <li class="nav-item dropdown mx-1 admin-nav " id="maintenanceID">
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
            <li class="nav-item mx-1 active">
            <a class="nav-link" href="#" href="help.php">Help</a>
            </li>
            <li class="nav-item mx-1">
            <a class="nav-link" href="#" onclick="logout()">Logout</a>
            </li>

        </ul>
    </nav>     
        <div id="iframeCont">
        </div>
        <!-- <div>
            <button type="Submit" class="btnUp" id="btnUp" name="btnUp" onclick="window.scrollTo(0, 0)"/>
        </div> -->
        <script src="../js/script-tab.js"></script>
        
    </body>
</html>

 