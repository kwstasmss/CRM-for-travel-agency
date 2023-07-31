<?php
session_start();
?>

<!DOCTYPE html>
 <html>
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loginpage</title>
    <link rel="stylesheet" href="login.css">
 </head>
 <body>
	 
 <?php

include 'db.php';

if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $query = "SELECT * FROM users WHERE email = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();
  
  if ($result->num_rows === 1) {
    // User found
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
      // Password correct
      $_SESSION['email'] = $user['email'];
      $_SESSION['role'] = $user['role'];
      $_SESSION['id'] = $user['id'];

      if ($user['first_login'] === 1) {
        // If first login, redirect to change password page
        header("Location: change_password.php");
      } else {

        
        // Not first login, redirect to customers page
        header("Location: customers.php");
      }
    } else {
      // Password incorrect
      echo "Wrong password";
    }
  } else {
    // User not found
    echo "No user found with this email";
  }
}
?>
<form method="post" action="">
  <input type="email" id="email" name="email" placeholder="Email" required><br>
  <input type="password" id="password" name="password" placeholder="Password" required>
  <div class="show-password">
      <input type="checkbox" id="showPasswordCheckbox">
      <label for="showPasswordCheckbox">Show Password</label>
  </div>
  <input type="submit" value="Login" name="login">
</form>

<script>
    document.getElementById("showPasswordCheckbox").addEventListener("change", function() {
      var passwordInput = document.getElementById("password");
      passwordInput.type = this.checked ? "text" : "password";
    });
  </script>

 </body>
 </html>