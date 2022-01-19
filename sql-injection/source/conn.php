<?php

$servername = "";
$username = "";
$password = "";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

mysqli_report(MYSQLI_REPORT_OFF);
mysqli_select_db($conn, 'db_name');
?>
