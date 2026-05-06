<?php

// Test script to verify attendance recording works

require __DIR__ . '/bootstrap/app.php';

use App\Models\User;
use App\Models\Classe;
use App\Models\AttendanceRecord;
use Illuminate\Support\Facades\DB;

// Get app instance
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Http\Kernel::class);

// Test 1: Check database connectivity
echo "=== Testing Database Connectivity ===\n";
try {
    $connection = DB::connection();
    echo "✓ Database connection successful\n";
} catch (Exception $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 2: Check if users exist
echo "\n=== Testing User Records ===\n";
$professor = User::where('role', 'professor')->first();
$student = User::where('role', 'student')->first();

if ($professor) {
    echo "✓ Found professor: {$professor->name} ({$professor->email})\n";
} else {
    echo "✗ No professor found\n";
}

if ($student) {
    echo "✓ Found student: {$student->name} ({$student->email})\n";
} else {
    echo "✗ No student found\n";
}

// Test 3: Check classes exist
echo "\n=== Testing Classes ===\n";
$classe = Classe::with('professors', 'students')->first();
if ($classe) {
    echo "✓ Found class: {$classe->code} - {$classe->name}\n";
    echo "  Professors: " . $classe->professors->count() . "\n";
    echo "  Students: " . $classe->students->count() . "\n";
} else {
    echo "✗ No classes found\n";
}

// Test 4: Test the whereHas query (the one we fixed)
echo "\n=== Testing Fixed whereHas Query ===\n";
if ($professor && $student && $classe) {
    try {
        $result = Classe::whereHas('professors', function($q) use ($professor) {
            $q->where('users.id', $professor->id);
        })
        ->whereHas('students', function($q) use ($student) {
            $q->where('users.id', $student->id);
        })
        ->find($classe->id);
        
        if ($result) {
            echo "✓ whereHas query successful\n";
            echo "  Found class: {$result->code}\n";
        } else {
            echo "✗ whereHas query returned no results\n";
        }
    } catch (Exception $e) {
        echo "✗ whereHas query failed: " . $e->getMessage() . "\n";
    }
} else {
    echo "⊘ Skipped (insufficient data)\n";
}

// Test 5: Test attendance record creation
echo "\n=== Testing Attendance Record Creation ===\n";
if ($student && $classe) {
    try {
        // Check if record exists today
        $existing = AttendanceRecord::where('student_id', $student->id)
            ->where('class_id', $classe->id)
            ->whereDate('recorded_at', today())
            ->first();
        
        if ($existing) {
            echo "⊘ Record already exists for today, skipping creation\n";
            echo "  Status: {$existing->status}\n";
        } else {
            $record = AttendanceRecord::create([
                'class_id' => $classe->id,
                'student_id' => $student->id,
                'qr_code_id' => null,
                'status' => 'present',
                'recorded_at' => now(),
            ]);
            echo "✓ Attendance record created successfully\n";
            echo "  Record ID: {$record->id}\n";
            echo "  Status: {$record->status}\n";
        }
    } catch (Exception $e) {
        echo "✗ Attendance creation failed: " . $e->getMessage() . "\n";
    }
} else {
    echo "⊘ Skipped (insufficient data)\n";
}

// Test 6: Test model relationships
echo "\n=== Testing Model Relationships ===\n";
try {
    if ($student) {
        $records = $student->attendanceRecords()->count();
        echo "✓ Student attendance records: {$records}\n";
    }
    
    if ($classe) {
        $records = $classe->attendanceRecords()->count();
        echo "✓ Class attendance records: {$records}\n";
    }
} catch (Exception $e) {
    echo "✗ Model relationships failed: " . $e->getMessage() . "\n";
}

echo "\n=== All Tests Complete ===\n";
