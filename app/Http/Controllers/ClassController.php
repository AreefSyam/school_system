<?php
namespace App\Http\Controllers;

use App\Models\AcademicYearModel;
use App\Models\ClassModel;
use App\Models\ClassTeacherYearModel;
use App\Models\StudentModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClassController extends Controller
{
    /**
     * Display a listing of classes.
     * Retrieves all class records along with related grade levels and academic years for filtering options.
     *
     * @return \Illuminate\View\View
     */
    public function list()
    {
        $data['header_title']  = "Class Management";
        $data['get_record']    = ClassModel::getRecordClass();    // Get the class data
        $data['gradeLevels']   = DB::table('grade_level')->get(); // Fetch grade levels to use in the filter dropdown
        $data['academicYears'] = DB::table('academic_year')->get();

        return view('admin.classManagement.list', $data);
    }

    /**
     * Show the form for creating a new class.
     * Provides necessary data to populate select options like grade levels and academic years.
     *
     * @return \Illuminate\View\View
     */
    public function add()
    {
        $data['header_title']  = "Add Class";
        $data['academicYears'] = DB::table('academic_year')->get();
        $data['gradeLevels']   = DB::table('grade_level')->get(); // Fetch grade levels for the form dropdown
        return view('admin.classManagement.add', $data);
    }

    /**
     * Store a newly created class in the database.
     * Validates input data and creates a new class record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAdd(Request $request)
    {
        // Validate inputs
        $request->validate([
            'className'      => 'required|string|max:255|unique:class,name', // Ensures class name is unique
            'status'         => 'required|in:0,1',
            'grade_level_id' => 'required|exists:grade_level,id', // Make sure the grade level exists
        ]);
        try {
            // Create the new class
            $class                   = new ClassModel;
            $class->name             = trim($request->className);
            $class->status           = $request->status;
            $class->grade_level_id   = $request->grade_level_id;
            $class->academic_year_id = $request->academic_year_id; // Store academic year
            $class->created_by       = Auth::user()->id;
            $class->save();
            return redirect()->route('class.list')->with('success', 'New class successfully created');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error creating class: ' . $e->getMessage());
            // Return back with an error message
            return redirect()->back()->with('error', 'There was an issue creating the class. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified class.
     * Fetches and passes class details along with necessary select option data.
     *
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Fetch the class details using the provided ID
        $class = ClassModel::findOrFail($id);
        // Fetch academic years
        $academicYears = AcademicYearModel::all();
        // Fetch grade levels directly from the database
        $gradeLevels = DB::table('grade_level')->orderBy('grade_name', 'asc')->get();
        // Pass the data to the view
        return view('admin.classManagement.edit', [
            'header_title'  => 'Edit Class',
            'class'         => $class,
            'gradeLevels'   => $gradeLevels,
            'academicYears' => $academicYears, // Pass the academic years here
        ]);
    }

    /**
     * Update the specified class in the database.
     * Validates input and updates the class details.
     *
     * @param  int $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, Request $request)
    {
        // Find the class record by ID
        $class = ClassModel::findOrFail($id);
        // Validate the input with uniqueness check for class name
        $request->validate([
            'className'      => 'required|string|max:255|unique:class,name,' . $class->id, // Ensure name is unique except for the current class
            'status'         => 'required|in:0,1',
            'grade_level_id' => 'required|exists:grade_level,id', // Validate grade level exists
        ]);
        // Update the class details
        $class->name             = trim($request->className);
        $class->status           = $request->status;
        $class->grade_level_id   = $request->grade_level_id;
        $class->academic_year_id = $request->academic_year_id;
        $class->save();
        // Redirect to the class list with a success message
        return redirect()->route('class.list')->with('success', 'Class details updated successfully.');
    }

    /**
     * Delete the specified class from the database.
     * Performs a soft deletion and redirects with a success message.
     *
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        $class = ClassModel::findOrFail($id); // Use findOrFail to simplify error handling
        $class->delete();                     // No need to call save() after delete()
        return redirect()->route('class.list')->with('success', 'Class deleted successfully.');
    }

    /**
     * Display the form and handle the assignment of students to a class.
     *
     * @param  int  $classId  The identifier for the class.
     * @return \Illuminate\View\View
     */
    public function assignStudents($classId)
    {
        $class = ClassModel::with('students')->findOrFail($classId); // Load all assigned students

        $academicYears = AcademicYearModel::all();
        $students      = StudentModel::searchStudents(request('student_name'), 10);

        // Get all assigned students without pagination
        $assignedStudents = $class->students;
        return view('admin.classManagement.assignStudents', compact('class', 'students', 'academicYears', 'assignedStudents'));
    }

    /**
     * Process the assignment of students to a class.
     *
     * Validates the request, checks for existing assignments, and updates the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $classId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAssignStudents(Request $request, $classId)
    {
        $class = ClassModel::with('academicYear')->findOrFail($classId);
        // Get the list of already assigned students
        $alreadyAssignedStudents = $request->input('already_assigned_students', []);
        // Validate that student_ids are provided and exist in the database
        $request->validate([
            'student_ids'   => 'required|array',
            'student_ids.*' => 'exists:student,id',
        ]);

        // Remove already assigned students from the student_ids array
        $newStudentIds = array_diff($request->student_ids, $alreadyAssignedStudents);
        // Check if the student is already assigned to another class for the same year
        $assignedStudents = DB::table('class_student')
            ->whereIn('student_id', $newStudentIds)
            ->where('academic_year_id', $class->academic_year_id)
            ->get();
        if ($assignedStudents->isNotEmpty()) {
            // Get the student names and return an error
            $studentNames = StudentModel::whereIn('id', $assignedStudents->pluck('student_id'))->pluck('full_name')->implode(', ');
            return redirect()->back()->withErrors([
                'student_ids' => "The following students are already assigned to a class for this year: $studentNames.",
            ])->withInput();
        }
        // Assign the new students to the class
        foreach ($newStudentIds as $studentId) {
            $class->students()->attach($studentId, ['student_name' => StudentModel::find($studentId)->full_name, 'academic_year_id' => $class->academic_year_id]);
        }
        return redirect()->route('class.assignStudents', $classId)->with('success', 'Students assigned to class successfully.');
    }

    /**
     * Remove a student from a class.
     *
     * @param  int  $classId
     * @param  int  $studentId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeStudent($classId, $studentId)
    {
        $class = ClassModel::findOrFail($classId);
        $class->students()->detach($studentId);
        return redirect()->route('class.assignStudents', $classId)->with('success', 'Student removed from class successfully.');
    }

    /**
     * Display the form for assigning a class teacher to a class.
     *
     * @param  int  $classId
     * @return \Illuminate\View\View
     */
    public function assignTeacher($classId)
    {
        // Fetch the class details
        $class = ClassModel::with('academicYear')->findOrFail($classId);
        // Fetch all teachers with pagination
        $teachers = User::where('role', 'teacher')->paginate(20);
        // Fetch the currently assigned teacher for the class in the specific academic year
        $assignedTeacher = ClassTeacherYearModel::where('class_id', $classId)
            ->where('academic_year_id', $class->academic_year_id)
            ->first();
        return view('admin.classManagement.assignTeacher', compact('class', 'teachers', 'assignedTeacher'));
    }

    /**
     * Handle the assignment of a teacher to a class.
     *
     * Validates the request and updates the teacher assignment for the class.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $classId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAssignTeacher(Request $request, $classId)
    {
        $class = ClassModel::with('academicYear')->findOrFail($classId);
        // Validate the teacher_id input
        $request->validate([
            'teacher_id' => 'required|exists:users,id',
        ]);
        // Ensure the selected teacher is not already assigned to another class for the same year
        $existingAssignment = ClassTeacherYearModel::where('teacher_id', $request->teacher_id)
            ->where('academic_year_id', $class->academic_year_id)
            ->first();
        if ($existingAssignment) {
            return redirect()->back()->withErrors([
                'teacher_id' => 'This teacher is already assigned to another class for the current academic year.',
            ])->withInput();
        }
        // Assign or update the teacher for the class and academic year
        ClassTeacherYearModel::updateOrCreate(
            [
                'class_id'         => $classId,
                'academic_year_id' => $class->academic_year_id,
            ],
            [
                'teacher_id' => $request->teacher_id,
            ]
        );
        return redirect()->route('class.assignTeacher', $classId)->with('success', 'Teacher assigned to class successfully.');
    }

    /**
     * Remove a teacher from a class assignment.
     *
     * @param  int  $classId
     * @param  int  $teacherId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeTeacher($classId, $teacherId)
    {
        // Remove the teacher from the class in the specific academic year
        ClassTeacherYearModel::where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->delete();

        return redirect()->route('class.assignTeacher', $classId)
            ->with('success', 'Teacher removed from class successfully.');
    }

}
