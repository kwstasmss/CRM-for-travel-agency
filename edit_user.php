<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit();
}


?>


<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit user</title>
  <link rel="stylesheet" href="edit_user.css">
</head>
<body>
<?php
include 'db.php';


$conn->set_charset("utf8mb4");




if ($_SERVER["REQUEST_METHOD"] == "GET") {
  $userId = $_GET['id'];

  $query = "SELECT fullname, email, role FROM users WHERE id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    // User found
    $user = $result->fetch_assoc();
  } else {
    // User not found
    echo "No user found with this id";
    exit;
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $userId = $_POST['id'];
  
    $query = "UPDATE users SET fullname = ?, email = ?, role = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $fullname, $email, $role, $userId);
    $stmt->execute();
  
    header("Location: users.php");
    exit;
  }
  
?>

<form action="" method="post">

<input type="hidden" name="id" value="<?php echo $userId; ?>">

  <label for="fullname">Full Name</label>
  <input type="text" id="fullname" name="fullname" value="<?php echo $user['fullname']; ?>">

  <label for="email">Email</label>
  <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>">

  <label for="role">Role</label>
  <select id="role" name="role">
    <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
    <option value="user" <?php echo $user['role'] == 'user' ? 'selected' : ''; ?>>User</option>
  </select>

  <input type="submit" value="Submit">
  <input type="hidden" name="id" value="<?php echo $userId; ?>">

</form>
</body>
</html>
