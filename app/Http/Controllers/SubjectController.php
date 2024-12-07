<?php

namespace App\Http\Controllers;

use App\Models\SubjectModel;
use App\Models\SyllabusModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    public function list()
    {
        $data['header_title'] = "Subject Management";
        $data['syllabuses'] = SyllabusModel::all(); // Fetch all syllabus options
        $data['gradeLevels'] = DB::table('grade_level')->get(); // Fetch grade levels for the form dropdown
        $data['academic_years'] = DB::table('academic_year')->get(); // Fetch academic years
        $data['get_record'] = SubjectModel::getRecordSubject(); // Apply filters in the model method
        return view('admin.subjectManagement.list', $data);
    }

    // Show Add Subject Page
    public function add()
    {
        $data['header_title'] = "Add Subject";
        $data['syllabuses'] = SyllabusModel::all(); // Fetch all syllabus options
        $data['gradeLevels'] = DB::table('grade_level')->get(); // Fetch grade levels for the form dropdown
        $data['academic_years'] = DB::table('academic_year')->get(); // Fetch academic years

        return view('admin.subjectManagement.add', $data);
    }

    // Store New Subject
    public function postAdd(Request $request)
    {
        // Validate inputs
        $request->validate([
            // 'subject_name' => 'required|string|max:255',
            'subject_name' => [
                'required',
                'string',
                'max:255',
                // Custom rule to ensure unique combination of subject_name, syllabus_id, and academic_year_id
                function ($attribute, $value, $fail) use ($request) {
                    $exists = SubjectModel::where('subject_name', $value)
                        ->where('syllabus_id', $request->syllabus_id)
                        ->where('academic_year_id', $request->academic_year_id)
                        ->exists();

                    if ($exists) {
                        $fail('The subject with the same name and syllabus already exists for the selected academic year.');
                    }
                },
            ],
            'syllabus_id' => 'required|exists:syllabus,id',
            'grade_level_id' => 'required|array|min:1', // Ensure at least one grade level is selected
            'grade_level_id.*' => 'exists:grade_level,id', // Ensure all grade levels exist
            'academic_year_id' => 'required|exists:academic_year,id' // Ensure the academic year is valid
        ]);

        try {
            // Create the new subject
            $subject = new SubjectModel();
            $subject->subject_name = trim($request->subject_name);
            $subject->syllabus_id = $request->syllabus_id;
            $subject->academic_year_id = $request->academic_year_id; // Set academic year
            $subject->created_by = Auth::user()->id;
            $subject->save();

            // Retrieve all grade levels from the request
            $gradeLevels = $request->grade_level_id;

            // Loop through each grade level and insert into the subject_grade table
            foreach ($gradeLevels as $gradeId) {
                DB::table('subject_grade')->insert([
                    'subject_id' => $subject->id,
                    'grade_level_id' => $gradeId,
                    'active' => 1, // Active for selected grades
                ]);
            }

            return redirect()->route('subjectManagement.list')->with('success', 'New subject successfully created.');
        } catch (\Exception $e) {
            Log::error('Error creating subject or marks: ' . $e->getMessage());
            return redirect()->back()->with('error', 'There was an issue creating the subject. Please try again.');
        }
    }

    // Show Edit Subject Page
    public function edit($id)
    {
        try {
            $subject = SubjectModel::with('gradeLevels')->findOrFail($id); // Fetch subject with associated grades
            $data['header_title'] = "Edit Subject";
            $data['subject'] = $subject;
            $data['syllabuses'] = SyllabusModel::all();
            $data['gradeLevels'] = DB::table('grade_level')->get();
            $data['academic_years'] = DB::table('academic_year')->get(); // Fetch academic years

            return view('admin.subjectManagement.edit', $data);
        } catch (\Exception $e) {
            Log::error('Error fetching subject: ' . $e->getMessage());
            return redirect()->route('subjectManagement.list')->with('error', 'Unable to load subject details.');
        }
    }

    // Update the Subject
    public function update($id, Request $request)
    {
        $subject = SubjectModel::findOrFail($id);

        $request->validate([
            'subject_name' => 'required|string|max:255|unique:subject,subject_name,' . $subject->id,
            'syllabus_id' => 'required|exists:syllabus,id',
            'grade_level_id' => 'required|array|min:1',
            'grade_level_id.*' => 'exists:grade_level,id',
            'academic_year_id' => 'required|exists:academic_year,id' // Ensure the academic year is valid
        ]);

        try {
            $subject->subject_name = trim($request->subject_name);
            $subject->syllabus_id = $request->syllabus_id;
            $subject->academic_year_id = $request->academic_year_id; // Update academic year
            $subject->save();

            // Update active statuses in subject_grade table based on selection
            foreach ($request->grade_level_id as $gradeId) {
                DB::table('subject_grade')->updateOrInsert(
                    ['subject_id' => $subject->id, 'grade_level_id' => $gradeId],
                    ['active' => 1]
                );
            }

            // Deactivate grades that are not selected
            DB::table('subject_grade')
                ->where('subject_id', $subject->id)
                ->whereNotIn('grade_level_id', $request->grade_level_id)
                ->update(['active' => 0]);

            return redirect()->route('subjectManagement.list')->with('success', 'Subject details updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating subject: ' . $e->getMessage());
            return redirect()->back()->with('error', 'There was an issue updating the subject. Please try again.');
        }
    }

    // Delete Subject
    public function delete($id)
    {
        try {
            $subject = SubjectModel::findOrFail($id);
            $subject->gradeLevels()->detach(); // Detach associated grade levels
            $subject->delete();

            return redirect()->route('subjectManagement.list')->with('success', 'Subject deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting subject: ' . $e->getMessage());
            return redirect()->route('subjectManagement.list')->with('error', 'Unable to delete subject.');
        }
    }
}
