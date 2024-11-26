@extends('layouts.app')

@section('content')
<div class="content-wrapper">

    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Subject Performance Analytics</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Filters Section -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filters</h3>
                </div>
                <form method="get" action="{{ route('analytic.subjectPerformance') }}">
                    <div class="card-body">
                        <div class="row">
                            <!-- Subject -->
                            <div class="form-group col-md-2">
                                <label>Subject</label>
                                <select class="form-control" name="subject_id">
                                    <option value="" disabled selected>-- Select Subject --</option>
                                    @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ request('subject_id')==$subject->id ?
                                        'selected' : '' }}>
                                        {{ $subject->subject_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Syllabus -->
                            <div class="form-group col-md-2">
                                <label>Syllabus</label>
                                <select class="form-control" name="syllabus_id">
                                    <option value="" disabled selected>-- Select Syllabus --</option>
                                    @foreach($syllabuses as $syllabus)
                                    <option value="{{ $syllabus->id }}" {{ request('syllabus_id')==$syllabus->id ?
                                        'selected' : '' }}>
                                        {{ $syllabus->syllabus_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Grade Level -->
                            <div class="form-group col-md-2">
                                <label>Grade Level</label>
                                <select class="form-control" name="grade_level_id">
                                    <option value="" disabled selected>-- Select Grade Level --</option>
                                    @foreach($gradeLevels as $grade)
                                    <option value="{{ $grade->id }}" {{ request('grade_level_id')==$grade->id ?
                                        'selected' : '' }}>
                                        {{ $grade->grade_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Academic Year -->
                            <div class="form-group col-md-2">
                                <label>Academic Year</label>
                                <select class="form-control" name="academic_year_id">
                                    <option value="" disabled selected>-- Select Academic Year --</option>
                                    @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}" {{ request('academic_year_id')==$year->id ?
                                        'selected' : '' }}>
                                        {{ $year->academic_year_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Exam Type -->
                            <div class="form-group col-md-2">
                                <label>Exam Type</label>
                                <select class="form-control" name="exam_type_id">
                                    <option value="" disabled selected>-- Select Exam Type --</option>
                                    @foreach($examTypes as $examType)
                                    <option value="{{ $examType->id }}" {{ request('exam_type_id')==$examType->id ?
                                        'selected' : '' }}>
                                        {{ $examType->exam_type_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Filter Buttons -->
                            <div class="form-group col-md-2">
                                <button type="submit" class="btn btn-primary" style="margin-top: 30px">Filter</button>
                                <a href="{{ route('analytic.subjectPerformance') }}" class="btn btn-success" style="margin-top: 30px">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Performance Chart -->
    <section class="content">
        <div class="container-fluid">
            <div class="card ">
                <div class="card-header">
                    <h3 class="card-title">Subject Performance Chart</h3>
                </div>
                <div class="card-body">
                    @if($data->isNotEmpty())
                    <canvas id="gradePerformanceChart" style="min-height: 400px;"></canvas>
                    @else
                    <p class="text-center">No data available for the selected filters.</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Performance Counts Table -->
    <section class="content">
        <div class="container-fluid">
            <div class="card ">
                <div class="card-header">
                    <h3 class="card-title">Subject Performance Counts</h3>
                </div>
                <div class="card-body">
                    @if($data->isNotEmpty())
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Year</th>
                                <th>Grade Year</th>
                                <th>Subject</th>
                                <th>Count A</th>
                                <th>Count B</th>
                                <th>Count C</th>
                                <th>Count D</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $row)
                            <tr>
                                <td>{{ $row->academic_year_name }}</td>
                                <td>{{ $row->grade_name }}</td>
                                <td>{{ $row->subject_name }}</td>
                                <td>{{ $row->count_A }}</td>
                                <td>{{ $row->count_B }}</td>
                                <td>{{ $row->count_C }}</td>
                                <td>{{ $row->count_D }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p class="text-center">No data available for the selected filters.</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    @if($data->isNotEmpty())
        const labels = @json($data->pluck('grade_name')->unique());
        const datasets = [
            {
                label: 'Count A',
                data: @json($data->pluck('count_A')),
                backgroundColor: 'rgba(75, 192, 192, 0.7)',
            },
            {
                label: 'Count B',
                data: @json($data->pluck('count_B')),
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
            },
            {
                label: 'Count C',
                data: @json($data->pluck('count_C')),
                backgroundColor: 'rgba(255, 206, 86, 0.7)',
            },
            {
                label: 'Count D',
                data: @json($data->pluck('count_D')),
                backgroundColor: 'rgba(255, 99, 132, 0.7)',
            },
        ];

        new Chart(document.getElementById('gradePerformanceChart'), {
            type: 'bar',
            data: { labels: labels, datasets: datasets },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: false, text: 'Grade Performance Counts' },
                },
                scales: {
                    y: { beginAtZero: true },
                },
            },
        });
    @endif
</script>
@endsection
