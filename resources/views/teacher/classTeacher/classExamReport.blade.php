@extends('layouts.app')

@section('content')
<div class="content-wrapper">

    <!-- Content Header -->
    <section class="content-header bg-cyan">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h1><strong> Marks for {{ $examType->exam_type_name }} - {{ $syllabus->syllabus_name }}
                            ({{ $selectedAcademicYear->academic_year_name }}) : {{ $class->name }} </strong></h1>
                    <h5> Please enter marks below. </h5>
                </div>
            </div>
        </div>
    </section>

    {{-- breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('teacher.dashboard') }}">Home </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('teacher.classTeacher.examTypeList', ['yearId' => $currentAcademicYear->id]) }}">Class
                    Report {{ $currentAcademicYear->academic_year_name }}</a>
            </li>
            <li class="breadcrumb-item">
                <a
                    href="{{ route('teacher.classTeacher.syllabusList', ['yearId' => $currentAcademicYear->id, 'examTypeId' => $examType->id]) }}">
                    {{ $examType->exam_type_name }}
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('teacher.classTeacher.classExamReport', [
                'yearId' => $currentAcademicYear->id,
                'examTypeId' => $examType->id,
                'syllabusId' => $syllabus->id,
                'examId' => $exams->get($syllabus->id)->id ?? null
            ]) }}">
                    {{ $syllabus->syllabus_name }}
                </a>
            </li>
        </ol>
    </nav>
    <!-- Individual Marks Table -->
    <section class="content">
        <div class="card">
            <div class="card-body">
                @if($marks->isEmpty())
                <p class="text-center text-danger">No marks available for this class in the selected exam type.</p>
                @else
                <div style="overflow-x: auto;">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Student Name</th>
                                @foreach ($subjects as $subject)
                                <th>{{ $subject->subject_name }}</th>
                                @endforeach
                                <th>Summary</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $index => $student)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $student->full_name }}</td>

                                @foreach ($subjects as $subject)
                                @php
                                $studentMark = $marks->get($student->id)?->firstWhere('subject_id',
                                $subject->subject_id);
                                $markValue = $studentMark?->status === 'absent' ? 'TH' : ($studentMark->mark ?? 'N/A');
                                @endphp

                                <td class="{{
                                        $markValue === 'TH'
                                            ? 'text-danger font-weight-bold'
                                            : (is_numeric($markValue)
                                                ? ($markValue >= 80
                                                    ? 'text-success'
                                                    : ($markValue < 40
                                                        ? 'text-danger'
                                                        : ''))
                                                : '') }}">
                                    {{ $markValue }}
                                </td>
                                @endforeach

                                <td>
                                    @php
                                    // Find the student's summary if it exists
                                    $studentSummary = $studentsSummary->firstWhere('student_id', $student->id);
                                    @endphp

                                    @if ($studentSummary && $studentSummary->summary)
                                    <a href="{{ route('teacher.classTeacher.writeSummary', [$selectedAcademicYear->id, $examType->id, $syllabus->id, $exams2->id, $class->id, $student->id]) }}"
                                        class="btn btn-success btn-sm">
                                        <i class="bi bi-check-square"></i>
                                        Done</a>
                                    @else
                                    <a href="{{ route('teacher.classTeacher.writeSummary', [$selectedAcademicYear->id, $examType->id, $syllabus->id, $exams2->id, $class->id, $student->id]) }}"
                                        class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil-square"></i>
                                        Write
                                    </a>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route('exams.marks.studentReport', [$selectedAcademicYear->id, $examType->id, $syllabus->id, $class->id, $student->id]) }}"
                                        class="btn btn-primary btn-sm" target="_blank">View Report</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                <!-- Right-aligned button for editing -->
                <div class="d-flex justify-content-end mt-3">
                    {{-- <a
                        href="{{ route('exams.marks.edit', [$year->id, $examType->id, $syllabus->id, $class->id, $exam->id]) }}"
                        class="btn btn-primary">Edit Marks</a> --}}
                </div>
            </div>
        </div>
    </section>

    <!-- Summary Table -->
    <section class="content">
        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h2>Student Performance Summary</h2>
                <!-- Buttons to trigger PDF download -->
                <div class="ml-auto">
                    <a href="{{ route('exams.marks.positionInYearLevel', [$selectedAcademicYear->id, 'examTypeId' => $examType->id, $syllabus->id,  $class->id, 'examId' => $exam->id, 'studentId' => $student->id]) }}"
                        class="btn btn-primary mr-2" target="_blank"><i class="bi bi-download"></i> Position in Class
                        Report</a>
                    <a href="{{ route('exams.marks.positionInYearLevel', [$selectedAcademicYear->id, 'examTypeId' => $examType->id, $syllabus->id,  $class->id, 'examId' => $exam->id, 'studentId' => $student->id]) }}"
                        class="btn btn-secondary" target="_blank"><i class="bi bi-download"></i> Position in Grade
                        Report</a>
                </div>
            </div>

            <div class="card-body">
                @if($studentsSummary->isEmpty())
                <p class="text-center text-danger">No student summaries available for this exam.</p>
                @else
                <div style="overflow-x: auto;">
                    <table id="summary-table" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Student Name</th>
                                <th>Total Grade</th>
                                <th>Total Marks</th>
                                <th>Percentage</th>
                                <th>Position in Class
                                    <i class="bi bi-sort-numeric-up" style="color:green" data-index="5"></i>
                                </th>
                                <th>Position in Grade
                                    <i class="bi bi-sort-numeric-up" style="color:green" data-index="6"></i>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($studentsSummary as $index => $summary)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $summary->student->full_name }}</td>
                                <td>{{ $summary->total_grade ?? 'N/A' }}</td>
                                <td>{{ $summary->total_marks ?? 'N/A' }}</td>
                                <td>{{ $summary->percentage ?? 'N/A' }}%</td>
                                <td>{{ $summary->position_in_class ?? 'N/A' }}</td>
                                <td>{{ $summary->position_in_grade ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @endif
            </div>
        </div>
    </section>
</div>

<!-- Sorting Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const table = document.querySelector('#summary-table');
    const headers = table.querySelectorAll('th');
    const rows = Array.from(table.querySelectorAll('tbody tr'));

    headers.forEach((header, index) => {
        // Only allow sorting for "Position in Class" and "Position in Grade"
        if (index === 5 || index === 6) {
            header.style.cursor = 'pointer';
            header.setAttribute('title', 'Click to sort');

            const icon = header.querySelector('i');

            header.addEventListener('click', function () {
                const ascending = header.dataset.order !== 'asc';
                header.dataset.order = ascending ? 'asc' : 'desc';

                // Reset all icons
                document.querySelectorAll('.bi-sort-numeric-up, .bi-sort-numeric-down-alt').forEach(icon => {
                    icon.classList.remove('bi-sort-numeric-up', 'bi-sort-numeric-down-alt');
                    icon.classList.add('bi-sort-numeric-up');
                });

                // Update the current icon
                if (ascending) {
                    icon.classList.remove('bi-sort-numeric-down-alt');
                    icon.classList.add('bi-sort-numeric-up');
                } else {
                    icon.classList.remove('bi-sort-numeric-up');
                    icon.classList.add('bi-sort-numeric-down-alt');
                }

                // Sort rows
                rows.sort((rowA, rowB) => {
                    const cellA = parseInt(rowA.children[index].textContent.trim()) || 0;
                    const cellB = parseInt(rowB.children[index].textContent.trim()) || 0;

                    return ascending ? cellA - cellB : cellB - cellA;
                });

                // Update table rows
                const tbody = table.querySelector('tbody');
                rows.forEach(row => tbody.appendChild(row));
            });
        }
    });
});

</script>
@endsection
