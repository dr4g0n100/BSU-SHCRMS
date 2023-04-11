<?php
    require_once '../php/centralConnection.php';
    session_start();
    if(empty($_SESSION['logged_in'])){
        header('Location: ../index.html');
    }

?>
<!DOCTYPE html>
<html>
 <head>
  <title>Backup</title>
  <link rel = "icon" href = "../images/BSU-Logo.webp" type = "image/x-icon">

  <?php include '../includes/dependencies0.php'; ?>

  <link rel="stylesheet" type="text/css" href="../css/backup.css">

 </head>
 <body>
    <?php include '../includes/navbar.php'; ?>

  <br />
  
  <div id="form_wrapper">
        <div id="form_left" class="row">
            <form method="get" id="export_form" action="../php/backup_db.php">
                <h3>Download a Backup</h3>
                <div class="form-group">
                    <label for="TxtFileName">Enter filename</label>
                    <input type="text" name="TxtFileName" id="TxtFileName" required />
                    <input type="submit" name="submit" id="submit" class="btn btn-info form-button" onclick="userCreateBackup()" value="Download" />
                </div>
            </form>
        </div>
      <div id="form_right">
            <img id="backupIcon" src="../images/backupIcon.webp" alt="Backup Icon" />
      </div>
    </div>
    <script src="../js/script-tab.js"></script>
 </body>
</html>

<script type="text/javascript">

    // ---------------------------start functions for System Logs---------------------------------------
            var act = "";

            //function called when logout tab pressed
            function logout(){
                act = "Logged out";
                logAction(act);
                /*alert("logs success");*/
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

            //called to log user clicking "logs" tab
            function userCheckLogs(){
                act = "Checked User Activities." 
                logAction(act);
            }

            function userCreateBackup(){
                act = "User made a backup." 
                logAction(act);
            }

        // ---------------------------end functions for System Logs---------------------------------------
    
            $(document).ready(function() {

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

            }); 
</script> 


