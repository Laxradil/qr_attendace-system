<?php
require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "All Users:\n";
foreach (App\Models\User::all() as $user) {
    echo "  ID: " . $user->id . " - " . $user->name . " - " . $user->email . " - Role: " . $user->role . "\n";
}

echo "\nAll Classes:\n";
foreach (App\Models\Classe::all() as $c) {
    echo "  ID: " . $c->id . " - " . $c->name . "\n";
}

echo "\nClass-Student relationships:\n";
$students = DB::table('class_student')->get();
foreach ($students as $s) {
    echo "  Student ID: " . $s->student_id . " - Class ID: " . $s->class_id . "\n";
}