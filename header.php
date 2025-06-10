<?php
session_start();

// Redirect to login if the user is not authenticated dashboard.php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Get user role from session (assuming it's stored as 'role')
$user_role = $_SESSION['role'];
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<nav class="navbar navbar-expand-lg" style="background-color: #007bff;">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
            <img src="assets/images/aslogo.png" alt="Logo" height="50" class="logo-img">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link custom-nav" href="dashboard.php"><i class="fa-solid fa-house"></i> Dashboard</a>
                </li>

                <?php if ($user_role == 'Cashier' || $user_role == 'Admin') { ?>
                    <li class="nav-item">
                        <a class="nav-link custom-nav" href="sales.php"><i class="fa-solid fa-cash-register"></i> Sales</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link custom-nav" href="products.php"><i class="fa-solid fa-box"></i> Inventory</a>
                    </li>
                <?php } ?>

                <?php if ($user_role == 'Admin' || $user_role == 'Manager') { ?>

                    <li class="nav-item">
                        <a class="nav-link custom-nav" href="reports.php"><i class="fa-solid fa-chart-line"></i> Reports</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link custom-nav" href="users.php"><i class="fa-solid fa-users"></i> Users</a>
                    </li>
                <?php } ?>

                <li class="nav-item">
                    <a class="nav-link logout-nav" href="logout.php">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>

    .custom-nav {
        color: white !important;
        font-weight: 500;
        transition: background-color 0.3s, color 0.3s;
    }

    .custom-nav:hover {
        background-color: rgba(255, 255, 255, 0.2);
        color: #f1f1f1 !important;
        font-weight: 600;
    }

    /* Logout Button Styles */
    .logout-nav {
        color: red !important;
        font-weight: 600;
        transition: background-color 0.3s, color 0.3s;
    }

    .logout-nav:hover {
        background-color: rgba(255, 0, 0, 0.2);
        color: #ff4d4d !important;
        font-weight: 700;
    }
</style>