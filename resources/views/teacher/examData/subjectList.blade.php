@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>All Subjects</h1>
                </div>
            </div>
            <a>Exam Data / {{ $selectedAcademicYear->academic_year_name ?? 'N/A' }} / {{ $examTypeName ?? 'N/A' }} / {{
                $syllabusName ?? 'N/A' }}</a>
        </div>
    </section>

    <!-- Subject List Content -->
    <section class="content">
        <div class="container-fluid">
            @if($subjects->isEmpty())
            <p class="text-center text-muted">No subjects available for the selected syllabus.</p>
            @else
            <div class="row">
                @foreach ($subjects as $subject)
                <div class="col-lg-3 col-6">
                    <!-- Small box -->
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ $subject->subject_name }}</h3>
                        </div>
                        <a href="{{ route('teacher.exams.classList', ['yearId' => $yearId, 'examTypeId' => $examTypeId, 'syllabusId' => $syllabusId, 'subjectId' => $subject->id]) }}"
                            class="small-box-footer">
                            View Classes <i class="fas fa-arrow-circle-right"></i>
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
