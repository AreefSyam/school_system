@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h1>Select Syllabi for Exam [{{ $examType->exam_type_name }} - {{ $year->academic_year_name }}]
                    </h1>
                </div>
            </div>
            {{-- <a>Data Exam / {{ $year->academic_year_name }} / {{ $examType->exam_type_name }}</a> --}}
        </div>
    </section>

    {{-- breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <!-- Home -->
            <li class="breadcrumb-item">
                <a href="{{ route('exams.yearList') }}">Exam Data </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('exams.examTypeList',  ['yearId' => $year->id]) }}"> {{ $year->academic_year_name
                    }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('exams.syllabusList',  ['yearId' => $year->id, 'examTypeId' => $examType->id]) }}"> {{
                    $examType->exam_type_name }}</a>
            </li>
        </ol>
    </nav>

    <!-- Syllabus Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @foreach ($syllabi as $syllabus)
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $syllabus->syllabus_name }}</h3>
                        </div>
                        <a href="{{ route('exams.classList', ['yearId' => $year->id, 'examTypeId' => $examType->id, 'syllabusId' => $syllabus->id]) }}"
                            class="small-box-footer">
                            View Classes <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection
