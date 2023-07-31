<?php
include 'db.php';
session_start();

$conn->set_charset("utf8mb4");




if(isset($_GET['id'])) {
  $id = $_GET['id'];

  // prepare and bind
  $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
  $stmt->bind_param("i", $id);

  // set parameters and execute
  $stmt->execute();

  $stmt->close();
  // Redirect to users page
  header("Location: users.php");
} else {
  echo "No user id provided";
}

$conn->close();
?>
