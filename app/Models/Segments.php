<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Segments extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_en', 'course_id', 'duration', 'lesson','instructor_id','course_category_id'
    ];

    public function lesson()
    {
        return $this->hasMany(Lesson::class);
    }
    public function courseCategory()
    {
        return $this->belongsTo(CourseCategory::class); 
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function material()
    {
        return $this->hasMany(Material::class);
    }

    public function quiz()
    {
        return $this->hasMany(Quiz::class);
    }

    public function review()
    {
        return $this->hasMany(Review::class);
    }

    public function discussion()
    {
        return $this->hasMany(Discussion::class);
    }

    public function enrollment()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function progress()
    {
        return $this->hasMany(Progress::class, 'course_id', 'id');
    }
}
