<?php 
  $Server = "localhost";    
  $User = "root";
  $DBPassword = "";
  $Database = "clinicRecord";

  $con = mysqli_connect($Server, $User, $DBPassword, $Database);
  $connect = mysqli_connect($Server, $User, $DBPassword, $Database);
  $connectPDO = new PDO("mysql:host=$Server;dbname=$Database", $User, $DBPassword);
  $connection = new mysqli($Server, $User, $DBPassword, $Database);
  
  mysqli_query($connect, "SET GLOBAL max_allowed_packet=16*1024*1024");

?>