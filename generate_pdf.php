<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;

include("config.php");

// Fetch sales data
$sales = $conn->query("SELECT * FROM Sales ORDER BY sale_date DESC");
if (!$sales) {
    die("Sales Query Failed: " . $conn->error);
}

// Fetch total stock value
$stockValueQuery = $conn->query("SELECT SUM(stock_quantity * selling_price) AS total_stock_value FROM products");
if (!$stockValueQuery) {
    die("Stock Query Failed: " . $conn->error);
}
$stockValue = $stockValueQuery->fetch_assoc()['total_stock_value'] ?? 0;

// Fetch highest-selling product
$highSellingQuery = $conn->query("
    SELECT p.name, SUM(si.quantity) AS total_sold 
    FROM sale_items si 
    JOIN products p ON si.product_id = p.product_id 
    GROUP BY si.product_id 
    ORDER BY total_sold DESC LIMIT 1
");
if (!$highSellingQuery) {
    die("Highest Selling Product Query Failed: " . $conn->error);
}
$highSelling = $highSellingQuery->fetch_assoc();

// Fetch low stock products
$lowStockQuery = $conn->query("SELECT name, stock_quantity FROM products WHERE stock_quantity < 10 ORDER BY stock_quantity ASC");
if (!$lowStockQuery) {
    die("Low Stock Query Failed: " . $conn->error);
}

// Start building the HTML for the report
$html = "
    <h2 style='text-align:center;'>Sales Report</h2>
    
    <h3 style='color: #333;'>Stock Summary</h3>
    <p><strong>Total Stock Value:</strong> PKR " . number_format($stockValue, 2) . "</p>
    <p><strong>Highest Selling Product:</strong> " . ($highSelling ? $highSelling['name'] . ' (' . $highSelling['total_sold'] . ' sold)' : 'No sales yet') . "</p>
    
    <h3 style='color: red;'>Low Stock Alert</h3>
    <ul>";
while ($row = $lowStockQuery->fetch_assoc()) {
    $html .= "<li style='color:red;'><strong>{$row['name']}</strong> - Only <strong>{$row['stock_quantity']}</strong> left!</li>";
}
$html .= "</ul>";

// Sales Data Table
$html .= "<h3>Sales Data</h3>
    <table border='1' cellpadding='5' cellspacing='0' width='100%' style='border-collapse: collapse; text-align:center;'>
        <tr style='background-color:#f2f2f2;'>
            <th>Sale ID</th>
            <th>User ID</th>
            <th>Date</th>
            <th>Total Amount</th>
        </tr>";

while ($row = $sales->fetch_assoc()) {
    $html .= "<tr>
                <td>{$row["sale_id"]}</td>
                <td>{$row["user_id"]}</td>
                <td>{$row["sale_date"]}</td>
                <td>PKR " . number_format($row["total_amount"], 2) . "</td>
              </tr>";
}

$html .= "</table>";

// Generate PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("sales_report.pdf", ["Attachment" => 1]);

?>