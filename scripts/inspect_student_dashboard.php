<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

$user = User::find(16);
if (! $user) {
    echo "No student found\n";
    exit(1);
}

Auth::setUser($user);
$request = Request::create('/student/dashboard', 'GET');
$response = $kernel->handle($request);
$html = $response->getContent();

$pos = strpos($html, 'function generateQR(');
if ($pos === false) {
    echo "generateQR not found\n";
} else {
    echo "generateQR found at " . $pos . "\n";
}
$pos2 = strpos($html, "generateQR('qrDashboard'");
if ($pos2 === false) {
    echo "qrDashboard call not found\n";
} else {
    echo "qrDashboard call found at " . $pos2 . "\n";
}
$pos3 = strpos($html, 'data-qrcode');
if ($pos3 === false) {
    echo "data-qrcode script tag not preloaded\n";
} else {
    echo "data-qrcode found at " . $pos3 . "\n";
}

$snippet = substr($html, $pos, 300);
echo "--- snippet ---\n" . $snippet . "\n";
