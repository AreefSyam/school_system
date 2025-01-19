<?php

namespace App\Repositories;

use App\Models\MarkModel;

class MarkRepository
{

    public function getMarks($classId, $examTypeId, $syllabusId, $academicYearId)
    {
        return MarkModel::where('class_id', $classId)
            ->where('exam_type_id', $examTypeId)
            ->where('syllabus_id', $syllabusId)
            ->where('academic_year_id', $academicYearId)
            ->get()
            ->groupBy('student_id');
    }

    public function getStudentMarks($studentId, $classId, $examTypeId, $syllabusId, $academicYearId)
    {
        return MarkModel::where('student_id', $studentId)
            ->where('class_id', $classId)
            ->where('exam_type_id', $examTypeId)
            ->where('syllabus_id', $syllabusId)
            ->where('academic_year_id', $academicYearId)
            ->get();
    }

}
