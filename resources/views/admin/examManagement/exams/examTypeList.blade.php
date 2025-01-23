@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header bg-dark">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h1> Select Exam Types for {{ $year->academic_year_name }}</h1>
                </div>
            </div>
        </div>
    </section>

    {{-- breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('exams.yearList') }}">Exam Data </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('exams.examTypeList',  ['yearId' => $year->id]) }}"> {{ $year->academic_year_name }}</a>
            </li>
        </ol>
    </nav>

    <!-- Exam Type Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @foreach ($examTypes as $examType)
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $examType->exam_type_name }}</h3>
                        </div>
                        <a href="{{ route('exams.syllabusList', ['yearId' => $year->id, 'examTypeId' => $examType->id]) }}"
                            class="small-box-footer">
                            View Syllabus <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
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
