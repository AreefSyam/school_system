@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header bg-cyan">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h1> <strong> List of Syllabus: Data for {{ $breadcrumbData['academicYearName']
                            }} {{ $breadcrumbData['examTypeName'] }} </strong></h1>
                    <h5> Please select syllabus type below. </h5>
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
                    Data {{ $breadcrumbData['academicYearName'] }}
                </a>
            </li>
            <li class="breadcrumb-item">
                <a
                    href="{{ route('teacher.exams.syllabusList', ['yearId' => $currentAcademicYear->id, 'examTypeId' => $examType->id]) }}">
                    {{ $breadcrumbData['examTypeName'] }}
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
                    <div class="small-box bg-olive">
                        <div class="inner">
                            <h3>{{ $syllabus->syllabus_name }}</h3>
                        </div>
                        {{-- <a
                            href="{{ route('teacher.exams.classList', ['yearId' => $yearId, 'examTypeId' => $examTypeId, 'syllabusId' => $syllabus->id],'subjectId' => $subject->id]) }}"
                            class="small-box-footer"> --}}
                            <a href="{{ route('teacher.exams.subjectList', ['yearId' => $yearId, 'examTypeId' => $examType->id, 'syllabusId' => $syllabus->id]) }}"
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
