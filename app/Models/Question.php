<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    // Sesuai file: 2026_02_11_145343_create_questions_table.php
    protected $fillable = [
        'material_id', 'type', 'cognitive_level', 
        'question_text', 'options', 'correct_answer_key', 'points'
    ];

    // Karena kolom 'options' bertipe JSON di database
    protected $casts = [
        'options' => 'array',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}