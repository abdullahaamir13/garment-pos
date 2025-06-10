<?php
include("config.php");

if (isset($_GET["barcode"])) {
    $barcode = $_GET["barcode"];
    
    $stmt = $conn->prepare("SELECT product_id, name, selling_price FROM products WHERE barcode = ?");
    $stmt->bind_param("s", $barcode);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($product = $result->fetch_assoc()) {
        echo json_encode(["success" => true, "product_id" => $product["product_id"], "selling_price" => $product["selling_price"]]);
    } else {
        echo json_encode(["success" => false]);
    }
}
?>