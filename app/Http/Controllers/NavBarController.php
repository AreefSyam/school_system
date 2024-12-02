<?php

namespace App\Http\Controllers;

use App\Models\AcademicYearModel;

class NavBarController extends Controller
{

    public function setAcademicYear($id)
    {
        // Reset the current year
        AcademicYearModel::query()->update(['is_current' => 0]);

        // Set the selected year as current
        $selectedYear = AcademicYearModel::findOrFail($id);
        $selectedYear->is_current = 1;
        $selectedYear->save();

        // Store the selected year in the session
        session(['academic_year' => $selectedYear->academic_year_name]);

        return redirect()->back()->with('success', 'Academic year updated successfully!');
    }

}
