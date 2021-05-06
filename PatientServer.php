<?php

error_reporting(0);
include 'includes/dbconn.php';

session_start();


//========== Initializing Variables ==========

//For Regstration
$firstname = "";
$lastname = "";
$email = "";
$ssn = "";
$phone = "";
$dob = "";
$dob_del = "";
$address = "";

$errors = array();

//========== Database Connection ==========
$patientdb = new mysqli($servername, $username, "", "db1", $sqlport, $socket);

// Check connection
if ($patientdb->connect_error) {
   die("Connection failed: " . $patientdb->connect_error);
}

$doctordb = new mysqli($servername, $username, "", "db2", $sqlport, $socket);

// Check connection
if ($doctordb->connect_error) {
   die("Connection failed: " . $doctordb->connect_error);
}

$ssdb = new mysqli($servername, $username, "", "db3", $sqlport, $socket);

// Check connection
if ($ssdb->connect_error) {
   die("Connection failed: " . $ssdb->connect_error);
}

//========== Server Functions ==========

// LOGIN PATIENT
if (isset($_POST['login_patient'])) {
   $email = $patientdb->real_escape_string($_POST['email']);
   $password = $patientdb->real_escape_string($_POST['password']);

   // if there are no errors with input
   if (count($errors) == 0) {

      // query the database for a matching user
      $results = $patientdb->query("SELECT * FROM PatientLogin RIGHT JOIN PatientData ON PatientLogin.PID = PatientData.PID
         WHERE PatientLogin.Email = '$email' and PatientLogin.Pass = '$password';");

         // check if a valid user is found
         if($results->num_rows == 1) {

            // save session details
            $rows = mysqli_fetch_assoc($results);

            $_SESSION['success'] = "You are now logged in";
            $_SESSION['name'] = $rows['Fname'] . " " . $rows['Lname'];
            $_SESSION['pid'] = $rows['PID'];
            $_SESSION['dob'] = $rows['DOB'];
            $_SESSION['ssn'] = $rows['SSN'];
            $_SESSION['sex'] = $rows['Gender'];
            $_SESSION['add'] = $rows['Address'];
            $_SESSION['email'] = $rows['Email'];
            $_SESSION['phone'] = $rows['Phone'];
            $_SESSION['pass'] = $password;

            // navigate to home page
            header('location: PatientPortalIndex.php');
         }
         else {
            array_push($errors, "The email or password was invalid");
         }
      }
   }

   // REGISTER PATIENT
   if (isset($_POST['reg_patient'])) {

      // receive all input values from the form
      $email = $patientdb->real_escape_string($_POST['email']);
      $password_1 = $patientdb->real_escape_string($_POST['password_1']);
      $password_2 = $patientdb->real_escape_string($_POST['password_2']);
      $firstname = $patientdb->real_escape_string($_POST['firstname']);
      $lastname = $patientdb->real_escape_string($_POST['lastname']);
      $ssn = $patientdb->real_escape_string($_POST['ssn']);
      $phone = $patientdb->real_escape_string($_POST['phone']);

      // get gender radio option
      $genradio = $_POST['gender'];
      switch ($genradio) {
         case "M":
         case "F":
         case "O":
         $genoption = $genradio;
         break;
         default:
         $genoption = "";
      }
      $gender = $patientdb->real_escape_string($genoption);

      $dob_del = $patientdb->real_escape_string($_POST['DoB']);
      $dob = str_replace("-", "", $dob_del);

      $address = $patientdb->real_escape_string($_POST['address']);

      // form validation to ensure all fields have data
      if (empty($email)) { array_push($errors, "An Email is required"); }
      if (empty($password_1)) { array_push($errors, "A Password is required"); }
      if (empty($firstname)) { array_push($errors, "A First Name is required"); }
      if (empty($lastname)) { array_push($errors, "A Last Name is required"); }
      if (empty($ssn)) { array_push($errors, "A Social Security Number is required"); }
      if (empty($phone)) { array_push($errors, "A Phone Number is required"); }
      if (empty($dob)) { array_push($errors, "A Date of Birth is required"); }
      if (empty($address)) { array_push($errors, "An Address is required"); }

      // password confirmation
      if ($password_1 != $password_2) { array_push($errors, "The two passwords do not match"); }

      // check that a user does not already exist with the same email
      $result = $patientdb->query("SELECT * FROM PatientLogin WHERE Email='$email' LIMIT 1;");
      $user = mysqli_fetch_assoc($result);

      // check for a conflicting email
      if ($user) {
         array_push($errors, "An account with this email already exists");
      }

      // if there are no errors, add the patient's account information
      if (count($errors) == 0) {
         // combine name
         $name = $firstname . " " . $lastname;

         // insert the login data
         $patientdb->query("INSERT INTO PatientLogin (Email, Pass) VALUES ('$email', '$password_1');");

         // get the id generated for this account
         $pidquery = $patientdb->query("SELECT PID FROM PatientLogin WHERE Email='$email' AND Pass='$password_1';");
         $rows = mysqli_fetch_assoc($pidquery);
         $pid = $rows['PID'];

         // insert the rest of the patient data
         $patientdb->query("INSERT INTO PatientData (PID, Fname, Lname, DOB, SSN, Gender, Address, Email, Phone)
         VALUES ('$pid', '$firstname', '$lastname', '$dob', '$ssn', '$gender', '$address', '$email', '$phone');");

         // set session variables after registration
         $_SESSION['name'] = $name;
         $_SESSION['pid'] = $pid;
         $_SESSION['success'] = "You are now logged in";
         $_SESSION['dob'] = $dob;
         $_SESSION['ssn'] = $ssn;
         $_SESSION['sex'] = $gender;
         $_SESSION['add'] = $address;
         $_SESSION['email'] = $email;
         $_SESSION['phone'] = $phone;
         $_SESSION['pass'] = $password_1;

         // navigate to the home page
         header('location: PatientPortalIndex.php');
      }
   }

   // UPDATE INFORMATION
   if (isset($_POST['update_patient_name'])) {

      //Recieve all input values from the form
      $name_new_1 = $patientdb->real_escape_string($_POST['name_new_1']);
      $name_new_2 = $patientdb->real_escape_string($_POST['name_new_2']);
      $pid = $_SESSION['pid'];

      //Check fields are non-empty
      if (empty($name_new_1)) { array_push($errors, "First name is required"); }
      if (empty($name_new_2)) { array_push($errors, "Last name is required"); }

      // If no input errors then update in database
      if(count($errors) == 0) {
         $patientdb->query("UPDATE PatientData SET Fname='$name_new_1', Lname='$name_new_2' WHERE PID='$pid';");
      }
      header("Refresh:0");
   }

   if (isset($_POST['update_patient_DOB'])) {

      //Recieve all input values from the form
      $DOB_new = $patientdb->real_escape_string($_POST['DOB_new']);
      $pid = $_SESSION['pid'];

      //Check fields are non-empty
      if (empty($DOB_new)) { array_push($errors, "New DOB is required"); }

      // If no input errors then update password in database
      if(count($errors) == 0) {
         $patientdb->query("UPDATE PatientData SET DOB='$DOB_new' WHERE PID='$pid';");
      }

   }

   if (isset($_POST['update_patient_SSN'])) {

      //Recieve all input values from the form
      $SSN_new = $patientdb->real_escape_string($_POST['SSN_new']);
      $pid = $_SESSION['pid'];

      //Check fields are non-empty
      if (empty($SSN_new)) { array_push($errors, "New SSN is required"); }

      // If no input errors then update password in database
      if(count($errors) == 0) {
         $patientdb->query("UPDATE PatientData SET SSN='$SSN_new' WHERE PID='$pid';");
      }

   }

   if (isset($_POST['update_patient_gen'])) {

      //Recieve all input values from the form
      $gen_new = $patientdb->real_escape_string($_POST['gen_new']);
      $pid = $_SESSION['pid'];

      //Check fields are non-empty
      if (empty($gen_new)) { array_push($errors, "New gender is required"); }

      // If no input errors then update password in database
      if(count($errors) == 0) {
         $patientdb->query("UPDATE PatientData SET Gender='$gen_new' WHERE PID='$pid';");
      }
      header("Refresh:0");
   }

   if (isset($_POST['update_patient_add'])) {

      //Recieve all input values from the form
      $add_new = $patientdb->real_escape_string($_POST['add_new']);
      $pid = $_SESSION['pid'];

      //Check fields are non-empty
      if (empty($add_new)) { array_push($errors, "New add is required"); }

      // If no input errors then update password in database
      if(count($errors) == 0) {
         $patientdb->query("UPDATE PatientData SET Address='$add_new' WHERE PID='$pid';");
      }

   }

   if (isset($_POST['update_patient_email'])) {

      //Recieve all input values from the form
      $email_new = $patientdb->real_escape_string($_POST['email_new']);
      $pid = $_SESSION['pid'];

      //Check fields are non-empty
      if (empty($email_new)) { array_push($errors, "New email is required"); }

      // If no input errors then update password in database
      if(count($errors) == 0) {
         $patientdb->query("UPDATE PatientLogin SET Email='$email_new' WHERE PID='$pid';");
         $patientdb->query("UPDATE PatientData SET Email='$email_new' WHERE PID='$pid';");
      }

   }

   if (isset($_POST['update_patient_phone'])) {

      //Recieve all input values from the form
      $phone_new = $patientdb->real_escape_string($_POST['phone_new']);
      $pid = $_SESSION['pid'];

      //Check fields are non-empty
      if (empty($phone_new)) { array_push($errors, "New phone is required"); }

      // If no input errors then update password in database
      if(count($errors) == 0) {
         $patientdb->query("UPDATE PatientData SET Phone='$phone_new' WHERE PID='$pid';");
      }

   }

   if (isset($_POST['update_patient_password'])) {

      //Recieve all input values from the form
      $password_old = $patientdb->real_escape_string($_POST['password_old']);
      $password_1 = $patientdb->real_escape_string($_POST['password_1']);
      $password_2 = $patientdb->real_escape_string($_POST['password_2']);
      $pid = $_SESSION['pid'];

      //Check fields are non-empty
      if (empty($password_old)) { array_push($errors, "Old Password is required"); }
      if (empty($password_1)) { array_push($errors, "New Password is required"); }
      if (empty($password_2)) { array_push($errors, "New Password is required"); }

      //Verify Passwords Match
      if ($password_1 != $password_2) { array_push($errors, "The two passwords do not match"); }
      if ($password_old != $_SESSION['pass']) { array_push($errors, "Does not match current password"); }

      // If no input errors then update password in database
      if(count($errors) == 0) {
         $patientdb->query("UPDATE PatientLogin SET Pass='$password_1' WHERE PID='$pid';");
      }
   }

   // SET SERVICE SELECTION
   if (isset($_POST['set_ssdb'])) {

      // receive all input values from the form
      $insurance = $ssdb->real_escape_string($_POST['insurselect']);
      $pharmacy = $ssdb->real_escape_string($_POST['pharmselect']);
      $doctor = $ssdb->real_escape_string($_POST['docselect']);
      $hospital = $ssdb->real_escape_string($_POST['hospselect']);
      $lab = $ssdb->real_escape_string($_POST['labselect']);
      $pid = $_SESSION['pid'];

      // form validation to ensure all fields have data
      if (empty($insurance)) { array_push($errors, "Insurance must be selected"); }
      if (empty($pharmacy)) { array_push($errors, "Pharmacy must be selected"); }
      if (empty($doctor)) { array_push($errors, "Doctor must be selected"); }
      if (empty($hospital)) { array_push($errors, "Hopsital must be selected"); }
      if (empty($lab)) { array_push($errors, "Lab must be selected"); }

      // proceed if no errors
      if (count($errors == 0)) {

         // insurance and pharmacy selection
         $ssdb->query("INSERT INTO SSDO (PatientID, InsuranceID, PharmacyID) VALUES ('$pid', '$insurance', '$pharmacy')
         ON DUPLICATE KEY UPDATE InsuranceID = VALUES(InsuranceID), PharmacyID = VALUES(PharmacyID);");

         // doctor selection
         $ssdb->query("INSERT INTO SSDD (PatientID, DoctorID) VALUES ('$pid', '$doctor')
         ON DUPLICATE KEY UPDATE DoctorID = VALUES(DoctorID);");

         // hospital selection
         $ssdb->query("INSERT INTO SSDH (PatientID, HospitalID) VALUES ('$pid', '$hospital')
         ON DUPLICATE KEY UPDATE HospitalID = VALUES(HospitalID);");

         // lab selection
         $ssdb->query("INSERT INTO SSDL (PatientID, LabID) VALUES ('$pid', '$lab')
         ON DUPLICATE KEY UPDATE LabID = VALUES(LabID);");
      }
   }
   ?>
