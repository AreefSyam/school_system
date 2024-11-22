<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Examination Report</title>
    <style>
        /* Custom styling for the PDF */
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h2>Student Examination Report</h2>
    <p><strong>Student Name:</strong> {{ $student->full_name }}</p>
    <p><strong>Class:</strong> {{ $class->name }}</p>
    <p><strong>Academic Year:</strong> {{ $year->academic_year_name }}</p>
    <p><strong>Exam Type:</strong> {{ $examType->exam_type_name }}</p>
    <p><strong>Syllabus:</strong> {{ $syllabus->syllabus_name }}</p>
    <p><strong>Attendance:</strong> {{ $studentSummary->attendance ?? 'N/A' }}</p>
    <p><strong>Position in Grade:</strong> {{ $studentSummary->position_in_grade ?? 'N/A' }}</p>
    <p><strong>Position in Class:</strong> {{ $studentSummary->position_in_class ?? 'N/A' }}</p>
    <p><strong>Total Grade:</strong> {{ $studentSummary->total_grade ?? 'N/A' }}</p>

    <table>
        <thead>
            <tr>
                <th>Subject</th>
                <th>Mark</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subjects as $subject)
            <tr>
                <td>{{ $subject->subject_name }}</td>
                <td>{{ $marks->get($subject->subject_id)?->first()->mark ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
