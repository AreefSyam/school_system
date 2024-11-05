<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class StudentModel extends Model
{
    use HasFactory;

    // Link to the student table in the database
    protected $table = 'student';

    protected $fillable = [
        'full_name',
        'date_of_birth',
        'address',
        'gender',
        'enrollment_date',
        'ic_number',
        'created_by'
    ];

    public function classes()
    {
        return $this->belongsToMany(ClassModel::class, 'class_student', 'student_id', 'class_id');
    }

    // Method to retrieve filtered student records
    public static function getRecordStudent()
    {
        // Select student data and the name of the user who created the record
        $data = self::select('student.*', 'users.name as created_by_name')
            ->leftJoin('users', 'users.id', '=', 'student.created_by');

        // Apply filters based on request parameters if they exist
        if (!empty(Request::get('full_name'))) {
            $data->where('student.full_name', 'like', '%' . Request::get('full_name') . '%');
        }

        if (!empty(Request::get('date_of_birth'))) {
            $data->whereDate('student.date_of_birth', '=', Request::get('date_of_birth'));
        }

        if (!empty(Request::get('gender'))) {
            $data->where('student.gender', '=', Request::get('gender'));
        }

        return $data->orderBy('student.id', 'desc')->paginate(10);
    }

    // Automatically generate student_code on creating a new record
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($student) {
            $prefix = 'KA';
            $lastStudent = self::orderBy('id', 'desc')->first();
            $nextNumber = $lastStudent ? $lastStudent->id + 1 : 1;
            $student->student_code = $prefix . str_pad($nextNumber, 5, '0', STR_PAD_LEFT); // e.g., KA00001
        });
    }

    // Method to return the list of genders
    public static function getGenders()
    {
        return ['Male', 'Female'];
    }

    // In StudentModel.php
    public static function searchStudents($name = null, $perPage = 10)
    {
        $query = self::query();

        if (!empty($name)) {
            $query->where('full_name', 'LIKE', '%' . $name . '%');
        }

        return $query->paginate($perPage);
    }
}

