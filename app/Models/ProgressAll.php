<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgressAll extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 
        'lesson_id',
        'material_id', 
        'course_id',
        'progress_percentage',
        'completed',
        'last_viewed_at',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
