
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
    <title>Εdit prospective customer</title>
    <link rel="stylesheet" href="editprospectivecustomers.css">
</head>
<body>

<?php
include 'db.php';

$conn->set_charset("utf8mb4");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST["fullname"];
    $tel = $_POST["tel"];
    $email = $_POST["email"];
    $time_period = $_POST["time_period"];
    $destination = $_POST["destination"];
    $people = $_POST["people"];
    $trip = $_POST["trip"];
    $departure_date = $_POST["departure_date"];
    $amount_paid = $_POST["amount_paid"];
    $high_travel_likelihood = isset($_POST["high_travel_likelihood"]) ? 1 : 0;
    $trip_completed = isset($_POST["trip_completed"]) ? 1 : 0;
    $offer_sent = isset($_POST["offer_sent"]) ? 1 : 0;
    $comments = $_POST["comments"];
    
    // εδώ θα πρέπει να πάρετε το id του manager από το fullname
    $managerName = $_POST["manager"];
    $query = "SELECT id FROM users WHERE fullname = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $managerName);
    $stmt->execute();
    $result = $stmt->get_result();
    $manager = $result->fetch_assoc()['id'];

    $customerId = $_POST["id"];
    $query = "UPDATE customers SET fullname = ?, tel = ?, email = ?, time_period = ?, destination = ?, people = ?, trip = ?, departure_date = ?, amount_paid = ?, high_travel_likelihood = ?, trip_completed = ?, offer_sent = ?, comments = ?, manager = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssssisssiiisii', $fullname, $tel, $email, $time_period, $destination, $people, $trip, $departure_date, $amount_paid, $high_travel_likelihood, $trip_completed, $offer_sent, $comments, $manager, $customerId);
    $stmt->execute();

    header("Location: customers.php");
    exit; 
} else if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        die('Invalid customer ID');
    }

    $id = $_GET['id'];
    $query = "SELECT * FROM customers WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $customer = $result->fetch_assoc();

    if (!$customer) {
        die('Customer not found');

        
    }

    $query = "SELECT fullname FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $customer['manager']);
    $stmt->execute();
    $result = $stmt->get_result();
    $manager_fullname = $result->fetch_assoc()['fullname'];

} else {
    die('Invalid request method');
}

// φέρνουμε όλα τα ονόματα των users για να φτιάξουμε το dropdown
$query = "SELECT fullname FROM users";
$result = $conn->query($query);
$users = $result->fetch_all(MYSQLI_ASSOC);
?>

<form method="post">
    <input type="hidden" name="id" value="<?= $customer['id'] ?>">
    <label for="fullname">Full Name:</label><br>
    <input type="text" id="fullname" name="fullname" value="<?= $customer['fullname'] ?>"><br>

    <label for="tel">Telephone:</label><br>
    <input type="tel" id="tel" name="tel" value="<?= $customer['tel'] ?>"><br>

    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" value="<?= $customer['email'] ?>"><br>

    <label for="time_period">Time Period:</label><br>
    <input type="text" id="time_period" name="time_period" value="<?= $customer['time_period'] ?>"><br>

    <label for="destination">Destination:</label><br>
    <input type="text" id="destination" name="destination" value="<?= $customer['destination'] ?>"><br>

    <label for="people">Number of People:</label><br>
    <input type="number" id="people" name="people" value="<?= $customer['people'] ?>"><br>

    <label for="trip">Trip:</label><br>
    <input type="text" id="trip" name="trip" value="<?= $customer['trip'] ?>"><br>

    <label for="departure_date">Departure Date:</label><br>
    <input type="date" id="departure_date" name="departure_date" value="<?= $customer['departure_date'] ?>"><br>

    <label for="amount_paid">Amount Paid:</label><br>
    <input type="number" step="0.01" id="amount_paid" name="amount_paid" value="<?= $customer['amount_paid'] ?>"><br>

    <label for="high_travel_likelihood">High Travel Likelihood:</label><br>
    <input type="checkbox" id="high_travel_likelihood" name="high_travel_likelihood" value="1" <?= $customer['high_travel_likelihood'] == 1 ? 'checked' : '' ?>><br>

    <label for="offer_sent">Offer Sent:</label><br>
    <input type="checkbox" id="offer_sent" name="offer_sent" value="1" <?= $customer['offer_sent'] == 1 ? 'checked' : '' ?>><br>

    <label for="trip_completed">Trip Completed:</label><br>
    <input type="checkbox" id="trip_completed" name="trip_completed" value="1" <?= $customer['trip_completed'] == 1 ? 'checked' : '' ?>><br>

    <label for="comments">Comments:</label><br>
    <textarea id="comments" name="comments"><?= $customer['comments'] ?></textarea><br>

    
    <label for="manager">Seller:</label><br>
        <select name="manager" id="manager">
            <?php foreach ($users as $user): ?>
            <option value="<?= $user['fullname'] ?>" <?= $manager_fullname == $user['fullname'] ? 'selected' : '' ?>><?= $user['fullname'] ?></option>
            <?php endforeach; ?>
        </select><br>

    <input type="submit" value="Update">
</form>

    
</body>
</html>