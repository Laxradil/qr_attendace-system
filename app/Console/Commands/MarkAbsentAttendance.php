<?php

namespace App\Console\Commands;

use App\Models\AttendanceRecord;
use App\Models\Classe;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MarkAbsentAttendance extends Command
{
    protected $signature = 'attendance:mark-absent';
    protected $description = 'Mark enrolled students absent after the class session has ended if they have no attendance record.';

    public function handle()
    {
        $now = Carbon::now('Asia/Manila');
        $today = $now->format('l');
        $scheduleRecords = Schedule::whereNotNull('start_time')->get();
        $classAbsentTimes = [];

        foreach ($scheduleRecords as $schedule) {
            if (!$this->scheduleMatchesDay($schedule, $today)) {
                continue;
            }

            if (empty($schedule->start_time)) {
                continue;
            }

            try {
                // Handle both H:i and H:i:s formats
                $format = strlen($schedule->start_time) > 5 ? 'H:i:s' : 'H:i';
                $startTime = Carbon::createFromFormat($format, $schedule->start_time, 'Asia/Manila');
            } catch (\Exception $e) {
                continue;
            }

            $scheduleStart = $startTime->setDate($now->year, $now->month, $now->day);
            $absentThreshold = $scheduleStart->copy()->addMinutes(20);
            if ($absentThreshold->greaterThan($now)) {
                continue;
            }

            if (!isset($classAbsentTimes[$schedule->class_id]) || $absentThreshold->greaterThan($classAbsentTimes[$schedule->class_id])) {
                $classAbsentTimes[$schedule->class_id] = $absentThreshold;
            }
        }

        $classIds = array_keys($classAbsentTimes);
        $markedAbsent = 0;
        $nowDate = $now->toDateString();

        foreach ($classIds as $classId) {
            $classe = Classe::with('students')->find($classId);
            if (!$classe) {
                continue;
            }

            foreach ($classe->students as $student) {
                $existing = AttendanceRecord::where('class_id', $classId)
                    ->where('student_id', $student->id)
                    ->whereDate('recorded_at', $nowDate)
                    ->exists();

                if ($existing) {
                    continue;
                }

                AttendanceRecord::create([
                    'class_id' => $classId,
                    'student_id' => $student->id,
                    'qr_code_id' => null,
                    'status' => 'absent',
                    'minutes_late' => 0,
                    'recorded_at' => $classAbsentTimes[$classId]->copy()->setTimezone('UTC'),
                ]);

                $markedAbsent++;
            }
        }

        $this->info("Marked {$markedAbsent} students absent for sessions that have ended.");
        return Command::SUCCESS;
    }

    private function scheduleMatchesDay(Schedule $schedule, string $day): bool
    {
        if (empty($schedule->days)) {
            return false;
        }

        $normalizedDay = strtolower($day);
        return collect($this->parseScheduleDays($schedule->days))
            ->map(fn($value) => strtolower($value))
            ->contains($normalizedDay);
    }

    private function parseScheduleDays(string $days): array
    {
        $tokens = preg_split('/[\s,;\/]+/', trim($days));
        $mapped = [];

        foreach ($tokens as $token) {
            $token = trim($token);
            if (!$token) {
                continue;
            }

            $lower = strtolower($token);
            $map = [
                'mon' => 'Monday',
                'monday' => 'Monday',
                'tue' => 'Tuesday',
                'tues' => 'Tuesday',
                'tuesday' => 'Tuesday',
                'wed' => 'Wednesday',
                'wednesday' => 'Wednesday',
                'thu' => 'Thursday',
                'thur' => 'Thursday',
                'thurs' => 'Thursday',
                'thursday' => 'Thursday',
                'fri' => 'Friday',
                'friday' => 'Friday',
                'sat' => 'Saturday',
                'saturday' => 'Saturday',
                'sun' => 'Sunday',
                'sunday' => 'Sunday',
            ];

            if (isset($map[$lower])) {
                $mapped[] = $map[$lower];
            }
        }

        return array_unique($mapped);
    }
}
