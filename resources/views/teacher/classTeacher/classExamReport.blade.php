{{-- @extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h1>Marks for {{ $examType->exam_type_name }} - {{ $syllabus->syllabus_name }}
                        ({{ $selectedAcademicYear->academic_year_name }}) : {{ $class->name }}</h1>
                </div>
            </div>
        </div>
    </section>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <!-- Home -->
            <li class="breadcrumb-item">
                <a href="{{ route('teacher.classTeacher.examTypeList', ['yearId' => $currentAcademicYear->id]) }}">
                    Class Report</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('teacher.classTeacher.examTypeList', ['yearId' => $currentAcademicYear->id]) }}">
                    {{ $currentAcademicYear->academic_year_name }}
                </a>
            </li>
            <li class="breadcrumb-item">
                <a
                    href="{{ route('teacher.classTeacher.syllabusList', ['yearId' => $currentAcademicYear->id, 'examTypeId' => $examType->id]) }}">
                    {{ $examTypeName }}
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
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Student Name</th>
                            @foreach ($subjects as $subject)
                            <th>{{ $subject->subject_name }}</th>
                            @endforeach
                            <th>Attendance</th>
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
                            $studentMark = $marks->get($student->id)?->firstWhere('subject_id', $subject->subject_id);
                            // $markValue = $studentMark->mark ?? 'N/A';
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

                            @php
                            $summary = $studentsSummary->firstWhere('student_id', $student->id);
                            @endphp
                            <td>{{ $summary->attendance ?? '0' }} days</td>
                            <td>
                                <a href="{{ route('exams.marks.studentReport', [$selectedAcademicYear->id, $examType->id, $syllabus->id, $class->id, $student->id]) }}"
                                    class="btn btn-primary btn-sm" target="_blank">View Report</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Right-aligned button for editing -->
                <div class="d-flex justify-content-end mt-3">
                    <a
                        href="{{ route('exams.marks.edit', [$year->id, $examType->id, $syllabus->id, $class->id, $exam->id]) }}"
                        class="btn btn-primary">Edit Marks</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Summary Table -->
    @if($studentsSummary->isNotEmpty() && $studentsSummary->first()->academicYear->id == $selectedAcademicYear->id)
    <section class="content">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Student Name</th>
                            <th>Total Grade</th>
                            <th>Total Marks</th>
                            <th>Percentage</th>
                            <th>Position in Class</th>
                            <th>Position in Grade</th>
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
        </div>
    </section>
    @endif

</div>
@endsection --}}


@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h1>Marks for {{ $examType->exam_type_name }} - {{ $syllabus->syllabus_name }}
                        ({{ $selectedAcademicYear->academic_year_name }}) : {{ $class->name }}</h1>
                </div>
            </div>
        </div>
    </section>

    {{-- breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('teacher.classTeacher.examTypeList', ['yearId' => $currentAcademicYear->id]) }}">
                    Class Report</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('teacher.classTeacher.examTypeList', ['yearId' => $currentAcademicYear->id]) }}">
                    {{ $currentAcademicYear->academic_year_name }}
                </a>
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
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Student Name</th>
                            @foreach ($subjects as $subject)
                            <th>{{ $subject->subject_name }}</th>
                            @endforeach
                            <th>Attendance</th>
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
                            $studentMark = $marks->get($student->id)?->firstWhere('subject_id', $subject->subject_id);
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

                            @php
                            $summary = $studentsSummary->firstWhere('student_id', $student->id);
                            @endphp
                            <td>{{ $summary->attendance ?? '0' }} days</td>
                            <td>
                                <a href="{{ route('exams.marks.studentReport', [$selectedAcademicYear->id, $examType->id, $syllabus->id, $class->id, $student->id]) }}"
                                    class="btn btn-primary btn-sm" target="_blank">View Report</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
            <div class="card-body">
                @if($studentsSummary->isEmpty())
                <p class="text-center text-danger">No student summaries available for this exam.</p>
                @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Student Name</th>
                            <th>Total Grade</th>
                            <th>Total Marks</th>
                            <th>Percentage</th>
                            <th>Position in Class</th>
                            <th>Position in Grade</th>
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
                @endif
            </div>
        </div>
    </section>
</div>
@endsection
