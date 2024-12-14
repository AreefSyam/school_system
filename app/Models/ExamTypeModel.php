<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamTypeModel extends Model
{
    use HasFactory;
    protected $table = 'exam_type';

    protected $fillable = ['exam_type_name'];

    public function exams()
    {
        return $this->hasMany(ExamModel::class, 'exam_type_id');
    }
    
}
