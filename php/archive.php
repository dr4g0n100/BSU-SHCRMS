<?php
    session_start();
    require_once 'Database.php';
    require 'centralConnection.php';
    date_default_timezone_set('Asia/Manila');


    $Message = "";
    $error = "";
    $studentArchCount = 0;
    $consArchCount = 0;
    $userArchCount = 0;
    $fuArchCount = 0;
    $mcArchCount = 0;

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
        global $ClinicRecordsDB, $Message, $error;

        if($_SERVER['REQUEST_METHOD'] == 'GET'){

            $archive_reason = $_GET["archReason"];
            //$archive_reason = '123';

            if($_GET["type"] == "archiveLogs"){
                $dst_folder = "../logs/archive";
                $src_folder = "../logs";

                // Get a list of all files in the source folder
                $files = scandir($src_folder);

                //get today logs
                $today = date("F-d-Y");
                $todayLogFile = "$today.txt";

                // Loop through each file in the source folder
                $ctr = 0;
                foreach ($files as $file) {
                    if($file != '.' && $file != '..'){
                        $src_path = $src_folder . '/' . $file; 
                        if (is_file($src_path)) {
                            if ($file != $todayLogFile) {
                                $dst_path = $dst_folder . '/' . $file;

                                $current_time = date("h:i:s A");
                                $userID = $_SESSION['userID'];
                                $TxtUserName = $_SESSION['user'];

                                $handle = fopen($src_path, "a");

                                $data = "archived\n$current_time - $TxtUserName - $archive_reason";

                                fwrite($handle, $data);
                                fclose($handle);

                                if (rename($src_path, $dst_path)) {
                                    $ctr++;
                                } else {
                                    $Message = "Error while archiving!";
                                    $error = '1';
                                    break;
                                }
                            }
                        }
                        
                    }
                    
                }

                $Message = "Successfully archived $ctr System Logs Files";
                $error = '0';
                if($ctr == 0){
                    $Message = "System does not permit archiving today's system logs";
                    $error = '1';
                }
                
                

                
                
            }else if($_GET["type"] == "archiveStaff"){
                
                $id = $_GET["id"];

                $sql = "UPDATE USERACCOUNTS SET archived_at = CURRENT_TIMESTAMP, user_archive_reason = '$archive_reason' WHERE user_id ='$id'";
                $query = $ClinicRecordsDB->GetRows($sql);
                $sql = "INSERT INTO ARCHIVEDSTAFF SELECT * FROM USERACCOUNTS WHERE user_id ='$id'";
                $Result1 = $ClinicRecordsDB->GetRows($sql);
                $sql = "DELETE FROM USERACCOUNTS WHERE user_id='$id'";
                $Result2 = $ClinicRecordsDB->GetRows($sql);

                if ($Result1 && $Result2){
                    
                    $Message = "Successfully archived staff account";
                }else{
                    $Message = "Failed to archive staff account";
                }
            }else if($_GET["type"] == "archiveStudent"){
                $id = $_GET["id"];

                $sql = "UPDATE personalmedicalrecord SET archived_at = CURRENT_TIMESTAMP, pm_archive_reason = '$archive_reason' WHERE StudentIDNumber ='$id'";
                $query = $ClinicRecordsDB->GetRows($sql);
                $sql = "INSERT INTO archivedstudent SELECT * FROM personalmedicalrecord WHERE StudentIDNumber = '$id'";
                $Result1 = $ClinicRecordsDB->GetRows($sql);
                $sql = "DELETE FROM personalmedicalrecord WHERE StudentIDNumber = '$id'";
                $Result2 = $ClinicRecordsDB->GetRows($sql);

                $sql = "UPDATE CONSULTATIONINFO SET archived_at = CURRENT_TIMESTAMP, cons_archive_reason = 'Student Personal Record has been archived, Reason: $archive_reason' WHERE IdNumb = '$id'";
                $query = $ClinicRecordsDB->GetRows($sql);
                $sql = "INSERT INTO ARCHIVEDCONSULTATION SELECT * FROM CONSULTATIONINFO WHERE IdNumb = '$id'";
                $Result3 = $ClinicRecordsDB->GetRows($sql);
                $sql = "DELETE FROM CONSULTATIONINFO WHERE IdNumb = '$id'";
                $Result4 = $ClinicRecordsDB->GetRows($sql);

                $sql = "UPDATE medicalcertificate SET archived_at = CURRENT_TIMESTAMP, mc_archive_reason = 'Student Personal Record has been archived, Reason: $archive_reason' WHERE student_id = '$id'";
                $query = $ClinicRecordsDB->GetRows($sql);
                $sql = "INSERT INTO archivemedcertificate SELECT * FROM medicalcertificate WHERE student_id  = '$id'";
                $Result5 = $ClinicRecordsDB->GetRows($sql);
                $sql = "DELETE FROM medicalcertificate WHERE student_id  = '$id'";
                $Result6 = $ClinicRecordsDB->GetRows($sql);

                $sql = "UPDATE followup SET archived_at = CURRENT_TIMESTAMP, fu_archive_reason = 'Student Personal Record has been archived, Reason: $archive_reason' WHERE IdNumb = '$id'";
                $query = $ClinicRecordsDB->GetRows($sql);
                $sql = "INSERT INTO archivedfollowup SELECT * FROM followup WHERE IdNumb  = '$id'";
                $Result7 = $ClinicRecordsDB->GetRows($sql);
                $sql = "DELETE FROM followup WHERE IdNumb  = '$id'";
                $Result8 = $ClinicRecordsDB->GetRows($sql);

                if ($Result1 && $Result2){
                    if($Result3 && $Result4){
                        if($Result5 && $Result6){
                            if($Result7 && $Result8){
                                $Message = "Successfully Archived student, consultation, medical certificate info and follow-up record";

                            }else{
                                $Message = "Failed to archive follow-up consultation";
                            }
                        }else{
                            $Message = "Failed to archive medical certificate info";
                        }
                    }else{
                        $Message = "Failed to archive consultation info";
                    }
                }else{
                    $Message = "Failed to archived student info";
                }
            }else if($_GET["type"] == "archiveConsultation"){
                $id = $_GET["id"];

                $sql = "UPDATE medicalcertificate SET archived_at = CURRENT_TIMESTAMP, mc_archive_reason = 'Consultation Record has been archived, Reason: $archive_reason' WHERE student_id IN (SELECT IdNumb FROM CONSULTATIONINFO WHERE Num = '$id')";
                $ResultMC = $ClinicRecordsDB->GetRows($sql);
                $sql = "INSERT INTO archivemedcertificate SELECT * FROM medicalcertificate WHERE student_id IN (SELECT IdNumb FROM consultationinfo WHERE Num = '$id')";
                $Result3 = $ClinicRecordsDB->GetRows($sql);
                $sql = "DELETE FROM medicalcertificate WHERE student_id IN (SELECT IdNumb FROM consultationinfo WHERE Num = '$id')";
                $Result4 = $ClinicRecordsDB->GetRows($sql);

                $sql = "UPDATE followup SET archived_at = CURRENT_TIMESTAMP, fu_archive_reason = 'Consultation Record has been archived, Reason: $archive_reason' WHERE IdNumb IN (SELECT IdNumb FROM CONSULTATIONINFO WHERE Num = '$id')";
                $ResultFU = $ClinicRecordsDB->GetRows($sql);
                $sql = "INSERT INTO archivedfollowup SELECT * FROM followup WHERE IdNumb IN (SELECT IdNumb FROM consultationinfo WHERE Num = '$id')";
                $Result5 = $ClinicRecordsDB->GetRows($sql);
                $sql = "DELETE FROM followup WHERE IdNumb IN (SELECT IdNumb FROM consultationinfo WHERE Num = '$id')";
                $Result6 = $ClinicRecordsDB->GetRows($sql);

                $sql = "UPDATE CONSULTATIONINFO SET archived_at = CURRENT_TIMESTAMP, cons_archive_reason = '$archive_reason' WHERE Num = '$id'";
                $query = $ClinicRecordsDB->GetRows($sql);
                $sql = "INSERT INTO ARCHIVEDCONSULTATION SELECT * FROM CONSULTATIONINFO WHERE Num = '$id'";
                $Result1 = $ClinicRecordsDB->GetRows($sql);
                $sql = "DELETE FROM CONSULTATIONINFO WHERE Num = '$id'";
                $Result2 = $ClinicRecordsDB->GetRows($sql);

                if ($Result1 && $Result2){
                    
                    if($Result3 && $Result4){
                        
                        if($Result5 && $Result6){
                            
                            $Message = "Successfully archived consultation record, medical certificates and follow-up consultations";
                        }else{
                            $Message = "Successfully archived consultation and medical certificate records only";
                        }
                        
                    }else{
                        $Message = "Successfully archived consultation record only";
                    }

                }else{
                    $Message = "Failed to archive consultation record, medical certificates and follow-up consultations";
                }
            }else if($_GET["type"] == "archiveAllConsultation"){
                $id = $_GET["id"];

                $sql = "UPDATE medicalcertificate SET archived_at = CURRENT_TIMESTAMP, mc_archive_reason = 'Consultation Record has been archived, Reason: $archive_reason' WHERE student_id = '$id'";
                $ResultMC = $ClinicRecordsDB->GetRows($sql);
                $sql = "INSERT INTO archivemedcertificate SELECT * FROM medicalcertificate WHERE student_id = '$id'";
                $Result3 = $ClinicRecordsDB->GetRows($sql);
                $sql = "DELETE FROM medicalcertificate WHERE student_id = '$id'";
                $Result4 = $ClinicRecordsDB->GetRows($sql);

                $sql = "UPDATE followup SET archived_at = CURRENT_TIMESTAMP, fu_archive_reason = 'Consultation Record has been archived, Reason: $archive_reason' WHERE IdNumb = '$id'";
                $ResultFU = $ClinicRecordsDB->GetRows($sql);
                $sql = "INSERT INTO archivedfollowup SELECT * FROM followup WHERE IdNumb = '$id'";
                $Result5 = $ClinicRecordsDB->GetRows($sql);
                $sql = "DELETE FROM followup WHERE IdNumb = '$id'";
                $Result6 = $ClinicRecordsDB->GetRows($sql);

                $sql = "UPDATE CONSULTATIONINFO SET archived_at = CURRENT_TIMESTAMP, cons_archive_reason = '$archive_reason' WHERE IdNumb = '$id'";
                $query = $ClinicRecordsDB->GetRows($sql);
                $sql = "INSERT INTO ARCHIVEDCONSULTATION SELECT * FROM CONSULTATIONINFO WHERE IdNumb = '$id'";
                $Result1 = $ClinicRecordsDB->GetRows($sql);
                $sql = "DELETE FROM CONSULTATIONINFO WHERE IdNumb = '$id'";
                $Result2 = $ClinicRecordsDB->GetRows($sql);

                if ($Result1 && $Result2){
                    
                    if($Result3 && $Result4){
                        
                        if($Result5 && $Result6){
                            
                            $Message = "Successfully archived consultation record, medical certificates and follow-up consultations of the Student";
                        }else{
                            $Message = "Successfully archived consultation and medical certificate records only";
                        }
                        
                    }else{
                        $Message = "Successfully archived consultation record only";
                    }

                }else{
                    $Message = "Failed to archive consultation record, medical certificates and follow-up consultations";
                }
            }else if($_GET["type"] == "archiveFollowUp"){
                $id = $_GET["id"];

                $sql = "UPDATE followup SET archived_at = CURRENT_TIMESTAMP, fu_archive_reason = '$archive_reason' WHERE Num = '$id'";
                $query = $ClinicRecordsDB->GetRows($sql);
                $sql = "INSERT INTO archivedfollowup SELECT * FROM followup WHERE Num = '$id'";
                $Result1 = $ClinicRecordsDB->GetRows($sql);
                $sql = "DELETE FROM followup WHERE Num = '$id'";
                $Result2 = $ClinicRecordsDB->GetRows($sql);

                if ($Result1 && $Result2){
                    $Message = "Successfully archived Follow-up Record";
                }else{
                    $Message = "Failed to archived Follow-up Record";
                }

            }else if($_GET["type"] == "archiveAllFollowUp"){
                $id = $_GET["id"];

                $sql = "UPDATE followup SET archived_at = CURRENT_TIMESTAMP, fu_archive_reason = '$archive_reason' WHERE IdNumb = '$id'";
                $query = $ClinicRecordsDB->GetRows($sql);
                $sql = "INSERT INTO archivedfollowup SELECT * FROM followup WHERE IdNumb = '$id'";
                $Result1 = $ClinicRecordsDB->GetRows($sql);
                $sql = "DELETE FROM followup WHERE IdNumb = '$id'";
                $Result2 = $ClinicRecordsDB->GetRows($sql);

                if ($Result1 && $Result2){
                    $Message = "Successfully archived Follow-up Record";
                    
                }else{
                    $Message = "Failed to archived Follow-up Record";
                }

            }else if($_GET["type"] == "archiveMC"){
                $id = $_GET["id"];

                $sql = "UPDATE medicalcertificate SET archived_at = CURRENT_TIMESTAMP, mc_archive_reason = '$archive_reason' WHERE mc_id_num  = '$id'";
                $ResultUpdate = $ClinicRecordsDB->GetRows($sql);
                $sql = "INSERT INTO archivemedcertificate SELECT * FROM medicalcertificate WHERE mc_id_num  = '$id'";
                $Result1 = $ClinicRecordsDB->GetRows($sql);
                $sql = "DELETE FROM medicalcertificate WHERE mc_id_num  = '$id'";
                $Result2 = $ClinicRecordsDB->GetRows($sql);

                if ($ResultUpdate && $Result1 && $Result2){
                    $Message = "Successfully archived Medical Certificate Records";
                }else{
                    $Message = "Failed to archive Medical Certificate records";
                }
            }else if($_GET["type"] == "archiveAllMC"){
                $id = $_GET["id"];

                $sql = "UPDATE medicalcertificate SET archived_at = CURRENT_TIMESTAMP, mc_archive_reason = '$archive_reason' WHERE student_id  = '$id'";
                $ResultUpdate = $ClinicRecordsDB->GetRows($sql);
                $sql = "INSERT INTO archivemedcertificate SELECT * FROM medicalcertificate WHERE student_id  = '$id'";
                $Result1 = $ClinicRecordsDB->GetRows($sql);
                $sql = "DELETE FROM medicalcertificate WHERE student_id  = '$id'";
                $Result2 = $ClinicRecordsDB->GetRows($sql);

                if ($ResultUpdate && $Result1 && $Result2){
                    $Message = "Successfully archived All Medical Certificate Records";
                }else{
                    $Message = "Failed to archive Medical Certificate records";
                }
            }else if($_GET["type"] == "autoArchive"){
                $interval = 2555; //7 years
                $deleteInterval = 365; // 1year
                /*$interval = 0;*/ //for testing

                $sql = "INSERT INTO ARCHIVEDSTAFF SELECT * FROM USERACCOUNTS WHERE DATEDIFF( CURRENT_TIMESTAMP, created_at) > $interval";
                $Result11 = $ClinicRecordsDB->GetRows($sql);
                $sql = "DELETE FROM USERACCOUNTS WHERE DATEDIFF(CURRENT_TIMESTAMP, created_at) > $interval";
                $Result12 = $ClinicRecordsDB->GetRows($sql);
                if($Result11 && $Result12){
                    $sql = "UPDATE ARCHIVEDSTAFF SET archived_at = CURRENT_TIMESTAMP, user_archive_reason = '$archive_reason' WHERE DATEDIFF( CURRENT_TIMESTAMP, created_at) > $interval";
                    $query = $ClinicRecordsDB->GetRows($sql);
                }
                

                $sql = "INSERT INTO archivedstudent SELECT * FROM personalmedicalrecord WHERE DATEDIFF( CURRENT_TIMESTAMP, created_at) > $interval";
                $Result21 = $ClinicRecordsDB->GetRows($sql);
                $sql = "DELETE FROM personalmedicalrecord WHERE DATEDIFF( CURRENT_TIMESTAMP, created_at) > $interval";
                $Result22 = $ClinicRecordsDB->GetRows($sql);
                if($Result21 && $Result22){
                    $sql = "UPDATE archivedstudent SET archived_at = CURRENT_TIMESTAMP, pm_archive_reason = '$archive_reason' WHERE DATEDIFF( CURRENT_TIMESTAMP, created_at) > $interval";
                    $query = $ClinicRecordsDB->GetRows($sql);
                }

                $sql = "INSERT INTO ARCHIVEDCONSULTATION SELECT * FROM CONSULTATIONINFO WHERE DATEDIFF( CURRENT_TIMESTAMP, created_at) > $interval";
                $Result31 = $ClinicRecordsDB->GetRows($sql);
                $sql = "DELETE FROM CONSULTATIONINFO WHERE DATEDIFF( CURRENT_TIMESTAMP, created_at) > $interval";
                $Result32 = $ClinicRecordsDB->GetRows($sql);
                if($Result31 && $Result32){
                    $sql = "UPDATE ARCHIVEDCONSULTATION SET archived_at = CURRENT_TIMESTAMP, cons_archive_reason = '$archive_reason' WHERE DATEDIFF( CURRENT_TIMESTAMP, created_at) > $interval";
                    $query = $ClinicRecordsDB->GetRows($sql);
                }

                $sql = "INSERT INTO archivemedcertificate SELECT * FROM medicalcertificate WHERE DATEDIFF( CURRENT_TIMESTAMP, created_at) > $interval";
                $Result41 = $ClinicRecordsDB->GetRows($sql);
                $sql = "DELETE FROM medicalcertificate WHERE DATEDIFF( CURRENT_TIMESTAMP, created_at) > $interval";
                $Result42 = $ClinicRecordsDB->GetRows($sql);
                if($Result41 && $Result42){
                    $sql = "UPDATE archivemedcertificate SET archived_at = CURRENT_TIMESTAMP, mc_archive_reason = '$archive_reason' WHERE DATEDIFF( CURRENT_TIMESTAMP, created_at) > $interval";
                    $query = $ClinicRecordsDB->GetRows($sql);
                }

                $sql = "INSERT INTO archivedfollowup SELECT * FROM followup WHERE DATEDIFF( CURRENT_TIMESTAMP, created_at) > $interval";
                $Result51 = $ClinicRecordsDB->GetRows($sql);
                $sql = "DELETE FROM followup WHERE DATEDIFF( CURRENT_TIMESTAMP, created_at) > $interval";
                $Result52 = $ClinicRecordsDB->GetRows($sql);
                if($Result51 && $Result52){
                    $sql = "UPDATE archivedfollowup SET archived_at = CURRENT_TIMESTAMP, fu_archive_reason = '$archive_reason' WHERE DATEDIFF( CURRENT_TIMESTAMP, created_at) > $interval";
                    $query = $ClinicRecordsDB->GetRows($sql);
                }

                $sql = "DELETE FROM ARCHIVEDSTAFF WHERE DATEDIFF( CURRENT_TIMESTAMP, archived_at) > $deleteInterval";
                $Result1 = $ClinicRecordsDB->GetRows($sql);

                $sql = "DELETE FROM archivedstudent WHERE DATEDIFF( CURRENT_TIMESTAMP, archived_at) > $deleteInterval";
                $Result2 = $ClinicRecordsDB->GetRows($sql);

                $sql = "DELETE FROM ARCHIVEDCONSULTATION WHERE DATEDIFF( CURRENT_TIMESTAMP, archived_at) > $deleteInterval";
                $Result3 = $ClinicRecordsDB->GetRows($sql);

                $sql = "DELETE FROM archivemedcertificate WHERE DATEDIFF( CURRENT_TIMESTAMP, archived_at) > $deleteInterval";
                $Result4 = $ClinicRecordsDB->GetRows($sql);

                $sql = "DELETE FROM archivedfollowup WHERE DATEDIFF( CURRENT_TIMESTAMP, archived_at) > $deleteInterval";
                $Result5 = $ClinicRecordsDB->GetRows($sql);

                if ($Result11 && $Result12  && $Result21 && $Result22 && $Result31 && $Result32 && $Result41 && $Result42 && $Result51 && $Result52 && $Result1 && $Result2 && $Result3 && $Result4 && $Result5) {
                    $Message = "Successfully Auto Archived Records";
                    $error = 0;
                }else{
                    $Message = "$Result11 \n $Result12  \n $Result21 \n $Result22 \n $Result31 \n $Result32 \n $Result41 \n $Result42 \n $Result51 \n $Result52 \n $Result1 \n $Result2 \n $Result3 \n $Result4 \n $Result5";
                    $error = 1;
                }
            }
        }

    }


    $XMLData = '';  
    $XMLData .= ' <output ';
    $XMLData .= ' Message = ' . '"'.$Message.'"';
    $XMLData .= ' error = ' . '"'.$error.'"';
    $XMLData .= ' />';
    
    //Generate XML output
    header('Content-Type: text/xml');
    //Generate XML header
    echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
    echo '<Document>';      
    echo $XMLData;
    echo '</Document>';

?>