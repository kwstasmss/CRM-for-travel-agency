<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "crm";

// Δημιουργία σύνδεσης
$conn = new mysqli($servername, $username, $password, $dbname);

// Έλεγχος σύνδεσης
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
