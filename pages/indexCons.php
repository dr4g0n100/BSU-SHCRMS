<?php  
require '../php/centralConnection.php';
 session_start();
 if(empty($_SESSION['logged_in'])){
    header('Location: ../index.html');
 } 
 $type = "";
 $id = '';

 if($_SERVER['REQUEST_METHOD'] == 'GET'){
        if($_GET["type"] == "checkRecords"){
            $query ="SELECT * FROM consultationinfo LEFT JOIN personalmedicalrecord ON consultationinfo.IdNumb = personalmedicalrecord.StudentIDNumber GROUP BY IdNumb ORDER BY Dates DESC";  
            $result = mysqli_query($connect, $query);
        }else if($_GET["type"] == "checkArchivedConsultation"){
            $query ="SELECT * FROM archivedconsultation LEFT JOIN personalmedicalrecord ON archivedconsultation.IdNumb = personalmedicalrecord.StudentIDNumber";
            $result = mysqli_query($connect, $query);
        }else if($_GET["type"] == "checkRange"){
            $start = $_GET['start'];
            $end = $_GET['end'];
            $query ="SELECT * FROM consultationinfo LEFT JOIN personalmedicalrecord ON consultationinfo.IdNumb = personalmedicalrecord.StudentIDNumber WHERE Dates >= '$start' AND Dates <= '$end'";  
            $result = mysqli_query($connect, $query);
        }else if($_GET["type"] == "checkRecordsId"){
            $id = $_GET["id"];
            $query ="SELECT * FROM consultationinfo LEFT JOIN personalmedicalrecord ON consultationinfo.IdNumb = personalmedicalrecord.StudentIDNumber WHERE IdNumb ='$id' ";  
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
                echo "<title>Consultation Summary</title>";
            }else if($_GET["type"] == "checkArchivedConsultation") {
                echo "<title>Archived Consultation Records</title>";
            }else if($_GET["type"] == "checkRecordsId"){
                echo "<title>Consultation Records</title>";
            }
        ?>
          
        <link rel = "icon" href = "../images/BSU-Logo.webp" type = "image/x-icon">
        
        <?php include '../includes/dependencies0.php'; ?>

        <link rel="stylesheet" href="../css/consultationTable-style.css">

        

        <script>
        // ---------------------------start functions for System Logs---------------------------------------
            var act = "";
            var acttype = "";
            var globalAL = "";
            var getType = "";
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
                    document.getElementById('tab1').innerHTML = '&bull;&nbsp;Archived Consultation History&nbsp;&bull;';
                }else if(y == "checkRecords"){
                    document.getElementById('tab1').innerHTML = '&bull;&nbsp;Consultation Summary&nbsp;&bull;';
                }else if(y == "checkRecordsId"){
                    document.getElementById('tab1').innerHTML = '&bull;&nbsp;Consultation History of '+name +'&nbsp;&bull;';
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

            function userViewRecord(StudentID){
                act = "Checked Student ID:" +StudentID +" Consultation."
                logAction(act);
            }

            function userArchiveRecord(StudentID,type){
                if(getType == 'checkRecordsId'){
                    acttype = "archiveConsultation";
                }else if(getType == 'checkRecords'){
                    acttype = "archiveAllConsultation";
                }

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
                acttype = "restoreConsultation";
                ID = StudentID;

                if(confirm("Are you sure you want to restore this Consultation Record?")){
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
                            window.location.href = '../../index.html';
                        }, 100);
                    }
                  })
            }

            var reportTitle = "";
            if(getType == 'checkRecords'){
                reportTitle = "Consultation Summary";
            }else if(getType == 'checkRecordsId'){
                reportTitle = "Consultation History of " +fullname;
            }
            var table = $('#consultation_data').DataTable({
                dom: 'fltpB',
                buttons: [                              
                    {
                        extend:'print',
                        text:'Print Report',
                        title:"<h1 style='text-align:center;'>" +reportTitle +"</h1>",
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

            var span = document.getElementById("NumConsultation");
            span.textContent = "Total Number of Consultation/s:   " + length.toString();

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
        
    </head>
    <body>
    <?php include '../includes/navbar.php'; ?> 
        <div class="cont container">
            
            <div class="tabs">
                
                <div class="tabs-head" style="display: inline-block;">
                    <?php  
                        if ($_GET["type"] == "checkRecordsId"){
                            $stringID = '';
                            if(!empty($id)){
                                $stringID = "id=$id";
                            }
                            echo "
                            <div>
                            <a id='backButton' class='btn btn-primary' href='indexCons.php?type=checkRecords' role='button'>Go Back</a>
                            </div>";
                        }
                    ?>
                    <span id="tab1" class="tabs-toggle is-active">&bull;&nbsp;Consultation Summary&nbsp;&bull;</span>
                </div>
                <div id="notif">
                    <?php if ($_GET["type"] == "checkRecords" || $type == 'checkRange' || $type == 'checkRecordsId' ){
                        echo "
                        <a id='newConsultation' class='btn btn-primary' href='newConsultation.php?type=newCons' role='button'>New Consultation</a>
                        <span id='NumConsultation'>Total Number of Consultation/s: </span>
                        ";

                    } ?>
                    
                </div>
                <div class="tabs-body">
                    <div class="tabs-content is-active table-responsive">  
                    <table id="consultation_data" class="table table-striped table-bordered">  
                          <thead>  
                               <tr>  
                                        <th>ID Number</th>
                                        <th>Full name</th>

                                        <?php 
                                        if ($_GET["type"] == "checkRecordsId"){
                                            echo "
                                            <th>Diagnosis</th>
                                            <th>Treatment</th>
                                            ";
                                        }
                                        ?>

                                        <?php 
                                        if ($_GET["type"] == "checkRecords"){
                                            echo "<th>Total</th>";
                                        }else{
                                            echo "<th>Staff</th>";
                                        } 
                                        ?>

                                        <th>Dates</th>

                                        <?php 
                                        if ($_GET["type"] == "checkArchivedConsultation")
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
                                $Staff = ucwords(strtolower($row['Physician']));

                                if(empty($Lastname) && ($type == "checkRecords" || $type == 'checkRange' || $type == 'checkRecordsId')){
                                    $queryArchive ="SELECT * FROM consultationinfo LEFT JOIN archivedstudent ON consultationinfo.IdNumb = archivedstudent.StudentIDNumber";
                                    $resultArchive = mysqli_query($connect, $queryArchive);
                                    $rowArchive = mysqli_fetch_array($resultArchive);
                                    $Lastname = ucwords($rowArchive['Lastname']);
                                    $Firstname = ucwords($rowArchive['Firstname']);
                                    $Middlename = ucwords($rowArchive['Middlename']);
                                }else if(empty($Lastname) && $type == "checkArchivedConsultation"){
                                    $queryArchive ="SELECT * FROM archivedconsultation LEFT JOIN archivedstudent ON archivedconsultation.IdNumb = archivedstudent.StudentIDNumber";
                                    $resultArchive = mysqli_query($connect, $queryArchive);
                                    $rowArchive = mysqli_fetch_array($resultArchive);
                                    $Lastname = ucwords($rowArchive['Lastname']);
                                    $Firstname = ucwords($rowArchive['Firstname']);
                                    $Middlename = ucwords($rowArchive['Middlename']);
                                }
                                
                                $querydates ="SELECT Dates FROM consultationinfo WHERE IdNumb = '$row[IdNumb]' ORDER BY Dates DESC";  
                                $resultdates = mysqli_query($connect, $querydates);

                                $querycount ="SELECT COUNT(IdNumb) as count FROM consultationinfo WHERE IdNumb = '$row[IdNumb]' ORDER BY Dates DESC";  
                                $resultcount = mysqli_query($connect, $querycount);

                                echo "  
                                <tr>
                                    <td>$row[IdNumb]</td>
                                    <td>$Lastname, $Firstname $Middlename</td>
                                ";

                                if ($_GET["type"] == "checkRecordsId"){
                                    echo "
                                    <th>$row[Diagnosis]</th>
                                    <th>$row[DiagnosticTestNeeded]</th>
                                ";
                                }

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
                                    <td>$Staff</td>
                                    <td>$row[Dates]</td>
                                    ";
                                }
                                                                        

                                    if ($_GET["type"] == "checkArchivedConsultation")
                                    echo "<td>$row[cons_archive_reason]</td>";
                                    
                                        if ($type == 'checkRecordsId' || $type == 'checkRange'){
                                            echo "<td>
                                            <a class='viewBTN btn btn-primary btn-sm'  href='newConsultation.php?num=$row[Num]&type=viewCons'>View</a>
                                            <a class='viewBTN btn btn-primary btn-sm'  href='indexFU.php?id=$row[IdNumb]&date=$row[Dates]&time=$row[Times]&type=checkRelFU'>Follow-ups</a>
                                            <a class='viewBTN btn btn-primary btn-sm' id='archiveBTN' onclick='userArchiveRecord($row[Num])'>Archive</a>";
                                        }else if ($type == 'checkArchivedConsultation'){
                                            echo "<td>
                                            <a class='viewBTN btn btn-primary btn-sm' href='newConsultation.php?num=$row[Num]&type=viewArchivedCons'>View</a>
                                            <a class='viewBTN btn btn-primary btn-sm' id='archiveBTN' onclick='userRestoreRecord($row[Num])'>Restore</a>";
                                        }else if ($type == 'checkRecords'){
                                            echo "<td>
                                            <a class='viewBTN btn btn-primary btn-sm' href='indexCons.php?type=checkRecordsId&id=$row[IdNumb]'>View All</a>
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
        <script src="../js/script-tab.js"></script>
    </body>
</html>
<?php
    $tempo = $_SESSION['accesslevel'];
    $tempor =  "";
    $_SESSION["typed"] = $_GET["type"];

    if($_GET["type"] == 'checkArchivedConsultation'){
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
 