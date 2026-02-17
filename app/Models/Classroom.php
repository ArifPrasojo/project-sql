<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Material;

class Classroom extends Model
{
    // Sesuai file: 2026_02_11_145314_create_classrooms_table.php
    protected $fillable = [
        'teacher_id', 'name', 'code', 'description', 'is_active'
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }
    
    public function students()
    {
        return $this->belongsToMany(User::class, 'class_enrollments', 'classroom_id', 'student_id')
                    ->withPivot('joined_at');
    }
}