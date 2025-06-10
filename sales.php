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

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Fetch available products with stock
$products = $conn->query("SELECT * FROM products WHERE stock_quantity > 0");

// Handle adding to cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_to_cart"])) {
    $product_id = $_POST["product_id_hidden"] ?? null;
    $quantity = $_POST["quantity"] ?? null;
    $custom_price = $_POST["custom_price"] ?? null;

    if (!$product_id || !$quantity) {
        echo "<script>alert('Please select a product and enter quantity!');</script>";
    } else {
        // Fetch product details
        $stmt = $conn->prepare("SELECT product_id, name, selling_price, purchase_price, stock_quantity FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        if ($product) {
            if ($quantity > $product['stock_quantity']) {
                echo "<script>alert('Only {$product['stock_quantity']} items available!');</script>";
            } else {
                $price = $custom_price ?: $product['selling_price'];

                if ($price < $product['purchase_price']) {
                    echo "<script>alert('Selling price cannot be lower than the purchase price (PKR {$product['purchase_price']})!');</script>";
                } else {
                    $subtotal = $price * $quantity;

                    // Add to cart session
                    $_SESSION['cart'][] = [
                        'product_id' => $product['product_id'],
                        'name' => $product['name'],
                        'price' => $price,
                        'quantity' => $quantity,
                        'subtotal' => $subtotal
                    ];

                    echo "<script>alert('Product added to cart successfully!');</script>";
                }
            }
        }
    }
}

// Handle cart removal
if (isset($_GET['remove'])) {
    $index = $_GET['remove']; // Get the index of the item to remove
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]); // Remove the item from the cart
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex the array to avoid gaps
        echo "<script>alert('Item removed from cart');</script>"; // Notify the user
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Process Sale</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h3>Process Sale</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="product_id" class="form-label">Select Product</label>
                        <select name="product_id" id="product_id" class="form-select" required onchange="updateTotal()">
                            <option value="">-- Select a Product --</option>
                            <?php while ($row = $products->fetch_assoc()) { ?>
                                <option value="<?= $row["product_id"] ?>" data-price="<?= $row["selling_price"] ?>" data-stock="<?= $row["stock_quantity"] ?>">
                                    <?= $row["name"] ?> - PKR <?= number_format($row["selling_price"], 2) ?> (Stock: <?= $row["stock_quantity"] ?>)
                                </option>
                            <?php } ?>
                        </select>
                        <input type="hidden" name="product_id_hidden" id="product_id_hidden">
                        <input type="hidden" id="product_price" value="0">
                    </div>

                    <div class="mb-3">
                        <label for="custom_price" class="form-label">Custom Price (Optional)</label>
                        <input type="number" name="custom_price" id="custom_price" class="form-control" min="0" step="0.01" oninput="updateTotal()">
                    </div>

                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" min="1" value="1" required oninput="updateTotal()">
                    </div>

                    <div class="mb-3">
                        <label for="barcode" class="form-label">Scan Barcode</label>
                        <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Scan barcode" autofocus>
                    </div>

                    <h5 id="total_price" class="text-center">Total: PKR 0.00</h5>

                    <button type="submit" name="add_to_cart" class="btn btn-info w-100">Add to Cart</button>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header bg-dark text-white text-center">
                <h4>Cart Summary</h4>
            </div>
            <div class="card-body">
                <?php if (!empty($_SESSION['cart'])) { ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($_SESSION['cart'] as $index => $item) { ?>
                                <tr>
                                    <td><?= $item['name'] ?></td>
                                    <td>PKR <?= number_format($item['price'], 2) ?></td>
                                    <td><?= $item['quantity'] ?></td>
                                    <td>PKR <?= number_format($item['subtotal'], 2) ?></td>
                                    <td><a href="?remove=<?= $index ?>" class="btn btn-danger btn-sm">Remove</a></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    <!-- Checkout Button -->
                    <a href="checkout.php" class="btn btn-success w-100">Proceed to Checkout</a>

                <?php } else { ?>
                    <p class="text-center">Your cart is empty.</p>
                <?php } ?>
            </div>
        </div>
    </div>

    <script>
        function updateTotal() {
            let price = parseFloat(document.getElementById("custom_price").value) || parseFloat(document.getElementById("product_price").value) || 0;
            let quantity = parseInt(document.getElementById("quantity").value) || 1;
            document.getElementById("total_price").innerText = "Total: PKR " + (price * quantity).toFixed(2);
        }

        document.getElementById("product_id").addEventListener("change", function() {
            let selectedOption = this.options[this.selectedIndex];
            document.getElementById("product_id_hidden").value = this.value;
            document.getElementById("product_price").value = selectedOption.getAttribute("data-price");
            updateTotal();
        });

        document.getElementById("barcode").addEventListener("input", function () {
            let barcode = this.value.trim();
            if (barcode.length > 3) {
                fetch("fetch_product.php?barcode=" + barcode)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById("product_id").value = data.product_id;
                            document.getElementById("product_price").value = data.selling_price;
                            document.getElementById("quantity").value = 1;
                            updateTotal();
                        } else {
                            alert("Product not found!");
                        }
                    });
            }
        });
    </script>
</body>
</html>