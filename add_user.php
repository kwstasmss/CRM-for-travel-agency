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
  <title>New user</title>
  <link rel="stylesheet" href="add_user.css">
</head>
<body>

<?php
include 'db.php';


$conn->set_charset("utf8mb4");




if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $fullname = $_POST['fullname'];
  $email = $_POST['email'];
  $role = $_POST['role'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Encrypt password

  $query = "INSERT INTO users (fullname, email, role, password) VALUES (?, ?, ?, ?)";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ssss", $fullname, $email, $role, $password);

  if ($stmt->execute()) {
    header("Location: users.php");
    exit;
  } else {
    echo "Error: " . $stmt->error;
  }
}
?>

<body>
  <form action="" method="post">
    <label for="fullname">Full Name</label>
    <input type="text" id="fullname" name="fullname">

    <label for="email">Email</label>
    <input type="email" id="email" name="email">

    <label for="password">Password</label>
    <input type="password" id="password" name="password">

    <label for="role">Role</label>
    <select id="role" name="role">
      <option value="admin">Admin</option>
      <option value="user">User</option>
    </select>

    <input type="submit" value="Submit">
  </form>
</body>
  
</body>
</html>