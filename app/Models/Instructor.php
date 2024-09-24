<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_bn',
        'contact_en',
        'contact_bn',
        'email',
        'role_id',
        'bio',
        'title',
        'designation',
        'image',
        'status',
        'password',
        'language',
        'access_block',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function courses(){
        return $this->hasMany(Course::class);
    }
}
