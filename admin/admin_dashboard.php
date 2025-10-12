<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}
$conn = mysqli_connect("localhost", "root", "", "courier_db");
if (!$conn) {
    die("❌ Connection failed: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="en" style="height: 100%;">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background-color: #f8f9fa;
            overflow: hidden;
        }
        .sidebar {
            height: 100vh;
            background-color: #003366;
            color: white;
            position: fixed;
            width: 220px;
            top: 0;
            left: 0;
            overflow-y: auto;
            padding-top: 60px;
        }
        .sidebar a {
            color: #ddd;
            padding: 10px 20px;
            display: block;
            text-decoration: underline;
            font-size:20px;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #00509e;
            color: white;
        }
        .topbar {
            height: 60px;
            background-color: #00509e;
            color: white;
            position: fixed;
            top: 0;
            left: 220px;
            right: 0;
            display: flex;
            align-items: center;
            padding: 0 20px;
            justify-content: space-between;
            z-index: 1000;
        }
        .content {
            position: absolute;
            top: 60px;
            left: 220px;
            right: 0;
            bottom: 0;
            overflow-y: auto;
            padding: 20px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
           <div class="text-center mb-3">
              <img src="https://cdn-icons-png.flaticon.com/512/891/891462.png" width="90" alt="Courier Logo" class="mb-2">
              <h2 style="font-weight: bold;">Courier Admin</h2>
            </div>
        <a href="admin_dashboard.php?page=home" >Dashboard</a>
        <a href="admin_dashboard.php?page=add">Add Courier</a>
        <a href="admin_dashboard.php?page=view">View Couriers</a>
        <a href="admin_dashboard.php?page=track">Track Courier</a>
        <a href="admin_dashboard.php?page=report">Download Report</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Topbar -->
    <div class="topbar">
       <h2><span><strong>Dashboard</strong> - Courier Management System</span></h2> 
       <h4><i><span>Welcome <?php echo $_SESSION['admin']; ?></span></i></h4> 
        <!-- <span>Welcome Admin</span> -->
    </div>

    <!-- Main Content Area -->
    <div class="content">
        <?php
        if (isset($_GET['page'])) {
            $page = $_GET['page'];

            if ($page == 'add') {
                include 'add_courier.php';
            } elseif ($page == 'view') {
                include 'view_couriers.php';
            } elseif ($page == 'track') {
                include '../track_courierr.php';
            } elseif ($page == 'report') {
                include 'download_report.php';
            } elseif ($page == 'home') {
                // Dashboard content
                $total = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM couriers"))[0];
                $delivered = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM couriers WHERE status='Delivered'"))[0];
                $inprogress = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM couriers WHERE status='In Progress' OR status='In Transit'"))[0];
                ?>
                <div class="container-fluid">
                    <div class="d-flex align-items-center mb-4">
                         <img src="https://cdn-icons-png.flaticon.com/512/891/891462.png" width="60" alt="Courier Logo" class="mb-2">
                        <h2 class="fw-bold mb-0">Courier Dashboard Overview</h2>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card text-white bg-primary mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Total Couriers</h5>
                                    <h2><?= $total ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-success mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Delivered</h5>
                                    <h2><?= $delivered ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-warning mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">In Progress</h5>
                                    <h2><?= $inprogress ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts -->
                    <div class="row">
                        <div class="col-md-6">
                            <canvas id="pieChart"></canvas>
                        </div>
                        <div class="col-md-6">
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div>
                </div>

                <script>
                    const pieCtx = document.getElementById("pieChart");
                    new Chart(pieCtx, {
                        type: "pie",
                        data: {
                            labels: ["Delivered", "In Progress"],
                            datasets: [{
                                data: [<?= $delivered ?>, <?= $inprogress ?>],
                                backgroundColor: ["#198754", "#ffc107"]
                            }]
                        }
                    });

                    const lineCtx = document.getElementById("lineChart");
                    new Chart(lineCtx, {
                        type: "line",
                        data: {
                            labels: ["Start", "Now"],
                            datasets: [{
                                label: "Total Couriers",
                                data: [0, <?= $total ?>],
                                borderColor: "#0d6efd",
                                backgroundColor: "rgba(13, 110, 253, 0.2)",
                                fill: true,
                                tension: 0.4
                            }]
                        }
                    });
                </script>
                <?php
            }
        } else {
            header("Location: admin_dashboard.php?page=home");
        }
        ?>
    </div>
</body>
</html>
