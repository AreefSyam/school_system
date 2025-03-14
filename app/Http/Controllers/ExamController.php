<?php
namespace App\Http\Controllers;

use App\Models\AcademicYearModel;
use App\Models\ClassModel;
use App\Models\ExamModel;
use App\Models\ExamTypeModel;
use App\Models\SubjectModel;
use App\Models\SyllabusModel;
use App\Models\TeacherAssignClasses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExamController extends Controller
{
    /**
     * Display a listing of all exams.
     * Fetches data from multiple models to display in the exam management view.
     *
     * @return \Illuminate\View\View
     */
    public function list()
    {
        $data['header_title']   = "Exam Management";
        $data['exam_types']     = ExamTypeModel::all();       // Fetch all exam types
        $data['syllabuses']     = SyllabusModel::all();       // Fetch all syllabus options
        $data['academic_years'] = AcademicYearModel::all();   // Fetch all academic years
        $data['get_record']     = ExamModel::getRecordExam(); // Use the search function

        return view('admin.examManagement.manages.list', $data);
    }

    /**
     * Show the form for creating a new exam.
     * Includes necessary data to populate dropdowns for exam types, syllabuses, and academic years.
     *
     * @return \Illuminate\View\View
     */
    public function add()
    {
        $data['header_title']   = "Add Exam";
        $data['exam_types']     = ExamTypeModel::all();     // Fetch all exam types
        $data['syllabuses']     = SyllabusModel::all();     // Fetch all syllabus options
        $data['academic_years'] = AcademicYearModel::all(); // Fetch all academic years

        return view('admin.examManagement.manages.add', $data);
    }

    /**
     * Store a newly created exam in the database after validation.
     * Checks for duplicate entries based on exam type, syllabus, and academic year before saving.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAdd(Request $request)
    {
        // Validate inputs
        $request->validate([
            'exam_name'        => 'required|string|max:255',
            'exam_type_id'     => 'required|exists:exam_type,id',
            'syllabus_id'      => 'required|exists:syllabus,id',
            'academic_year_id' => 'required|exists:academic_year,id',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after_or_equal:start_date',
            'status'           => 'required|in:available,unavailable', // Add status validation
        ]);

        // Check if the combination already exists
        $exists = ExamModel::where('exam_type_id', $request->exam_type_id)
            ->where('syllabus_id', $request->syllabus_id)
            ->where('academic_year_id', $request->academic_year_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors([
                'exam_type_id' => 'The selected combination of Exam Type, Academic Year, and Syllabus already exists.',
            ])->withInput();
        }

        try {
            // Create the new exam
            $exam                   = new ExamModel();
            $exam->exam_name        = trim($request->exam_name);
            $exam->exam_type_id     = $request->exam_type_id;
            $exam->syllabus_id      = $request->syllabus_id;
            $exam->academic_year_id = $request->academic_year_id;
            $exam->start_date       = $request->start_date;
            $exam->end_date         = $request->end_date;
            $exam->status           = $request->status; // Add status field
            $exam->created_by       = Auth::user()->id; // Store the creator
            $exam->save();

            return redirect()->route('examManagement.list')->with('success', 'New exam successfully created');
        } catch (\Exception $e) {
            Log::error('Error creating exam: ' . $e->getMessage());
            return redirect()->back()->with('error', 'There was an issue creating the exam. Please try again.');
        }
    }

    /**
     * Show the form for editing an existing exam.
     * Fetches the exam and related data for dropdowns.
     *
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $exam                   = ExamModel::findOrFail($id);
            $data['header_title']   = "Edit Exam";
            $data['exam']           = $exam;
            $data['exam_types']     = ExamTypeModel::all();
            $data['syllabuses']     = SyllabusModel::all();
            $data['academic_years'] = AcademicYearModel::all();

            return view('admin.examManagement.manages.edit', $data);
        } catch (\Exception $e) {
            Log::error('Error fetching exam: ' . $e->getMessage());
            return redirect()->route('examManagement.list')->with('error', 'Unable to load exam details.');
        }
    }

    /**
     * Update the specified exam in the database.
     * Validates input data and updates the exam details.
     *
     * @param  int $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, Request $request)
    {
        $exam = ExamModel::findOrFail($id);

        $request->validate([
            'exam_name'        => 'required|string|max:255',
            'exam_type_id'     => 'required|exists:exam_type,id',
            'syllabus_id'      => 'required|exists:syllabus,id',
            'academic_year_id' => 'required|exists:academic_year,id',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after_or_equal:start_date',
            'status'           => 'required|in:available,unavailable', // Add status validation
        ]);

        try {
            $exam->exam_name        = trim($request->exam_name);
            $exam->exam_type_id     = $request->exam_type_id;
            $exam->syllabus_id      = $request->syllabus_id;
            $exam->academic_year_id = $request->academic_year_id;
            $exam->start_date       = $request->start_date;
            $exam->end_date         = $request->end_date;
            $exam->status           = $request->status; // Save the status field
            $exam->save();

            return redirect()->route('examManagement.list')->with('success', 'Exam details updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating exam: ' . $e->getMessage());
            return redirect()->back()->with('error', 'There was an issue updating the exam. Please try again.');
        }
    }

    /**
     * Delete the specified exam from the database.
     * Performs a soft deletion on the exam record.
     *
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Display a list of all academic years ordered by their start date.
     *
     * @return \Illuminate\View\View
     */
    public function yearList()
    {
        $academicYears = DB::table('academic_year')->orderBy('start_date', 'desc')->get();
        return view('admin.examManagement.exams.years', compact('academicYears'));
    }

    /**
     * List all exam types available for a specific academic year.
     * This function retrieves distinct exam types that have exams registered for the given year.
     *
     * @param  int $yearId The ID of the academic year
     * @return \Illuminate\View\View
     */
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

    /**
     * List of syllabi (KAFA or YTP) for the selected year and exam type
     * Display a list of syllabi for a selected year and exam type.
     * Fetches distinct syllabi that are associated with examinations for the specified year and exam type.
     *
     * @param  int $yearId The ID of the academic year
     * @param  int $examTypeID The ID of the exam type
     * @return \Illuminate\View\View
     */
    public function syllabusList($yearId, $examTypeID)
    {
        // Find the selected academic year and exam type
        $year     = AcademicYearModel::findOrFail($yearId);
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

    /**
     * List all classes available for a selected syllabus, exam type, and academic year.
     * Retrieves classes that are linked to a specific syllabus and exam type within the given year.
     *
     * @param  int $yearId The ID of the academic year
     * @param  int $examTypeID The ID of the exam type
     * @param  int $syllabusID The ID of the syllabus
     * @return \Illuminate\View\View
     */
    public function classList($yearId, $examTypeID, $syllabusID)
    {
        $year     = AcademicYearModel::findOrFail($yearId);
        $examType = ExamTypeModel::findOrFail($examTypeID);
        $syllabus = SyllabusModel::findOrFail($syllabusID);
        $classes  = ClassModel::where('academic_year_id', $yearId)->get(); // Fetch classes assigned to this exam

        // Fetch the specific examination instance
        $exam = ExamModel::where([
            'academic_year_id' => $yearId,
            'exam_type_id'     => $examTypeID,
            'syllabus_id'      => $syllabusID,
        ])->firstOrFail();

        return view('admin.examManagement.exams.classList', compact('year', 'examType', 'syllabus', 'classes', 'exam'));
    }

    // TEACHER
    /**
     * Display a list of available exam types for the current academic year.
     * This function retrieves distinct exam types associated with the authenticated teacher and the academic year from session.
     *
     * @param  int|null $yearId The academic year ID from the session if not provided.
     * @return \Illuminate\View\View
     */
    public function examTypeListTeacher($yearId)
    {
        $currentAcademicYear = AcademicYearModel::findOrFail(session('academic_year_id')); // Get the academic year from the session
        if (! $currentAcademicYear) {                                                       // If no current academic year is found, redirect with an error message
            return redirect()->back()->with('error', 'No academic year is set as current. Please select an academic year.');
        }
        $examTypes = DB::table('examination') // Fetch distinct exam types for the given academic year
            ->join('exam_type', 'examination.exam_type_id', '=', 'exam_type.id')
            ->where('examination.academic_year_id', $currentAcademicYear->id) // Filter by academic year
            ->select('exam_type.id', 'exam_type.exam_type_name')              // Fetch only relevant fields
            ->distinct()                                                      // Ensure no duplicates
            ->get();
        $breadcrumbData = [
            'academicYearName' => $currentAcademicYear->academic_year_name,
        ];
        return view('teacher.examData.examTypeList', compact('currentAcademicYear', 'examTypes', 'breadcrumbData'));
    }

    /**
     * List all syllabi for a specific exam type and academic year.
     * Retrieves syllabi that have examinations scheduled within the selected academic year and exam type.
     *
     * @param  int|null $yearId Optional academic year ID; defaults to session value if not provided.
     * @param  int $examTypeId The ID of the exam type.
     * @return \Illuminate\View\View
     */
    public function syllabusListTeacher($yearId = null, $examTypeId)
    {
        $yearId               = session('academic_year_id'); // Use session year ID if no parameter provided
        $selectedAcademicYear = AcademicYearModel::findOrFail($yearId);
        // fetch data
        $examType = ExamTypeModel::findOrFail($examTypeId);
        $syllabi  = DB::table('examination')
            ->join('syllabus', 'examination.syllabus_id', '=', 'syllabus.id')
            ->where('examination.academic_year_id', $selectedAcademicYear->id)
            ->where('examination.exam_type_id', $examTypeId)
            ->select('syllabus.id', 'syllabus.syllabus_name')
            ->distinct()
            ->get();
        $breadcrumbData = [
            'academicYearName' => $selectedAcademicYear->academic_year_name,
            'examTypeName'     => $examType->exam_type_name,
        ];
        return view('teacher.examData.syllabusList', compact('syllabi', 'yearId', 'examType', 'selectedAcademicYear', 'breadcrumbData'));
    }

    /**
     * List subjects assigned to the authenticated teacher for a specific syllabus and exam type.
     * This includes fetching the subjects where the teacher has classes assigned within the given academic parameters.
     *
     * @param  int|null $yearId Optional academic year ID; defaults to session value if not provided.
     * @param  int $examTypeId The ID of the exam type.
     * @param  int $syllabusId The ID of the syllabus.
     * @return \Illuminate\View\View
     */
    public function subjectListTeacher($yearId = null, $examTypeId, $syllabusId)
    {
        $teacherId            = auth()->id();                           // Get the authenticated teacher's ID
        $teacher              = auth()->user();                         // Fetch the authenticated user object to get user name
        $yearId               = session('academic_year_id');            // Use session year ID if no parameter provided
        $selectedAcademicYear = AcademicYearModel::findOrFail($yearId); // Fetch the academic year for display
        $examType             = ExamTypeModel::findOrFail($examTypeId); // fetch data
        $syllabus             = SyllabusModel::findOrFail($syllabusId);
        // Fetch the subjects assigned to the teacher for the given syllabus and academic year
        $subjects = TeacherAssignClasses::join('subject', 'teacherassignclasses.subject_id', '=', 'subject.id')
            ->select('subject.id', 'subject.subject_name')
            ->where('teacherassignclasses.user_id', $teacherId)
            ->where('teacherassignclasses.academic_year_id', $yearId)
            ->where('teacherassignclasses.syllabus_id', $syllabusId)
            ->distinct()
            ->get();
        // Fetch the name for breadcrumb
        $breadcrumbData = [
            'academicYearName' => $selectedAcademicYear->academic_year_name,
            'examTypeName'     => $examType->exam_type_name,
            'syllabusName'     => $syllabus->syllabus_name,
        ];
        // Pass all data to the view
        return view('teacher.examData.subjectList', compact('subjects', 'yearId', 'examType', 'syllabus', 'selectedAcademicYear', 'breadcrumbData', 'teacher'));
    }

    /**
     * Display classes assigned to a teacher for a specific subject, syllabus, and exam type.
     * Retrieves classes that the teacher is assigned to teach within the selected academic parameters.
     *
     * @param  int|null $yearId Optional academic year ID; defaults to session value if not provided.
     * @param  int $examTypeId The ID of the exam type.
     * @param  int $syllabusId The ID of the syllabus.
     * @param  int $subjectId The ID of the subject.
     * @return \Illuminate\View\View
     */
    public function classListTeacher($yearId = null, $examTypeId, $syllabusId, $subjectId)
    {
        $teacherId            = auth()->id();
        $yearId               = session('academic_year_id');            // Use session year ID if no parameter provided
        $selectedAcademicYear = AcademicYearModel::findOrFail($yearId); // Fetch the academic year for display
        $examType             = ExamTypeModel::findOrFail($examTypeId);
        $syllabus             = SyllabusModel::findOrFail($syllabusId);
        $subject              = SubjectModel::findOrFail($subjectId);
        // Fetch classes assigned to the teacher
        $classes = TeacherAssignClasses::join('class', 'teacherassignclasses.class_id', '=', 'class.id')
            ->select('class.id', 'class.name')
            ->where('teacherassignclasses.user_id', $teacherId)
            ->where('teacherassignclasses.academic_year_id', $yearId)
            ->where('teacherassignclasses.syllabus_id', $syllabusId)
            ->where('teacherassignclasses.subject_id', $subjectId) // Filter by subject
            ->distinct()
            ->get();
        $breadcrumbData = [
            'academicYearName' => $selectedAcademicYear->academic_year_name,
            'examTypeName'     => $examType->exam_type_name,
            'syllabusName'     => $syllabus->syllabus_name,
            'subjectName'      => $subject->subject_name,
        ];
        return view('teacher.examData.classList', compact('classes', 'yearId', 'examType', 'syllabus', 'subject', 'teacherId', 'breadcrumbData'));
    }

    /**
     * Display a list of exam types for the current academic year for the teacher view.
     * This method fetches exam types that have exams associated with them within the specified academic year from session.
     *
     * @param  int|null $yearId The academic year ID; defaults to the session's academic year if not provided.
     * @return \Illuminate\View\View
     */
    public function examTypeListClassTeacher($yearId = null)
    {
        $teacherId           = auth()->id();
        $yearId              = session('academic_year_id'); // Use session year ID if no parameter provided
        $currentAcademicYear = AcademicYearModel::findOrFail($yearId);
        // If no current academic year is found
        if (! $currentAcademicYear) {
            return redirect()->back()->with('error', 'No academic year is set as current. Please select an academic year.');
        }
        // Fetch distinct exam types for the given academic year
        $examTypes = Cache::remember("exam_types_$yearId", 60, function () use ($currentAcademicYear) {
            return DB::table('examination')
                ->join('exam_type', 'examination.exam_type_id', '=', 'exam_type.id')
                ->where('examination.academic_year_id', $currentAcademicYear->id)
                ->select('exam_type.id', 'exam_type.exam_type_name')
                ->distinct()
                ->get();
        });
        return view('teacher.classTeacher.examTypeList', compact('examTypes', 'currentAcademicYear'));
    }

    /**
     * Display a list of syllabi available for a specific exam type within the selected academic year.
     * Retrieves distinct syllabi that are scheduled for exams under the given exam type for class report purposes.
     *
     * @param  int|null $yearId Optional academic year ID; uses session value if not provided.
     * @param  int $examTypeId The ID of the exam type.
     * @return \Illuminate\View\View
     */
    public function syllabusListClassTeacher($yearId = null, $examTypeId)
    {
        $yearId               = session('academic_year_id'); // Use session year ID if no parameter provided
        $selectedAcademicYear = AcademicYearModel::findOrFail($yearId);
        $examType             = ExamTypeModel::select('id', 'exam_type_name')
            ->where('id', $examTypeId)
            ->firstOrFail();
        // Fetch the exam type name
        $examTypeName = DB::table('exam_type')
            ->where('id', $examTypeId)
            ->value('exam_type_name');
        // Fetch syllabi associated with the exam type and academic year
        $syllabi = DB::table('examination')
            ->join('syllabus', 'examination.syllabus_id', '=', 'syllabus.id')
            ->where('examination.academic_year_id', $selectedAcademicYear->id)
            ->where('examination.exam_type_id', $examTypeId)
            ->select('syllabus.id', 'syllabus.syllabus_name')
            ->distinct()
            ->get();

        // Extract syllabus IDs
        $syllabusIds = $syllabi->pluck('id')->toArray();

        // Fetch all exams that match the current academic year, exam type, and involved syllabi.
        $exams = ExamModel::where('academic_year_id', $yearId)
            ->where('exam_type_id', $examTypeId)
            ->whereIn('syllabus_id', $syllabusIds)
            ->get()
            ->keyBy('syllabus_id'); // Mapping exams to their respective syllabi for easy access.

        return view('teacher.classTeacher.syllabusList', compact('syllabi', 'yearId', 'examType', 'selectedAcademicYear', 'examTypeName', 'exams'));
    }

}
