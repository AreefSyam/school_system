@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h1><strong> Syllabi for Exam Type: {{ $examTypeName }}</strong></h1>
                    <h5> Please select an exam type below. </h5>
                </div>
            </div>
        </div>
    </section>

    {{-- breadcrumb --}}
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
        </ol>
    </nav>

    <!-- Syllabus Content -->
    <section class="content">
        <div class="container-fluid">
            @if($syllabi->isEmpty())
            <p class="text-center text-danger">No syllabi available for this exam type in the selected academic year.
            </p>
            @else
            <div class="row">
                @foreach ($syllabi as $syllabus)
                <div class="col-lg-3 col-6">
                    <!-- Small box -->
                    <div class="small-box bg-pink">
                        <div class="inner">
                            <h3>{{ $syllabus->syllabus_name }}</h3>
                        </div>
                        <a href="{{ route('teacher.classTeacher.classExamReport', ['yearId' => $yearId, 'examTypeId' => $examType->id, 'syllabusId' => $syllabus->id, 'examId' => $exams->get($syllabus->id)->id ?? null]) }}"
                            class="small-box-footer">
                            View Subject <i class="fas fa-arrow-circle-right"></i>
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
