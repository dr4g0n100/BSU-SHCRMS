<?php
    require_once '../php/centralConnection.php';
    session_start();
    if(empty($_SESSION['logged_in'])){
        header('Location: ../index.html');
    } 

    $message = '';
?>
<!DOCTYPE html>  
<html>  
 <head>  
  <title>Restore</title>  

  <link rel = "icon" href = "../images/BSU-Logo.webp" type = "image/x-icon">

  <?php include '../includes/dependencies0.php'; ?>

  <link rel="stylesheet" type="text/css" href="../css/restore.css">

  <script>
    var globalAL = "";

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

            function userRestore(){
                act = "User Restored from a Backup." 
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
 </head>  
 <body>  
    <?php include '../includes/navbar.php'; ?>
  <br /><br />  

    <div id="form_wrapper">
        <div id="form_left" class="row">
            <form method="post" enctype="multipart/form-data" id="export_form">
            <h3 align="center" id="leftHeader">Please select a backup file to restore database</h3>  
            <br />
            <div><?php echo $message; ?></div>
                <h3 id="leftSubHeader">Select Sql File</h3>
                <input type="file" name="database" /></p>
                <br />
                <input type="submit" name="import" class="btn btn-info form-button" id="btnRestore" value="Restore" /><br><br>
            </form>
        </div>
      <div id="form_right">
            <img id="restoreIcon" src="../images/restore.webp" alt="Restore Icon" />
      </div>
    </div>
    <div>
        <button type="Submit" class="Upbtn" id="btnUp" name="btnUp" onclick="window.scrollTo(0, 0)"/>
    </div>
    <script src="../js/script-tab.js"></script> 
 </body>  
</html>

<?php 

$tempo = $_SESSION['accesslevel'];

    echo "<script type='text/javascript'>
        globalAL = '$tempo';
    </script>";


if(isset($_POST["import"]))
{
 if($_FILES["database"]["name"] != '')
 {
  $array = explode(".", $_FILES["database"]["name"]);
  $extension = end($array);
  if($extension == 'sql')
  {
   $output = '';
   $count = 0;
   $file_data = file($_FILES["database"]["tmp_name"]);
   foreach($file_data as $row)
   {
    $start_character = substr(trim($row), 0, 2);
    if($start_character != '--' || $start_character != '/*' || $start_character != '//' || $row != '')
    {

     $output = $output . $row;
     
     $end_character = substr(trim($row), -1, 1);
     if($end_character == ';')
     {
      if(!mysqli_query($connect, $output))
      {
       $count++;
      }
      $output = '';
     }
    }
   }
   if($count > 0)
   {
    $message = '<label class="text-danger">There is an error in Database Import</label>';
   }
   else
   {
    $message = '<label class="text-success">Database Successfully Imported</label>';
   }
  }
  else
  {
   $message = '<label class="text-danger">Invalid File</label>';
  }
 }
 else
 {
  $message = '<label class="text-danger">Please Select Sql File</label>';
 }


 //Alert pop-up if upload success or failed
 $btnColor = 'btn-red';
 if ($message == '<label class="text-success">Database Successfully Imported</label>'){
    $btnColor = 'btn-green';
 }

 echo "

 <script>
    $.alert(
    {theme: 'modern',
     content: '$message',
     title:'', 
     buttons:{
         Ok:{
         text:'Ok',
         btnClass: '$btnColor'
     }}});

     userRestore();

    setTimeout(function(){
        window.location.href = 'indexHomepage.php';
    }, 2000);

 </script>

 ";
}
?>


