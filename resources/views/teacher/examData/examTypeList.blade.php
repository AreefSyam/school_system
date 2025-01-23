@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header bg-cyan">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h1> <strong>List of Examination: Data for {{ $breadcrumbData['academicYearName'] }}</strong></h1>
                    <h5> Please select exam type below. </h5>
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
                <a href="{{ route('teacher.exams.examTypeList', ['yearId' => $currentAcademicYear->id]) }}">Exam Data {{ $breadcrumbData['academicYearName'] }}
                </a>
            </li>
        </ol>
    </nav>

    <!-- Exam Type Content -->
    <section class="content">
        <div class="container-fluid">
            @if($examTypes->isEmpty())
            <p class="text-center text-danger">No exam types available for this academic year.</p>
            @else
            <div class="row">
                @foreach ($examTypes as $examType)
                <div class="col-lg-3 col-6">
                    <!-- Small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $examType->exam_type_name }}</h3>
                        </div>
                        <a href="{{ route('teacher.exams.syllabusList', ['yearId' => $currentAcademicYear->id, 'examTypeId' => $examType->id]) }}"
                            class="small-box-footer">
                            View Syllabus <i class="fas fa-arrow-circle-right"></i>
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
