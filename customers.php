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
    <title>Customers</title>
    <link rel="stylesheet" href="customers.css">

    <style>
.tabcontent {
    display: none;
}
</style>

</head>
<body>


<?php
include 'db.php';


$conn->set_charset("utf8mb4");




$query = "SELECT * FROM customers ORDER BY fullname ASC";
$result = $conn->query($query);
$completedCustomers = $result->fetch_all(MYSQLI_ASSOC);


?>

<div class="header">
    <div class="left">
        <a href="add_customer.php">Add Customer</a>
        <?php if ($_SESSION['role'] == 'admin') { ?>
            <a href="users.php">Users</a>
        <?php } ?>
    </div>
    <div class="right">
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="color-legend">
    <h3>Color Indication:</h3>
    <ul>
        <li><span class="color-box" style="background: linear-gradient(to right, #ED2939 50%, lime 50%);"></span> Highly likely to travel & Offer has been sent</li>
        <li><span class="color-box" style="background: #ED2939;"></span> Highly likely to travel</li>
        <li><span class="color-box" style="background: lime;"></span> Offer has been sent</li>
    </ul>
</div>



<div class="tab">
    <button class="tablinks" onclick="openCity(event, 'ProspectiveCustomers')">Prospective Customers</button>
    <button class="tablinks" onclick="openCity(event, 'Customers')">Customers</button>
</div>

<div id="ProspectiveCustomers" class="tabcontent">
    <table>
        <tr>
            <th>Full Name</th>
            <th>Telephone</th>
            <th>Email</th>
            <th>Time Period</th>
            <th>Destination</th>
            <th>Number of People</th>
            <th>Comments</th>
            <th>Seller</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($completedCustomers as $customer): ?>
            <?php
            if (!$customer['trip_completed']) {
                if ($customer['high_travel_likelihood'] && $customer['offer_sent']) {
                    $color = 'style="background: linear-gradient(to right, #ED2939 50%, lime 50%);"';
                } elseif ($customer['high_travel_likelihood']) {
                    $color = 'style="background: #ED2939;"';
                } elseif ($customer['offer_sent']) {
                    $color = 'style="background: lime;"';
                } else {
                    $color = '';
                }
                
                // Αναζήτηση του ονόματος του διαχειριστή με βάση το ID
                $managerId = $customer['manager'];
                $query = "SELECT fullname FROM users WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('i', $managerId);
                $stmt->execute();
                $result = $stmt->get_result();
                $manager = $result->fetch_assoc()['fullname'];
                
                ?>
                <tr>
                    <td <?= $color ?>><?= $customer['fullname'] ?></td>
                    <td><?= $customer['tel'] ?></td>
                    <td><?= $customer['email'] ?></td>
                    <td><?= $customer['time_period'] ?></td>
                    <td><?= $customer['destination'] ?></td>
                    <td><?= $customer['people'] ?></td>
                    <td><?= $customer['comments'] ?></td>
                    <td><?= $manager ?></td>
                    <td><a href="editprospectivecustomers.php?id=<?= $customer['id']; ?>">Edit</a></td>
                </tr>
            <?php } ?>
        <?php endforeach; ?>
    </table>
</div>


<div id="Customers" class="tabcontent">

    <table>
        <tr>
            <th>Full Name</th>
            <th>Telephone</th>
            <th>Email</th>
            <th>Number of People</th>
            <th>Trip</th>
            <th>Departure Date</th>
            <th>Amount Paid</th>
            <th>Seller</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($completedCustomers as $customer): ?>
            <?php if ($customer['trip_completed']) {
                // Αναζήτηση του ονόματος του διαχειριστή με βάση το ID
                $managerId = $customer['manager'];
                $query = "SELECT fullname FROM users WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('i', $managerId);
                $stmt->execute();
                $result = $stmt->get_result();
                $manager = $result->fetch_assoc()['fullname'];
            ?>
                <tr>
                    <td><?= $customer['fullname'] ?></td>
                    <td><?= $customer['tel'] ?></td>
                    <td><?= $customer['email'] ?></td>
                    <td><?= $customer['people'] ?></td>
                    <td><?= $customer['trip'] ?></td>
                    <td><?= date('d/m/Y', strtotime($customer['departure_date'])) ?></td>
                    <td><?= $customer['amount_paid'] ?></td>
                    <td><?= $manager ?></td>
                    <td><a href="editprospectivecustomers.php?id=<?= $customer['id']; ?>">Edit</a></td>
                </tr>
            <?php } ?>
        <?php endforeach; ?>
    </table>
</div>




<script>
function openCity(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    if (document.getElementById(cityName).style.display === "block") {
        document.getElementById(cityName).style.display = "none";
    } else {
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
    }
}

</script>
</body>
</html>