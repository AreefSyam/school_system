<?php

namespace App\Http\Controllers;

use App\Models\MarkModel;
use App\Models\ClassModel;
use App\Models\StudentModel;
use Illuminate\Http\Request;
use App\Models\ExamTypeModel;
use App\Models\SyllabusModel;
use App\Models\AcademicYearModel;
use Illuminate\Support\Facades\DB;

class MarkController extends Controller
{
    // Display marks for a specific class, syllabus, and exam type
    public function index($yearId, $examTypeId, $syllabusId, $classId)
    {
        // Fetch the related data
        $class = ClassModel::findOrFail($classId);
        $syllabus = SyllabusModel::findOrFail($syllabusId);
        $examType = ExamTypeModel::findOrFail($examTypeId);
        $year = AcademicYearModel::findOrFail($yearId);

        // Fetch marks for all students in the class for the given syllabus and exam type
        $marks = DB::table('marks')
            ->where('class_id', $classId)
            ->where('exam_type_id', $examTypeId)
            ->where('syllabus_id', $syllabusId)
            ->get()
            ->groupBy('student_id'); // Grouping marks by student_id for easy access in the view

        // Fetch students for the class
        $students = StudentModel::whereHas('classes', function ($query) use ($classId) {
            $query->where('class_id', $classId);
        })->get();

        // Fetch subjects for the syllabus and grade level
        $subjects = DB::table('subject')
            ->join('subject_grade', 'subject.id', '=', 'subject_grade.subject_id')
            ->where('subject.syllabus_id', $syllabusId)
            ->where('subject_grade.grade_level_id', $class->grade_level_id)
            ->select('subject.id as subject_id', 'subject.subject_name')
            ->get();

        // Return the view with the data
        return view('admin.examManagement.exams.marks.tableMarkClass', compact('class', 'syllabus', 'examType', 'year', 'students', 'marks', 'subjects'));
    }

    // Edit marks for a specific class, syllabus, and exam type
    public function edit($yearId, $examTypeId, $syllabusId, $classId)
    {
        $class = ClassModel::findOrFail($classId);
        $syllabus = SyllabusModel::findOrFail($syllabusId);
        $examType = ExamTypeModel::findOrFail($examTypeId);
        $year = AcademicYearModel::findOrFail($yearId);

        $marks = DB::table('marks')
            ->where('class_id', $classId)
            ->where('exam_type_id', $examTypeId)
            ->where('syllabus_id', $syllabusId)
            ->get()
            ->groupBy('student_id');

        $students = StudentModel::whereHas('classes', function ($query) use ($classId) {
            $query->where('class_id', $classId);
        })->get();

        $subjects = DB::table('subject')
            ->join('subject_grade', 'subject.id', '=', 'subject_grade.subject_id')
            ->where('subject.syllabus_id', $syllabusId)
            ->where('subject_grade.grade_level_id', $class->grade_level_id)
            ->select('subject.id as subject_id', 'subject.subject_name')
            ->get();

        return view('admin.examManagement.exams.marks.tableMarkClassEditable', compact('class', 'syllabus', 'examType', 'year', 'students', 'marks', 'subjects'));
    }

    // Update all marks for a specific class, syllabus, and exam type
    public function updateAll(Request $request)
    {
        // Validate the necessary fields
        $request->validate([
            'class_id' => 'required|integer',
            'syllabus_id' => 'required|integer',
            'exam_type_id' => 'required|integer',
            'academic_year_id' => 'required|integer',
        ]);

        $marks = $request->input('marks', []);

        foreach ($marks as $studentId => $subjects) {
            foreach ($subjects as $subjectId => $mark) {
                MarkModel::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'subject_id' => $subjectId,
                    ],
                    [
                        'mark' => $mark,
                        'class_id' => $request->class_id,
                        'syllabus_id' => $request->syllabus_id,
                        'exam_type_id' => $request->exam_type_id,
                        'academic_year_id' => $request->academic_year_id,
                    ]
                );
            }
        }

        return redirect()->route('exams.marks', [
            $request->academic_year_id,
            $request->exam_type_id,
            $request->syllabus_id,
            $request->class_id,
        ])->with('success', 'Marks updated successfully.');
    }

    // Store a new mark
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer',
            'subject_id' => 'required|integer',
            'mark' => 'nullable|integer'
        ]);

        $mark = MarkModel::where('student_id', $validated['student_id'])
                    ->where('subject_id', $validated['subject_id'])
                    ->first();

        if ($mark) {
            $mark->update(['mark' => $validated['mark']]);
        } else {
            MarkModel::create($validated);
        }

        return response()->json(['success' => true]);
    }

    // Update an existing mark
    public function update(Request $request, $studentId, $subjectId)
    {
        $validated = $request->validate([
            'mark' => 'nullable|integer',
        ]);

        $mark = MarkModel::where('student_id', $studentId)->where('subject_id', $subjectId)->first();

        if ($mark) {
            $mark->update(['mark' => $validated['mark']]);
        } else {
            MarkModel::create([
                'student_id' => $studentId,
                'subject_id' => $subjectId,
                'class_id' => $request->class_id,
                'syllabus_id' => $request->syllabus_id,
                'exam_type_id' => $request->exam_type_id,
                'academic_year_id' => $request->academic_year_id,
                'mark' => $validated['mark'],
            ]);
        }

        return response()->json(['success' => true]);
    }

    // Delete a mark
    public function destroy($markId)
    {
        $mark = MarkModel::findOrFail($markId);
        $mark->delete();

        return response()->json(['success' => true]);
    }
}
