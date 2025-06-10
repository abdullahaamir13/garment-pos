<?php
include("config.php");
include 'header.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (empty($_SESSION['cart'])) {
    header("Location: sales.php");
    exit();
}

// Calculate the total amount for the cart
$total_amount = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_amount += $item['subtotal'];
}

// Handle the sale confirmation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_sale'])) {
    // Get payment details (you can extend this with more fields as needed)
    $payment_method = $_POST['payment_method'] ?? 'Cash';
    $sale_date = date('Y-m-d H:i:s'); // Current date and time
    $user_id = $_SESSION['user_id']; // The logged-in user

    // Insert the sale into the sales table
    $stmt = $conn->prepare("INSERT INTO sales (user_id, total_amount, payment_method, sale_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("idss", $user_id, $total_amount, $payment_method, $sale_date);
    $stmt->execute();
    $sale_id = $stmt->insert_id;
    $stmt->close();

    // Insert sale items into the sale_items table
    foreach ($_SESSION['cart'] as $item) {
        $stmt = $conn->prepare("INSERT INTO sale_items (sale_id, product_id, quantity, subtotal) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $sale_id, $item['product_id'], $item['quantity'], $item['subtotal']);
        $stmt->execute();
        $stmt->close();

        // Update the product stock quantity
        $stmt = $conn->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE product_id = ?");
        $stmt->bind_param("ii", $item['quantity'], $item['product_id']);
        $stmt->execute();
        $stmt->close();
    }

    // Clear the cart
    unset($_SESSION['cart']);
    
    // Redirect to a confirmation page or display success
    echo "<script>alert('Sale completed successfully!'); window.location.href='sale_confirmation.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout - Garment POS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h3>Checkout</h3>
            </div>
            <div class="card-body">
                <h4>Cart Summary</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['cart'] as $item) { ?>
                            <tr>
                                <td><?= $item['name'] ?></td>
                                <td>PKR <?= number_format($item['price'], 2) ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td>PKR <?= number_format($item['subtotal'], 2) ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <h5>Total: PKR <?= number_format($total_amount, 2) ?></h5>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select name="payment_method" id="payment_method" class="form-select" required>
                            <option value="Cash">Cash</option>
                            <option value="Card">Card</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                        </select>
                    </div>

                    <button type="submit" name="confirm_sale" class="btn btn-success w-100">Confirm Sale</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>