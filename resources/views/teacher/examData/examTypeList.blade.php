@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1> Exam Types for Academic Year: <strong>{{ $currentAcademicYear->academic_year_name }} </strong></h1>
                </div>
            </div>
            <a>Exam Data / {{ $currentAcademicYear->academic_year_name }}  </a>
        </div>
    </section>

    <!-- Exam Type Content -->
    <section class="content">
        <div class="container-fluid">
            @if($examTypes->isEmpty())
            <p class="text-center text-muted">No exam types available for this academic year.</p>
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
@endsection
