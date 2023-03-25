<?php
require_once 'Database.php';
require '../centralConnection.php';
date_default_timezone_set('Asia/Manila');

  $Message = '';
  $Error = "0";

  /*$startDate = $_POST['startDate'];
  $endDate = $_POST['endDate'];*/

  $startDate = "2023-03-01";
  $endDate = "2023-03-10";

  $startDate = date("Y-m-d", strtotime($startDate));
  $endDate = date("Y-m-d", strtotime($endDate));

    $Staffs = "";
    $CountPMMale = "";
    $CountPMFemale = "";
    $CountConsMale = "";
    $CountConsFemale = "";
    $CountFUMale = "";
    $CountFUFemale = "";
    $CountMCMale = "";
    $CountMCFemale = "";


    $ClinicRecordsDB = new Database($Server,$User,$DBPassword);

    if ($ClinicRecordsDB->Connect()==true)
    {
      $Result = $ClinicRecordsDB->SelectDatabase($Database);
                          
      if($Result == true)
      {   
        
          FetchCount($startDate, $endDate);
        
      }
      else
      {
        $Message = 'Failed to fetch information!';
        $Error = "1";
      }
    }  
    else
    {
      $Message = 'The database is offline!';
      $Error = "1";    
    } 

  $XMLData = '';    
  $XMLData .= ' <output ';
  $XMLData .= ' Message = ' . '"'.$Message.'"';
  $XMLData .= ' Error = ' . '"'.$Error.'"';
  $XMLData .= ' Staffs = ' . '"'.$Staffs.'"';
  $XMLData .= ' CountPMMale = ' . '"'.$CountPMMale.'"';
  $XMLData .= ' CountPMFemale = ' . '"'.$CountPMFemale.'"';
  $XMLData .= ' CountConsMale = ' . '"'.$CountConsMale.'"';
  $XMLData .= ' CountConsFemale = ' . '"'.$CountConsFemale.'"';
  $XMLData .= ' CountFUMale = ' . '"'.$CountFUMale.'"';
  $XMLData .= ' CountFUFemale = ' . '"'.$CountFUFemale.'"';
  $XMLData .= ' CountMCMale = ' . '"'.$CountMCMale.'"';
  $XMLData .= ' CountMCFemale = ' . '"'.$CountMCFemale.'"';

    

  function FetchCount($startDate, $endDate){
    global $connection,$Staffs,$CountPMMale,$CountPMFemale,$CountConsMale,$CountConsFemale,$CountFUMale,$CountFUFemale,$CountMCMale,$CountMCFemale;

    $StaffArr = array();
    $StaffSubArr = array();

    $sql = "SELECT * FROM useraccounts";
    $result = mysqli_query($connection, $sql);

    if(mysqli_num_rows($result) > 0){
        $count = 0;
        while ($Row = $result->fetch_array()) {
            if($Row){ 
            $count++;    
                $LastName = stripslashes($Row['LastName']);;
                $FirstName = stripslashes($Row['FirstName']);;
                $StaffID = stripslashes($Row['IdNum']);;
                $StaffSubArr[] = "$LastName, $FirstName";

                
                

            }
        }
         
                   
    }else{
      $StaffsArr[] = "";
    }



    $Staffs = implode(',',$StaffSubArr);
    echo $count;

  }

?>