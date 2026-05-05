<?php

namespace App\Console\Commands;

use App\Models\AttendanceRecord;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ResetAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:reset {--days=1 : Number of days to keep attendance records}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset (delete) attendance records older than specified days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $cutoffDate = Carbon::now()->subDays($days);

        $this->info("Deleting attendance records older than {$days} day(s) ({$cutoffDate->toDateString()})...");

        $deleted = AttendanceRecord::where('recorded_at', '<', $cutoffDate)->delete();

        $this->info("Deleted {$deleted} attendance records.");

        // Also log this action
        \App\Models\SystemLog::create([
            'user_id' => 1, // Assuming admin user ID 1, or you can make it configurable
            'action' => 'other',
            'description' => "Automated reset: Deleted {$deleted} attendance records older than {$days} days",
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Laravel Scheduler',
        ]);

        return Command::SUCCESS;
    }
}
