<?php
include('config.php');
include 'header.php';

// Fetch products from the database
$query = "SELECT * FROM products";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .table-hover tbody tr:hover { background-color: #f5f5f5; }
        .btn-sm { margin: 2px; }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Manage Products</h2>

    <!-- Add Product Button -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addProductModal">
        <i class="fa-solid fa-plus"></i> Add Product
    </button>

    <!-- Search Bar -->
    <div class="mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Search products...">
    </div>

    <!-- Products Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Purchase Price</th>
                    <th>Selling Price</th>
                    <th>Stock</th>
                    <th>Barcode</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['category']) ?></td>
                        <td><?= number_format($row['purchase_price'], 2) ?> Rs.</td>
                        <td><?= number_format($row['selling_price'], 2) ?> Rs.</td>
                        <td><?= htmlspecialchars($row['stock_quantity']) ?></td>
                        <td>
                            <img src="generate_barcode.php?code=<?= urlencode($row['barcode']); ?>" alt="Barcode">
                            <p><?= htmlspecialchars($row['barcode']); ?></p>
                        </td>
                        <td>
                            <a href="edit_product.php?product_id=<?= urlencode($row['product_id']); ?>" class="btn btn-warning btn-sm">
                                <i class="fa-solid fa-pen"></i> Edit
                            </a>
                            <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $row['product_id']; ?>)">
                                <i class="fa-solid fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="add_product.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <input type="text" class="form-control" name="category" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Purchase Price (Rs.)</label>
                        <input type="number" class="form-control" name="purchase_price" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Selling Price (Rs.)</label>
                        <input type="number" class="form-control" name="selling_price" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stock</label>
                        <input type="number" class="form-control" name="stock_quantity" min="0" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Search Functionality (Now searches all columns)
    document.getElementById('searchInput').addEventListener('input', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            row.style.display = rowText.includes(searchValue) ? '' : 'none';
        });
    });

    // Confirm Delete Function
    function confirmDelete(productId) {
        if (confirm('Are you sure you want to delete this product?')) {
            window.location.href = 'delete_product.php?product_id=' + productId;
        }
    }
</script>
</body>
</html>