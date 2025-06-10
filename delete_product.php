<?php
include 'config.php';

// // Enable error reporting for debugging
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// Check if product_id is set via GET
if (isset($_GET['product_id'])) {
    $id = intval($_GET['product_id']); // Sanitize input

    // Prepare and execute delete query
    $query = "DELETE FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect to products.php after deletion
        header("Location: products.php");
        exit();
    } else {
        echo "Error deleting product: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "Invalid request!";
}

$conn->close();
?>