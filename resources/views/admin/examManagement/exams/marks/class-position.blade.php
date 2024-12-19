<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Class Position Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h1, p { margin: 0; padding: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Class Position Report - {{ $class->name }}</h1>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Student Name</th>
                <th>Position in Class</th>
                <th>Total Grade</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($studentSummaries as $summary)
            <tr>
                <td>{{ $loop->iteration }}</td> <!-- Adds the loop number -->
                <td>{{ $summary->student->full_name }}</td>
                <td>{{ $summary->position_in_class ?? 'N/A' }}</td>
                <td>{{ $summary->total_grade ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
