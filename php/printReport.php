<?php
require 'centralConnection.php';
date_default_timezone_set('Asia/Manila');

function dashDate($date){
    return date("Y-m-d", strtotime($date) );
}

function textDate($date){
    return date("F/d/Y", strtotime($date) );
}
$excelData = '';
$fileName = "excel.xls"; 

$start = $_GET['start'];
$end = $_GET['end'];
$type = $_GET['type'];

if($type == 'own'){
	$id = $_GET['id'];
	$query = "SELECT * FROM useraccounts WHERE IdNum = '$id'";
	$result = $connection->query($query);
	$excelData.="Individual Summary Report" ."\n\n"; 
}else if($type == 'all'){
	$query = "SELECT * FROM useraccounts";
	$result = $connection->query($query);
	$excelData.="All Staff Summary Report" ."\n\n"; 
}

if ($result->num_rows > 0) {
	while($row = $result->fetch_array()){
		$id = $row['IdNum'];
		
	    $excelData.="Position:\t$row[Position]"  ."\n"; 
	    $excelData.="Name:\t$row[LastName], $row[FirstName]" ."\n";

	    $excelData.= textDate($start) ."\t to \t" .textDate($end) ."\n";

		$excelData.= "\n";

		$total = 0;

		$queryPMCount = "SELECT COUNT(*) as count FROM personalmedicalrecord WHERE (StaffIDNumber = '$id' AND (Date >= '$start' AND Date <= '$end'))";
		$resultPMCount = $connection->query($queryPMCount);
		$rowPMCount = $resultPMCount->fetch_assoc();

		if ($rowPMCount['count'] > 0) {
			$queryPM = "SELECT * FROM personalmedicalrecord WHERE (StaffIDNumber = '$id' AND (Date >= '$start' AND Date <= '$end'))";
			$resultPM = $connection->query($queryPM);
			$excelData.="Student info Summary\n";
		    $excelData.="ID\tFullname\tCourse\\Strand\tAge\tSex\tContact No.\tDate Recorded\n";
		    while($rowPM = $resultPM->fetch_assoc()){
		    	$excelData.="$rowPM[StudentIDNumber]\t$rowPM[Lastname], $rowPM[Firstname] $rowPM[Middlename]\t$rowPM[Course]\t$rowPM[Age]\t$rowPM[Sex]\t$rowPM[StudentContactNumber]\t$rowPM[Date]\n";
		    }
		    $excelData.="\t\t\t\t\t\tTotal Students info recorded: \t" .$rowPMCount['count'] ."\n"; 
		    $total += $rowPMCount['count'];
		}else{
			$excelData.="Total Students info recorded: \t" .$rowPMCount['count'] ."\n";
		}

		$queryConsCount = "SELECT COUNT(*) as count FROM consultationinfo WHERE (PhysicianID = '$id' AND (Dates >= '$start' AND Dates <= '$end'))";
		$resultConsCount = $connection->query($queryConsCount);
		$rowConsCount = $resultConsCount->fetch_assoc();

		if ($rowConsCount['count'] > 0) {
			$queryCons = "SELECT * FROM consultationinfo LEFT JOIN personalmedicalrecord ON consultationinfo.IdNumb = personalmedicalrecord.StudentIDNumber WHERE (PhysicianID = '$id' AND (Dates >= '$start' AND Dates <= '$end'))";
			$resultCons = $connection->query($queryCons);
			$excelData.="Consultations Summary\n";
		    $excelData.="ID\tFullname\tCourse\\Strand\tYear\tSex\tConsultation Date\tConsultation Time\n";
			while($rowCons = $resultCons->fetch_assoc()){
				$excelData.="$rowCons[IdNumb]\t$rowCons[Lastname], $rowCons[Firstname] $rowCons[Middlename]\t$rowCons[Course]\t$rowCons[Year]\t$rowCons[Sex]\t$rowCons[Dates]\t$rowCons[Times]\n";
			}
			
			$rowCons = $resultCons->fetch_assoc();
		    $excelData.="\t\t\t\t\t\tTotal Consultations recorded: \t" .$rowConsCount['count'] ."\n"; 
		    $total += $rowConsCount['count'];
		}else{
			$excelData.="Total Consultations recorded: \t" .$rowConsCount['count'] ."\n"; 
		}

		$queryFUCount = "SELECT COUNT(*) as count FROM followup WHERE (PhysicianID = '$id' AND (Dates >= '$start' AND Dates <= '$end'))";
		$resultFUCount = $connection->query($queryFUCount);
		$rowFUCount = $resultFUCount->fetch_assoc();

		if ($rowFUCount['count'] > 0) {
			$queryFU = "SELECT * FROM followup LEFT JOIN personalmedicalrecord ON followup.IdNumb = personalmedicalrecord.StudentIDNumber WHERE (PhysicianID = '$id' AND (Dates >= '$start' AND Dates <= '$end'))";
			$resultFU = $connection->query($queryFU);
			$excelData.="Follow-ups Summary\n";
		    $excelData.="ID\tFullname\tCourse\\Strand\tYear\tSex\tConsultation Date\tFollow-up Date-Time\n";
			while($rowFU = $resultFU->fetch_assoc()){
				$excelData.="$rowFU[IdNumb]\t$rowFU[Lastname], $rowFU[Firstname] $rowFU[Middlename]\t$rowFU[Course]\t$rowFU[Year]\t$rowFU[Sex]\t$rowFU[cons_date]\t$rowFU[Dates] - $rowFU[fu_time]\n";
			}
		    $excelData.="\t\t\t\t\t\tTotal Follow-Ups recorded: \t" .$rowFUCount['count'] ."\n"; 
		    $total += $rowFUCount['count'];
		}else{
			$excelData.="Total Follow-Ups recorded: \t" .$rowFUCount['count'] ."\n";
		}

		$queryMCCount = "SELECT COUNT(*) as count FROM medicalcertificate WHERE (mc_physician_id = '$id' AND (date_requested >= '$start' AND date_requested <= '$end'))";
		$resultMCCount = $connection->query($queryMCCount);
		$rowMCCount = $resultMCCount->fetch_assoc();

		if ($rowMCCount['count'] > 0) {
			$queryMC = "SELECT * FROM medicalcertificate LEFT JOIN personalmedicalrecord ON medicalcertificate.student_id = personalmedicalrecord.StudentIDNumber WHERE (mc_physician_id = '$id' AND (date_requested >= '$start' AND date_requested <= '$end'))";
			$resultMC = $connection->query($queryMC);
			$excelData.="Medical Certificates Summary\n";
		    $excelData.="ID\tFullname\tCourse\\Strand\tYear\tSex\tDate Requested\n";
			while($rowMC = $resultMC->fetch_assoc()){
				$excelData.="$rowMC[student_id]\t$rowMC[Lastname], $rowMC[Firstname] $rowMC[Middlename]\t$rowMC[Course]\t$rowMC[Year]\t$rowMC[Sex]\t$rowMC[date_requested]\n";
			}
			
		    $excelData.="\t\t\t\t\t\tTotal Medical Certificates recorded\t" .$rowMCCount['count'] ."\n"; 
		    $total += $rowMCCount['count'];
		}else{
			$excelData.="Total Medical Certificates recorded\t" .$rowMCCount['count'] ."\n"; 
		}

		$excelData.="Total: \t" .$total ."\n\n"; 

	}
		    
}else{
	$Message = "No record found.";
	$Error = "1";
}


header("Content-Type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=\"$fileName\"");

// Render excel data 
echo $excelData; 

exit;
?>
