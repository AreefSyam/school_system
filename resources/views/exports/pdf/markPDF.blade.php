<!DOCTYPE html>
<html>
<head>
    <title>Marks PDF Export</title>
</head>
<body>
    <h1>Student Marks</h1>
    <table border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>No.</th>
                <th>Student Name</th>
                @foreach($subjects as $subject)
                    <th>{{ $subject->subject_name }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($students as $index => $student)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $student->full_name }}</td>
                @foreach($subjects as $subject)
                <td>
                    @php
                    // Assuming $marks is passed with relevant data
                    $studentMark = $marks->where('student_id', $student->id)->where('subject_id', $subject->id)->first();
                    @endphp
                    {{ $studentMark ? $studentMark->marks : 'N/A' }}
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
