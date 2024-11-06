<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable=[
        'instructor_id',
        'course_id',
        'certificate_type',
        'image'        
    ];

    // Relationship with Instructor
    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }

    // Relationship with Course
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
