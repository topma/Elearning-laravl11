<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_en', 'price', 'image', 'subscription_price','status',
    ];

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

    public function reviews()
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

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'course_id');
    }

    public function segments()
    {
        return $this->hasMany(Segments::class, 'course_id');
    }

    public function segment()
    {
        return $this->hasMany(Segments::class);
    }

    public function progress()
    {
        return $this->hasMany(Progress::class, 'segments_id', 'id');
    }
}
