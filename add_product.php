<?php
include('config.php'); //create conection from database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $purchase_price = (float) $_POST['purchase_price'];
    $selling_price = (float) $_POST['selling_price'];
    $stock_quantity = (int) $_POST['stock_quantity'];

    // Ensure selling price is not lower than purchase price
    if ($selling_price < $purchase_price) {
        echo "Error: Selling price cannot be lower than purchase price.";
        exit();
    }

    // Generate Unique Barcode
    $barcode = time() . rand(100, 999);

    // Secure insertion using prepared statement
    $stmt = $conn->prepare("INSERT INTO products (name, category, purchase_price, selling_price, stock_quantity, barcode) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssddis", $name, $category, $purchase_price, $selling_price, $stock_quantity, $barcode);

    if ($stmt->execute()) {
        header('Location: products.php?success=1');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>