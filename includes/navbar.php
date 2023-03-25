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
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-tools" viewBox="0 0 16 16">
              <path d="M1 0 0 1l2.2 3.081a1 1 0 0 0 .815.419h.07a1 1 0 0 1 .708.293l2.675 2.675-2.617 2.654A3.003 3.003 0 0 0 0 13a3 3 0 1 0 5.878-.851l2.654-2.617.968.968-.305.914a1 1 0 0 0 .242 1.023l3.27 3.27a.997.997 0 0 0 1.414 0l1.586-1.586a.997.997 0 0 0 0-1.414l-3.27-3.27a1 1 0 0 0-1.023-.242L10.5 9.5l-.96-.96 2.68-2.643A3.005 3.005 0 0 0 16 3c0-.269-.035-.53-.102-.777l-2.14 2.141L12 4l-.364-1.757L13.777.102a3 3 0 0 0-3.675 3.68L7.462 6.46 4.793 3.793a1 1 0 0 1-.293-.707v-.071a1 1 0 0 0-.419-.814L1 0Zm9.646 10.646a.5.5 0 0 1 .708 0l2.914 2.915a.5.5 0 0 1-.707.707l-2.915-2.914a.5.5 0 0 1 0-.708ZM3 11l.471.242.529.026.287.445.445.287.026.529L5 13l-.242.471-.026.529-.445.287-.287.445-.529.026L3 15l-.471-.242L2 14.732l-.287-.445L1.268 14l-.026-.529L1 13l.242-.471.026-.529.445-.287.287-.445.529-.026L3 11Z"/>
            </svg>
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
          <a class="nav-link" href="help.php?type=<?php echo $_SESSION['accesslevel']; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle" viewBox="0 0 16 16">
              <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
              <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
            </svg>
          </a>
        </li>
        <li class="nav-item mx-1">
          <a class="nav-link" href="#" onclick="logout()">Logout</a>
        </li>

      </ul>
</nav> 