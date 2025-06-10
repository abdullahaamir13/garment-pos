<?php
include("header.php");
include("config.php");

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Fetch Users
$result = $conn->query("SELECT user_id, username, role FROM Users");

// Handle User Addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_user"])) {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $role = $_POST["role"];

    // Validate inputs
    if (!empty($username) && !empty($password) && in_array($role, ["Admin", "Cashier"])) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO Users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashed_password, $role);
        $stmt->execute();
        $stmt->close();
        
        header("Location: users.php");
        exit();
    }
}

// Handle User Deletion (Prevent Admin Deletion)
if (isset($_GET["delete"])) {
    $user_id = intval($_GET["delete"]); // Ensure it's an integer

    if ($user_id > 0) {
        // Check the user's role before deletion
        $stmt = $conn->prepare("SELECT role FROM Users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($role);
        $stmt->fetch();
        $stmt->close();

        // Only allow deletion if the user is NOT an Admin
        if ($role !== "Admin") {
            $stmt = $conn->prepare("DELETE FROM Users WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();
        }
    }

    header("Location: users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Users | Garment POS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2 class="text-center text-primary">User Management</h2>
    
    <table class="table table-striped table-bordered mt-3">
        <thead class="table-dark">
            <tr>
                <th>Username</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($row["username"]) ?></td>
                <td><?= htmlspecialchars($row["role"]) ?></td>
                <td>
                    <?php if ($row["role"] !== "Admin") { ?>
                        <a href="users.php?delete=<?= $row['user_id'] ?>" class="btn btn-danger btn-sm"
                           onclick="return confirm('Are you sure you want to delete this user?');">
                            <i class="fa fa-trash"></i> Delete
                        </a>
                    <?php } else { ?>
                        <button class="btn btn-secondary btn-sm" disabled>
                            <i class="fa fa-lock"></i> Admin
                        </button>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <h3 class="text-center text-success mt-4">Add New User</h3>
    <form method="POST" class="w-50 mx-auto p-3 border rounded bg-white">
        <div class="mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <div class="mb-3">
            <select name="role" class="form-select" required>
                <option value="Admin">Admin</option>
                <option value="Cashier">Cashier</option>
            </select>
        </div>
        <button type="submit" name="add_user" class="btn btn-primary w-100">
            <i class="fa fa-user-plus"></i> Add User
        </button>
    </form>
</div>
</body>
</html>