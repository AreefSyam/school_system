<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\StudentModel;
use Illuminate\Http\Request;
use App\Models\AcademicYearModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
{
    public function list()
    {
        $data['header_title'] = "Class Management";
        $data['get_record'] = ClassModel::getRecordClass(); // Get the class data

        // Fetch grade levels to use in the filter dropdown
        $data['gradeLevels'] = DB::table('grade_level')->get();
        $data['academicYears'] = DB::table('academic_year')->get();

        return view('admin.classManagement.list', $data);
    }

    public function add()
    {
        $data['header_title'] = "Add Class";
        $data['academicYears'] = DB::table('academic_year')->get();
        // Fetch grade levels for the form dropdown
        $data['gradeLevels'] = DB::table('grade_level')->get();

        return view('admin.classManagement.add', $data);
    }


    // Post Add
    public function postAdd(Request $request)
    {
        // Validate inputs
        $request->validate([
            'className' => 'required|string|max:255|unique:class,name', // Ensures class name is unique
            'status' => 'required|in:0,1',
            'grade_level_id' => 'required|exists:grade_level,id', // Make sure the grade level exists
        ]);


        try {
            // Create the new class
            $class = new ClassModel;
            $class->name = trim($request->className);
            $class->status = $request->status;
            $class->grade_level_id = $request->grade_level_id;
            $class->academic_year_id = $request->academic_year_id; // Store academic year
            $class->created_by = Auth::user()->id;
            $class->save();

            return redirect()->route('class.list')->with('success', 'New class successfully created');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error creating class: ' . $e->getMessage());

            // Return back with an error message
            return redirect()->back()->with('error', 'There was an issue creating the class. Please try again.');
        }
    }


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
            'header_title' => 'Edit Class',
            'class' => $class,
            'gradeLevels' => $gradeLevels,
            'academicYears' => $academicYears,  // Pass the academic years here
        ]);
    }



    // Update the editing class
    public function update($id, Request $request)
    {
        // Find the class record by ID
        $class = ClassModel::findOrFail($id);

        // Validate the input with uniqueness check for class name
        $request->validate([
            'className' => 'required|string|max:255|unique:class,name,' . $class->id,  // Ensure name is unique except for the current class
            'status' => 'required|in:0,1',
            'grade_level_id' => 'required|exists:grade_level,id', // Validate grade level exists
        ]);

        // Update the class details
        $class->name = trim($request->className);
        $class->status = $request->status;
        $class->grade_level_id = $request->grade_level_id;
        $class->academic_year_id = $request->academic_year_id;
        $class->save();

        // Redirect to the class list with a success message
        return redirect()->route('class.list')->with('success', 'Class details updated successfully.');
    }

    // Delete Class Model
    public function delete($id)
    {
        $class = ClassModel::findOrFail($id); // Use findOrFail to simplify error handling
        $class->delete(); // No need to call save() after delete()

        return redirect()->route('class.list')->with('success', 'Class deleted successfully.');
    }

    public function assignStudents($classId)
    {
        $class = ClassModel::with('students')->findOrFail($classId);
        $students = StudentModel::searchStudents(request('student_name'), 10);

        return view('admin.classManagement.assignStudents', compact('class', 'students'));
    }

    public function postAssignStudents(Request $request, $classId)
    {
        $class = ClassModel::with('academicYear')->findOrFail($classId);

        // Get the list of already assigned students
        $alreadyAssignedStudents = $request->input('already_assigned_students', []);

        // Validate that student_ids are provided and exist in the database
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:student,id'
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
                'student_ids' => "The following students are already assigned to a class for this year: $studentNames."
            ])->withInput();
        }

        // Assign the new students to the class
        foreach ($newStudentIds as $studentId) {
            $class->students()->attach($studentId, ['student_name' => StudentModel::find($studentId)->full_name, 'academic_year_id' => $class->academic_year_id]);
        }

        return redirect()->route('class.assignStudents', $classId)->with('success', 'Students assigned to class successfully.');
    }




    public function removeStudent($classId, $studentId)
    {
        $class = ClassModel::findOrFail($classId);
        $class->students()->detach($studentId);

        return redirect()->route('class.assignStudents', $classId)->with('success', 'Student removed from class successfully.');
    }
}
