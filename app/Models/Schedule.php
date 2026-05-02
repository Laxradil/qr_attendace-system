<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'subject_code',
        'subject_name',
        'professor_id',
        'professor',
        'days',
        'time',
        'room',
    ];

    public function professorUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'professor_id');
    }
}