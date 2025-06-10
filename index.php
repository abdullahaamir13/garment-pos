<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garment POS - Login</title>
    
    <!-- Bootstrap & Custom Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- Google Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>

</head>
<body style="background-color: black;">

    <!-- Particles Background -->
    <div id="particles-js"></div>
    
    <div class="login-container">
        <div class="login-box shadow-lg">
            
            <!-- Left Side (Login Form) -->
            <div class="login-form">
                <h2 class="text-light text-center mb-4">Login</h2>
                <form action="authenticate.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label text-light">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-light">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>

            <!-- Right Side (Logo) -->
            <div class="login-logo">
                <img src="assets/images/logo.png" alt="A&S Garments Logo">
            </div>

        </div>
    </div>

    <!-- JS & Animation Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/particles.js"></script>
    <script src="assets/js/app.js"></script>

</body>
</html>