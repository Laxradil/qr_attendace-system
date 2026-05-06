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
        $now = Carbon::now();
        $today = $now->format('l');
        $scheduleRecords = Schedule::whereNotNull('end_time')->get();
        $classEndTimes = [];

        foreach ($scheduleRecords as $schedule) {
            if (!$this->scheduleMatchesDay($schedule, $today)) {
                continue;
            }

            try {
                // Handle both H:i and H:i:s formats
                $format = strlen($schedule->end_time) > 5 ? 'H:i:s' : 'H:i';
                $endTime = Carbon::createFromFormat($format, $schedule->end_time);
            } catch (\Exception $e) {
                continue;
            }

            $scheduleEnd = $endTime->setDate($now->year, $now->month, $now->day);
            if ($scheduleEnd->greaterThan($now)) {
                continue;
            }

            if (!isset($classEndTimes[$schedule->class_id]) || $scheduleEnd->greaterThan($classEndTimes[$schedule->class_id])) {
                $classEndTimes[$schedule->class_id] = $scheduleEnd;
            }
        }

        $classIds = array_keys($classEndTimes);
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
                    'recorded_at' => $classEndTimes[$classId],
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
