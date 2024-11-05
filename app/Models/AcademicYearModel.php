<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class AcademicYearModel extends Model
{
    use HasFactory;

    // Link to academic_year table database
    protected $table = 'academic_year';

    static public function getRecordAcademicYear()
    {
        // Select from the academic_year table
        $data = self::select('academic_year.*', 'users.name as created_by_name')
            ->join('users', 'users.id', '=', 'academic_year.created_by');

        // Filter by academic_year_name if provided
        if (!empty(Request::get('academic_year_name'))) {
            $data = $data->where('academic_year.academic_year_name', 'like', '%' . Request::get('academic_year_name') . '%');
        }

        // Filter by status (active or inactive) if provided
        if (!empty(Request::get('status'))) {
            $data = $data->where('academic_year.status', '=', Request::get('status'));
        }

        // Filter by start_date or end_date if provided
        if (!empty(Request::get('start_date'))) {
            $data = $data->whereDate('academic_year.start_date', '>=', Request::get('start_date'));
        }

        if (!empty(Request::get('end_date'))) {
            $data = $data->whereDate('academic_year.end_date', '<=', Request::get('end_date'));
        }

        // Order by id in descending order
        $data = $data->orderBy('id', 'desc')->paginate(10);

        return $data;
    }

    // public function examTypes()
    // {
    //     return $this->belongsToMany(ExamTypeModel::class, 'exam_type_syllabus_year', 'academic_year_id', 'exam_type_id')
    //         ->withPivot('syllabus_id');
    // }
}
