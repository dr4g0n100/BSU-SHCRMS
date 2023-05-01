<?php
    require_once 'Database.php';
    require 'centralConnection.php';
    date_default_timezone_set('Asia/Manila');
    session_start();


    $Action = " ";
    $isSuccess = " ";

    $Action = $_POST['action'];
    $isSuccess = $_POST['isSuccess'];
    $TxtUserName = $_SESSION['user'];
    $userID = $_SESSION['userID'];
    $position = $_SESSION['position'];
    if($position == 0)
      $position = "Staff";
    else
      $position = "Admin";

    $ClinicRecordsDB = new Database($Server,$User,$DBPassword);

    if ($ClinicRecordsDB->Connect()==true)
    {
      $Result = $ClinicRecordsDB->SelectDatabase($Database);
                          
      if($Result == true)
      {   
        /*$sql = "INSERT INTO SYSTEMLOGS 
              (userID, username, action, isSuccess, date, position) 
              VALUES ('$userID','$TxtUserName', '$Action', '$isSuccess',CURRENT_TIMESTAMP, '$position')";
        $Result = $ClinicRecordsDB->Execute($sql);*/

        $today = date("F-d-Y");
        $current_time = date("h:i A");
        $filepath = "../logs/$today.txt";
        if(file_exists($filepath)) {
            $handle = fopen($filepath, "a");
        } else {
            $handle = fopen($filepath, "w");
        }

        $data = "$current_time - $userID - $TxtUserName - $Action";

        fwrite($handle, $data."\n");
        fclose($handle);



      }
      else
      {
        $Message = 'Failed to search user!';
        $Verify = false;
      }
    }  
    else
    {
      $Message = 'The database is offline!';
      $Verify = false;   
    } 
?>