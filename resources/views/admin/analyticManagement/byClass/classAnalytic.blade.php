@extends('layouts.app')

@section('content')

<div class="content-wrapper">

    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Class Performance Analytics</h1>
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
                <form method="get" action="{{ route('analytic.classPerformance') }}">
                    <div class="card-body">
                        <div class="row">
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
                            <!-- Class Dropdown -->
                            <div class="form-group col-md-2">
                                <label>Class</label>
                                <select class="form-control" name="class_id">
                                    <option value="" disabled selected>-- Select Class --</option>
                                    @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ request('class_id')==$class->id ? 'selected' :
                                        '' }}>
                                        {{ $class->name }}
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
                                <a href="{{ route('analytic.classPerformance') }}" class="btn btn-success"
                                    style="margin-top: 30px">Reset</a>
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
                    <h3 class="card-title">Class Performance Chart</h3>
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
                    <h3 class="card-title">Class Performance Counts</h3>
                </div>
                <div class="card-body">
                    @if($data->isNotEmpty())
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Year</th>
                                <th>Class Name</th>
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
                                <td>{{ $row->class_name }}</td> <!-- Display class name -->
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
    // @if($data->isNotEmpty())
    //     const labels = @json($data->pluck('subject_name')->unique());
    //     const datasets = [
    //         {
    //             label: 'Count A',
    //             data: @json($data->pluck('count_A')),
    //             backgroundColor: 'rgba(75, 192, 192, 0.7)',
    //         },
    //         {
    //             label: 'Count B',
    //             data: @json($data->pluck('count_B')),
    //             backgroundColor: 'rgba(54, 162, 235, 0.7)',
    //         },
    //         {
    //             label: 'Count C',
    //             data: @json($data->pluck('count_C')),
    //             backgroundColor: 'rgba(255, 206, 86, 0.7)',
    //         },
    //         {
    //             label: 'Count D',
    //             data: @json($data->pluck('count_D')),
    //             backgroundColor: 'rgba(255, 99, 132, 0.7)',
    //         },
    //     ];

    //     new Chart(document.getElementById('gradePerformanceChart'), {
    //         type: 'bar',
    //         data: { labels: labels, datasets: datasets },
    //         options: {
    //             responsive: true,
    //             plugins: {
    //                 legend: { position: 'top' },
    //                 title: { display: false, text: 'Grade Performance Counts' },
    //             },
    //             scales: {
    //                 y: { beginAtZero: true },
    //             },
    //         },
    //     });
    // @endif

    @if($data->isNotEmpty())
    const labels = @json($data->pluck('subject_name')); // Use subject names as labels
    const datasets = [
        {
            label: 'Count A',
            data: @json($data->pluck('count_A')), // Data for Count A
            backgroundColor: 'rgba(75, 192, 192, 0.7)', // Color for Count A
        },
        {
            label: 'Count B',
            data: @json($data->pluck('count_B')), // Data for Count B
            backgroundColor: 'rgba(54, 162, 235, 0.7)', // Color for Count B
        },
        {
            label: 'Count C',
            data: @json($data->pluck('count_C')), // Data for Count C
            backgroundColor: 'rgba(255, 206, 86, 0.7)', // Color for Count C
        },
        {
            label: 'Count D',
            data: @json($data->pluck('count_D')), // Data for Count D
            backgroundColor: 'rgba(255, 99, 132, 0.7)', // Color for Count D
        },
    ];

    new Chart(document.getElementById('gradePerformanceChart'), {
        type: 'bar', // Bar chart type
        data: {
            labels: labels, // Set the labels
            datasets: datasets, // Add the datasets
        },
        options: {
            responsive: true, // Responsive layout
            plugins: {
                legend: {
                    position: 'top', // Place legend at the top
                },
                title: {
                    display: false, // Optional: Hide the title
                    text: 'Grade Performance Counts', // Title text
                },
            },
            scales: {
                y: {
                    beginAtZero: true, // Y-axis starts at 0
                },
            },
        },
    });
@endif

</script>
@endsection
