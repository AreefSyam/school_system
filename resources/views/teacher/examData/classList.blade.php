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
            <a>Exam Data / {{ session('academic_year', 'Unknown Year') }} / {{ $examTypeName ?? 'N/A' }} / {{ $syllabusName ?? 'N/A' }} / {{ $subjectName ?? 'N/A' }}</a>
        </div>
    </section>

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
                        <a href="{{ route('teacher.exams.marks', ['yearId' => $yearId, 'examTypeId' => $examTypeId, 'syllabusId' => $syllabusId, 'subjectId' => $subjectId , 'classId' => $class->id]) }}"
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
