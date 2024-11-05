@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Select Class for Exam [{{ $examType->exam_type_name }} - {{ $syllabus->syllabus_name }} - {{
                        $year->academic_year_name }}] </h1>
                </div>
            </div>
            <a>Data Exam / {{ $year->academic_year_name }} / {{ $examType->exam_type_name }} / {{ $syllabus->syllabus_name }}</a>
        </div>
    </section>

    <!-- Class List Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @foreach ($classes as $class)
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $class->name }}</h3>
                        </div>
                        <a href="{{ route('exams.marks', ['yearId' => $year->id, 'syllabusID' => $syllabus->id, 'examTypeID' => $examType->id, 'classId' => $class->id]) }}"
                            class="small-box-footer">
                            View Marks <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection
