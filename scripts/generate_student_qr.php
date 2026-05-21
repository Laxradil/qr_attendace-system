<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\QRCode;
use Illuminate\Support\Str;

$students = User::where('role', 'student')->get();
$created = 0;
$updated = 0;
$existing = 0;

foreach ($students as $student) {
    $qr = QRCode::firstOrNew(['student_id' => $student->id]);
    if (! $qr->exists) {
        $qr->uuid = Str::uuid()->toString();
        $qr->code = json_encode([
            'type' => 'student_attendance',
            'student_id' => $student->id,
            'student_name' => $student->name,
            'student_email' => $student->email,
            'uuid' => $qr->uuid,
            'generated_at' => now()->toIso8601String(),
        ]);
        $qr->save();
        echo "created: student_id={$student->id} name={$student->name} id={$qr->id}\n";
        $created++;
    } elseif (! $qr->code) {
        $qr->uuid = $qr->uuid ?: Str::uuid()->toString();
        $qr->code = json_encode([
            'type' => 'student_attendance',
            'student_id' => $student->id,
            'student_name' => $student->name,
            'student_email' => $student->email,
            'uuid' => $qr->uuid,
            'generated_at' => now()->toIso8601String(),
        ]);
        $qr->save();
        echo "updated: student_id={$student->id} name={$student->name} id={$qr->id}\n";
        $updated++;
    } else {
        echo "exists: student_id={$student->id} name={$student->name} id={$qr->id}\n";
        $existing++;
    }
}

echo "\nSummary: created={$created} updated={$updated} existing={$existing}\n";
