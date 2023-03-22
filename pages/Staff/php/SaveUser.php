<?php
  require_once 'Database.php';
  require '../../../php/centralConnection.php';
	date_default_timezone_set('Asia/Manila');

  $Message = '';
  $Error;



  // Receive Data from Client
   
    $TxtStaffIDNumber = $_POST['TxtStaffIDNumber'];
    $RadStatus = $_POST['RadStatus'];
    $TxtLastname = $_POST['TxtLastname'];
    $TxtFirstname = $_POST['TxtFirstname'];
    $TxtMiddlename = $_POST['TxtMiddlename'];
    $TxtExtension = $_POST['TxtExtension'];
    $RadPosition = $_POST['RadPosition'];
    $TxtRank = $_POST['TxtRank'];
    $TxtContactNumber = $_POST['TxtContactNumber'];
    $TxtEmail = $_POST['TxtEmail'];
    $TxtUsername = $_POST['TxtUsername'];
    $TxtPassword = $_POST['TxtPassword'];
    
    $ClinicRecordsDB = new Database($Server,$User,$DBPassword);

    if ($ClinicRecordsDB->Connect()==true)
    {
      $Result = $ClinicRecordsDB->SelectDatabase($Database);
                          
      if($Result == true)
      {   
        UpdateUser();
      }
      else
      {
        $Message = 'Failed to save information!';
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
    $XMLData .= ' />';
    
    //Generate XML output
    header('Content-Type: text/xml');
    //Generate XML header
    echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
    echo '<Document>';    	
    echo $XMLData;
    echo '</Document>';

    function UpdateUser(){

   
      global $sql, $ClinicRecordsDB,$Message,$Error, $TxtStaffIDNumber, $TxtLastname, $TxtFirstname, $TxtMiddlename, $TxtExtension, $RadPosition, $TxtRank, $TxtContactNumber, $TxtEmail, $TxtUsername, $TxtPassword, $RadStatus;

      /*$RadStatus = strtolower($RadStatus);
      $TxtLastname = strtolower($TxtLastname);
      $TxtFirstname = strtolower($TxtFirstname);
      $TxtMiddlename = strtolower($TxtMiddlename);
      $TxtExtension = strtolower($TxtExtension);
      $RadPosition = strtolower($RadPosition);
      $TxtRank = strtolower($TxtRank);
      $TxtEmail = strtolower($TxtEmail);*/

      /* $TxtPassword = password_hash($TxtPassword, PASSWORD_DEFAULT); */

      // Store a string into the variable which need to be Encrypted
      $simple_string = $TxtPassword;
  
      // Store the cipher method
      $ciphering = "AES-128-CTR";

      // Use OpenSSl Encryption method
      $iv_length = openssl_cipher_iv_length($ciphering);
      $options = 0;

      // Non-NULL Initialization Vector for encryption
      $encryption_iv = '1234567891011121';

      // Store the encryption key
      $encryption_key = "BenguetStateUniversityMedicalClinic";

      // Use openssl_encrypt() function to encrypt the data
      $encryption = openssl_encrypt($simple_string, $ciphering, $encryption_key, $options, $encryption_iv);
      
      $sql = "UPDATE USERACCOUNTS SET IdNum='$TxtStaffIDNumber', Status='$RadStatus', Email='$TxtEmail',  Username='$TxtUsername', Password='$encryption', LastName='$TxtLastname', FirstName='$TxtFirstname', MiddleName='$TxtMiddlename', Extension='$TxtExtension', Position='$RadPosition', Rank='$TxtRank', ContactNum='$TxtContactNumber', created_at = CURRENT_TIMESTAMP WHERE IdNum='$TxtStaffIDNumber'";


      $Result = $ClinicRecordsDB->Execute($sql);
      $Message = 'Successfully saved the information!'; 
      $Error = "0";
      
    }
?>
