<?php
    require_once '../php/Database.php';
    require '../php/centralConnection.php';
    date_default_timezone_set('Asia/Manila');
    session_start();
    if(empty($_SESSION['logged_in'])){
    header('Location: ../index.html');
    }

    $type = $_GET["type"];

    if($type == "checkRecords"){
        $folder_path = "../logs";
    }else if($type == "checkArchivedLogs"){
        $folder_path = "../logs/archive";
    }

    $dir_contents = scandir($folder_path);

?>  

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <?php
             if($_GET["type"] == "checkRecords"){
                echo "<title>System Logs</title>";
             }else if($_GET["type"] == "checkArchivedLogs"){
                echo "<title>Archived System Logs</title>";
             }
        ?>
          
        <link rel = "icon" href = "../images/BSU-Logo.webp" type = "image/x-icon">
        
        <?php include '../includes/dependencies0.php'; ?>

        <link rel="stylesheet" href="../css/logs.css">

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
                    document.getElementById('tab1').innerHTML = '&bull;&nbsp;Archived System Logs&nbsp;&bull;';
                }else{
                    document.getElementById('tab1').innerHTML = '&bull;&nbsp;System Logs&nbsp;&bull;';
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

            function archiveLogs(){
                var acttype = "archiveLogs";
                var reason = '';
                if (reason = window.prompt("Specify a reason for archiving?")){
                    
                    $.ajax({
                    url:"../php/archive.php",
                    method:"GET",
                    data:jQuery.param({ type: acttype, archReason:reason }),
                    success:function(xml){
                        $(xml).find('output').each(function()
                        {
                            var message = $(this).attr('Message');
                            var error = $(this).attr('error');
                            

                            var btnColor = 'btn-red';
                            if(error == 0){
                                btnColor = 'btn-green';
                                logAction(message);
                            }
                            
                            $.alert(
                            {theme: 'modern',
                            content:message,
                            title:'', 
                            useBootstrap: false,
                            buttons:{
                                Ok:{
                                text:'Ok',
                                btnClass: btnColor
                            }}});
                            setTimeout(function(){
                                location.reload();
                            }, 2000);

                        });
                        
                    },  
                    error: function (e)
                    {
                        //Display Alert Box
                        $.alert(
                        {theme: 'modern',
                        content:'Failed to fetch information due to error',
                        title:'', 
                        useBootstrap: false,
                        buttons:{
                            Ok:{
                            text:'Ok',
                            btnClass: 'btn-red'
                        }}});
                    }

                    })
                }else if(reason == ''){
                    alert('Please specify a reason');
                }

            }

            function userRestoreRecord(filename){
                acttype = "restoreLogs";
                fileN = filename+".txt";

                if(confirm("Are you sure you want to restore "+filename +"'s system logs?")){
                    $.ajax({
                    url:"../php/restore.php",
                    method:"GET",
                    data:jQuery.param({file: fileN , type: acttype}),
                    success:function(xml){
                        $(xml).find('output').each(function()
                        {
                            var message = $(this).attr('Message');
                            logAction(message);
                            $.alert(
                            {theme: 'modern',
                                content: message,
                                title:'', 
                                buttons:{
                                Ok:{
                                    text:'Ok',
                                    btnClass: 'btn-green'
                                }}});
                            setTimeout(function(){
                                location.reload();
                            }, 2000);

                        });    
                    },  
                    error: function (e)
                    {
                        //Display Alert Box
                        $.alert(
                        {theme: 'modern',
                        content:'Failed to fetch information due to error',
                        title:'', 
                        useBootstrap: false,
                        buttons:{
                            Ok:{
                            text:'Ok',
                            btnClass: 'btn-red'
                        }}});
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
         
            var table = $('#user_data').DataTable({

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
                    
                }
                

            }); 

        </script>  
    </head>
    <body>
    <?php include '../includes/navbar.php'; ?>    
        <div class="cont container">
            <div class="tabs">
                <div class="tabs-head">
                    <span id="tab1" class="tabs-toggle is-active">&bull;&nbsp;SYSTEM LOGS&nbsp;&bull;</span>
                </div>
                
                <div class="tabs-body">
                    <div id="notif">
                    <?php
                        if ($type == 'checkRecords'){
                        echo "
                        <a id='newConsultation' class='viewBtn btn btn-danger mx-4' href='#' onclick='archiveLogs()' role='button'>Archive Old Logs</a>
                        ";
                        }
                    ?>
                    <span id="NumRecord">Total Number of Record/s: </span>
                    
                </div>
                    <div class="tabs-content is-active table-responsive">  
                    <table id="user_data" class="table table-striped table-bordered">  
                          <thead>  
                               <tr>  
                                    <th>Date</th>

                                    <?php
                                    if($type == "checkRecords"){
                                        echo "
                                        <th>Time</th>
                                        <th>User ID</th>
                                        <th>Username</th>
                                        <th width='40%'>System Feedback</th>
                                        ";
                                    }else if($type == "checkArchivedLogs"){
                                        echo "
                                        <th>Time Archived</th>
                                        <th>Archivist</th>
                                        <th>Archive Reason</th>
                                        <th>Action</th>
                                        ";
                                    }
                                    ?>
                                        
                                        
                               </tr>  
                          </thead> 
                          <tbody>
                          
                          <?php   
    
                            foreach ($dir_contents as $item) {
                                // Skip the special directories . and ..
                                if ($item == "." || $item == "..") {
                                    continue;
                                }
                                // Check if the item is a file or directory
                                if (!is_dir($folder_path . "/" . $item)) {

                                    if($type == "checkRecords"){
                                        // Open the file for reading
                                        $file = fopen("$folder_path/$item", "r");

                                        //get filename as date
                                        $date = substr($item, 0, strrpos($item, '.'));

                                        // Read the file contents into a string
                                        $content = fread($file, filesize("$folder_path/$item"));

                                        //trim extra white spaces
                                        $content = trim($content);

                                        // Split the contents into an array of lines
                                        $lines = explode("\n", $content);

                                        // Close the file
                                        fclose($file);

                                        // Output the data as an HTML table
                                        foreach ($lines as $line) {
                                            if($line == 'archived'){
                                            break;
                                            }

                                            $arrayLine = explode(" - ", $line);
                                            if($line != ''){
                                                echo "<tr>";
                                                echo "<td>" . htmlspecialchars($date) . "</td>";
                                                foreach ($arrayLine as $data){
                                                    echo "<td>" . htmlspecialchars($data) . "</td>";
                                                }

                                                echo "</tr>";
                                            }
                                            

                                            
                                        }
                                    }else if($type == "checkArchivedLogs"){
                                        //get filename as date
                                        $date = substr($item, 0, strrpos($item, '.'));

                                        $file_path = "$folder_path/$item";
                                        $file_lines = file($file_path);
                                        $last_line = trim(end($file_lines)); 

                                        $arrayLine = explode(" - ", $last_line);

                                        echo "<tr>";
                                        echo "<td><a href='../logs/archive/$item' download>" . htmlspecialchars($date) . "</a></td>";
                                        foreach ($arrayLine as $data){
                                            echo "<td>" . htmlspecialchars($data) . "</td>";
                                        }
                                        echo "
                                            <td class='text-center'> 
                                                <a class='btn-success btn-sm' href='#' onclick='userRestoreRecord(\"$date\")' data-toggle='tooltip' data-placement='bottom' title='Restore'>
                                                    <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-dropbox' viewBox='0 2 16 16'>
                                                      <path d='M8.01 4.555 4.005 7.11 8.01 9.665 4.005 12.22 0 9.651l4.005-2.555L0 4.555 4.005 2 8.01 4.555Zm-4.026 8.487 4.006-2.555 4.005 2.555-4.005 2.555-4.006-2.555Zm4.026-3.39 4.005-2.556L8.01 4.555 11.995 2 16 4.555 11.995 7.11 16 9.665l-4.005 2.555L8.01 9.651Z'/>
                                                    </svg>
                                                </a>
                                            </td>";
                                        echo "</tr>";
                                    }
                                    
                                    
                                }
                            }


                          ?> 

                        </tbody>  
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

    if($_GET["type"] == 'checkArchivedLogs'){
        $tempor = "checkArchived";
    }else{
        $tempor = "checkRecord";
    }

    echo "<script type='text/javascript'>
        globalAL = '$tempo';
        editTableNav('$tempor');
    </script>";
?>
 