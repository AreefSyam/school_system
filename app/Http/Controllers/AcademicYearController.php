<?php

namespace App\Http\Controllers;

use App\Models\AcademicYearModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AcademicYearController extends Controller
{
    // List Academic Year
    public function list()
    {
        $data['header_title'] = "Academic Year Management";
        $data['get_record'] = AcademicYearModel::getRecordAcademicYear(); // Get the academic year data

        return view('admin.academicYearManagement.list', $data);
    }

    // Add Academic Year Page
    public function add()
    {
        $data['header_title'] = "Add Academic Year";

        return view('admin.academicYearManagement.add', $data);
    }

    // Post Add Academic Year
    public function postAdd(Request $request)
    {
        // Validate inputs
        $request->validate([
            'academic_year_name' => 'required|string|max:255|unique:academic_year,academic_year_name', // Ensure unique year name
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date', // End date must be after the start date
            'status' => 'required|in:0,1',
        ]);

        try {
            // Create the new academic year
            $academicYear = new AcademicYearModel;
            $academicYear->academic_year_name = trim($request->academic_year_name);
            $academicYear->start_date = $request->start_date;
            $academicYear->end_date = $request->end_date;
            $academicYear->status = $request->status;
            $academicYear->created_by = Auth::user()->id;
            $academicYear->save();

            return redirect()->route('academicYear.list')->with('success', 'New academic year successfully created');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error creating academic year: ' . $e->getMessage());

            // Return back with an error message
            return redirect()->back()->with('error', 'There was an issue creating the academic year. Please try again.');
        }
    }

    // Edit Academic Year Page
    public function edit($id)
    {

        // Fetch the academic year details using the provided ID
        $academicYear = AcademicYearModel::findOrFail($id);

        // Pass the data to the view
        return view('admin.academicYearManagement.edit', [
            'header_title' => 'Edit Academic Year',
            'academicYear' => $academicYear,
        ]);
    }

    // Update the academic year
    public function update($id, Request $request)
    {
        // Find the academic year record by ID
        $academicYear = AcademicYearModel::findOrFail($id);

        // Validate the input with uniqueness check for academic year name, excluding the current record
        $request->validate([
            'academic_year_name' => 'required|string|max:255|unique:academic_year,academic_year_name,' . $academicYear->id,  // Ensure name is unique except for the current record
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:0,1',
        ]);

        // Update the academic year details
        $academicYear->academic_year_name = trim($request->academic_year_name);
        $academicYear->start_date = $request->start_date;
        $academicYear->end_date = $request->end_date;
        $academicYear->status = $request->status;
        $academicYear->save();

        // Redirect to the academic year list with a success message
        return redirect()->route('academicYear.list')->with('success', 'Academic year details updated successfully.');
    }



    // Delete Academic Year
    public function delete($id)
    {
        try {
            $academicYear = AcademicYearModel::findOrFail($id); // Use findOrFail to simplify error handling
            $academicYear->delete(); // No need to call save() after delete

            return redirect()->route('academicYear.list')->with('success', 'Academic year deleted successfully.');
        } catch (\Exception $e) {
            // Log the exception and redirect with an error
            Log::error('Error deleting academic year: ' . $e->getMessage());
            return redirect()->route('academicYear.list')->with('error', 'Unable to delete academic year.');
        }
    }
}
