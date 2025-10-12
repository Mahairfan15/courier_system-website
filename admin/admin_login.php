<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "courier_db");
if (!$conn) {
    die("❌ Database connection failed: " . mysqli_connect_error());
}

$message = "";
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password' AND role='admin'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['admin'] = $username;
        header("Location: admin_dashboard.php");
    } else {
        $message = "Invalid username or password!";
    }
}
// if (isset($_POST['login'])) {
//     $username = trim($_POST['username']);
//     $password = trim($_POST['password']);

//     $stmt = $conn->prepare("SELECT * FROM users WHERE username='$username' AND password='$password' AND role='admin'");
//     $stmt->bind_param("s", $username);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     if ($result && $result->num_rows === 1) {
//         $row = $result->fetch_assoc();
//         // Assuming password is hashed with password_hash()
//         if (password_verify($password, $row['password'])) {
//             $_SESSION['admin'] = $username;
//             header("Location: admin_dashboard.php");
//             exit;
//         }
//     }
//     $message = "❌ Invalid username or password!";
//     $stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Courier System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ff7a36,#ffeb7a, #ffff99,#ff7a36);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-card {
           background-color: #fcf8e3;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
        }
        #login{
           background-color:  #F95C19;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h4 class="mb-4 text-center">Admin Login</h4>
        <?php if ($message): ?>
            <div class="alert alert-danger"><?= $message ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" required class="form-control">
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" required class="form-control">
            </div>
            <button type="submit" name="login" class="btn w-100" id="login">Login</button>
        </form>
    </div>
</body>
</html>
