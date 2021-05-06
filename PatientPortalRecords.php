<?php include('PatientServer.php') ?>

<!DOCTYPE html>
<html>

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
      <a href="PatientPortalServiceSelect.php" class="w3-bar-item w3-button w3-hide-small w3-hover-white">Service Selection</a>
      <a href="PatientPortalUpdate.php" class="w3-bar-item w3-button w3-hide-small w3-hover-white">Update Personal Information</a>
      <a href="PatientPortalRecords.php" class="w3-bar-item w3-button w3-hide-small w3-hover-white">View Records</a>
      <div class="nav-right">
         <a href="PatientPortalIndex.php?logout='1'" class="w3-bar-item w3-button w3-hide-small w3-hover-white">LOGOUT</a>
      </div>
   </div>

   <!--Header-->
   <div class="header w3-theme-d4">
      <h1><b>Reports</b></h1>
   </div>

   <!--Charges Display-->
   <div class="home-middle">
      <?php
      $pid = mysqli_real_escape_string($patientdb, $_SESSION['pid']);

      if (count($errors) == 0) {
         $query = "SELECT SUM(Price) AS total_charge FROM PatientRecords WHERE PID = $pid;";
         $result = mysqli_query($patientdb, $query);
         $assoc = mysqli_fetch_assoc($result);
         $charges = $assoc['total_charge'];
      }

      if ($_SESSION['pid'] != null) {
         $name = $_SESSION['name'];
         echo "<p><b>Total charges for ".$name.": </b>$".$charges."</p>";
      }
      ?>
   </div>

   <div style="width: 100%">

      <!--Pre-defined $queries-->
      <?php
      $query = "SELECT * FROM PatientRecords WHERE PID='$pid';";
        if(array_key_exists('diagnosis_filter', $_POST)) {
            diagnosis_filter();
        }
        else if(array_key_exists('service_filter', $_POST)) {
            service_filter();
        }
        else if(array_key_exists('prescription_filter', $_POST)) {
            prescription_filter();
        }
        else if(array_key_exists('charges_filter', $_POST)) {
            charges_filter();
        }
        else if(array_key_exists('clear_filter', $_POST)) {
            clear_filter();
        }
        else if(array_key_exists('doctor_filter', $_POST)) {
            doctor_filter();
        }
        else if(array_key_exists('date_filter', $_POST)) {
            date_filter();
        }
        else if(array_key_exists('attribute_filter', $_POST)) {
            attribute_filter();
        }
        function diagnosis_filter() {
           $pid = $GLOBALS['pid'];
           $GLOBALS['query'] = "SELECT * FROM PatientRecords WHERE Type='Diagnosis' AND PID='$pid';";
        }
        function service_filter() {
           $pid = $GLOBALS['pid'];
           $GLOBALS['query'] = "SELECT * FROM PatientRecords WHERE Type='Service' AND PID='$pid';";
        }
        function prescription_filter() {
           $pid = $GLOBALS['pid'];
           $GLOBALS['query'] = "SELECT * FROM PatientRecords WHERE Type='Prescription' AND PID='$pid';";
        }
        function charges_filter() {
           $pid = $GLOBALS['pid'];
           $GLOBALS['query'] = "SELECT * FROM PatientRecords WHERE Type='Charge' AND PID='$pid';";
        }
        function clear_filter() {
           $pid = $GLOBALS['pid'];
           $GLOBALS['query'] = "SELECT * FROM PatientRecords WHERE PID='$pid';";
        }
        function doctor_filter() {
           $pid = $GLOBALS['pid'];
           $GLOBALS['query'] = "SELECT * FROM PatientRecords WHERE PID='$pid' ORDER BY DID;";
        }
        function date_filter() {
           $pid = $GLOBALS['pid'];
           $GLOBALS['query'] = "SELECT * FROM PatientRecords WHERE PID='$pid' ORDER BY Date;";
        }
        function attribute_filter() {
           $pid = $GLOBALS['pid'];
           $date = $_POST['DoB'];
           $type = $_POST['docselect'];
           if($type=='Any'){
             $GLOBALS['query'] = "SELECT * FROM PatientRecords WHERE PID='$pid' AND Date='$date';";
          } else {
             $GLOBALS['query'] = "SELECT * FROM PatientRecords WHERE PID='$pid' AND Date='$date' AND Type='$type';";
          };

        }

    ?>

      <!--Filters-->
      <div class="leftsplit">
         <div style="margin-left:40px">
            <h4><b>Filter By</b></h4>
            <form method="post">
               <input type="Submit" class="w3-button w3-round w3-green w3-ripple" style="margin-top: 5px" name="diagnosis_filter" value="Diagnosis"></button><br>
               <input type="submit" class="w3-button w3-round w3-green w3-ripple" style="margin-top: 5px" name="service_filter" value="Service"></button><br>
               <input type="submit" class="w3-button w3-round w3-green w3-ripple" style="margin-top: 5px" name="prescription_filter" value="Prescriptions"></button><br>
               <input type="submit" class="w3-button w3-round w3-green w3-ripple" style="margin-top: 5px" name="charges_filter" value="Charges"></button>
            </form>
         </div>

         <div style="margin-left:40px">
            <h4><b>Sort By</b></h4>
            <form method="post">
               <input type="Submit" class="w3-button w3-round w3-green w3-ripple" style="margin-top: 5px" name="doctor_filter" value="Doctor"></button><br>
               <input type="submit" class="w3-button w3-round w3-green w3-ripple" style="margin-top: 5px" name="date_filter" value="Date"></button><br>
            </form>
         </div>

         <div style="margin-left:40px">
            <h4><b>Find By Attribute</b></h4>
            <form method="post">
               <div class="input-group">
                  <label><b>Doctor</b><label>
                  <select id="docselect" name="docselect" class="form-control">
                    <?php
                        echo "<option value='Diagnosis'>Diagnosis</option>";
                        echo "<option value='Service'>Service</option>";
                        echo "<option value='Prescription'>Prescriptions</option>";
                        echo "<option value='Charge'>Charges</option>";
                        echo "<option value='Any'>Any</option>";
                    ?>
                 </select><br>
              	  <label><b>Date of Birth</b><label>
            	  <input type="date" name="DoB" value="Date of Birth"><br>
                 <input type="submit" class="w3-button w3-round w3-green w3-ripple" style="margin-top: 5px" name="attribute_filter" value="Search"></button><br>
               </div>
            </form>
         </div>

         <div style="margin-left:40px">
            <form method="post">
               <input type="submit" class="w3-button w3-round w3-red w3-ripple" style="margin-top: 5px" name="clear_filter" value="Clear Filters"></button>
            </form>
         </div>
      </div>

      <!--Report Section-->
      <div class="rightsplit">
         <?php
         $html = "";
         $rtype = "Diagnosis";

         //$query = "SELECT * FROM PatientRecords";
         $results = mysqli_query($patientdb, $GLOBALS['query']);

         $html .= "<table class='w3-hoverable'><tr>";
         // generate headers
         $html .= "<th>Record Type</th>";
         $html .= "<th>Date</th>";
         $html .= "<th>Doctor</th>";
         $html .= "<th>Cost</th>";
         $html .= "<th>Description</th>";

         // Loop through $query
         while($rows = mysqli_fetch_assoc($results)) {
            $html .= "<tr>";
            // type
            $html .= "<td> ".$rows['Type']." </td>";
            // date
            $html .= "<td> ".$rows['Date']." </td>";      
            //Docotor
            $did = $rows['DID'];
            $temp_query = "SELECT Lname,Fname FROM DoctorData WHERE DID='$did';";
            $temp_results = mysqli_query($doctordb, $temp_query);
            $temp_rows = mysqli_fetch_assoc($temp_results);

            $html .= "<td> ".$temp_rows['Fname']." ".$temp_rows['Lname']." </td>";
            // Price
            $html .= "<td> $".$rows['Price']."</td>";
            // description
            $html .= "<td> ".$rows['Description']." </td>";


         }
         $html .= "</table>";
         echo $html;
         ?>
      </div>

   </div>

</body>

</html>
