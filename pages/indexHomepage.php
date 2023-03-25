<?php
    session_start();
    if(empty($_SESSION['logged_in'])){
        header('Location: ../index.html');
    } 
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>BSU - SHCRMS</title>
  <link rel = "icon" href = "../images/BSU-UHSLogo.png" type = "image/x-icon">
	
  <?php include '../includes/dependencies0.php'; ?>

  <link rel="stylesheet" type="text/css" href="../css/homepage-style.css">
  <script type="text/javascript">

// ---------------------------start functions for System Logs---------------------------------------
            var act = "";
            var globalAL = "";

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
        // ---------------------------end functions for System Logs---------------------------------------
    
            $(document).ready(function() {

                var acclvl = sessionStorage.getItem('isStandard');

                if(acclvl == "true"){
                    $(".admin-nav").hide();
                }
            }); 
  </script>
</head>
<body>
  <?php include '../includes/navbar.php'; ?>  
	  <div id="form_wrapper">
      <div id="form_left">
        <div id="vission">
          <h1>Vision</h1>
          <p>The university health services envisions itself as a provider of excellent health services for the Benguet State University community.</p>
        </div>
        <div id="mission">
          <h1>Mission</h1>
          <p>Develop a better quality of life through health promotions, disease prevention, and medical interention.</p>
        </div>
      </div>
      <div id="form_right">
        <img id="bsuUHS-logo" src="../images/BSU-UHSLogo.png" alt="BSU-UHS Logo">
        <img id="bsu-logo" src="../images/BSULogo.png" alt="BSU Logo">
      </div>
    </div>
    <script src="../js/script-tab.js"></script>
  
</body>
</html>
<?php
$tempo = $_SESSION['accesslevel'];

    echo "<script type='text/javascript'>
        globalAL = '$tempo';
    </script>";
?>