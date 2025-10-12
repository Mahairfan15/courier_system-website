<?php
$conn = mysqli_connect("localhost", "root", "", "courier_db");
$message = "";

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];

    $check = mysqli_query($conn, "SELECT * FROM customers WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $message = "❌ Email already registered.";
    } else {
        $insert = "INSERT INTO customers (name, email, phone, address) VALUES ('$name', '$email', '$contact', '$address')";
        if (mysqli_query($conn, $insert)) {
            $message = "✅ Registered successfully!";
        } else {
            $message = "❌ Registration failed.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Registration</title>
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
        .register-box {
            max-width: 500px;
            margin: 60px auto;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .card-header {
            background-color:  #F95C19;
            color: white;
        }
        #button{
            background-color:  #F95C19;
           
        }
    </style>
</head>
<body>

<div class="register-box">
    <div class="card">
        <div class="card-header text-center">
            <h4>📋 Customer Registration</h4>
        </div>
        <div class="card-body">
            <?php if ($message): ?>
                <div class="alert alert-info text-center"><?= $message ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" required placeholder="enter your name">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" required placeholder="example@email.com">
                </div>
                <div class="mb-3">
                    <label class="form-label">Contact Number</label>
                    <input type="text" name="contact" class="form-control" required placeholder="03XXXXXXXXX">
                </div>
                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" name="register" class="btn w-100" id="button">Register</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
