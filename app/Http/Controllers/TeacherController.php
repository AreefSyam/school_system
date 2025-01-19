<?php
namespace App\Http\Controllers;

use App\Models\AcademicYearModel;
use App\Models\ClassModel;
use App\Models\GradeLevelModel;
use App\Models\SubjectModel;
use App\Models\SyllabusModel;
use App\Models\TeacherAssignClasses;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    /**
     * Display a listing of teachers.
     * Retrieves all teachers from the database and sends them to the view.
     *
     * @return \Illuminate\View\View
     */
    public function list()
    {
        $data['get_record']   = User::getRecordTeacher(); // Refactor this if needed, User::getAdmin() should return only teacher
        $data['header_title'] = "Teacher List";
        return view('admin.userManagement.teacher.list', $data);
    }

    /**
     * Show the form for creating a new teacher.
     *
     * @return \Illuminate\View\View
     */
    public function add()
    {
        $data['header_title'] = "Add Teacher";
        return view('admin.userManagement.teacher.add', $data);
    }

    /**
     * Store a newly created teacher in the database.
     * Validates and creates a teacher record with a hashed password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAdd(Request $request)
    {
        // Centralize validation in one step
        $this->validateRequest($request);
        // Create the new teacher
        $user           = new User;
        $user->name     = trim($request->name);
        $user->email    = trim($request->email);
        $user->password = Hash::make($request->password); // Hash the password
        $user->role     = 'teacher';
        $user->save();

        return redirect()->route('teacher.list')->with('success', 'New teacher successfully created');
    }

    /**
     * Show the form for editing an existing teacher.
     *
     * @param  int $id Teacher ID
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $data['user']         = User::findOrFail($id); // Use findOrFail to avoid manual null checking
        $data['header_title'] = "Edit Teacher";
        return view('admin.userManagement.teacher.edit', $data);
    }

    /**
     * Update the specified teacher in the database.
     * Validates input and updates teacher information, password optional.
     *
     * @param  int $id Teacher ID
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, Request $request)
    {
        $user = User::findOrFail($id); // Find the user
                                       // Validate the input with the user ID for uniqueness checks
        $this->validateRequest($request, $user->id);
        // Update the user's details
        $user->name  = trim($request->name);
        $user->email = trim($request->email);
        // Update the password only if a new one is provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        return redirect()->route('teacher.list')->with('success', 'Teacher details updated successfully.');
    }

    /**
     * Delete the specified teacher from the database.
     *
     * @param  int $id Teacher ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        $user = User::findOrFail($id); // Use findOrFail to simplify error handling
        $user->delete();               // No need to call save() after delete()
        return redirect()->route('teacher.list')->with('success', 'Teacher deleted successfully.');
    }

    /**
     * Validates the request data for creating or updating a teacher.
     * Sets rules for name, email, and password fields.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int|null $userId Used for unique validation during updates
     */
    private function validateRequest(Request $request, $userId = null)
    {
        $request->validate([
            'name'     => 'required|string|max:255|unique:users,name,' . $userId, // Ignore current user ID on update
            'email'    => 'required|email|max:255|unique:users,email,' . $userId, // Ignore current user ID on update
            'password' => $userId ? 'nullable|min:6' : 'required|min:6',          // Password is required only on create
        ]);
    }

    /**
     * Show the form for assigning a class to a teacher.
     * Fetches necessary data for dropdowns and displays the assignment form.
     *
     * @param  int  $id  The teacher's ID.
     * @return \Illuminate\View\View
     */
    public function assignClass($id)
    {
        $teacher         = User::where('id', $id)->where('role', 'teacher')->firstOrFail();
        $data['teacher'] = $teacher;
        // Fetch required dropdown data
        $data['subjects']      = SubjectModel::all();
        $data['gradeLevels']   = GradeLevelModel::all();
        $data['academicYears'] = AcademicYearModel::all(); // Send all academic years
        $data['header_title']  = "Assign Class to Teacher";
        $data['syllabuses']    = SyllabusModel::all(); // Include syllabuses
        return view('admin.userManagement.teacher.assignClass', $data);
    }

    /**
     * Process the assignment of a class to a teacher.
     * Validates and creates a new assignment if no duplicate exists.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id  The teacher's ID.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAssignClass(Request $request, $id)
    {
        // Retrieve the teacher with the specified ID and role
        $teacher = User::where('id', $id)
            ->where('role', 'teacher')
            ->firstOrFail();

        // Validate the incoming request
        $validated = $request->validate([
            'subject_id'       => 'required|exists:subject,id',
            'grade_level_id'   => 'required|exists:grade_level,id',
            'class_id'         => 'required|exists:class,id',
            'academic_year_id' => 'required|exists:academic_year,id',
            'syllabus_id'      => 'required|exists:syllabus,id',
        ]);

        // Check for duplicate assignments (optional safeguard)
        $existingAssignment = TeacherAssignClasses::where([
            'user_id'          => $teacher->id,
            'subject_id'       => $validated['subject_id'],
            'grade_level_id'   => $validated['grade_level_id'],
            'class_id'         => $validated['class_id'],
            'academic_year_id' => $validated['academic_year_id'],
            'syllabus_id'      => $validated['syllabus_id'],
        ])->exists();

        if ($existingAssignment) {
            return redirect()
                ->route('teacher.assignClass', $teacher->id)
                ->with('error', 'This class assignment already exists for the selected teacher.');
        }

        // Create a new class assignment
        TeacherAssignClasses::create([
            'user_id'          => $teacher->id,
            'subject_id'       => $validated['subject_id'],
            'grade_level_id'   => $validated['grade_level_id'],
            'class_id'         => $validated['class_id'],
            'academic_year_id' => $validated['academic_year_id'],
            'syllabus_id'      => $validated['syllabus_id'],
        ]);

        // Redirect back with a success message
        return redirect()
            ->route('teacher.classAssignments', $teacher->id)
            ->with('success', 'Class assigned to teacher successfully.');
    }

    /**
     * Display a teacher's class assignments.
     * Optionally filters assignments based on provided criteria.
     *
     * @param  int  $id  The teacher's ID.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function classAssignments($id, Request $request)
    {
        $teacher = User::where('id', $id)->where('role', 'teacher')->firstOrFail();

        $query = TeacherAssignClasses::with([
            'academicYear',
            'class',
            'gradeLevel',
            'subject',
            'syllabus',
        ])->where('user_id', $teacher->id);

        // Apply filters if provided
        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $classAssignments = $query->get();

        // Fetch options for filters
        $academicYears = AcademicYearModel::select('id', 'academic_year_name')->get();
        $classes       = ClassModel::select('id', 'name')->get();
        $subjects      = SubjectModel::select('id', 'subject_name')->get();

        return view('admin.userManagement.teacher.teacherClassList', [
            'teacher'          => $teacher,
            'classAssignments' => $classAssignments,
            'academicYears'    => $academicYears,
            'classes'          => $classes,
            'subjects'         => $subjects,
        ]);
    }

    /**
     * Delete a class assignment.
     * Removes a specific assignment and returns to the previous view with a success message.
     *
     * @param  int  $assignmentId  The class assignment's ID.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAssignment($assignmentId)
    {
        $assignment = TeacherAssignClasses::findOrFail($assignmentId);
        $assignment->delete();

        return back()->with('success', 'Class assignment deleted successfully.');
    }

    /**
     * Retrieve classes based on the selected academic year.
     * Includes grade level information formatted for display.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getClassesByAcademicYear(Request $request)
    {
        $academicYearId = $request->academic_year_id;
        $classes        = ClassModel::where('academic_year_id', $academicYearId)
            ->with('gradeLevel')
            ->get(['id', 'name', 'grade_level_id']);
        return response()->json($classes->map(function ($classes) {
            return [
                'id'   => $classes->id,
                'name' => "{$classes->name} ({$classes->gradeLevel->grade_name})",
            ];
        }));
    }

    /**
     * Retrieve subjects associated with a specific academic year.
     * Formats the response to include syllabus details.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSubjectsByAcademicYear(Request $request)
    {
        $academicYearId = $request->academic_year_id;

        // Validate academic_year_id to ensure it's valid
        if (! $academicYearId) {
            return response()->json(['error' => 'Invalid academic year'], 400);
        }
        $subjects = SubjectModel::where('academic_year_id', $academicYearId)
            ->with('syllabus') // Eager load syllabus relationship
            ->get();
        // Format response with subject and syllabus details
        return response()->json($subjects->map(function ($subject) {
            return [
                'id'   => $subject->id,
                'name' => "{$subject->subject_name} ({$subject->syllabus->syllabus_name})",
            ];
        }));
    }

    /**
     * Fetch syllabus details for a specific subject.
     * Validates input and ensures the subject and syllabus exist.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSyllabusBySubject(Request $request)
    {
        $subjectId = $request->subject_id;

        // Validate the input
        if (! $subjectId) {
            return response()->json(['error' => 'Invalid subject'], 400);
        }

        $subject = SubjectModel::with('syllabus')->find($subjectId);

        if (! $subject || ! $subject->syllabus) {
            return response()->json(['error' => 'No syllabus found for the selected subject'], 404);
        }

        return response()->json([
            'id'   => $subject->syllabus->id,
            'name' => $subject->syllabus->syllabus_name,
        ]);
    }

    /**
     * Retrieve the grade level for a specific class.
     * Validates input and ensures the class and grade level exist.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGradeLevelByClass(Request $request)
    {
        $classId = $request->class_id;

        // Validate the input
        if (! $classId) {
            return response()->json(['error' => 'Invalid class'], 400);
        }

        $class = ClassModel::with('gradeLevel')->find($classId);

        if (! $class || ! $class->gradeLevel) {
            return response()->json(['error' => 'No grade level found for the selected class'], 404);
        }

        return response()->json([
            'id'   => $class->gradeLevel->id,
            'name' => $class->gradeLevel->grade_name,
        ]);
    }

    /**
     * Retrieve students associated with a specific class and academic year.
     * Validates class and academic year IDs before fetching students.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStudentsByClass(Request $request)
    {
        $classId        = $request->class_id;
        $academicYearId = $request->academic_year_id;

        // Validate inputs
        if (! $classId || ! $academicYearId) {
            return response()->json(['error' => 'Invalid class or academic year'], 400);
        }

        // Fetch students from the class_student table based on class_id and academic_year_id
        $students = DB::table('class_student')
            ->where('class_id', $classId)
            ->where('academic_year_id', $academicYearId)
            ->select('student_id as id', 'student_name as full_name') // Use aliases for consistency
            ->get();

        // Return the list of students
        return response()->json($students);
    }

}
