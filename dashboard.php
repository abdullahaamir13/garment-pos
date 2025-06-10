<?php
include 'header.php';

// Start session only if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Ensure role is set in the session
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'Cashier'; // Default to 'Cashier' if role not set
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Garment POS</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 30px;
        }
        .card {
            transition: 0.3s;
        }
        .card:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<!-- Dashboard Content -->
<div class="container">
    <h2 class="text-center mb-4">Dashboard</h2>
    
    <div class="row justify-content-center">
        <!-- Sales Card (Visible to All) -->
        <div class="col-md-4">
            <div class="card shadow p-3 mb-5 bg-white rounded">
                <div class="card-body text-center">
                    <h5 class="card-title"><i class="fa-solid fa-cash-register fa-2x"></i></h5>
                    <p class="card-text">Process Transactions</p>
                    <a href="sales.php" class="btn btn-primary">Go to Sales</a>
                </div>
            </div>
        </div>
         <!-- Products Card  (Visible to All) -->
         <div class="col-md-4">
                <div class="card shadow p-3 mb-5 bg-white rounded">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="fa-solid fa-box fa-2x"></i></h5>
                        <p class="card-text">Manage Inventory & Stock</p>
                        <a href="products.php" class="btn btn-primary">Go to Products</a>
                    </div>
                </div>
            </div>

        <!-- Show Only for Admin -->
        <?php if ($role == 'Admin') : ?>
            <!-- Reports Card -->
            <div class="col-md-4">
                <div class="card shadow p-3 mb-5 bg-white rounded">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="fa-solid fa-chart-line fa-2x"></i></h5>
                        <p class="card-text">View Sales & Stock Reports</p>
                        <a href="reports.php" class="btn btn-primary">Go to Reports</a>
                    </div>
                </div>
            </div>

            <!-- Manage Users Card -->
            <div class="col-md-4">
                <div class="card shadow p-3 mb-5 bg-white rounded">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="fa-solid fa-users fa-2x"></i></h5>
                        <p class="card-text">Manage Users</p>
                        <a href="users.php" class="btn btn-primary">Go to Users</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>