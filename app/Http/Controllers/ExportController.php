<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\StudentModel;
use App\Models\SubjectModel;

class ExportController extends Controller
{
    // // Export as CSV
    // public function exportCSV()
    // {
    //     return Excel::download(new MarksExport, 'marks.csv', \Maatwebsite\Excel\Excel::CSV);
    // }

    // // Export as Excel
    // public function exportExcel()
    // {
    //     return Excel::download(new MarksExport, 'marks.xlsx');
    // }

    // Export as PDF
    public function exportPDF()
    {
        $students = StudentModel::all();
        $subjects = SubjectModel::all();
        // $marks = ...; // Replace with your logic to get marks data

        $data = [
            'students' => $students,
            'subjects' => $subjects,
            // 'marks' => $marks,
        ];

        $pdf = PDF::loadView('exports.pdf.markPDF', $data);
        return $pdf->download('marks.pdf');
    }
}
