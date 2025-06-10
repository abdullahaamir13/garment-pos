<?php
include('config.php');

if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);

    // Fetch the product details
    $query = "SELECT * FROM products WHERE product_id = $product_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        die("Product not found.");
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']); // Ensure the ID is properly set

    // Get form data
    $name = $conn->real_escape_string($_POST['name']);
    $category = $conn->real_escape_string($_POST['category']);
    $purchase_price = floatval($_POST['purchase_price']);
    $selling_price = floatval($_POST['selling_price']);
    $stock_quantity = intval($_POST['stock_quantity']);

    // Update query
    $query = "UPDATE products SET 
                name = '$name', 
                category = '$category', 
                purchase_price = '$purchase_price', 
                selling_price = '$selling_price', 
                stock_quantity = '$stock_quantity' 
              WHERE product_id = $product_id";

    if ($conn->query($query) === TRUE) {
        header('Location: products.php');
        exit();
    } else {
        echo "Error updating product: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Edit Product</h2>

    <form action="" method="POST">
        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product_id) ?>">
        
        <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Category</label>
            <input type="text" class="form-control" name="category" value="<?= htmlspecialchars($product['category']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Purchase Price (Rs.)</label>
            <input type="number" class="form-control" name="purchase_price" value="<?= htmlspecialchars($product['purchase_price']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Selling Price (Rs.)</label>
            <input type="number" class="form-control" name="selling_price" value="<?= htmlspecialchars($product['selling_price']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Stock</label>
            <input type="number" class="form-control" name="stock_quantity" value="<?= htmlspecialchars($product['stock_quantity']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>