<?php
include("config.php");
include("header.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Fetch total stock value
$stockValueQuery = $conn->query("SELECT SUM(stock_quantity * selling_price) AS total_stock_value FROM products");
$stockValue = $stockValueQuery->fetch_assoc()['total_stock_value'] ?? 0;

// Fetch highest-selling product
$highSellingQuery = $conn->query("
    SELECT p.name, SUM(si.quantity) AS total_sold 
    FROM sale_items si 
    JOIN products p ON si.product_id = p.product_id 
    GROUP BY si.product_id 
    ORDER BY total_sold DESC LIMIT 1
");
$highSelling = $highSellingQuery->fetch_assoc();

// Fetch low stock products
$lowStockQuery = $conn->query("SELECT name, stock_quantity FROM products WHERE stock_quantity < 10 ORDER BY stock_quantity ASC");

// Fetch product-wise sales table
$productSalesQuery = $conn->query("
    SELECT p.name, SUM(si.quantity) AS total_sold, p.stock_quantity, p.selling_price
    FROM sale_items si 
    JOIN products p ON si.product_id = p.product_id 
    GROUP BY si.product_id 
    ORDER BY total_sold DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sales Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #fff;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        .main-content {
            padding: 20px;
        }
        .card {
            border: none;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .icon {
            font-size: 24px;
            margin-right: 10px;
            color: #6c757d;
        }
    </style>
</head>
<body>

<!-- Main Content -->
<div class="main-content">
    <div class="container">
        <h3 class="mb-4">Sales Report</h3>

        <div class="row">
            <!-- Total Stock Value -->
            <div class="col-md-4">
                <div class="card p-3">
                    <h5><i class="fas fa-warehouse icon"></i> Total Stock Value</h5>
                    <h3 class="text-primary">PKR <?= number_format($stockValue, 2) ?></h3>
                </div>
            </div>

            <!-- Highest Selling Product -->
            <div class="col-md-4">
                <div class="card p-3">
                    <h5><i class="fas fa-shopping-cart icon"></i> Highest Selling Product</h5>
                    <h4 class="text-success">
                        <?= $highSelling ? $highSelling['name'] . ' (' . $highSelling['total_sold'] . ' sold)' : 'No sales yet' ?>
                    </h4>
                </div>
            </div>

            <!-- Low Stock Alert -->
            <div class="col-md-4">
                <div class="card p-3">
                    <h5><i class="fas fa-exclamation-triangle icon text-danger"></i> Low Stock Alert</h5>
                    <ul class="list-group">
                        <?php while ($row = $lowStockQuery->fetch_assoc()) { ?>
                            <li class="list-group-item d-flex justify-content-between">
                                <?= $row['name'] ?>
                                <span class="badge bg-danger"><?= $row['stock_quantity'] ?> left</span>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- PDF Export Button -->
        <div class="d-flex justify-content-end mt-3">
            <button onclick="window.location.href='generate_pdf.php'" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Download PDF Report
            </button>
        </div>

        <!-- Sales Table -->
        <div class="card mt-4 p-3">
            <h5><i class="fas fa-table icon"></i> All Sales</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Product Name</th>
                            <th>Units Sold</th>
                            <th>Stock Remaining</th>
                            <th>Price Per Unit (PKR)</th>
                            <th>Total Revenue (PKR)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $productSalesQuery->fetch_assoc()) { ?>
                            <tr>
                                <td><?= $row['name'] ?></td>
                                <td><?= $row['total_sold'] ?></td>
                                <td><?= $row['stock_quantity'] ?></td>
                                <td><?= number_format($row['selling_price'], 2) ?></td>
                                <td><?= number_format($row['total_sold'] * $row['selling_price'], 2) ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

</body>
</html>