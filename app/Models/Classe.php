<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classe extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'code',
        'name',
        'description',
        'professor_id',
        'student_count',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'student_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the professor who owns this class
     */
    public function professor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'professor_id');
    }

    /**
     * Get the students enrolled in this class
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_student', 'class_id', 'student_id')
            ->withTimestamps()
            ->wherePivot('enrolled_at');
    }

    /**
     * Get the QR codes for this class
     */
    public function qrCodes(): HasMany
    {
        return $this->hasMany(QRCode::class);
    }

    /**
     * Get the attendance records for this class
     */
    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    /**
     * Get the schedules for this class
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
}
