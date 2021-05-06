<?php

$servername = "localhost";
$username = "httpdh34";
$socket = "/vols/sdb38/httpdh34_db/httpdh34_db.sock";
$sqlport = "3340"

//If you want a connection created add the following code to your file at the top:
//   Include includes/dbconn.php

//Add in the connection to the database, databases are db1, db2, db3, db4, db5:
//There is no password to worry about, connection is socket dependent only
//port does not matter either as this is a local connection
//$conn = new mysqli($servername, $username, "", "db1", $sqlport, $socket); 

//if ($conn->connect_error) {
//   die("Connection failed: " . $conn->connect_error);
//}


?> 
