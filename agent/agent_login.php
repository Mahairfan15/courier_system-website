<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "courier_db");
if (!$conn) {
    die("❌ Connection failed: " . mysqli_connect_error());
}

$message = "";
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password' AND role='agent'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['agent'] = $username;
        header("Location: agent_dashboard.php");
    } else {
        $message = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agent Login - Courier System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ff7a36,#ffeb7a, #ffff99,#ff7a36);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.2);
        }

        .login-card .form-control {
            border-radius: 8px;
        }

        .login-card .btn {
            border-radius: 8px;
        }

        .login-card .logo {
            width: 60px;
        }

        .input-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }

        .input-group .form-control {
            padding-left: 35px;
        }
        #login{
             background-color:  #F95C19;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-4">
            <img src="https://cdn-icons-png.flaticon.com/512/456/456212.png" class="logo mb-2">
            <h3>Agent Login</h3>
        </div>

        <?php if ($message != ""): ?>
            <div class="alert alert-danger text-center"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3 position-relative input-group">
                <span class="input-icon"><i class="bi bi-person-fill"></i></span>
                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>

            <div class="mb-3 position-relative input-group">
                <span class="input-icon"><i class="bi bi-lock-fill"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <button type="submit" name="login" class="btn  w-100"id="login">Login</button>
        </form>
    </div>
</body>
</html>
