<?php
session_start();
date_default_timezone_set('Asia/Manila');

$Server = "localhost";    
$User = "root";
$DBPassword = "";
$Database = "clinicRecord";
$connect = mysqli_connect($Server, $User, $DBPassword, $Database);


$tables = '*';
$return = '';

//Call the core function
backup_tables($Server, $User, $DBPassword, $Database, $tables);



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
    $fileName = "C:\Backups\AutoBackup (" . date('M-d-Y') .'--' . date('h.i A') . ').sql';
    $handle = fopen($fileName,'w+');
    fwrite($handle,$return);
    if(fclose($handle)){
         ob_clean();
         flush();            
         exit;
    }
}

?>