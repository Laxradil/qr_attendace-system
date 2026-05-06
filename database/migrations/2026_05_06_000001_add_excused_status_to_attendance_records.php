<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE attendance_records DROP CONSTRAINT IF EXISTS attendance_records_status_check');
        DB::statement("ALTER TABLE attendance_records ADD CONSTRAINT attendance_records_status_check CHECK ((status::text = ANY (ARRAY['present'::text, 'late'::text, 'absent'::text, 'excused'::text]::text[])))");
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE attendance_records DROP CONSTRAINT IF EXISTS attendance_records_status_check');
        DB::statement("ALTER TABLE attendance_records ADD CONSTRAINT attendance_records_status_check CHECK ((status::text = ANY (ARRAY['present'::text, 'late'::text, 'absent'::text]::text[])))");
    }
};
