<?php


/**
 * @property int $id
 * @property int $class_id
 * @property int $student_id
 * @property int $qr_code_id
 * @property string $status
 * @property int|null $minutes_late
 * @property \Illuminate\Support\Carbon|null $recorded_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttendanceRecord extends Model
{
    protected $table = 'attendance_records';

    protected $fillable = [
        'class_id',
        'student_id',
        'qr_code_id',
        'status',
        'minutes_late',
        'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function qrCode(): BelongsTo
    {
        return $this->belongsTo(QRCode::class);
    }
}
