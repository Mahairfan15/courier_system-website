<?php
// session_start();
if (!isset($_SESSION['agent'])) {
    header("Location: agent_login.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "courier_db");
if (!$conn) {
    die("❌ Connection failed: " . mysqli_connect_error());
}
$message = "";

if (isset($_POST['update'])) {
    $courier_id = $_POST['courier_id'];
    $status_message = $_POST['status_message'];

    $insert = "INSERT INTO tracking_status (courier_id, status_message, updated_on) 
               VALUES ('$courier_id', '$status_message', NOW())";

    if (mysqli_query($conn, $insert)) {
        // Also update current status in couriers table
        $update_status = "UPDATE couriers SET status = '$status_message' WHERE id = '$courier_id'";
        mysqli_query($conn, $update_status);
        $message = "✅ Status updated successfully.";
    } else {
        $message = "❌ Failed to update status.";
    }
}

$couriers = mysqli_query($conn, "SELECT id, tracking_number FROM couriers");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Courier Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f1f4f6;
            font-family: 'Segoe UI', sans-serif;
        }
        .container {
            max-width: 700px;
            margin-top: 40px;
        }
        .card {
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">🚚 Update Courier Status</h5>
        </div>
        <div class="card-body">
            <?php if ($message != ""): ?>
                <div class="alert alert-info text-center"> <?php echo $message; ?> </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Select Courier</label>
                    <select name="courier_id" class="form-select" required>
                        <option value="">Select Tracking Number</option>
                        <?php while ($row = mysqli_fetch_assoc($couriers)): ?>
                            <option value="<?= $row['id'] ?>"><?= $row['tracking_number'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status Message</label>
                    <select name="status_message"  class="form-select" required>
                        <option value="">select status message</option>
                        <option value="IN PROGRESS">IN PROGRESS</option>
                        <option value="IN TRANSIT">IN TRANSIT</option>
                        <option value="DELIVERED">DELIVERED<option>
                    </select>
                </div>

                <button type="submit" name="update" class="btn btn-success w-100">🔄 Update Status</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
