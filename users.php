
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
  <title>Users</title>
  <link rel="stylesheet" href="users.css">

</head>
<body>


<?php
include 'db.php';
$conn->set_charset("utf8mb4");




$sql = "SELECT id, fullname, email, role FROM users";
$result = $conn->query($sql);
?>

<div class="flex-container">
    <a href="add_user.php">Add User</a>
    <a href="customers.php">Go Back</a>
  </div>
  <table>
    <tr>
      <th>Fullname</th>
      <th>Email</th>
      <th>Role</th>
      <th>Actions</th>
    </tr>
    <?php while ($user = $result->fetch_assoc()): ?>
      <tr>
        <td><?php echo $user['fullname']; ?></td>
        <td><?php echo $user['email']; ?></td>
        <td><?php echo $user['role']; ?></td>
        <td>
          <a href="edit_user.php?id=<?php echo $user['id']; ?>">Edit</a>
          <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $user['id']; ?>, '<?php echo $user['fullname']; ?>')">Delete</a>


        </td>
      </tr>
    <?php endwhile; ?>
  </table>

  <script type="text/javascript">
  function confirmDelete(userId, fullname) {
    if (confirm("Are you sure you want to delete " + fullname + "?")) {
      window.location.href = "delete_user.php?id=" + userId;
    }
  }
</script>
  
</body>
</html>