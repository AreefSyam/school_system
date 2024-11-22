<?php

namespace App\Repositories;
use App\Models\StudentSummaryModel;

class StudentSummaryRepository
{
    public function getSummaries($classId, $examTypeId, $syllabusId, $academicYearId)
    {
        return StudentSummaryModel::where('class_id', $classId)
            ->where('exam_type_id', $examTypeId)
            ->where('syllabus_id', $syllabusId)
            ->where('academic_year_id', $academicYearId)
            ->get();
    }

    public function getStudentSummary($studentId, $classId, $examTypeId, $syllabusId, $academicYearId)
    {
        return StudentSummaryModel::where('student_id', $studentId)
            ->where('class_id', $classId)
            ->where('exam_type_id', $examTypeId)
            ->where('syllabus_id', $syllabusId)
            ->where('academic_year_id', $academicYearId)
            ->first();
    }


    public function upsertSummaries(array $summaries)
    {
        return StudentSummaryModel::upsert($summaries, ['student_id', 'class_id', 'exam_type_id', 'syllabus_id', 'academic_year_id'], ['attendance', 'total_marks', 'percentage', 'total_grade']);
    }

    public function calculatePositions($classId, $examTypeId, $syllabusId, $academicYearId, $gradeLevelId)
    {
        // Fetch summaries for position in class
        $classSummaries = StudentSummaryModel::where('class_id', $classId)
            ->where('exam_type_id', $examTypeId)
            ->where('syllabus_id', $syllabusId)
            ->where('academic_year_id', $academicYearId)
            ->orderByDesc('total_marks')
            ->get();

        $classPosition = 1;
        foreach ($classSummaries as $summary) {
            $summary->position_in_class = $classPosition++;
            $summary->save();
        }

        // Fetch summaries for position in grade
        $gradeSummaries = StudentSummaryModel::whereHas('student.classes', function ($query) use ($gradeLevelId) {
            $query->where('grade_level_id', $gradeLevelId);
        })
            ->where('exam_type_id', $examTypeId)
            ->where('syllabus_id', $syllabusId)
            ->where('academic_year_id', $academicYearId)
            ->orderByDesc('total_marks')
            ->get();

        $gradePosition = 1;
        foreach ($gradeSummaries as $summary) {
            $summary->position_in_grade = $gradePosition++;
            $summary->save();
        }
    }

}
