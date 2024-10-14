<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 
        'course_id',
        'progress_percentage',
        'completed',
        'last_viewed_material_id',
        'last_viewed_lesson_id',
        'last_viewed_at',
    ];
}
