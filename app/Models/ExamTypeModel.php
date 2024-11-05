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

    // Relationship with SyllabusModel through pivot
    // public function syllabuses()
    // {
    //     return $this->belongsToMany(SyllabusModel::class, 'exam_type_syllabus_year', 'exam_type_id', 'syllabus_id')
    //         ->withPivot('academic_year_id');
    // }
}
