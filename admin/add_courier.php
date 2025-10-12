<?php
// session_start();
$conn = mysqli_connect("localhost", "root", "", "courier_db");

// Fetch customers for dropdowns
$customers = mysqli_query($conn, "SELECT id, name FROM customers");

$message = "";
function generateTrackingNumber() {
    return 'TRK' . rand(100000, 999999);
}
if (isset($_POST['submit'])) {
    $tracking_number = generateTrackingNumber();
    $sender_id = $_POST['sender_id'];
    $receiver_id = $_POST['receiver_id'];
    $courier_type = $_POST['courier_type'];
    $weight = $_POST['weight'];
    $price = $_POST['price'];
    $from_location = $_POST['from_location'];
    $to_location = $_POST['to_location'];
    $delivery_date = $_POST['delivery_date'];
    $status = "In Progress";

    $query = "INSERT INTO couriers (tracking_number, sender_id, receiver_id, courier_type, weight, price, status, from_location, to_location, delivery_date)
        VALUES ('$tracking_number', '$sender_id', '$receiver_id', '$courier_type', '$weight', '$price', '$status', '$from_location', '$to_location', '$delivery_date')";
    if (mysqli_query($conn, $query)) {
        // Fetch sender email
        $getEmail = mysqli_query($conn, "SELECT email FROM customers WHERE id = '$sender_id'");
        $senderData = mysqli_fetch_assoc($getEmail);
        $senderEmail = $senderData['email'];

        // Email content
        $subject = "Courier Confirmation - Tracking #$tracking_number";
        $messageBody = "
        Dear Customer,

        Your courier has been successfully booked.
        Here are the details:

        Tracking Number: $tracking_number
        From: $from_location
        To: $to_location
        Courier Type: $courier_type
        Delivery Date: $delivery_date

        Thank you for using our service!
        ";

        $headers = "From: noreply@courier-system.com";

        // Send the email
        // if (mail($senderEmail, $subject, $messageBody, $headers)) {
        //     $message = "✅ Courier added & confirmation email sent to $senderEmail.<br>Tracking #: <strong>$tracking_number</strong>";
        // } else {
        //     $message = "✅ Courier added, but email could not be sent. Tracking #: <strong>$tracking_number</strong>";
        // }// Skip email sending on localhost
$message = "✅ Courier added. (Email not sent in localhost mode) Tracking #: <strong>$tracking_number</strong>";

    } else {
        $message = "❌ Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Courier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
          background: linear-gradient(to right, #e3f2fd, #ffffff);
            font-family: 'Segoe UI', sans-serif;
          
        }
        .container {
            max-width: 720px;
            margin-top: 40px;
        }
        .card {
            border-radius: 10px;
        }
        .card-header {
            font-weight: bold;
            font-size: 1.25rem;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h5 class="mb-0">📦 Add New Courier</h5>
        </div>
        <div class="card-body">
            <?php if ($message != ""): ?>
                <div class="alert alert-info text-center"><?php echo $message; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Sender</label>
                    <select name="sender_id" class="form-select" required>
                        <option value="">Select Sender</option>
                        <?php foreach ($customers as $customer): ?>
                            <option value="<?= $customer['id'] ?>"><?= $customer['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Receiver</label>
                    <select name="receiver_id" class="form-select" required>
                        <option value="">Select Receiver</option>
                        <?php foreach ($customers as $customer): ?>
                            <option value="<?= $customer['id'] ?>"><?= $customer['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Courier Type</label>
                    <select name="courier_type" class="form-select" required>
                        <option value="">Select courier type</option>
                        <option value="Local Courier Services">Local Courier Services.</option>
                        <option value="International Courier Services">International Courier Services.</option>
                        <option value="Standard Delivery Services">Standard Delivery Services.</option>
                        <option value="Same-Day Couriers">Same-Day Couriers</option>
                        <option value="Overnight Courier Services">Overnight Courier Services.</option>
                    </select>
                    
                </div>

                <div class="mb-3">
                    <label class="form-label">Weight (kg)</label>
                    <input type="number" step="" name="weight" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Price (PKR)</label>
                    <input type="number" step="1" name="price" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">From Location</label>
                    <input type="text" name="from_location" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">To Location</label>
                    <input type="text" name="to_location" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Delivery Date</label>
                    <input type="date" name="delivery_date" class="form-control" required>
                </div>

                <button type="submit" name="submit" class="btn btn-success w-100">
                    ➕ Add Courier
                </button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
                        