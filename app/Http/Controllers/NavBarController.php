<?php

// namespace App\Http\Controllers;
// use App\Models\AcademicYearModel;
// use Illuminate\Support\Facades\Request;
// use Illuminate\Support\Facades\Session;

// class NavBarController extends Controller
// {

//     public function setAcademicYear($id)
//     {
//         // Reset the current year
//         AcademicYearModel::query()->update(['is_current' => 0]);

//         // Set the selected year as current
//         $selectedYear = AcademicYearModel::findOrFail($id);
//         $selectedYear->is_current = 1;
//         $selectedYear->save();

//         // Store the selected year in the session
//         session(['academic_year' => $selectedYear->academic_year_name]);

//         return redirect()->back()->with('success', 'Academic year updated successfully!');
//     }

//     public function setAcademicYear(Request $request, $id)
//     {
//         $academicYear = AcademicYearModel::findOrFail($id);
//         Session::put('academic_year', $academicYear->academic_year_name); // Set academic year in session
//         Session::put('academic_year_id', $academicYear->id); // Save ID for filtering
//         return response()->json(['success' => true]);
//     }

// }

namespace App\Http\Controllers;

use App\Models\AcademicYearModel;
use Illuminate\Http\Request; // Import Request correctly
use Illuminate\Support\Facades\Session;

class NavBarController extends Controller
{
    // public function setAcademicYear(Request $request, $id)
    // {
    //     // Validate the existence of the academic year
    //     $academicYear = AcademicYearModel::findOrFail($id);
    //     // Check if the selected academic year is already current
    //     if ($academicYear->is_current) {
    //         // Respond with JSON for AJAX requests
    //         if ($request->expectsJson()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'The selected academic year is already active!',
    //             ]);
    //         }
    //         // Redirect back for non-AJAX requests
    //         return redirect()->back()->with('info', 'The selected academic year is already active!');
    //     }
    //     // Reset the `is_current` field for all academic years
    //     AcademicYearModel::query()->update(['is_current' => 0]);
    //     // Set the selected academic year as current
    //     $academicYear->is_current = 1;
    //     $academicYear->save();

    //     // Store the selected academic year in the session
    //     Session::put('academic_year', $academicYear->academic_year_name);
    //     Session::put('academic_year_id', $academicYear->id);

    //     // Respond with JSON for AJAX requests
    //     if ($request->expectsJson()) {
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Academic year updated successfully!',
    //         ]);
    //     }

    //     // Redirect back for non-AJAX requests
    //     return redirect()->back()->with('success', 'Academic year updated successfully!');
    // }

    public function setAcademicYear(Request $request, $id)
    {
        // Validate the existence of the academic year
        $academicYear = AcademicYearModel::findOrFail($id);

        // Check if the selected academic year is already set in the session
        if (session('academic_year_id') == $id) {
            // Respond with JSON for AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'The selected academic year is already active!',
                ]);
            }
            // Redirect back for non-AJAX requests
            return redirect()->back()->with('info', 'The selected academic year is already active!');
        }

        // Store the selected academic year in the session
        Session::put('academic_year', $academicYear->academic_year_name);
        Session::put('academic_year_id', $academicYear->id);

        // Respond with JSON for AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Academic year updated successfully!',
            ]);
        }

        // Redirect back for non-AJAX requests
        return redirect()->back()->with('success', 'Academic year updated successfully!');
    }

}
