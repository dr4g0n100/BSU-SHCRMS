<?php
    require_once '../php/centralConnection.php';
    session_start();
    if(empty($_SESSION['logged_in'])){
        header('Location: ../../index.html');
    }

    
?>
<!DOCTYPE html>
<html>
 <head>
  <title>Backup</title>
  <script src="../dist/jquery.min.js"></script>
  <link rel = "icon" href = "../images/BSU-Logo.webp" type = "image/x-icon">
  <link rel="stylesheet" type="text/css" href="../css/backup.css">
  <script src="../dist/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="../dist/jquery-confirm.min.css">
  <script src="../dist/jquery-confirm.min.js"></script>
  <script src="../dist/jspdf.debug.js"></script>
  <script src="../dist/jspdf.min.js"></script>
  <script src="../dist/html2pdf.bundle.min.js"></script>
 </head>
 <body>
    <nav>
        <a href="indexHomepage.php" id="back" class="nav-pages">Go Back</a>    
    </nav>

    

  <br />
  
  <div id="form_wrapper">
        <div id="form_left" class="row">
            <form method="post" id="export_form">
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

<?php

 if(isset($_POST['TxtFileName']))
{

    $tables = '*';
    $return = '';
  
    //Call the core function
    backup_tables($Server, $User, $DBPassword, $Database, $tables);


}

//Core function
function backup_tables($host, $user, $pass, $dbname, $tables = '*') {
    global $return; 
    $connect = mysqli_connect($host,$user,$pass, $dbname);

    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit;
    }

    mysqli_query($connect, "SET NAMES 'utf8'");

    //get all of the tables
    if($tables == '*')
    {
        $tables = array();
        $result = mysqli_query($connect, 'SHOW TABLES');
        while($row = mysqli_fetch_row($result))
        {
            $tables[] = $row[0];
        }
    }
    else
    {
        $tables = is_array($tables) ? $tables : explode(',',$tables);
    }

    $TxtFileName = $_POST['TxtFileName'];
    $return.= "\n\nDROP DATABASE if exists clinicRecord;\n";
    $return.= "CREATE DATABASE clinicRecord;\n";
    $return.= "USE clinicRecord;\n";

    
    //cycle through
    foreach($tables as $table)
    {
        $result = mysqli_query($connect, 'SELECT * FROM '.$table);
        $num_fields = mysqli_num_fields($result);
        $num_rows = mysqli_num_rows($result);

        $return.= 'DROP TABLE IF EXISTS '.$table.';';
        $row2 = mysqli_fetch_row(mysqli_query($connect, 'SHOW CREATE TABLE '.$table));
        $return.= "\n\n".$row2[1].";\n\n";
        $counter = 1;

        //Over tables
        for ($i = 0; $i < $num_fields; $i++) 
        {   //Over rows
            while($row = mysqli_fetch_row($result))
            {   
                if($counter == 1){
                    $return.= 'INSERT INTO '.$table.' VALUES(';
                } else{
                    $return.= '(';
                }

                //Over fields
                for($j=0; $j<$num_fields; $j++) 
                {
                    $row[$j] = addslashes($row[$j]);
                    $row[$j] = str_replace("\n","\\n",$row[$j]);
                    if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
                    if ($j<($num_fields-1)) { $return.= ','; }
                }

                if($num_rows == $counter){
                    $return.= ");\n";
                } else{
                    $return.= "),\n";
                }
                ++$counter;
            }
        }
        $return.="\n\n\n";
    }

    //save file
    $fileName = $TxtFileName ." (" . date('M-d-Y') .'--' . date('h.i A') . ').sql';
    $handle = fopen($fileName,'w+');
    fwrite($handle,$return);
    if(fclose($handle)){

         header('Content-Description: File Transfer');
         header('Content-Type: application/octet-stream');
         header('Content-Disposition: attachment; filename=' . basename($fileName));
         header('Content-Transfer-Encoding: binary');
         header('Expires: 0');
         header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fileName));
            ob_clean();
            flush();
            readfile($fileName);            
            unlink($fileName);
        exit;
    }
}

?>
