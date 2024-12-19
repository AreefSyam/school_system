<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarkModel extends Model
{
    use HasFactory;

    protected $table = 'marks';

    protected $fillable = [
        'student_id', 'class_id', 'subject_id',
        'syllabus_id', 'exam_type_id',
        'academic_year_id', 'mark', 'status', 'summary'
    ];

    public function student()
    {
        return $this->belongsTo(StudentModel::class, 'student_id');
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(SubjectModel::class, 'subject_id');
    }

    public function syllabus()
    {
        return $this->belongsTo(SyllabusModel::class, 'syllabus_id');
    }

    public function examType()
    {
        return $this->belongsTo(ExamTypeModel::class, 'exam_type_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYearModel::class, 'academic_year_id');
    }

}
