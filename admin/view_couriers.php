<?php
// session_start();
$conn = mysqli_connect("localhost", "root", "", "courier_db");
if (!$conn) {
    die("❌ Connection failed: " . mysqli_connect_error());
}

// Redirect to login if not logged in
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}
$query = "SELECT c.*, 
                 s.name AS sender_name, 
                 r.name AS receiver_name 
          FROM couriers c
          JOIN customers s ON c.sender_id = s.id
          JOIN customers r ON c.receiver_id = r.id";
$result = mysqli_query($conn, "SELECT * FROM couriers ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Couriers</title>
    <style>
        table {
            border-collapse: collapse;
            width: 90%;
        }
        th, td {
            border: 1px solid #999;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <h2>All Courier Shipments</h2>

    <table>
        <tr>
            <th>Tracking #</th>
            <th>Courier Type</th>
            <th>Weight</th>
            <th>Price</th>
            <th>From</th>
            <th>To</th>
            <th>Delivery Date</th>
            <th>Status</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['tracking_number']; ?></td>
            <td><?php echo $row['courier_type']; ?></td>
            <td><?php echo $row['weight']; ?> kg</td>
            <td><?php echo $row['price']; ?> Rs</td>
            <td><?php echo $row['from_location']; ?></td>
            <td><?php echo $row['to_location']; ?></td>
            <td><?php echo $row['delivery_date']; ?></td>
            <td><?php echo $row['status']; ?></td>
        </tr>
        <?php } ?>
    </table>

    <br>
    <a href="admin_dashboard.php">← Back to Dashboard</a>
</body>
</html>
