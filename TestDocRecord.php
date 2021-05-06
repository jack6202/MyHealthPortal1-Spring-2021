<?php include('server.php') ?>

<!------------- HTML ------------->
<!DOCTYPE html>
<head>
  <title>Doctor Portal Diagnosis</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-blue.css">
  <link href="css/doctorPortal.css" rel='stylesheet'>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>

<body>
  <!--Navbar-->
  <div class="w3-bar w3-theme w3-large">
    <a href="DoctorPortalIndex.php" class="w3-bar-item w3-button w3-hide-small w3-hover-white">BACK</a>
    <div class="nav-right">
      <a href="DoctorPortalIndex.php?logout='1'" class="w3-bar-item w3-button w3-hide-small w3-hover-white">LOGOUT</a>
    </div>
  </div>

  <!--Header-->
  <div class="header w3-theme-d4">
    <h1><b>Reports</b></h1>
  </div>

  <div style="width: 100%">

  <!--Filters-->
  <div class="leftsplit">
    <div style="margin-left:10px">
      <h4><b>Filter By</b></h4>
      <button class="w3-button w3-round w3-blue w3-ripple" href="TestDocRecord.php?filter=diagnosis" style="margin-top: 5px" name="diagnosis_filter">Diagnosis</button><br>
      <button class="w3-button w3-round w3-blue w3-ripple" href="TestDocRecord.php?filter=service" style="margin-top: 5px" name="service_filter">Service</button><br>
      <button class="w3-button w3-round w3-blue w3-ripple" href="TestDocRecord.php?filter=prescription" style="margin-top: 5px" name="prescription_filter">Prescription</button>
    </div>

    <div style="margin-left:10px">
      <h4><b>Sort By</b></h4>
      <button class="w3-button w3-round w3-blue w3-ripple" href="TestDocRecord.php?sort=age" style="margin-top: 5px" name="age_filter">Patient Age</button><br>
      <button class="w3-button w3-round w3-blue w3-ripple" href="TestDocRecord.php?sort=gender" style="margin-top: 5px" name="gender_filter">Patient Gender</button><br>
      <button class="w3-button w3-round w3-blue w3-ripple" href="TestDocRecord.php?sort=date" style="margin-top: 5px" name="date_filter">Date</button>
    </div>
  </div>

  <!--Report Section-->
  <div class="rightsplit">
    <?php
      $html = "";

      // get list of patients that have selected this doctor
      $ssddquery = "SELECT * FROM SSDD WHERE DoctorID='$_SESSION['did']'";
      $ssddresult = mysqli_query($db3, $ssddquery);

      // loop through patients
      while($pidlist = mysqli_fetch_assoc($ssddresult)) {
        $recordquery = "SELECT * FROM PatientRecords WHERE PID='$pidlist['PatientID']' AND NOT Type='Charge'";

        // filters
        switch($_GET['filter']) {
          case 'diagnosis':
            $query .= " AND Type='Diagnosis';";
            break;
          case 'service':
            $query .= " AND Type='Service';";
            break;
          case 'prescription':
            $query .= " AND Type='Prescription';";
            break;
          default:
            $query .= ";";
            break;
        }

        // parse the filtered records
        $recordresult = mysqli_query($db1, $recordquery);
      }

      // loop through records
      while($row = mysqli_fetch_assoc($result)) {
        $html .= "<table class='w3-hoverable w3-border'><tr>";

        // generate headers
        $html .= "<th>Record Type</th>";
        $html .= "<th>Date</th>";
        $html .= "<th>Patient Name</th>";
        $html .= "<th>Patient Age</th>";
        $html .= "<th>Patient Gender</th>";

        // change table structure for record type
        switch($rtype) {
          case "Diagnosis":
            $html .= "<th>Description</th>";
            $html .= "<th>Codes</th>";
            $html .= "</tr>";

            $html .= "<tr>";
            // type
            $html .= "<td>Diagnosis</td>";
            // date
            $html .= "<td>".$date."</td>";
            // name
            $html .= "<td>".$name."</td>";
            // age
            $html .= "<td>".$age."</td>";
            // gender
            $html .= "<td>".$gender."</td>";
            // description
            $html .= "<td>".$desc."</td>";
            // codes
            $html .= "<td>".$codes."</td>";
            $html .= "</tr>";
            break;
          case "Prescription":
            $html .= "<th>Prescription</th>";
            $html .= "<th>Manufacturer</th>";
            $html .= "<th>Price Per Unit</th>";
            $html .= "</tr>";

            $html .= "<tr>";
            // type
            $html .= "<td>Prescription</td>";
            // date
            $html .= "<td>".$date."</td>";
            // name
            $html .= "<td>".$name."</td>";
            // age
            $html .= "<td>".$age."</td>";
            // gender
            $html .= "<td>".$gender."</td>";
            // prescription
            $html .= "<td>".$prescription."</td>";
            // manufacturer
            $html .= "<td>".$manufacturer."</td>";
            // price per unit
            $html .= "<td>".$ppu."</td>";
            $html .= "</tr>";
            break;
          case "Service":
            $html .= "<th>Service Type</th>";
            $html .= "<th>Cost</th>";
            $html .= "</tr>";

            $html .= "<tr>";
            // type
            $html .= "<td>Service</td>";
            // date
            $html .= "<td>".$date."</td>";
            // name
            $html .= "<td>".$name."</td>";
            // age
            $html .= "<td>".$age."</td>";
            // gender
            $html .= "<td>".$gender."</td>";
            // service type
            $html .= "<td>".$service."</td>";
            // cost
            $html .= "<td>".$gender."</td>";
            $html .= "</tr>";
            break;
          default:
            $html .= "</tr>";
            break;
        }
        $html .= "</table>";
      }
      echo $html;
    ?>
  </div>

  </div>

</body>
</html>