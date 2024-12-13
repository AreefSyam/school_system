@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <section>
        <div class="content-header">
            <div class="col-12 text mb-1">
                <h3 class="font-weight-bold">Teacher Insight</h3>
                <label>Current Academic Year: <strong>{{ $currentAcademicYear->academic_year_name }}</strong></label>
            </div>
        </div>
    </section>

    <!-- Assigned Class List -->
    <section class="content">
        <div class="container-fluid">
            @if($classes->isEmpty())
            <p class="text-center text-danger">No classes assigned for the current academic year.</p>
            @else
            <div class="row">
                @foreach ($classes as $class)
                <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                    <div class="card border-success">
                        <div class="card-body text-center">
                            <h5 class="card-title text-success font-weight-bold">{{ $class->name }}</h5>
                            <p class="card-text">Click below to view marks for this class.</p>
                            {{-- <a href="{{ route('exams.marks', ['yearId' => $currentAcademicYear->id, 'classId' => $class->id]) }}"
                                class="btn btn-success btn-sm">
                                View Marks <i class="fas fa-arrow-circle-right"></i>
                            </a> --}}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </section>

</div>

@endsection



{{--

@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <section>
        <div class="content-header">
            <div class="col-12 text mb-1">
                <h3 class="font-weight-bold">Teacher Insight</h3>
                <label for="academicYearDropdown">Current Academic Year: {{ $currentAcademicYear->academic_year_name
                    }}</label>
            </div>
        </div>
    </section>

    // display all the assigned class to this teacher
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
                        <a href="{{ route('exams.marks', ['yearId' => $year->id, 'syllabusId' => $syllabus->id, 'examTypeId' => $examType->id, 'classId' => $class->id]) }}"
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

@endsection --}}
