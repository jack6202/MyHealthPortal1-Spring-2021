<?php
  session_start();

  if (!isset($_SESSION['success'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: PatientPortalLogin.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	session_unset();
  	header("location: PatientPortalIndex.php");
  }
?>

<? include('PatientServer.php') ?>

<!--Application landing page here-->
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
    <div class="nav-right">
      <a href="PatientPortalIndex.php?logout='1'" class="w3-bar-item w3-button w3-hide-small w3-hover-white">LOGOUT</a>
    </div>
  </div>

  <!--Header-->
  <div class="header w3-theme-d4">
    <h1><b><?php echo "Welcome " . $_SESSION["name"] . ""?></b></h1>
  </div>

  <!--Select Services Button-->
  <div class="home-middle">
    <a class="w3-button w3-xlarge w3-round w3-green w3-ripple" href="PatientPortalServiceSelect.php">Select Services</a>
  </div>

  <!--Update Information Button-->
  <div class="home-middle">
    <a class="w3-button w3-xlarge w3-round w3-green w3-ripple" href="PatientPortalUpdate.php">Update Personal Information</a>
  </div>

  <!--View Records Button-->
  <div class="home-middle">
    <a class="w3-button w3-xlarge w3-round w3-green w3-ripple" href="PatientPortalRecords.php">View Records</a>
  </div>

  <!--Footer-->
  <div class="footer center w3-theme-d4">
    <h3>CS360 Spring 2021</h3>
  </div>
</body>

</html>
