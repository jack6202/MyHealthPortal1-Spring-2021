<?php include('PatientServer.php') ?>

<!------------- HTML ------------->
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Welcome to My Health Portal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-green.css">
  <link href="css/patientPortal.css" rel='stylesheet'>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>

<body>
  <!--Navbar-->
  <div class="w3-bar w3-theme w3-large">
    <a href="PatientPortalIndex.php" class="w3-bar-item w3-button w3-hide-small w3-hover-white">HOME</a>
    <a class="w3-bar-item w3-hide-small w3-white">Service Selection</a>
    <a href="PatientPortalUpdate.php" class="w3-bar-item w3-button w3-hide-small w3-hover-white">Update Personal Information</a>
    <a href="PatientPortalRecords.php" class="w3-bar-item w3-button w3-hide-small w3-hover-white">View Records</a>
    <div class="nav-right">
      <a href="PatientPortalIndex.php?logout='1'" class="w3-bar-item w3-button w3-hide-small w3-hover-white">LOGOUT</a>
    </div>
  </div>

  <!--Display Current Selections-->
  <div class="center">
    <h2><b>Current Service Selections</b></h2>

    <!--Get Current Info-->
    <?php
      $pid = $_SESSION['pid'];
      $result = $ssdb->query("SELECT InsuranceID, PharmacyID, DoctorID, LabID, HospitalID FROM SSDO JOIN SSDD ON SSDO.PatientID = SSDD.PatientID
                                 JOIN SSDH ON SSDD.PatientID = SSDH.PatientID
                                 JOIN SSDL ON SSDH.PatientID = SSDL.PatientID
                                 WHERE SSDO.PatientID = '$pid';");
      $row = $result->fetch_assoc();
      if($row) {

        // save selections for display
        $sdoc = $row['DoctorID'];
        $sinsur = $row['InsuranceID'];
        $spharm = $row['PharmacyID'];
        $shosp = $row['HospitalID'];
        $slab = $row['LabID'];
        $selected = true;
      }
      else {

        // case where selections are missing/haven't been made
        $selected = false;
      }
    ?>

    <!--Display Current Insurance-->
    <div class="ssdb-display">
      <h3>Insurance</h3>
      <?php
        if ($selected) {
          $result = $patientdb->query("SELECT InsurID, CompanyName FROM Insurance WHERE InsurID = '$sinsur';");
          $row = $result->fetch_assoc();
          echo $row['InsurID'] . " " . $row['CompanyName'];
        }
        else {
          echo "No Insurance Selected";
        }
      ?>
    </div>

    <!--Display Current Pharmacy-->
    <div class="ssdb-display">
      <h3>Pharmacy</h3>
      <?php
        if ($selected) {
          $result = $ssdb->query("SELECT PharmID, PharmacyName FROM Pharmacies WHERE PharmID = '$spharm';");
          $row = $result->fetch_assoc();
          echo $row['PharmID'] . " " . $row['PharmacyName'];
        }
        else {
          echo "No Pharmacy Selected";
        }
      ?>
    </div>

    <!--Display Current Doctor-->
    <div class="ssdb-display">
      <h3>Doctor</h3>
      <?php
        if ($selected) {
          $result = $doctordb->query("SELECT DID, Fname, Lname FROM DoctorData WHERE DID = '$sdoc';");
          $row = $result->fetch_assoc();
          $name = $row['Fname'] . " " . $row['Lname'];
          echo $row['DID'] . " " . $name;
        }
        else {
          echo "No Doctor Selected";
        }
      ?>
    </div>

    <!--Display Current Hospital-->
    <div class="ssdb-display">
      <h3>Hospital</h3>
      <?php
        if ($selected) {
          $result = $ssdb->query("SELECT HID, HospitalName FROM Hospitals WHERE HID = '$shosp';");
          $row = $result->fetch_assoc();
          echo $row['HID'] . " " . $row['HospitalName'];
        }
        else {
          echo "No Hospital Selected";
        }
      ?>
    </div>

    <!--Display Current Lab-->
    <div class="ssdb-display">
      <h3>Lab</h3>
      <?php
        if ($selected) {
          $result = $ssdb->query("SELECT LID, LabName FROM Labs WHERE LID = '$slab';");
          $row = $result->fetch_assoc();
          echo $row['LID'] . " " . $row['LabName'];
        }
        else {
          echo "No Lab Selected";
        }
      ?>
    </div>
  </div>

  <!--Edit Selections Button-->
  <div class="home-middle">
    <button class="w3-button w3-xlarge w3-round w3-green w3-ripple"
    onclick="document.getElementById('ssdbForm').style.display='block'" style="width:auto;"
    id="btnEditssdb" type="submit" name="editssdb">Edit Selections
    </button>
  </div>

  <!--Edit Selections Form-->
  <div id="ssdbForm" class="modal">
    <form class="modal-content" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">

      <!--Insurance Selection-->
      <div class="input-group">
        <label><b>Select Insurance</b></label><br>
        <select id="insurselect" name="insurselect" class="form-control">
          <?php
            if($result = $patientdb->query("SELECT * FROM Insurance;")){
              while($row = $result->fetch_assoc()) {
                echo "<option value=$row[InsurID]>$row[CompanyName]</option>";
              }
            } else echo "<option>error</option>";

            $result->free();
          ?>
        </select>
      </div>

      <!--Pharmacy Selection-->
      <div class="input-group">
        <label><b>Select Pharmacy</b></label><br>
        <select id="pharmselect" name="pharmselect" class="form-control">
          <?php
            if($result = $ssdb->query("SELECT * FROM Pharmacies;")){
              while($row = $result->fetch_assoc()) {
                echo "<option value=$row[PharmID]>$row[PharmacyName]</option>";
              }
            } else echo "<option>error</option>";

            $result->free();
          ?>
        </select>
      </div>

      <!--Doctor Selection-->
      <div class="input-group">
        <label><b>Select Doctor</b></label><br>
        <select id="docselect" name="docselect" class="form-control">
          <?php
            if($result = $doctordb->query("SELECT * FROM DoctorData;")){
              while($row = $result->fetch_assoc()) {
                $name = $row['Fname'] . " " . $row['Lname'];
                echo "<option value=$row[DID]>$name</option>";
              }
            } else echo "<option>error</option>";

            $result->free();
          ?>
        </select>
      </div>

      <!--Hospital Selection-->
      <div class="input-group">
        <label><b>Select Hospital</b></label><br>
        <select id="hospselect" name="hospselect" class="form-control">
          <?php
            if($result = $ssdb->query("SELECT HID, HospitalName FROM Hospitals;")){
              while($row = $result->fetch_assoc()) {
                echo "<option value=$row[HID]>$row[HospitalName]</option>";
              }
            } else echo "<option>error</option>";

            $result->free();
          ?>
        </select>
      </div>

      <!--Lab Selection-->
      <div class="input-group">
        <label><b>Select Lab</b></label><br>
        <select id="labselect" name="labselect" class="form-control">
          <?php
            if($result = $ssdb->query("SELECT LID, LabName FROM Labs;")){
              while($row = $result->fetch_assoc()) {
                echo "<option value=$row[LID]>$row[LabName]</option>";
              }
            } else echo "<option>error</option>";

            $result->free();
          ?>
        </select>
      </div>

      <!--Submit Button-->
  	  <div class="input-group">
  	    <button type="submit" class="w3-button w3-round w3-green w3-ripple" name="set_ssdb">Set Selections</button>
	    </div>

	    <!--Cancel Button-->
	    <div class="input-group">
        <button type="button" onclick="document.getElementById('ssdbForm').style.display='none'" class="w3-button w3-round w3-red">Cancel</button>
	    </div>

    </form>
  </div>

  <!--Close Popup Script-->
  <script>
	// get the login and signup popups
	var modals = document.getElementsByClassName('modal');

	// rehide modals when clicked outside
	window.onclick = function(event) {
	  for(i=0; i<modals.length;i++) {
      if (event.target == modals[i]) {
        modals[i].style.display = "none";
      }
	  }
	}
  </script>
</body>

</html>
