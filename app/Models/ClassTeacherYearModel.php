<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassTeacherYearModel extends Model
{
    use HasFactory;

    protected $table = 'class_teacher_year'; // Table name

    protected $fillable = [
        'class_id',
        'teacher_id',
        'academic_year_id',
    ];

    // Relationship to ClassModel
    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    // Relationship to User (Teacher)
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Relationship to AcademicYearModel
    public function academicYear()
    {
        return $this->belongsTo(AcademicYearModel::class, 'academic_year_id');
    }
}
