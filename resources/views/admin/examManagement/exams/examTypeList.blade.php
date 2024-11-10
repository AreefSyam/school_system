@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> Select Exam Types for {{ $year->academic_year_name }}</h1>
                </div>
            </div>
            <a>Data Exam / {{ $year->academic_year_name }} / </a>
        </div>
    </section>

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
@endsection
