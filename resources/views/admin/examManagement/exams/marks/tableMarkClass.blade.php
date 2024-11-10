@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Marks for {{ $examType->exam_type_name }} - {{ $syllabus->syllabus_name }} ({{ $year->academic_year_name }}) : {{ $class->name }}</h1>
                </div>
            </div>
            <a>Data Exam / {{ $year->academic_year_name }} / {{ $examType->exam_type_name }} / {{ $syllabus->syllabus_name }} / {{ $class->name }}</a>
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-body">

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            @foreach($subjects as $subject)
                                <th>{{ $subject->subject_name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr>
                            <td>{{ $student->full_name }}</td>
                            @foreach($subjects as $subject)
                                @php
                                    $studentMark = $marks->get($student->id)?->firstWhere('subject_id', $subject->subject_id);
                                @endphp
                                <td>{{ $studentMark->mark ?? 'N/A' }}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Right-aligned button container -->
                <div class="d-flex justify-content-end mt-3">
                    <!-- Button to navigate to the edit page -->
                    <a href="{{ route('exams.marks.edit', [$year->id, $examType->id, $syllabus->id, $class->id]) }}" class="btn btn-primary">
                        Edit Marks
                    </a>
                </div>

            </div>
        </div>
    </section>
</div>
@endsection
