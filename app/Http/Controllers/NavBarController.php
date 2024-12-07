<?php

namespace App\Http\Controllers;

use App\Models\AcademicYearModel;
use Illuminate\Http\Request; // Import Request correctly
use Illuminate\Support\Facades\Session;

class NavBarController extends Controller
{

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
                'message' => 'Academic year changed successfully!',
            ]);
        }

        // Redirect back for non-AJAX requests
        return redirect()->back()->with('success', 'Academic year changed successfully!');
    }

}
