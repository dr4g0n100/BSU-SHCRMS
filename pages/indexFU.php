<?php  
require '../php/centralConnection.php';
 session_start();
 if(empty($_SESSION['logged_in'])){
    header('Location: ../index.html');
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
          
        <link rel = "icon" href = "../images/BSU-Logo.webp" type = "image/x-icon">

        <?php include '../includes/dependencies0.php'; ?>

        <link rel="stylesheet" href="../css/followupTable-style.css">

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
                    document.getElementById('tab1').innerHTML = '&bull;&nbsp;Archived Follow-up Consultation&nbsp;&bull;';
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
                acttype = "restoreFollowUp";
                ID = StudentID;

                if(confirm("Are you sure you want to restore this Follow-up Record?")){
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
                  ],
                "oLanguage": {
                "sSearch": "Filter results:"
                }
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
        
    </head>
    <body>
     <?php include '../includes/navbar.php'; ?>  

        <div class="cont container">
            <div class="tabs">
                <div class="tabs-head" style="display: inline-flex;">
                    <?php  
                        if ($_GET["type"] == "checkRecordsId"){
                            echo "
                            <div>
                            <a id='backButton' class='btn btn-light m-3 bg-transparent border-0' onclick='window.history.back();' role='button'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='30' height='30' fill='currentColor' class='bi bi-arrow-left-circle' viewBox='0 0 16 16'>
                                  <path fill-rule='evenodd' d='M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z'/>
                                </svg>
                            </a>
                            </div>";
                        }else if ($_GET["type"] == "checkRelFU"){
                            echo "
                            <div>
                            <a id='backButton' class='btn btn-light m-3 bg-transparent border-0' onclick='window.history.back();' role='button'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='30' height='30' fill='currentColor' class='bi bi-arrow-left-circle' viewBox='0 0 16 16'>
                                  <path fill-rule='evenodd' d='M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z'/>
                                </svg></a>
                            </div>";
                        }
                    ?>
                    <span id="tab1" class="tabs-toggle is-active">&bull;&nbsp;Follow-Up Consultation&nbsp;&bull;</span>
                </div>
                <div id="notif">
                    <?php if ($_GET["type"] == "checkRecords" || $type == 'checkRange' || $type == 'checkRecordsId' || $type == 'checkRelFU'){
                        echo "
                        <a id='newFollowUp' class='btn btn-primary' href='newFollowUp.php?id=$id&date=$date&time=$time&type=createFU' role='button'>New Follow-Up</a>
                        <span id='NumFollowUp' style='align:center'>Total Number of Follow-Up/s: </span>
                        ";
                        //"newFollowUp.php?id="+studID+"&date="+date+"&time="+time+"&type=createFU";
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
                                            echo "<td class='text-center'>
                                            <a class='viewBTN btn-primary btn-sm' href='newFollowUp.php?num=$row[Num]&type=viewFollowUp' data-toggle='tooltip' data-placement='bottom' title='View'>
                                                <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-eye' viewBox='0 0 16 16'>
                                                  <path d='M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z'/>
                                                  <path d='M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z'/>
                                                </svg>
                                            </a>
                                            &nbsp
                                            <a class='btn-danger btn-sm' id='archiveBTN' href='#' onclick='userArchiveRecord($row[Num])' data-toggle='tooltip' data-placement='bottom' title='Archive'>
                                                <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-archive' viewBox='0 0 16 16'>
                                                  <path d='M0 2a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 12.5V5a1 1 0 0 1-1-1V2zm2 3v7.5A1.5 1.5 0 0 0 3.5 14h9a1.5 1.5 0 0 0 1.5-1.5V5H2zm13-3H1v2h14V2zM5 7.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z'/>
                                                </svg>
                                            </a>";
                                        }else if ($type == 'checkArchivedFollowUp'){
                                            echo "<td class='text-center'>
                                            <a class='viewBTN btn-primary btn-sm' href='newFollowUp.php?num=$row[Num]&type=viewArchivedFollowUp' data-toggle='tooltip' data-placement='bottom' title='View'>
                                                <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-eye' viewBox='0 0 16 16'>
                                                  <path d='M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z'/>
                                                  <path d='M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z'/>
                                                </svg>
                                            </a>
                                            &nbsp
                                            <a class='viewBTN btn-primary btn-sm' href='#' id='archiveBTN' onclick='userRestoreRecord($row[Num])' data-toggle='tooltip' data-placement='bottom' title='Restore'>
                                                <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-dropbox' viewBox='0 0 16 16'>
                                                  <path d='M8.01 4.555 4.005 7.11 8.01 9.665 4.005 12.22 0 9.651l4.005-2.555L0 4.555 4.005 2 8.01 4.555Zm-4.026 8.487 4.006-2.555 4.005 2.555-4.005 2.555-4.006-2.555Zm4.026-3.39 4.005-2.556L8.01 4.555 11.995 2 16 4.555 11.995 7.11 16 9.665l-4.005 2.555L8.01 9.651Z'/>
                                                </svg>
                                            </a>";
                                        }else if ($type == 'checkRecords'){
                                            echo "<td class='text-center'>
                                            <a class='viewBTN btn-primary btn-sm' href='indexFU.php?type=checkRecordsId&id=$row[IdNumb]' data-toggle='tooltip' data-placement='bottom' title='View All'>
                                                <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-eye' viewBox='0 0 16 16'>
                                                  <path d='M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z'/>
                                                  <path d='M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z'/>
                                                </svg>
                                            </a>
                                            &nbsp
                                            <a class='btn-danger btn-sm' id='archiveBTN' href='#' onclick='userArchiveRecord($row[IdNumb])' data-toggle='tooltip' data-placement='bottom' title='Archive All'>
                                                <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-archive' viewBox='0 0 16 16'>
                                                  <path d='M0 2a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 12.5V5a1 1 0 0 1-1-1V2zm2 3v7.5A1.5 1.5 0 0 0 3.5 14h9a1.5 1.5 0 0 0 1.5-1.5V5H2zm13-3H1v2h14V2zM5 7.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z'/>
                                                </svg>
                                            </a>";
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
 