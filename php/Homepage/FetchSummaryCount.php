<?php
require_once 'Database.php';
require '../centralConnection.php';
date_default_timezone_set('Asia/Manila');

  $Message = '';
  $Error = "0";

  $startDate = $_POST['startDate'];
  $endDate = $_POST['endDate'];

  /*$startDate = "2023-03-25";
  $endDate = "2023-03-25";*/

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

  $XMLData .= ' />';
    
    //Generate XML output
    header('Content-Type: text/xml');
    //Generate XML header
    echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
    echo '<Document>';      
    echo $XMLData;
    echo '</Document>';

    

  function FetchCount($startDate, $endDate){
    global $connection,$Staffs,$CountPMMale,$CountPMFemale,$CountConsMale,$CountConsFemale,$CountFUMale,$CountFUFemale,$CountMCMale,$CountMCFemale;

    $StaffArr = array();
    $CountPMMaleArr = array();
    $CountPMFemaleArr = array();
    $CountConsMaleArr = array();
    $CountConsFemaleArr = array();
    $CountFUMaleArr = array();
    $CountFUFemaleArr = array();
    $CountMCMaleArr = array();
    $CountMCFemaleArr = array();

    $sqlUser = "SELECT * FROM useraccounts";
    $resultUser = mysqli_query($connection, $sqlUser);

    if(mysqli_num_rows($resultUser) > 0){
        while ($RowUser = $resultUser->fetch_array()) {
            if($RowUser){ 
                $LastName = stripslashes($RowUser['LastName']);;
                $FirstName = stripslashes($RowUser['FirstName']);;
                $StaffID = stripslashes($RowUser['IdNum']);;
                $StaffArr[] = "$LastName, $FirstName";

                $sql = "SELECT 
                          COUNT(CASE WHEN personalmedicalrecord.Sex = 'male' THEN 1 END) as count_male, 
                          COUNT(CASE WHEN personalmedicalrecord.Sex = 'female' THEN 1 END) as count_female
                        FROM PersonalMedicalRecord 
                        WHERE 
                          (Date >= '$startDate' AND Date <= '$endDate') AND StaffIDNumber='$StaffID'";
                $result = mysqli_query($connection, $sql);

                if(mysqli_num_rows($result) > 0){
                    $Row = $result->fetch_array(); 
                    if($Row){        
                        $CountPMMaleArr[] = stripslashes($Row['count_male']);;
                        $CountPMFemaleArr[] = stripslashes($Row['count_female']);;
                      }           
                }else{
                  $CountPMMaleArr[] = 0;
                  $CountPMFemaleArr[] = 0;
                }

                $sql = "SELECT 
                          COUNT(CASE WHEN personalmedicalrecord.Sex = 'male' THEN 1 END) as count_male, 
                          COUNT(CASE WHEN personalmedicalrecord.Sex = 'female' THEN 1 END) as count_female
                        FROM consultationinfo 
                        LEFT JOIN personalmedicalrecord
                        ON 
                          consultationinfo.IdNumb = personalmedicalrecord.StudentIDNumber
                        WHERE 
                          (Dates >= '$startDate' AND Dates <= '$endDate') AND PhysicianID='$StaffID'";
                $result = mysqli_query($connection, $sql);

                if(mysqli_num_rows($result) > 0){
                    $Row = $result->fetch_array(); 
                    if($Row){        
                        $CountConsMaleArr[] = stripslashes($Row['count_male']);;
                        $CountConsFemaleArr[] = stripslashes($Row['count_female']);;
                      }           
                }else{
                  $CountConsMaleArr[] = 0;
                  $CountConsFemaleArr[] = 0;
                }

                $sql = "SELECT 
                          COUNT(CASE WHEN personalmedicalrecord.Sex = 'male' THEN 1 END) as count_male, 
                          COUNT(CASE WHEN personalmedicalrecord.Sex = 'female' THEN 1 END) as count_female
                        FROM followup 
                        LEFT JOIN personalmedicalrecord
                        ON 
                          followup.IdNumb = personalmedicalrecord.StudentIDNumber
                        WHERE
                          (Dates >= '$startDate' AND Dates <= '$endDate') AND PhysicianID='$StaffID'";
                $result = mysqli_query($connection, $sql);

                if(mysqli_num_rows($result) > 0){
                    $Row = $result->fetch_array(); 
                    if($Row){        
                        $CountFUMaleArr[] = stripslashes($Row['count_male']);;
                        $CountFUFemaleArr[] = stripslashes($Row['count_female']);;
                      }           
                }else{
                  $CountFUMaleArr[] = 0;
                  $CountFUFemaleArr[] = 0;
                }

                $sql = "SELECT 
                          COUNT(CASE WHEN personalmedicalrecord.Sex = 'male' THEN 1 END) as count_male, 
                          COUNT(CASE WHEN personalmedicalrecord.Sex = 'female' THEN 1 END) as count_female
                        FROM medicalcertificate 
                        LEFT JOIN personalmedicalrecord
                        ON 
                          medicalcertificate.student_id = personalmedicalrecord.StudentIDNumber
                        WHERE
                          (date_requested >= '$startDate' AND date_requested <= '$endDate') AND mc_physician_id='$StaffID'";
                $result = mysqli_query($connection, $sql);

                if(mysqli_num_rows($result) > 0){
                    $Row = $result->fetch_array(); 
                    if($Row){        
                        $CountMCMaleArr[] = stripslashes($Row['count_male']);;
                        $CountMCFemaleArr[] = stripslashes($Row['count_female']);;
                      }           
                }else{
                  $CountMCMaleArr[] = 0;
                  $CountMCFemaleArr[] = 0;
                }

                

            }

        }
                   
    }else{
      $StaffsArr[] = "";
    }

    $Staffs = implode('-',$StaffArr);
    $CountPMMale = implode('-',$CountPMMaleArr);
    $CountPMFemale = implode('-',$CountPMFemaleArr);
    $CountConsMale = implode('-',$CountConsMaleArr);
    $CountConsFemale = implode('-',$CountConsFemaleArr);
    $CountFUMale = implode('-',$CountFUMaleArr);
    $CountFUFemale = implode('-',$CountFUFemaleArr);
    $CountMCMale = implode('-',$CountMCMaleArr);
    $CountMCFemale = implode('-',$CountMCFemaleArr);

  }

?>