<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyllabusModel extends Model
{
    use HasFactory;
    protected $table = 'syllabus';

    protected $fillable = ['syllabus_name'];

    public function subjects()
    {
        return $this->hasMany(SubjectModel::class, 'syllabus_id');
    }

    // Relationship with ExamTypeModel through pivot
    // public function examTypes()
    // {
    //     return $this->belongsToMany(ExamTypeModel::class, 'exam_type_syllabus_year', 'syllabus_id', 'exam_type_id')
    //         ->withPivot('academic_year_id');
    // }
}
