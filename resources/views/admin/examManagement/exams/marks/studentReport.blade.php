<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Examination Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 4px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .signature-row {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-bottom: 20px;
        }

        .signature {
            width: 48%;
            display: inline-block;
        }

        .signature p {
            margin: 0;
            line-height: 1;
        }

        .signature p strong {
            display: block;
            margin-bottom: 17px;
        }
    </style>
</head>

<body>

    <h1 style="text-align: center; margin-bottom: 5px;">Rekod Kemajuan Pelajar</h1>
    <h3 style="text-align: center; margin-top: 0; margin-bottom: 5px;">KAFA Kelas Pengajian</h3>
    <br>

    <p style="margin: 5px 0;">
        <strong>Tahun:</strong> {{ $year->academic_year_name }}
        &nbsp;&nbsp;&nbsp;&nbsp;
        <!-- Adds a proper tab space -->
        <strong>Peperiksaan:</strong>
        @if ($examType->exam_type_name === 'PPT')
        Pertengahan Tahun
        @elseif ($examType->exam_type_name === 'PAT')
        Akhir Tahun
        @else
        {{ $examType->exam_type_name }}
        @endif
    </p>

    <p style="display: flex; justify-content: space-between; margin: 5px 0;">
        <strong>Nama Pelajar:</strong> {{ $student->full_name }} <br>
        <strong>Kelas:</strong> {{ $class->name }}
    </p>

    <p><strong>Kiraan Gred:</strong> (A : 80-100), (B : 60-79), (C : 40-59), (D : 0-39)</p>

    <table>
        <thead>
            <tr>
                <th colspan="3" style="text-align: center;">
                    <strong>{{ $syllabus->syllabus_name }}</strong>
                </th>
            </tr>
            <tr>
                <th>Subject</th>
                <th>Mark</th>
                <th>Grade</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($subjects as $subject)
            <tr>
                <td>{{ $subject->subject_name }}</td>
                <td>
                    @php
                    $markData = $marks->get($subject->subject_id)?->first();
                    $mark = $markData->mark ?? 'N/A';
                    @endphp
                    <span style="{{ ($mark == 'TH' || $mark < 40) ? 'color: red; font-weight: bold;' : '' }}">
                        {{ $mark }}
                    </span>
                </td>
                <td>
                    @php
                    // Determine status
                    $status = $markData->status ?? 'present';
                    $grade = 'TH';

                    // Determine grade logic
                    if (is_numeric($mark)) {
                    if ($mark >= 80) {
                    $grade = 'A';
                    } elseif ($mark >= 60) {
                    $grade = 'B';
                    } elseif ($mark >= 40) {
                    $grade = 'C';
                    } elseif ($mark >= 0 && $status === 'present') {
                    $grade = 'D';
                    }
                    }

                    // Handle absent case
                    if ($mark == 0 && $status === 'absent') {
                    $grade = 'TH';
                    }

                    @endphp
                    <span
                        style="{{ (($grade === 'D' || $grade === 'TH') || ($mark < 40)) ? 'color: red; font-weight: bold;' : '' }}">
                        {{ $grade }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <br>

    <table>
        <tbody>
            <tr>
                <td><strong>Jumlah Markah</strong></td>
                <td>
                    {{ $studentSummary->total_marks ?? 'N/A' }}
                </td>
            </tr>
            <tr>
                <td><strong>Gred</strong></td>
                <td>
                    {{ $studentSummary->total_grade ?? 'N/A' }}
                </td>
            </tr>
            <tr>
                <td><strong>Peratus</strong></td>
                <td>
                    {{ $studentSummary->percentage.'%' ?? 'N/A' }}
                </td>
            </tr>
            <tr>
                <td><strong>Kedudukan Dalam Kelas</strong></td>
                <td>
                    {{ $studentSummary->position_in_class ?? 'N/A' }}
                </td>
            </tr>
            <tr>
                <td><strong>Kedudukan Dalam Darjah</strong></td>
                <td>
                    {{ $studentSummary->position_in_grade ?? 'N/A' }}
                </td>
            </tr>
        </tbody>
    </table>
    <p><strong>Ulasan Guru Kelas:</strong>
        @if ($studentSummary->summary)
            {{ $studentSummary->summary }}
        @else
        <span style="display: block; width: 100%; border-bottom: 2px solid black; margin-top: 5px; height: 25px;"></span>
        <span style="display: block; width: 100%; border-bottom: 2px solid black; margin-top: 5px; height: 25px;"></span>
        @endif
    </p>

    <br>

    <!-- Signatures Section -->
    <div class="signature-row">
        <div class="signature">
            <p><strong>Tandatangan Guru Kelas:</strong><br> _____________________________</p>
        </div>
        <div class="signature">
            <p><strong>Tandatangan Penyelia:</strong><br> _____________________________</p>
        </div>
    </div>

    <div class="signature-row">
        <div class="signature">
            <p><strong>Tandatangan Ibu Bapa/Penjaga:</strong><br> _____________________________</p>
        </div>
        <div class="signature">
            <p><strong>Cop Sekolah:</strong><br> _____________________________</p>
        </div>
    </div>
</body>

</html>
