<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ClassModel;
use App\Models\SubjectModel;
use Illuminate\Http\Request;
use App\Models\SyllabusModel;
use App\Models\GradeLevelModel;
use App\Models\AcademicYearModel;
use Illuminate\Support\Facades\DB;
use App\Models\TeacherAssignClasses;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    // Show a list of all Admins.
    public function list()
    {
        $data['get_record'] = User::getRecordTeacher(); // Refactor this if needed, User::getAdmin() should return only teacher
        $data['header_title'] = "Teacher List";
        return view('admin.userManagement.teacher.list', $data);
    }

    // Show the form for creating a new Admin.
    public function add()
    {
        $data['header_title'] = "Add Teacher";
        return view('admin.userManagement.teacher.add', $data);
    }

    // Store a new Admin.
    public function postAdd(Request $request)
    {
        // Centralize validation in one step
        $this->validateRequest($request);
        // Create the new teacher
        $user = new User;
        $user->name = trim($request->name);
        $user->email = trim($request->email);
        $user->password = Hash::make($request->password); // Hash the password
        $user->role = 'teacher';
        $user->save();

        return redirect()->route('teacher.list')->with('success', 'New teacher successfully created');
    }

    // Show the form for editing an existing Admin.
    public function edit($id)
    {
        $data['user'] = User::findOrFail($id); // Use findOrFail to avoid manual null checking
        $data['header_title'] = "Edit Teacher";
        return view('admin.userManagement.teacher.edit', $data);
    }

    // Update the specified Admin in storage.
    public function update($id, Request $request)
    {
        $user = User::findOrFail($id); // Find the user or throw 404
        // Validate the input with the user ID for uniqueness checks
        $this->validateRequest($request, $user->id);
        // Update the user's details
        $user->name = trim($request->name);
        $user->email = trim($request->email);
        // Update the password only if a new one is provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        return redirect()->route('teacher.list')->with('success', 'Teacher details updated successfully.');
    }

    // Delete the specified Admin.
    public function delete($id)
    {
        $user = User::findOrFail($id); // Use findOrFail to simplify error handling
        $user->delete(); // No need to call save() after delete()
        return redirect()->route('teacher.list')->with('success', 'Teacher deleted successfully.');
    }

    // Validate the incoming request for both creating and updating an teacher.
    private function validateRequest(Request $request, $userId = null)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:users,name,' . $userId, // Ignore current user ID on update
            'email' => 'required|email|max:255|unique:users,email,' . $userId, // Ignore current user ID on update
            'password' => $userId ? 'nullable|min:6' : 'required|min:6', // Password is required only on create
        ]);
    }

    public function assignClass($id)
    {
        $teacher = User::where('id', $id)->where('role', 'teacher')->firstOrFail();
        $data['teacher'] = $teacher;
        // Fetch required dropdown data
        $data['subjects'] = SubjectModel::all();
        $data['gradeLevels'] = GradeLevelModel::all();
        $data['academicYears'] = AcademicYearModel::all(); // Send all academic years
        $data['header_title'] = "Assign Class to Teacher";
        $data['syllabuses'] = SyllabusModel::all(); // Include syllabuses
        return view('admin.userManagement.teacher.assignClass', $data);
    }

    public function postAssignClass(Request $request, $id)
    {
        // Retrieve the teacher with the specified ID and role
        $teacher = User::where('id', $id)
            ->where('role', 'teacher')
            ->firstOrFail();

        // Validate the incoming request
        $validated = $request->validate([
            'subject_id' => 'required|exists:subject,id',
            'grade_level_id' => 'required|exists:grade_level,id',
            'class_id' => 'required|exists:class,id',
            'academic_year_id' => 'required|exists:academic_year,id',
            'syllabus_id' => 'required|exists:syllabus,id',
        ]);

        // Check for duplicate assignments (optional safeguard)
        $existingAssignment = TeacherAssignClasses::where([
            'user_id' => $teacher->id,
            'subject_id' => $validated['subject_id'],
            'grade_level_id' => $validated['grade_level_id'],
            'class_id' => $validated['class_id'],
            'academic_year_id' => $validated['academic_year_id'],
            'syllabus_id' => $validated['syllabus_id'],
        ])->exists();

        if ($existingAssignment) {
            return redirect()
                ->route('teacher.assignClass', $teacher->id)
                ->with('error', 'This class assignment already exists for the selected teacher.');
        }

        // Create a new class assignment
        TeacherAssignClasses::create([
            'user_id' => $teacher->id,
            'subject_id' => $validated['subject_id'],
            'grade_level_id' => $validated['grade_level_id'],
            'class_id' => $validated['class_id'],
            'academic_year_id' => $validated['academic_year_id'],
            'syllabus_id' => $validated['syllabus_id'],
        ]);

        // Redirect back with a success message
        return redirect()
            // ->route('teacher.list')
            ->route('teacher.classAssignments', $teacher->id)
            ->with('success', 'Class assigned to teacher successfully.');
    }

    public function classAssignments($id)
    {
        $teacher = User::where('id', $id)->where('role', 'teacher')->firstOrFail();

        $classAssignments = TeacherAssignClasses::with([
            'academicYear',
            'class',
            'gradeLevel',
            'subject',
            'syllabus',
        ])->where('user_id', $teacher->id)->get();

        return view('admin.userManagement.teacher.teacherClassList', [
            'teacher' => $teacher,
            'classAssignments' => $classAssignments,
        ]);
    }

    public function deleteAssignment($assignmentId)
    {
        $assignment = TeacherAssignClasses::findOrFail($assignmentId);
        $assignment->delete();

        return back()->with('success', 'Class assignment deleted successfully.');
    }

    // Fetch classes dynamically based on academic year
    public function getClassesByAcademicYear(Request $request)
    {
        $academicYearId = $request->academic_year_id;
        $classes = ClassModel::where('academic_year_id', $academicYearId)
            ->with('gradeLevel')
            ->get(['id', 'name', 'grade_level_id']);
        return response()->json($classes->map(function ($classes) {
            return [
                'id' => $classes->id,
                'name' => "{$classes->name} ({$classes->gradeLevel->grade_name})",
            ];
        }));
    }

    public function getSubjectsByAcademicYear(Request $request)
    {
        $academicYearId = $request->academic_year_id;

        // Validate academic_year_id to ensure it's valid
        if (!$academicYearId) {
            return response()->json(['error' => 'Invalid academic year'], 400);
        }
        $subjects = SubjectModel::where('academic_year_id', $academicYearId)
            ->with('syllabus') // Eager load syllabus relationship
            ->get();
        // Format response with subject and syllabus details
        return response()->json($subjects->map(function ($subject) {
            return [
                'id' => $subject->id,
                'name' => "{$subject->subject_name} ({$subject->syllabus->syllabus_name})",
            ];
        }));
    }

    public function getSyllabusBySubject(Request $request)
    {
        $subjectId = $request->subject_id;

        // Validate the input
        if (!$subjectId) {
            return response()->json(['error' => 'Invalid subject'], 400);
        }

        $subject = SubjectModel::with('syllabus')->find($subjectId);

        if (!$subject || !$subject->syllabus) {
            return response()->json(['error' => 'No syllabus found for the selected subject'], 404);
        }

        return response()->json([
            'id' => $subject->syllabus->id,
            'name' => $subject->syllabus->syllabus_name,
        ]);
    }

    public function getGradeLevelByClass(Request $request)
    {
        $classId = $request->class_id;

        // Validate the input
        if (!$classId) {
            return response()->json(['error' => 'Invalid class'], 400);
        }

        $class = ClassModel::with('gradeLevel')->find($classId);

        if (!$class || !$class->gradeLevel) {
            return response()->json(['error' => 'No grade level found for the selected class'], 404);
        }

        return response()->json([
            'id' => $class->gradeLevel->id,
            'name' => $class->gradeLevel->grade_name,
        ]);
    }

    public function getStudentsByClass(Request $request)
    {
        $classId = $request->class_id;
        $academicYearId = $request->academic_year_id;

        // Validate inputs
        if (!$classId || !$academicYearId) {
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
