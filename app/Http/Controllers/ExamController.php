<?php

namespace App\Http\Controllers;

use App\Models\ExamModel;
use App\Models\ClassModel;
use App\Models\StudentModel;
use App\Models\SubjectModel;
use Illuminate\Http\Request;
use App\Models\ExamTypeModel;
use App\Models\SyllabusModel;
use App\Models\AcademicYearModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    // List Exams
    public function list()
    {
        $data['header_title'] = "Exam Management";
        $data['exam_types'] = ExamTypeModel::all(); // Fetch all exam types
        $data['syllabuses'] = SyllabusModel::all(); // Fetch all syllabus options
        $data['academic_years'] = AcademicYearModel::all(); // Fetch all academic years
        $data['get_record'] = ExamModel::getRecordExam(); // Use the search function


        return view('admin.examManagement.manages.list', $data);
    }

    // Show Add Exam Page
    public function add()
    {
        $data['header_title'] = "Add Exam";
        $data['exam_types'] = ExamTypeModel::all(); // Fetch all exam types
        $data['syllabuses'] = SyllabusModel::all(); // Fetch all syllabus options
        $data['academic_years'] = AcademicYearModel::all(); // Fetch all academic years

        return view('admin.examManagement.manages.add', $data);
    }

    // Store New Exam
    public function postAdd(Request $request)
    {
        // Validate inputs
        $request->validate([
            'exam_name' => 'required|string|max:255',
            'exam_type_id' => 'required|exists:exam_type,id',
            'syllabus_id' => 'required|exists:syllabus,id',
            'academic_year_id' => 'required|exists:academic_year,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        try {
            // Create the new exam
            $exam = new ExamModel();
            $exam->exam_name = trim($request->exam_name);
            $exam->exam_type_id = $request->exam_type_id;
            $exam->syllabus_id = $request->syllabus_id;
            $exam->academic_year_id = $request->academic_year_id;
            $exam->start_date = $request->start_date;
            $exam->end_date = $request->end_date;
            $exam->created_by = Auth::user()->id; // Store the creator
            $exam->save();

            return redirect()->route('examManagement.list')->with('success', 'New exam successfully created');
        } catch (\Exception $e) {
            Log::error('Error creating exam: ' . $e->getMessage());
            return redirect()->back()->with('error', 'There was an issue creating the exam. Please try again.');
        }
    }

    // Show Edit Exam Page
    public function edit($id)
    {
        try {
            $exam = ExamModel::findOrFail($id);
            $data['header_title'] = "Edit Exam";
            $data['exam'] = $exam;
            $data['exam_types'] = ExamTypeModel::all();
            $data['syllabuses'] = SyllabusModel::all();
            $data['academic_years'] = AcademicYearModel::all();

            return view('admin.examManagement.manages.edit', $data);
        } catch (\Exception $e) {
            Log::error('Error fetching exam: ' . $e->getMessage());
            return redirect()->route('examManagement.list')->with('error', 'Unable to load exam details.');
        }
    }

    // Update the Exam
    public function update($id, Request $request)
    {
        $exam = ExamModel::findOrFail($id);

        $request->validate([
            'exam_name' => 'required|string|max:255',
            'exam_type_id' => 'required|exists:exam_type,id',
            'syllabus_id' => 'required|exists:syllabus,id',
            'academic_year_id' => 'required|exists:academic_year,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        try {
            $exam->exam_name = trim($request->exam_name);
            $exam->exam_type_id = $request->exam_type_id;
            $exam->syllabus_id = $request->syllabus_id;
            $exam->academic_year_id = $request->academic_year_id;
            $exam->start_date = $request->start_date;
            $exam->end_date = $request->end_date;
            $exam->save();

            return redirect()->route('examManagement.list')->with('success', 'Exam details updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating exam: ' . $e->getMessage());
            return redirect()->back()->with('error', 'There was an issue updating the exam. Please try again.');
        }
    }

    // Delete Exam
    public function delete($id)
    {
        try {
            $exam = ExamModel::findOrFail($id);
            $exam->delete();

            return redirect()->route('examManagement.list')->with('success', 'Exam deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting exam: ' . $e->getMessage());
            return redirect()->route('examManagement.list')->with('error', 'Unable to delete exam.');
        }
    }

    // List of academic years
    public function yearList()
    {
        $academicYears = DB::table('academic_year')->orderBy('start_date', 'desc')->get();
        return view('admin.examManagement.exams.years', compact('academicYears'));
    }

    // List of exam types (PPT, PAT) for a specific year
    public function examTypeList($yearId)
    {
        $year = AcademicYearModel::findOrFail($yearId);

        // Fetch distinct exam types that are registered for the given year
        $examTypes = DB::table('examination')
            ->join('exam_type', 'examination.exam_type_id', '=', 'exam_type.id')
            ->where('examination.academic_year_id', $yearId)
            ->select('exam_type.*')
            ->distinct()
            ->get();

        return view('admin.examManagement.exams.examTypeList', compact('year', 'examTypes'));
    }

    // List of syllabi (KAFA or YTP) for the selected year and exam type
    public function syllabusList($yearId, $examTypeID)
    {
        // Find the selected academic year and exam type
        $year = AcademicYearModel::findOrFail($yearId);
        $examType = ExamTypeModel::findOrFail($examTypeID);

        // Fetch distinct syllabi that are registered for the given year and exam type
        $syllabi = DB::table('examination')
            ->join('syllabus', 'examination.syllabus_id', '=', 'syllabus.id')
            ->where('examination.academic_year_id', $yearId)
            ->where('examination.exam_type_id', $examTypeID)
            ->select('syllabus.*')
            ->distinct()
            ->get();

        return view('admin.examManagement.exams.syllabusList', compact('year', 'examType', 'syllabi'));
    }

    // // List of classes for the selected syllabus and exam type
    public function classList($yearId, $examTypeID, $syllabusID)
    {
        $year = AcademicYearModel::findOrFail($yearId);
        $examType = ExamTypeModel::findOrFail($examTypeID);
        $syllabus = SyllabusModel::findOrFail($syllabusID);
        $classes = ClassModel::where('academic_year_id', $yearId)->get(); // Fetch classes assigned to this exam
        return view('admin.examManagement.exams.classList', compact('year', 'examType', 'syllabus', 'classes'));
    }
}
