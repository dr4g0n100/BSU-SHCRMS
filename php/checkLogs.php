<?php  
	require_once 'Database.php';
	require 'centralConnection.php';
	date_default_timezone_set('Asia/Manila');
	session_start();

	$Data = '';

    $ClinicRecordsDB = new Database($Server,$User,$DBPassword);

  	if ($ClinicRecordsDB->Connect()==true)
  	{
    	$Result = $ClinicRecordsDB->SelectDatabase($Database);
                      
    	if($Result == true)
    	{         
    		
    		$sql = "SELECT * FROM SYSTEMLOGS";          
    	 	$ClinicRecordsQuery = $ClinicRecordsDB->GetRows($sql);  
    		if($ClinicRecordsQuery)
    		{                    
      			while($Row = $ClinicRecordsQuery->fetch_array())
      			{
	      			if($Row)
	      			{
	      				$Data .= ' <logs ';
	      				$Data .= ' Username = ' . '"'.stripslashes($Row['username']).'"';
	      				$Data .= ' Action = ' . '"'.stripslashes($Row['action']).'"';
	      				$Data .= ' isSuccess = ' . '"'.stripslashes($Row['isSuccess']).'"';
	      				$Data .= ' Date = ' . '"'.stripslashes($Row['date']).'"';
	     				$Data .= ' position = ' . '"'.stripslashes($Row['position']).'"';
	      				$Data .= ' />';
	      			}
      			}   	   
      		} 
    			  
    	}
	}

	//Generate XML output
	header('Content-Type: text/xml');
	//Generate XML header
	echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
	echo '<Document>';    	
	echo $Data;
	echo '</Document>'; 
?>
