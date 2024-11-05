<?php

namespace App\Models;

use App\Models\SyllabusModel;
use App\Models\ExamTypeModel;
use App\Models\AcademicYearModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExamModel extends Model
{
    use HasFactory;

    protected $table = 'examination';

    protected $fillable = [
        'exam_type_id',
        'academic_year_id',
        'syllabus_id',
        'exam_name',
        'start_date',
        'end_date'
    ];

    // Define the relationship with ExamType
    public function examType()
    {
        return $this->belongsTo(ExamTypeModel::class, 'exam_type_id');
    }

    // Define the relationship with AcademicYear
    public function academicYear()
    {
        return $this->belongsTo(AcademicYearModel::class, 'academic_year_id');
    }

    // Define the relationship with Syllabus
    public function syllabus()
    {
        return $this->belongsTo(SyllabusModel::class, 'syllabus_id');
    }

    // Method to retrieve filtered exam records
    public static function getRecordExam()
    {
        return self::with(['examType', 'academicYear', 'syllabus']) // Eager load relationships
            ->when(Request::get('exam_name'), function ($query) {
                $query->where('exam_name', 'like', '%' . Request::get('exam_name') . '%');
            })
            ->when(Request::get('exam_type_id'), function ($query) {
                $query->where('exam_type_id', Request::get('exam_type_id'));
            })
            ->when(Request::get('academic_year_id'), function ($query) {
                $query->where('academic_year_id', Request::get('academic_year_id'));
            })
            ->when(Request::get('syllabus_id'), function ($query) {
                $query->where('syllabus_id', Request::get('syllabus_id'));
            })
            ->orderBy('start_date', 'desc')
            ->paginate(10);
    }
}
