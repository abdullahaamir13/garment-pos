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

// Get the last inserted sale_id (the sale that was just completed)
$sale_id = $_GET['sale_id'] ?? null;

if (!$sale_id) {
    // If no sale_id is provided, redirect to the sales page
    header("Location: sales.php");
    exit();
}

// Fetch sale details
$sale_stmt = $conn->prepare("SELECT sale_id, user_id, total_amount, payment_method, sale_date FROM sales WHERE sale_id = ?");
$sale_stmt->bind_param("i", $sale_id);
$sale_stmt->execute();
$sale_result = $sale_stmt->get_result();
$sale = $sale_result->fetch_assoc();
$sale_stmt->close();

if (!$sale) {
    echo "Sale not found!";
    exit();
}

// Fetch sale items
$item_stmt = $conn->prepare("SELECT si.product_id, p.name, si.quantity, si.subtotal FROM sale_items si JOIN products p ON si.product_id = p.product_id WHERE si.sale_id = ?");
$item_stmt->bind_param("i", $sale_id);
$item_stmt->execute();
$item_result = $item_stmt->get_result();
$item_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sale Confirmation - Garment POS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h3>Sale Confirmation</h3>
            </div>
            <div class="card-body">
                <h4>Thank you for your purchase!</h4>
                <p>Your sale has been successfully processed. Below is a summary of your transaction:</p>

                <h5>Sale Details:</h5>
                <ul>
                    <li><strong>Sale ID:</strong> <?= $sale['sale_id'] ?></li>
                    <li><strong>Sale Date:</strong> <?= date('Y-m-d H:i:s', strtotime($sale['sale_date'])) ?></li>
                    <li><strong>Total Amount:</strong> PKR <?= number_format($sale['total_amount'], 2) ?></li>
                    <li><strong>Payment Method:</strong> <?= $sale['payment_method'] ?></li>
                </ul>

                <h5>Items Purchased:</h5>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($item = $item_result->fetch_assoc()) { ?>
                            <tr>
                                <td><?= $item['name'] ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td>PKR <?= number_format($item['subtotal'], 2) ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <a href="sales.php" class="btn btn-primary">Go to Sales Page</a>
            </div>
        </div>
    </div>
</body>
</html>