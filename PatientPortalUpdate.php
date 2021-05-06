<?php include('PatientServer.php') ?>

<!------------- HTML ------------->
<!DOCTYPE html>
<head>
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Update Information</title>
   <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-green.css">
   <link href="css/patientPortal.css" rel='stylesheet'>
   <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>

<body>
   <!--Navbar-->
   <div class="w3-bar w3-theme w3-left-align w3-large">
      <a href="PatientPortalIndex.php" class="w3-bar-item w3-button w3-hide-small w3-hover-white">HOME</a>
      <a href="PatientPortalServiceSelect.php" class="w3-bar-item w3-button w3-hide-small w3-hover-white">Service Selection</a>
      <a href="PatientPortalUpdate.php" class="w3-bar-item w3-button w3-hide-small w3-hover-white">Update Personal Information</a>
      <a href="PatientPortalRecords.php" class="w3-bar-item w3-button w3-hide-small w3-hover-white">View Records</a>
   </div>

   <!--Header-->
   <div class="header w3-theme-d4">
      <h1><b>My Patient Health Portal</b></h1>
   </div>

   <!--Form Errors-->
   <div class="home-middle">
      <b><?php include('errors.php'); ?></b>
   </div>
   <div style="width: 100%">
      <div class="leftsplit">
        <div style="margin-left:10px">
         <!--Change Information Buttons-->
         <button class="w3-button w3-round w3 w3-green w3-ripple"
         onclick="document.getElementById('changeNameForm').style.display='block'" style="width:auto;"
         id="btnChangeName" type="submit" name="changeName">Change Name</button>
         <br><br>

         <button class="w3-button w3-round w3 w3-green w3-ripple" onclick="document.getElementById('changeDOBForm').style.display='block'" style="width:auto;" id="btnChangeDOB" type="submit" name="changeDOB">Change DOB</button>
         <br><br>

         <button class="w3-button w3-round w3 w3-green w3-ripple"
         onclick="document.getElementById('changeSSNForm').style.display='block'" style="width:auto;"
         id="btnChangeSSN" type="submit" name="changeSSN">Change SSN</button>
         <br><br>

         <button class="w3-button w3-round w3 w3-green w3-ripple"
         onclick="document.getElementById('changeGenForm').style.display='block'" style="width:auto;"
         id="btnChangeGen" type="submit" name="changeGen">Change Gender</button>
         <br><br>

         <button class="w3-button w3-round w3 w3-green w3-ripple"
         onclick="document.getElementById('changeAddForm').style.display='block'" style="width:auto;"
         id="btnChangeAdd" type="submit" name="changeAdd">Change Address</button>
         <br><br>

         <button class="w3-button w3-round w3 w3-green w3-ripple"
         onclick="document.getElementById('changeEmailForm').style.display='block'" style="width:auto;"
         id="btnChangeEmail" type="submit" name="changeEmail">Change Email</button>
         <br><br>

         <button class="w3-button w3-round w3 w3-green w3-ripple"
         onclick="document.getElementById('changePhoneForm').style.display='block'" style="width:auto;"
         id="btnChangePhone" type="submit" name="changePhone">Change Phone</button>
         <br><br>

         <button class="w3-button w3-round w3 w3-green w3-ripple"
         onclick="document.getElementById('changePassForm').style.display='block'" style="width:auto;"
         id="btnChangePass" type="submit" name="changePass">Change Password</button>
         <br>
        </div>
      </div>

      <?php
      $pid = mysqli_real_escape_string($patientdb, $_SESSION['pid']);
      $query = "SELECT * FROM PatientData  WHERE PID='$pid';";
      $results = mysqli_query($patientdb, $query);
      $rows = mysqli_fetch_assoc($results);
       ?>

      <div class="rightsplit">
        <div style="float: left">
         <label><b>Name: </b><label>
         <?php echo $rows["Fname"]." ".$rows["Lname"]; ?> <br><br>
         <label><b>Date of Birth: </b><label>
         <?php echo $rows["DOB"]; ?> <br><br>
         <label><b>SSN: </b><label>
         <?php
         $temp_ssn = $rows['SSN'];
         $hidden_ssn = '***-**-'.substr($temp_ssn,-4);
         echo $hidden_ssn; ?> <br><br>
         <label><b>Gender: </b><label>
         <?php echo $rows["Gender"]; ?> <br><br>
         <label><b>Address: </b><label>
         <?php echo $rows["Address"]; ?> <br><br>
         <label><b>Email: </b><label>
         <?php echo $rows["Email"]; ?> <br><br>
         <label><b>Phone: </b><label>
         <?php
         $temp_num = $rows['Phone'];
         echo substr($temp_num,0,3)."-".substr($temp_num,3,3)."-".substr($temp_num,6) ;?>
        </div>
      </div>
   </div>

   <!--Change Name Form-->
   <div id="changeNameForm" class="modal">
      <form class="modal-content" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
         <h2>Change Name</h2>

         <!--New Name Input-->
         <div class="input-group">
            <input type="text" class="form-control" name="name_new_1" placeholder="First Name" required>
         </div>

         <!--New Name Input 2-->
         <div class="input-group">
            <input type="text" class="form-control" name="name_new_2" placeholder="Last Name" required>
         </div>

         <!--Submit Button-->
         <div class="input-group">
            <button type="submit" class="w3-button w3-round w3-green w3-ripple" name="update_patient_name">Submit</button>
         </div>

         <!--Cancel Button-->
         <div class="input-group">
            <button type="button" onclick="document.getElementById('changeNameForm').style.display='none'" class="w3-button w3-round w3-red">Cancel</button>
         </div>
      </form>
   </div>

   <!--Change DOB Form-->
   <div id="changeDOBForm" class="modal">
      <form class="modal-content" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
         <h2>Change DOB</h2>

         <!--New DOB Input-->
         <div class="input-group">
            <input type="date" class="form-control" name="DOB_new" placeholder="New DOB" required>
         </div>


         <!--Submit Button-->
         <div class="input-group">
            <button type="submit" class="w3-button w3-round w3-green w3-ripple" name="update_patient_DOB">Submit</button>
         </div>

         <!--Cancel Button-->
         <div class="input-group">
            <button type="button" onclick="document.getElementById('changeDOBForm').style.display='none'" class="w3-button w3-round w3-red">Cancel</button>
         </div>
      </form>
   </div>

   <!--Change SSN Form-->
   <div id="changeSSNForm" class="modal">
      <form class="modal-content" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
         <h2>Change SSN</h2>

         <!--New SSN Input-->
         <div class="input-group">
            <input type="number" class="form-control" name="SSN_new" placeholder="xxxxxxxxx" min="100000000" max="999999999" required>
         </div>


         <!--Submit Button-->
         <div class="input-group">
            <button type="submit" class="w3-button w3-round w3-green w3-ripple" name="update_patient_SSN">Submit</button>
         </div>

         <!--Cancel Button-->
         <div class="input-group">
            <button type="button" onclick="document.getElementById('changeSSNForm').style.display='none'" class="w3-button w3-round w3-red">Cancel</button>
         </div>
      </form>
   </div>

   <!--Change Gender Form-->
   <div id="changeGenForm" class="modal">
      <form class="modal-content" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
         <h2>Change Gender</h2>

         <!--New Name Input-->
         <div class="input-group">
            <input type="text" class="form-control" name="gen_new" placeholder="New Gender" required>
         </div>


         <!--Submit Button-->
         <div class="input-group">
            <button type="submit" class="w3-button w3-round w3-green w3-ripple" name="update_patient_gen">Submit</button>
         </div>

         <!--Cancel Button-->
         <div class="input-group">
            <button type="button" onclick="document.getElementById('changeGenForm').style.display='none'" class="w3-button w3-round w3-red">Cancel</button>
         </div>
      </form>
   </div>

   <!--Change Address Form-->
   <div id="changeAddForm" class="modal">
      <form class="modal-content" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
         <h2>Change Address</h2>

         <!--New Address Input-->
         <div class="input-group">
            <input type="text" class="form-control" name="add_new" placeholder="New Name" required>
         </div>


         <!--Submit Button-->
         <div class="input-group">
            <button type="submit" class="w3-button w3-round w3-green w3-ripple" name="update_patient_add">Submit</button>
         </div>

         <!--Cancel Button-->
         <div class="input-group">
            <button type="button" onclick="document.getElementById('changeAddForm').style.display='none'" class="w3-button w3-round w3-red">Cancel</button>
         </div>
      </form>
   </div>


   <!--Change Email Form-->
   <div id="changeEmailForm" class="modal">
      <form class="modal-content" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
         <h2>Change Email</h2>

         <!--New Name Input-->
         <div class="input-group">
            <input type="email" class="form-control" name="email_new" placeholder="New Email" required>
         </div>


         <!--Submit Button-->
         <div class="input-group">
            <button type="submit" class="w3-button w3-round w3-green w3-ripple" name="update_patient_email">Submit</button>
         </div>

         <!--Cancel Button-->
         <div class="input-group">
            <button type="button" onclick="document.getElementById('changeEmailForm').style.display='none'" class="w3-button w3-round w3-red">Cancel</button>
         </div>
      </form>
   </div>

   <!--Change Phone Form-->
   <div id="changePhoneForm" class="modal">
      <form class="modal-content" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
         <h2>Change Phone</h2>

         <!--New Phone Input-->
         <div class="input-group">
            <input type="text" class="form-control" name="phone_new" placeholder="New Phone" required>
         </div>


         <!--Submit Button-->
         <div class="input-group">
            <button type="submit" class="w3-button w3-round w3-green w3-ripple" name="update_patient_phone">Submit</button>
         </div>

         <!--Cancel Button-->
         <div class="input-group">
            <button type="button" onclick="document.getElementById('changePhoneForm').style.display='none'" class="w3-button w3-round w3-red">Cancel</button>
         </div>
      </form>
   </div>

   <!--Change Password Form-->
   <div id="changePassForm" class="modal">
      <form class="modal-content" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
         <h2>Change Password</h2>

         <!--Old Password Input-->
         <div class="input-group">
            <input type="password" class="form-control" name="password_old" placeholder="Old Password" required autofocus>
         </div>

         <!--New Password Input 1-->
         <div class="input-group">
            <input type="password" class="form-control" name="password_1" placeholder="New Password" required>
         </div>

         <!--New Password Input 2-->
         <div class="input-group">
            <input type="password" class="form-control" name="password_2" placeholder="New Password" required>
         </div>

         <!--Submit Button-->
         <div class="input-group">
            <button type="submit" class="w3-button w3-round w3-green w3-ripple" name="update_patient_password">Submit</button>
         </div>

         <!--Cancel Button-->
         <div class="input-group">
            <button type="button" onclick="document.getElementById('changePassForm').style.display='none'" class="w3-button w3-round w3-red">Cancel</button>
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
