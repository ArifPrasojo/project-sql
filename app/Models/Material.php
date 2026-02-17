<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'classroom_id', 'title', 'category', 'content', 'file_path', 'sequence'
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
    // ====================================

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}