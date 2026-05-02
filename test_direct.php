<?php
require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\QRCode;

// Get first QR code
$qr = QRCode::first();

if (!$qr) {
    echo "No QR codes in database\n";
    exit;
}

echo "Testing QR code generation directly\n";
echo "=====================================\n";
echo "UUID: " . $qr->uuid . "\n\n";

// Test the SVG generation
$svg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
    ->size(300)
    ->generate($qr->uuid);

$svgString = (string)$svg;

echo "SVG Generated: " . strlen($svgString) . " bytes\n";
echo "First 200 chars:\n";
echo substr($svgString, 0, 200) . "\n";

// Save to file
file_put_contents('test_qr_output.svg', $svgString);
echo "\n✓ Saved to test_qr_output.svg\n";
