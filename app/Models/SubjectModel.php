<?php

namespace App\Models;

use App\Models\SyllabusModel;
use App\Models\User; // Import User model
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubjectModel extends Model
{
    use HasFactory;

    protected $table = 'subject';

    protected $fillable = [
        'subject_name',
        'syllabus_id',
        'created_by' // Add created_by here
    ];

    // Define the relationship with the Syllabus model
    // public function syllabus()
    // {
    //     return $this->belongsTo(SyllabusModel::class);
    // }

    public function syllabus()
    {
        return $this->belongsTo(SyllabusModel::class, 'syllabus_id');
    }

    // Define the relationship with the User model
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Define the many-to-many relationship with the AcademicYear model
    // public function academicYears()
    // {
    //     return $this->belongsToMany(AcademicYearModel::class, 'subject_academic_year', 'subject_id', 'academic_year_id');
    // }

    // Define the many-to-many relationship with the GradeLevel model
    public function gradeLevels()
    {
        return $this->belongsToMany(GradeLevelModel::class, 'subject_grade', 'subject_id', 'grade_level_id')
            ->withPivot('active');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYearModel::class, 'academic_year_id');
    }


    public static function getRecordSubject()
    {
        return self::with(['syllabus', 'gradeLevels', 'creator', 'academicYear'])  // Add 'academicYear' to load the relationship
            ->leftJoin('syllabus', 'syllabus.id', '=', 'subject.syllabus_id')
            ->select('subject.*', 'syllabus.syllabus_name')
            ->when(Request::get('subject_name'), function ($query) {
                $query->where('subject.subject_name', 'like', '%' . Request::get('subject_name') . '%');
            })
            ->when(Request::get('syllabus_id'), function ($query) {
                $query->where('subject.syllabus_id', '=', Request::get('syllabus_id'));
            })
            ->when(Request::get('grade_level_id'), function ($query) {
                $query->whereHas('gradeLevels', function ($q) {
                    $q->where('grade_level_id', Request::get('grade_level_id'))
                        ->where('active', 1);
                });
            })
            ->when(Request::get('academic_year_id'), function ($query) {
                $query->whereHas('gradeLevels', function ($q) {
                    $q->where('academic_year_id', Request::get('academic_year_id'));
                });
            })
            ->paginate(10);
    }
}
