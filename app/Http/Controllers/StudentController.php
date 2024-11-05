<?php

namespace App\Http\Controllers;

use App\Models\StudentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    // List Students
    public function list()
    {
        $data['genders'] = StudentModel::getGenders();
        $data['header_title'] = "Student Management";
        $data['get_record'] = StudentModel::getRecordStudent();
        return view('admin.studentManagement.list', $data);
    }

    // Show Add Student Page
    public function add()
    {
        $data['genders'] = StudentModel::getGenders();
        $data['header_title'] = "Add Student";
        return view('admin.studentManagement.add', $data);
    }

    // Store New Student
    public function postAdd(Request $request)
    {
        // Validate inputs
        $request->validate([
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'address' => 'nullable|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'enrollment_date' => 'required|date',
            'ic_number' => 'required|string|max:20|unique:student,ic_number',
        ]);

        try {
            // Create the new student
            $student = new StudentModel();
            $student->full_name = trim($request->full_name);
            $student->date_of_birth = $request->date_of_birth;
            $student->address = $request->address;
            $student->gender = $request->gender;
            $student->enrollment_date = $request->enrollment_date;
            $student->ic_number = $request->ic_number;
            $student->created_by = Auth::user()->id;
            $student->save();

            return redirect()->route('studentManagement.list')->with('success', 'New student successfully created');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error creating student: ' . $e->getMessage());

            // Return back with an error message
            return redirect()->back()->with('error', 'There was an issue creating the student. Please try again.');
        }
    }

    // Show Edit Student Page
    public function edit($id)
    {
        try {
            // Fetch the student details using the provided ID
            $student = StudentModel::findOrFail($id);
            $data['genders'] = StudentModel::getGenders();
            $data['header_title'] = 'Edit Student';

            // Pass the data to the view
            return view('admin.studentManagement.edit', compact('student', 'data'));
        } catch (\Exception $e) {
            // Log the exception and redirect to the student list with an error message
            Log::error('Error fetching student: ' . $e->getMessage());
            return redirect()->route('studentManagement.list')->with('error', 'Unable to load student details.');
        }
    }

    // Update the Student
    public function update($id, Request $request)
    {
        $student = StudentModel::findOrFail($id);

        // Validate inputs with unique checks
        $request->validate([
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'address' => 'nullable|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'enrollment_date' => 'required|date',
            'ic_number' => 'required|string|max:20|unique:student,ic_number,' . $student->id,
        ]);

        try {
            // Update the student's details
            $student->full_name = trim($request->full_name);
            $student->date_of_birth = $request->date_of_birth;
            $student->address = $request->address;
            $student->gender = $request->gender;
            $student->enrollment_date = $request->enrollment_date;
            $student->ic_number = $request->ic_number;
            $student->save();

            // Redirect to the student list with a success message
            return redirect()->route('studentManagement.list')->with('success', 'Student details updated successfully.');
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error updating student: ' . $e->getMessage());
            return redirect()->back()->with('error', 'There was an issue updating the student. Please try again.');
        }
    }

    // Delete Student
    public function delete($id)
    {
        try {
            $student = StudentModel::findOrFail($id);
            $student->delete();

            return redirect()->route('studentManagement.list')->with('success', 'Student deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting student: ' . $e->getMessage());
            return redirect()->route('studentManagement.list')->with('error', 'Unable to delete the student.');
        }
    }
    
}
