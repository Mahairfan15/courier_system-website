
<?php
$conn = mysqli_connect("localhost", "root", "", "courier_db");

$trackingData = null;
$error = "";

if (isset($_POST['track'])) {
    $tracking = $_POST['tracking_number'] ?? '';

    $query = "SELECT c.*, 
                s.name AS sender_name, 
                r.name AS receiver_name,
                ts.status_message,
                ts.updated_on
              FROM couriers c
              JOIN customers s ON c.sender_id = s.id
              JOIN customers r ON c.receiver_id = r.id
              LEFT JOIN tracking_status ts ON c.id = ts.courier_id
              WHERE c.tracking_number = '$tracking'";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $trackingData = mysqli_fetch_assoc($result);
    } else {
        $error = "❌ No record found for Tracking Number: $tracking";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Track Courier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
          background: linear-gradient(135deg, #ff7a36,#ffeb7a, #ffff99,#ff7a36);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .container {
            max-width: 700px;
            margin-top: 40px;
        }
        .card {
            border-radius: 10px;
        }
        .card-header{
           background-color:  #F95C19;
        }
    </style>
</head>
<body>

<div class="container" >
    <div class="card shadow">
        <div class="card-header text-white ">
            <h5 class="mb-0">📦 Track Courier</h5>
        </div>
        <div class="card-body">
            <form method="POST" class="mb-4">
                <div class="input-group">
                    <select name="tracking_number" class="form-select" required>
                      <option value="">Select Tracking Number</option>
                       <?php
                        $all = mysqli_query($conn, "SELECT tracking_number FROM couriers");
                        while ($row = mysqli_fetch_assoc($all)) {
                       echo "<option value='" . $row['tracking_number'] . "'>" . $row['tracking_number'] . "</option>";
    }
    ?>
                    </select>

                    <!-- <input type="text" name="tracking_number" class="form-control" placeholder="Enter Tracking Number" required> -->
                    <button type="submit" name="track" class="btn btn-success">Track</button>
                </div>
            </form>

            <?php if ($error): ?>
                <div class="alert alert-danger text-center"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($trackingData): ?>
                <div class="alert alert-info">
                    <h6>📄 Tracking Result:</h6>
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Tracking #:</strong> <?= $trackingData['tracking_number'] ?></li>
                        <li class="list-group-item"><strong>Sender:</strong> <?= $trackingData['sender_name'] ?></li>
                        <li class="list-group-item"><strong>Receiver:</strong> <?= $trackingData['receiver_name'] ?></li>
                        <li class="list-group-item"><strong>From:</strong> <?= $trackingData['from_location'] ?></li>
                        <li class="list-group-item"><strong>To:</strong> <?= $trackingData['to_location'] ?></li>
                        <li class="list-group-item"><strong>Status:</strong> <?= $trackingData['status'] ?></li>
                        <li class="list-group-item"><strong>Delivery Date:</strong> <?= $trackingData['delivery_date'] ?></li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
