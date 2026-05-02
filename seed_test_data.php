<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$professor = \App\Models\User::create([
    'name' => 'Prof. John Smith',
    'email' => 'professor@example.com',
    'password' => bcrypt('password'),
    'role' => 'professor'
]);

$class1 = \App\Models\Classe::create([
    'name' => 'SHAWN CLASS CC104',
    'code' => 'CC104',
    'professor_id' => $professor->id,
]);

$class2 = \App\Models\Classe::create([
    'name' => 'MAP AWARENESS ML101',
    'code' => 'ML101',
    'professor_id' => $professor->id,
]);

$student = \App\Models\User::where('email', 'test@example.com')->first();
$student->enrolledClasses()->attach([$class1->id, $class2->id]);

echo "Classes created and student enrolled!\n";
echo "Student ID: " . $student->id . "\n";
echo "Class 1 ID: " . $class1->id . "\n";
echo "Class 2 ID: " . $class2->id . "\n";
