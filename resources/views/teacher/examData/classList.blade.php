@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>All Classes</h1>
                </div>
            </div>
            {{-- <a>Exam Data / {{ session('academic_year', 'Unknown Year') }} / {{ $examTypeName ?? 'N/A' }} / {{
                $syllabusName ?? 'N/A' }} / {{ $subjectName ?? 'N/A' }}</a> --}}
        </div>
    </section>

    {{-- breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <!-- Home -->
            <li class="breadcrumb-item">
                <a href="{{ route('teacher.exams.examTypeList', ['yearId' => $currentAcademicYear->id]) }}">
                    Exam Data</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('teacher.exams.examTypeList', ['yearId' => $currentAcademicYear->id]) }}">
                    {{ $currentAcademicYear->academic_year_name ?? 'N/A' }}
                </a>
            </li>
            <li class="breadcrumb-item">
                <a
                    href="{{ route('teacher.exams.syllabusList', ['yearId' => $currentAcademicYear->id, 'examTypeId' => $examType->id]) }}">
                    {{ $examTypeName ?? 'N/A' }}
                </a>
            </li>
            <li class="breadcrumb-item">
                <a
                    href="{{ route('teacher.exams.subjectList', ['yearId' => $currentAcademicYear->id, 'examTypeId' => $examType->id, 'syllabusId' => $syllabus->id]) }}">
                    {{ $syllabusName ?? 'N/A' }}
                </a>
            </li>
            <li class="breadcrumb-item">
                <a
                    href="{{ route('teacher.exams.classList', ['yearId' => $currentAcademicYear->id, 'examTypeId' => $examType->id, 'syllabusId' => $syllabus->id, 'subjectId' => $subject->id]) }}">
                    {{ $subjectName ?? 'N/A' }}
                </a>
            </li>
        </ol>
    </nav>

    <!-- Class Content -->
    <section class="content">
        <div class="container-fluid">
            @if($classes->isEmpty())
            <p class="text-center text-muted">No classes available for the selected criteria.</p>
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
@endsection
