
<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit();
}


?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New password</title>
    <link rel="stylesheet" href="change_password.css">
</head>
<body>
<?php
include 'db.php';


if (isset($_POST['change_password'])) {
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];

  if ($password == $confirm_password) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET password = ?, first_login = 0 WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $hashed_password, $_SESSION['email']);
    $stmt->execute();

    if ($stmt->affected_rows === 1) {
      echo "Password changed successfully";
      header("Location: customers.php");
    } else {
      echo "Failed to change password";
    }
  } else {
    echo "Passwords do not match";
  }

  
}
?>
<form method="post" action="change_password.php">
  <label for="password">New Password:</label><br>
  <input type="password" id="password" name="password" required><br>
  <label for="confirm_password">Confirm New Password:</label><br>
  <input type="password" id="confirm_password" name="confirm_password" required><br>
  <input type="checkbox" id="show_password" onclick="showPassword()"> Show Password<br>
  <input type="submit" value="Change Password" name="change_password">
</form>

<script>
function showPassword() {
  var passwordField = document.getElementById("password");
  var confirmPasswordField = document.getElementById("confirm_password");
  if (passwordField.type === "password") {
    passwordField.type = "text";
    confirmPasswordField.type = "text";
  } else {
    passwordField.type = "password";
    confirmPasswordField.type = "password";
  }
}
</script>

</body>
</html>