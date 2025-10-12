<?php
// session_start();
$conn = mysqli_connect("localhost", "root", "", "courier_db");
if (!$conn) {
    die("❌ Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM couriers ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Download Courier Report</title>
    <style>
        table {
            width: 95%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #444;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #ccc;
        }
        .btn {
            margin-top: 15px;
            padding: 10px 20px;
            background-color: green;
            color: white;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background-color: darkgreen;
        }
    </style>
    <script>
        function printReport() {
            window.print();
        }
    </script>
</head>
<body>
    <h2>Courier Shipment Report</h2>

    <button class="btn" onclick="printReport()">🖨 Print Report</button>

    <table>
        <tr>
            <th>ID</th>
            <th>Tracking #</th>
            <th>Type</th>
            <th>Weight</th>
            <th>Price</th>
            <th>From</th>
            <th>To</th>
            <th>Delivery Date</th>
            <th>Status</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
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
