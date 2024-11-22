<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSummaryModel extends Model
{
    use HasFactory;

    protected $table = 'students_summary';

    // Define fillable fields to allow mass assignment
    protected $fillable = [
        'student_id',
        'class_id',
        'exam_type_id',
        'syllabus_id',
        'academic_year_id',
        'total_marks',
        'percentage',
        'total_grade',
        'position_in_class',
        'position_in_grade',
        'attendance'
    ];

    // Define relationships if necessary
    public function student()
    {
        return $this->belongsTo(StudentModel::class, 'student_id');
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function examType()
    {
        return $this->belongsTo(ExamTypeModel::class, 'exam_type_id');
    }

    public function syllabus()
    {
        return $this->belongsTo(SyllabusModel::class, 'syllabus_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYearModel::class, 'academic_year_id');
    }
}
