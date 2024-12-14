@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <!-- Performance Metrics Section -->
            <div class="row">
                <div class="col-12 mb-1">
                    <h4 class="font-weight-bold">Performance Metrics</h4>
                </div>

                <!-- Class Performance -->
                <div class="col-md-6 col-lg-4">
                    <div class="small-box bg-info">
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

            <!-- Subject Section -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 mb-1">
                            <h4 class="font-weight-bold">Subjects:</h4>
                        </div>
                        @if($assignedSubjects->isEmpty())
                        <p class="text-center text-danger">No subjects are assigned to you for the selected academic
                            year.</p>
                        @else
                        @foreach($assignedSubjects as $assignment)
                        @if($assignment->subject && $assignment->syllabus && $assignment->class)
                        <div class="col-md-6 col-lg-4">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $assignment->subject->subject_name }}</h3>
                                    <p>{{ $assignment->class->name }} - {{ $assignment->syllabus->syllabus_name }}</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-stats-bars"></i>
                                </div>
                                <div class="d-flex justify-content-around py-2">
                                    <!-- Button for PPT Exam Type -->
                                    <a href="{{ route('teacher.exams.marks', [
                            'yearId' => $currentAcademicYear->id ?? 0,
                            'examTypeId' => 1, // PPT Exam Type ID
                            'syllabusId' => $assignment->syllabus->id,
                            'subjectId' => $assignment->subject->id,
                            'classId' => $assignment->class->id
                        ]) }}" class="btn btn-light">
                                        Key in Marks - PPT
                                    </a>

                                    <!-- Button for PAT Exam Type -->
                                    <a href="{{ route('teacher.exams.marks', [
                            'yearId' => $currentAcademicYear->id ?? 0,
                            'examTypeId' => 2, // PAT Exam Type ID
                            'syllabusId' => $assignment->syllabus->id,
                            'subjectId' => $assignment->subject->id,
                            'classId' => $assignment->class->id
                        ]) }}" class="btn btn-light">
                                        Key in Marks - PAT
                                    </a>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="col-12">
                            <p class="text-danger">Incomplete data for one or more assignments. Please contact admin.
                            </p>
                        </div>
                        @endif
                        @endforeach
                        @endif
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>

@endsection
