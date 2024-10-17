<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Notifications\Notifiable;

class Student extends Model implements CanResetPassword
{
    use HasFactory, Notifiable, CanResetPasswordTrait;

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\StudentResetPasswordNotification($token));
    }

    protected $fillable = [
        'name_en',
        'name_bn',
        'contact_en',
        'contact_bn',
        'email',
        'date_of_birth',
        'gender',
        'image',
        'bio',
        'profession',
        'nationality',
        'address',
        'city',
        'state',
        'postcode',
        'country',
        'status',
        // 'password',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function answer()
    {
        return $this->hasMany(Answer::class);
    }

    public function review()
    {
        return $this->hasMany(Review::class);
    }

    public function enrollment()
    {
        return $this->hasMany(Enrollment::class);
    }

    // public function isEnrolledInCourse($courseId)
    // {
    //     \Log::info('Checking enrollment for Student ID: ' . $this->id . ', Course ID: ' . $courseId);
    //     return $this->enrollments()->where('course_id', $courseId)->exists();
    // }
}
