<?php
require __DIR__ . '/vendor/autoload.php'; // Ensure the autoloader is included

use Picqer\Barcode\BarcodeGeneratorPNG;

if (isset($_GET['code'])) {
    $barcode = $_GET['code'];

    // Create barcode generator instance
    $generator = new BarcodeGeneratorPNG();

    // Set response header to PNG
    header('Content-Type: image/png');

    // Generate and output barcode
    echo $generator->getBarcode($barcode, $generator::TYPE_CODE_128, 2, 50);
} else {
    echo "Invalid barcode.";
}
?>