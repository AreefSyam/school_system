<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherAssignClasses extends Model
{
    use HasFactory;

    protected $table = 'teacherassignclasses';

    protected $fillable = [
        'user_id',
        'subject_id',
        'grade_level_id',
        'class_id',
        'academic_year_id',
        'syllabus_id',
    ];

    // Relationship to Academic Year
    public function academicYear()
    {
        return $this->belongsTo(AcademicYearModel::class, 'academic_year_id');
    }

    // Relationship to Class
    public function class ()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    // Relationship to Grade Level
    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevelModel::class, 'grade_level_id');
    }

    // Relationship to Subject
    public function subject()
    {
        return $this->belongsTo(SubjectModel::class, 'subject_id');
    }

    // Relationship to Syllabus
    public function syllabus()
    {
        return $this->belongsTo(SyllabusModel::class, 'syllabus_id');
    }

    // Relationship with ExamTypeModel
    public function examType()
    {
        return $this->belongsTo(ExamTypeModel::class, 'exam_type_id', 'id');
    }
}
