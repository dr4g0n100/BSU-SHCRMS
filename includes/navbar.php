<nav class="navbar navbar-expand navbar-light bg-light">
    <li class="nav-item userFN">
      <span id="userFullname"><b><?php echo ucwords($_SESSION['homePosDisp']) . " ";
      $tempNAME = strtolower($_SESSION['fullname']);
      echo ucwords($tempNAME); 
      ?></b></span>
    </li>
      <div class="mr-auto"></div>
      <ul class="navbar-nav">
        <li class="nav-item mx-1 ">
          <a class="nav-link" href="indexHomepage.php">Home</a>
        </li>
         <li class="nav-item dropdown mx-1">
          <a class="nav-link dropdown-toggle" href="indexHomepage.php" id="navbarDropdown" role="button" data-display="static" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Census
          </a>
          <div class="dropdown-menu dropdown-menu-lg-right" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="studentSummary.php">Student Summary</a>
            <a class="dropdown-item" href="indexcensus.php">Dashboard</a>
          </div>
        </li>
        
        <li class="nav-item mx-1">
          <a class="nav-link admin-nav" href="indexStaff.php?type=checkRecords">User List</a>
        </li>
        <li class="nav-item dropdown mx-1">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-display="static" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Records
          </a>
          <div class="dropdown-menu dropdown-menu-lg-right" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="indexStudent.php?type=checkRecords">Student Records</a>
            <a class="dropdown-item" href="indexCons.php?type=checkRecords">Consultation Records</a>
            <!-- <a class="dropdown-item" href="indexFU.php?type=checkRecords">Follow-up Consultation</a> -->
            <a class="dropdown-item" href="indexMC.php?type=checkMC">Medical Certificate</a>
          </div>
        </li>
        <li class="nav-item dropdown mx-1 admin-nav">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-display="static" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Archived Records
          </a>
          <div class="dropdown-menu dropdown-menu-lg-right" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="indexStudent.php?type=checkArchivedStudent">Archived Student Records</a>
            <a class="dropdown-item" href="indexCons.php?type=checkArchivedConsultation">Archived Consultation Records</a>
            <a class="dropdown-item" href="indexFU.php?type=checkArchivedFollowUp">Archived Follow-up Records</a>
            <a class="dropdown-item" href="indexMC.php?type=checkArchivedMC">Archived Medical Certificates</a>
            <a class="dropdown-item" href="indexStaff.php?type=checkArchivedStaff">Archived Staff Accounts</a>
            <a class="dropdown-item" href="logs.php?type=checkArchivedLogs">Archived System Logs</a>
          </div>
        </li>
        <li class="nav-item dropdown mx-1 admin-nav">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-display="static" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Maintenance
          </a>
          <div class="dropdown-menu dropdown-menu-lg-right" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="logs.php?type=checkRecords" onclick="userCheckLogs()">Logs</a>
            <a class="dropdown-item" href="degreeList.php">Degree List</a>
            <a class="dropdown-item" href="vaccineList.php">Vaccine List</a>
            <a class="dropdown-item" href="backup.php">Backup</a>
            <a class="dropdown-item" href="restore.php">Restore</a>
          </div>
        </li>
        <li class="nav-item mx-1">
          <a class="nav-link" href="help.php?type=<?php echo $_SESSION['accesslevel']; ?>">Help</a>
        </li>
        <li class="nav-item mx-1">
          <a class="nav-link" href="#" onclick="logout()">Logout</a>
        </li>

      </ul>
</nav> 