@extends('layouts.app')

@section('content')
<div class="content-wrapper">

    <!-- Content Header -->
    <section class="content-header bg-cyan">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1><strong>Welcome, {{ auth()->user()->name }}</strong></h1>
                </div>
            </div>
        </div>
    </section>


    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('teacher.dashboard') }}">Home</a>
            </li>
        </ol>
    </nav>

    <!-- Performance Metrics Section -->
    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 mb-3">
                    <h4 class="font-weight-bold">Performance:</h4>
                </div>
                <!-- Class Performance -->
                <div class="col-md-6 col-lg-4">
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3>Class</h3>
                            <p>Performance</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('teacher.analytic.classPerformance', ['yearId' => $currentAcademicYear ?? '']) }}"
                            class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Individual Performance -->
                <div class="col-md-6 col-lg-4">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>Individual</h3>
                            <p>Performance</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('teacher.analytic.individualPerformance', ['yearId' => $currentAcademicYear ?? '']) }}"
                            class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Subject Section -->
    <section class="content mt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 mb-3">
                    <h4 class="font-weight-bold">Subjects:</h4>
                </div>

                @if($assignedSubjects->isEmpty())
                <p class="text-center text-danger">No subjects are assigned to you for the selected academic year. Try
                    reloading the page or select an academic year at the header.</p>
                @else
                @foreach($assignedSubjects as $assignment)
                @if($assignment->subject && $assignment->syllabus && $assignment->class)
                <div class="col-md-6 col-lg-4">
                    <div class="small-box bg-teal">
                        <div class="inner">
                            <h3>{{ $assignment->subject->subject_name }}</h3>
                            <p>{{ $assignment->class->name }} - {{ $assignment->syllabus->syllabus_name }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        @php
                        // Check for available exams
                        $examPPT = $examinations->firstWhere(fn($exam) => $exam->exam_type_id === 1 &&
                        $exam->syllabus_id === $assignment->syllabus->id &&
                        $exam->status === 'available');
                        $examPAT = $examinations->firstWhere(fn($exam) => $exam->exam_type_id === 2 &&
                        $exam->syllabus_id === $assignment->syllabus->id &&
                        $exam->status === 'available');
                        @endphp
                        @if($examPPT || $examPAT)
                        <div class="d-flex justify-content-around py-2">
                            <!-- Button for PPT Exam Type -->
                            @if($examPPT)
                            <a href="{{ route('teacher.exams.marks', [
                                'yearId' => $currentAcademicYear->id ?? 0,
                                'examTypeId' => 1,
                                'syllabusId' => $assignment->syllabus->id,
                                'subjectId' => $assignment->subject->id,
                                'classId' => $assignment->class->id
                            ]) }}" class="btn btn-light">
                                Key in Marks - PPT
                            </a>
                            @endif

                            <!-- Button for PAT Exam Type -->
                            @if($examPAT)
                            <a href="{{ route('teacher.exams.marks', [
                                'yearId' => $currentAcademicYear->id ?? 0,
                                'examTypeId' => 2,
                                'syllabusId' => $assignment->syllabus->id,
                                'subjectId' => $assignment->subject->id,
                                'classId' => $assignment->class->id
                            ]) }}" class="btn btn-light">
                                Key in Marks - PAT
                            </a>
                            @endif
                        </div>
                        @else
                        <div class="py-2 text-center">
                            <span class="badge bg-secondary">Key in Marks Disabled</span>
                        </div>
                        @endif
                    </div>
                </div>
                @else
                <div class="col-12">
                    <p class="text-danger">Incomplete data for one or more assignments. Please contact admin.</p>
                </div>
                @endif
                @endforeach
                @endif
            </div>
        </div>
    </section>

</div>
@endsection
