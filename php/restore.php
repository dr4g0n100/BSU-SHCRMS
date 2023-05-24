<?php
    require_once 'Database.php';
    require_once 'centralConnection.php';
    date_default_timezone_set('Asia/Manila');

    $Message = "";

    $ClinicRecordsDB = new Database($Server,$User,$DBPassword);

    if ($ClinicRecordsDB->Connect()==true)
    {
        $Result = $ClinicRecordsDB->SelectDatabase($Database);
                      
        if($Result == true){         
            archiveRecord();       
        }else{
                $Message = 'Failed to delete Logs';
        }
    }else{
        $Message = 'The database is offline';
    }

    function archiveRecord (){
        global $ClinicRecordsDB, $Message, $connect;

        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            if($_GET["type"] == "restoreLogs"){
                $filename = $_GET["file"];
                $archRecordPath = "../logs/archive/$filename";
                $logRecPath = "../logs/$filename";
                if (file_exists($logRecPath)){

                    $file_lines = file($archRecordPath);
                    $file_lines = array_slice($file_lines, 3);
                    $file_lines = array_slice($file_lines, 0, -3);
                    $file_contents = implode("", $file_lines);

                    $existing_content = file_get_contents($logRecPath);

                    $updated_content = $file_contents . $existing_content;

                    file_put_contents($logRecPath, $file_contents);

                    unlink($archRecordPath);
                    $Message = "Successfully restored System Logs";
                }else{

                    $file = file($archRecordPath);

                    array_splice($file, -2);

                    file_put_contents($archRecordPath, implode('', $file));

                    rename($archRecordPath, "../logs/$filename");
                    $Message = "Successfully restored System Logs";
                }
                
                
            }else if($_GET["type"] == "restoreStaff"){
                
                $id = $_GET["id"];

                $sql = "INSERT INTO USERACCOUNTS SELECT * FROM ARCHIVEDSTAFF WHERE user_id ='$id'";
                $Result1 = $ClinicRecordsDB->GetRows($sql);
                $sql = "DELETE FROM ARCHIVEDSTAFF WHERE user_id ='$id'";
                $Result2 = $ClinicRecordsDB->GetRows($sql);

                if ($Result1 && $Result2){
                    $sql = "UPDATE USERACCOUNTS SET archived_at = '', created_at = CURRENT_TIMESTAMP WHERE user_id ='$id'";
                    $query = $ClinicRecordsDB->GetRows($sql);
                    $Message = "Successfully restored staff account";
                }else{
                    $Message = "Failed to restore staff account";
                }
            }else if($_GET["type"] == "restoreStudent"){
                $id = $_GET["id"];

                $sql = "INSERT INTO personalmedicalrecord SELECT * FROM archivedstudent WHERE StudentIDNumber = '$id'";
                $Result1 = $ClinicRecordsDB->GetRows($sql);
                $sql = "DELETE FROM archivedstudent WHERE StudentIDNumber = '$id'";
                $Result2 = $ClinicRecordsDB->GetRows($sql);

                if ($Result1 && $Result2){
                    $sql = "UPDATE personalmedicalrecord SET archived_at = '', created_at = CURRENT_TIMESTAMP WHERE StudentIDNumber ='$id'";
                    $query = $ClinicRecordsDB->GetRows($sql);
                    $Message = "Successfully restored student info";
                }else{
                    $Message = "Failed to restored student info";
                }

                
            }else if($_GET["type"] == "restoreConsultation"){
                $id = $_GET["id"];

                $StudID = '';
                $resultStudID = mysqli_query($connect, "SELECT IdNumb FROM ARCHIVEDCONSULTATION WHERE Num = '$id'");
                if ($resultStudID) {
                    $rowStudID = mysqli_fetch_array($resultStudID);
                    $StudID = $rowStudID['IdNumb'];
                }

                $sql = "INSERT INTO CONSULTATIONINFO SELECT * FROM ARCHIVEDCONSULTATION WHERE Num = '$id'";
                $Result1 = $ClinicRecordsDB->GetRows($sql);
                $sql = "DELETE FROM ARCHIVEDCONSULTATION WHERE Num = '$id'";
                $Result2 = $ClinicRecordsDB->GetRows($sql);

                if ($Result1 && $Result2){
                    $sql = "UPDATE CONSULTATIONINFO SET archived_at = '', created_at = CURRENT_TIMESTAMP WHERE Num ='$id'";
                    $query = $ClinicRecordsDB->GetRows($sql);
                    $Message = "Successfully restored consultation record of Student ID of $StudID";
                }else{
                    $Message = "Failed to restore consultation record";
                }
            }else if($_GET["type"] == "restoreFollowUp"){
                $id = $_GET["id"];

                $StudID = '';
                $resultStudID = mysqli_query($connect, "SELECT IdNumb FROM archivedfollowup WHERE Num = '$id'");
                if ($resultStudID) {
                    $rowStudID = mysqli_fetch_array($resultStudID);
                    $StudID = $rowStudID['IdNumb'];
                }

                $sql = "INSERT INTO followup SELECT * FROM archivedfollowup WHERE Num = '$id'";
                $Result1 = $ClinicRecordsDB->GetRows($sql);
                $sql = "DELETE FROM archivedfollowup WHERE Num = '$id'";
                $Result2 = $ClinicRecordsDB->GetRows($sql);

                if ($Result1 && $Result2){
                    $sql = "UPDATE followup SET archived_at = '', created_at = CURRENT_TIMESTAMP WHERE Num ='$id'";
                    $query = $ClinicRecordsDB->GetRows($sql);
                    $Message = "Successfully restored Follow-up Record of Student ID of $StudID";
                }else{
                    $Message = "Failed to restored Follow-up Record";
                }
            }else if($_GET["type"] == "restoreMC"){
                $id = $_GET["id"];

                $StudID = '';
                $resultStudID = mysqli_query($connect, "SELECT student_id FROM archivemedcertificate WHERE mc_id_num = '$id'");
                if ($resultStudID) {
                    $rowStudID = mysqli_fetch_array($resultStudID);
                    $StudID = $rowStudID['student_id'];
                }

                $sql = "INSERT INTO medicalcertificate SELECT * FROM archivemedcertificate WHERE mc_id_num = '$id'";
                $Result1 = $ClinicRecordsDB->GetRows($sql);
                $sql = "DELETE FROM archivemedcertificate WHERE mc_id_num = '$id'";
                $Result2 = $ClinicRecordsDB->GetRows($sql);

                if ($Result1 && $Result2){
                    $sql = "UPDATE medicalcertificate SET archived_at = '', created_at = CURRENT_TIMESTAMP WHERE id_num ='$id'";
                    $query = $ClinicRecordsDB->GetRows($sql);
                    $Message = "Successfully restored medical certificate of Student ID of $StudID";
                }else{
                    $Message = "Failed to restore medical certificate";
                }
            }
        }

    }


    $XMLData = '';  
    $XMLData .= ' <output ';
    $XMLData .= ' Message = ' . '"'.$Message.'"';
    $XMLData .= ' />';
    
    //Generate XML output
    header('Content-Type: text/xml');
    //Generate XML header
    echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
    echo '<Document>';      
    echo $XMLData;
    echo '</Document>';

?>