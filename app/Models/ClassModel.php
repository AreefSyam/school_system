<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'class';

    public function students()
    {
        return $this->belongsToMany(StudentModel::class, 'class_student', 'class_id', 'student_id');
    }

    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevelModel::class, 'grade_level_id');
    }

    // Relationship with AcademicYearModel
    public function academicYear()
    {
        return $this->belongsTo(AcademicYearModel::class, 'academic_year_id');
    }

    // For assign class teacher
    public function classTeacherYears()
    {
        return $this->hasMany(ClassTeacherYearModel::class, 'class_id');
    }
    public function assignedTeacher($academicYearId)
    {
        return $this->classTeacherYears()->where('academic_year_id', $academicYearId)->with('teacher')->first();
    }


    public static function getRecordClass()
    {
        // Select class records with associated grade level, academic year, and created_by (users table)
        $data = self::select(
            'class.*',
            'grade_level.grade_name',
            'academic_year.academic_year_name',
            'users.name as created_by_name'
        )
            ->join('grade_level', 'grade_level.id', '=', 'class.grade_level_id') // Join grade levels
            ->join('academic_year', 'academic_year.id', '=', 'class.academic_year_id') // Join academic year
            ->join('users', 'users.id', '=', 'class.created_by'); // Join users to get created by

        // Filter by class name
        if (!empty(Request::get('classname'))) {
            $data = $data->where('class.name', 'like', '%' . Request::get('classname') . '%');
        }

        // Filter by grade level if provided
        if (!empty(Request::get('grade_level_id'))) {
            $data = $data->where('class.grade_level_id', '=', Request::get('grade_level_id'));
        }

        // Filter by year if provided
        if (!empty(Request::get('academic_year_id'))) {
            $data = $data->where('class.academic_year_id', '=', Request::get('academic_year_id'));
        }

        $data = $data->orderBy('id', 'desc')->paginate(10);

        return $data;
    }
}
