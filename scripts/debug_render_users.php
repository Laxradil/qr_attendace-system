<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
/** @var \App\Http\Controllers\AdminController $c */
$c = app()->make(\App\Http\Controllers\AdminController::class);
$admin = \App\Models\User::where('role', 'admin')->first();
if ($admin) {
    auth()->setUser($admin);
}

// Provide an empty errors bag to avoid undefined variable in layout
view()->share('errors', new \Illuminate\Support\ViewErrorBag());

$view = $c->users();
if ($view instanceof \Illuminate\Contracts\View\View) {
    echo "--- RENDER START ---\n";
    echo substr($view->render(), 0, 20000);
    echo "\n--- RENDER END ---\n";
} else {
    echo "Controller did not return a View.\n";
}
