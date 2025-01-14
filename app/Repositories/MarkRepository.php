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

    public function upsertMarks(array $marks)
    {
        return MarkModel::upsert($marks, ['student_id', 'subject_id', 'class_id', 'syllabus_id', 'exam_type_id', 'academic_year_id'], ['mark']);
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

    public function updateMarkStatus($studentId, $subjectId, $classId, $syllabusId, $examTypeId, $academicYearId, $status)
    {
        return MarkModel::where([
            'student_id' => $studentId,
            'subject_id' => $subjectId,
            'class_id' => $classId,
            'syllabus_id' => $syllabusId,
            'exam_type_id' => $examTypeId,
            'academic_year_id' => $academicYearId,
        ])->update(['status' => $status]);
    }

}
