@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header bg-cyan">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h1> <strong> List of Class: Data for {{
                            $breadcrumbData['academicYearName'] }} {{
                            $breadcrumbData['examTypeName'] }} {{ $breadcrumbData['syllabusName'] }} {{ $breadcrumbData['subjectName'] }}</strong></h1>
                    <h5> Please select a class you teach below. </h5>
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
                <a href="{{ route('teacher.exams.examTypeList', ['yearId' => $currentAcademicYear->id]) }}">Exam
                    Data {{ $breadcrumbData['academicYearName'] ?? 'N/A'  }}
                </a>
            </li>
            <li class="breadcrumb-item">
                <a
                    href="{{ route('teacher.exams.syllabusList', ['yearId' => $currentAcademicYear->id, 'examTypeId' => $examType->id]) }}">
                    {{ $breadcrumbData['examTypeName'] ?? 'N/A' }}
                </a>
            </li>
            <li class="breadcrumb-item">
                <a
                    href="{{ route('teacher.exams.subjectList', ['yearId' => $currentAcademicYear->id, 'examTypeId' => $examType->id, 'syllabusId' => $syllabus->id]) }}">
                    {{ $breadcrumbData['syllabusName'] ?? 'N/A' }}
                </a>
            </li>
            <li class="breadcrumb-item">
                <a
                    href="{{ route('teacher.exams.classList', ['yearId' => $currentAcademicYear->id, 'examTypeId' => $examType->id, 'syllabusId' => $syllabus->id, 'subjectId' => $subject->id]) }}">
                    {{  $breadcrumbData['subjectName'] ?? 'N/A' }}
                </a>
            </li>
        </ol>
    </nav>

    <!-- Class Content -->
    <section class="content">
        <div class="container-fluid">
            @if($classes->isEmpty())
            <p class="text-center text-danger">No classes available for the selected criteria.</p>
            @else
            <div class="row">
                @foreach ($classes as $class)
                <div class="col-lg-3 col-6">
                    <!-- Small box -->
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ $class->name }}</h3>
                        </div>
                        <a href="{{ route('teacher.exams.marks', ['yearId' => $yearId, 'examTypeId' => $examType->id, 'syllabusId' => $syllabus->id, 'subjectId' => $subject->id , 'classId' => $class->id]) }}"
                            class="small-box-footer">
                            View Marks <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </section>
</div>

<style>
    .small-box .inner h3 {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endsection
