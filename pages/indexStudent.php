<?php  
require '../php/centralConnection.php';
session_start();
if(empty($_SESSION['logged_in'])){
 header('Location: ../index.html');
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

        if($_GET["type"] == 'checkArchivedStudent'){
            $viewType = "viewArchivedRecord";
        }else{
            $viewType = "viewRecord";
        }
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
        <link rel = "icon" href = "../images/BSU-Logo.webp" type = "image/x-icon">
        
        <?php include '../includes/dependencies0.php'; ?>

        <link rel="stylesheet" type="text/css" href="../css/studentTable-style.css">

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

            function editTableNav(y){
                if(y == "checkArchived"){
                    document.getElementById('tab1').innerHTML = '&bull;&nbsp;Archived Students Record&nbsp;&bull;';
                }else{
                    document.getElementById('tab1').innerHTML = '&bull;&nbsp;Students Record&nbsp;&bull;';
                }
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
                        window.location.href = 'indexStudent.php?type=checkRecords';
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
                        window.location.href = 'indexStudent.php?type=checkArchivedStudent';
                        
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
                  ],
                "oLanguage": {
                "sSearch": "Filter results:"
                }
            });
            var length = table.page.info().recordsTotal;

            var span = document.getElementById("NumRecord");
            span.textContent = "Total Number of Record/s: " + length.toString();

            var acclvl = sessionStorage.getItem('isStandard');

            if(acclvl == "true"){
                $(".admin-nav").hide();
                document.getElementById("userFullname").style.width = "52%";
            }
        });  
        </script>  
        
    </head>
    <body>
    <?php include '../includes/navbar.php'; ?>  
        <div class="cont container">
            <div class="tabs">
                <div class="tabs-head">
                    <span id="tab1" class="tabs-toggle is-active">&bull;&nbsp;Students Record&nbsp;&bull;</span>
                </div>
                <div id="notif">
                    <?php if ($_GET["type"] == "checkRecords" || $type == 'checkRange'){
                        echo "
                        <a id='newRecord' class='btn btn-primary' href='newStudent.php?type=newRecord' role='button'>New Record</a>
                    <span id='NumRecord'>Total Number of Record/s: </span>
                        ";

                    }else{
                        echo "
                        <span id='NumRecord' style='margin-left:71.8%;'>Total Number of Record/s: </span>
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
                                        <th>Degree / Strand</th>
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
                                $Lastname = ucwords($row['Lastname']);
                                $FirstName = ucwords($row['Firstname']);
                                $MiddleName = ucwords($row['Middlename']);
                                $course = "";
                                if($row['Course'] != ""){
                                    $course = $row['Course'];
                                }else{
                                    $course = ucwords($row['StudentCategory']);
                                }
                                echo "  
                                <tr>
                                    <td>$row[StudentIDNumber]</td>
                                    <td><a href='newStudent.php?id=$row[StudentIDNumber]&type=$viewType'>$Lastname, $FirstName $MiddleName</a></td>
                                    <td>$course</td>
                                    <td>$row[Age]</td>
                                    <td>$Sex</td>
                                    <td>$row[StudentContactNumber]</td>
                                    <td>$row[Date]</td>
                                    ";
                                    if ($_GET["type"] == "checkArchivedStudent")
                                    echo "<td>$row[pm_archive_reason]</td>";
                                    
                                        
                                        if ($type == 'checkRecords' || $type == 'checkRange'){
                                            echo "<td class='text-center'>
                                            <a class='viewBTN btn-primary btn-sm' href='newStudent.php?id=$row[StudentIDNumber]&type=viewRecord' data-toggle='tooltip' data-placement='bottom' title='View'>
                                                <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-eye' viewBox='0 0 16 16'>
                                                  <path d='M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z'/>
                                                  <path d='M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z'/>
                                                </svg>
                                            </a>
                                            <br>
                                            <a class='btn-danger btn-sm' href='#' id='archiveBTN' onclick='userArchiveRecord($row[StudentIDNumber])' data-toggle='tooltip' data-placement='bottom' title='Archive'>
                                                <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-archive' viewBox='0 0 16 16'>
                                                  <path d='M0 2a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 12.5V5a1 1 0 0 1-1-1V2zm2 3v7.5A1.5 1.5 0 0 0 3.5 14h9a1.5 1.5 0 0 0 1.5-1.5V5H2zm13-3H1v2h14V2zM5 7.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z'/>
                                                </svg>
                                            </a>";
                                        }else if ($type == 'checkArchivedStudent'){
                                            echo "<td class='text-center'>
                                            <a class='viewBTN btn-primary btn-sm' href='newStudent.php?id=$row[StudentIDNumber]&type=viewArchivedRecord' data-toggle='tooltip' data-placement='bottom' title='View'>
                                                <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-eye' viewBox='0 0 16 16'>
                                                  <path d='M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z'/>
                                                  <path d='M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z'/>
                                                </svg>
                                            </a>
                                            <br>
                                            <a class='viewBTN btn-primary btn-sm' id='archiveBTN' onclick='userRestoreRecord($row[StudentIDNumber])' data-toggle='tooltip' data-placement='bottom' title='Restore'>
                                            <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-arrow-counterclockwise' viewBox='0 0 16 16'>
                                            <path fill-rule='evenodd' d='M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z'/>
                                            <path d='M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z'/>
                                            </svg>
                                            </a>";
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
 