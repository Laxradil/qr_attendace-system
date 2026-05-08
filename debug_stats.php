<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

use App\Models\AttendanceRecord;

$stats = AttendanceRecord::selectRaw(
    'COUNT(*) as total_records, 
     SUM(CASE WHEN status = \'present\' THEN 1 ELSE 0 END) as present_count,
     SUM(CASE WHEN status = \'late\' THEN 1 ELSE 0 END) as late_count,
     SUM(CASE WHEN status = \'absent\' THEN 1 ELSE 0 END) as absent_count,
     SUM(CASE WHEN status = \'excused\' THEN 1 ELSE 0 END) as excused_count'
)->first();

echo "Stats:\n";
var_dump($stats);
echo "\nTotal Records: " . ($stats?->total_records ?? 'NULL') . "\n";
echo "Present Count: " . ($stats?->present_count ?? 'NULL') . "\n";
echo "Late Count: " . ($stats?->late_count ?? 'NULL') . "\n";
echo "Absent Count: " . ($stats?->absent_count ?? 'NULL') . "\n";
echo "Excused Count: " . ($stats?->excused_count ?? 'NULL') . "\n";
