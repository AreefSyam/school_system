@extends('layouts.app')

@section('content')

<div class="content-wrapper">

    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>
                        Class Performance Analytics:
                        <strong>{{ $selectedAcademicYear->academic_year_name ?? 'N/A' }} - {{ $class->name ?? 'N/A'
                            }}</strong>
                    </h1>
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
                <form method="get"
                    action="{{ route('teacher.analytic.classPerformance', ['yearId' => $currentAcademicYear->id ?? '']) }}">
                    <div class="card-body">
                        @if(isset($error))
                        <p class="text-center text-danger">{{ $error }}</p>
                        @else
                        <div class="row">
                            <!-- Exam Type -->
                            <div class="form-group col-md-3">
                                <label for="exam_type_id">Exam Type</label>
                                <select class="form-control" id="exam_type_id" name="exam_type_id">
                                    <option value="" disabled selected>-- Select Exam Type --</option>
                                    @foreach($examTypes as $examType)
                                    <option value="{{ $examType->id }}" {{ request('exam_type_id')==$examType->id ?
                                        'selected' : '' }}>
                                        {{ $examType->exam_type_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Syllabus -->
                            <div class="form-group col-md-3">
                                <label for="syllabus_id">Syllabus</label>
                                <select class="form-control" id="syllabus_id" name="syllabus_id">
                                    <option value="" disabled selected>-- Select Syllabus --</option>
                                    @foreach($syllabuses as $syllabus)
                                    <option value="{{ $syllabus->id }}" {{ request('syllabus_id')==$syllabus->id ?
                                        'selected' : '' }}>
                                        {{ $syllabus->syllabus_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Filter Buttons -->
                            <div class="form-group col-md-4">
                                <button type="submit" class="btn btn-primary" style="margin-top: 30px">Filter</button>
                                <a href="{{ route('teacher.analytic.classPerformance', ['yearId' => $currentAcademicYear->id ?? '']) }}"
                                    class="btn btn-success" style="margin-top: 30px">Reset</a>
                                <button id="saveImage" type="button" class="btn btn-info" style="margin-top: 30px">Save
                                    as Image</button>
                            </div>
                            {{-- Appears only filter button clicked --}}
                            @if(request('academic_year_id') || request('class_id') || request('syllabus_id') ||
                            request('exam_type_id'))
                            <div class="form-group col-md-2">
                                <!-- Redirect to reportStudentLess60Percent with filters -->
                                <a href="{{ route('teacher.analytic.reportStudentLess60Percent', [
                                                            'yearId' => $currentAcademicYear->id ?? '',
                                                            'class_id' => request('class_id'),
                                                            'syllabus_id' => request('syllabus_id'),
                                                            'exam_type_id' => request('exam_type_id')
                                                        ]) }}" class="btn btn-warning" style="margin-top: 30px">
                                    Check Students < 61% </a>
                            </div>
                            @endif
                            @endif
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Performance Chart -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
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
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Class Performance Counts</h3>
                </div>
                <div class="card-body">
                    @if($data->isNotEmpty())
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Year</th>
                                <th>Class Name</th>
                                <th>Subject</th>
                                <th>Total A</th>
                                <th>Total B</th>
                                <th>Total C</th>
                                <th>Total D</th>
                                <th>Total TH</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->academic_year_name }}</td>
                                <td>{{ $row->class_name }}</td>
                                <td>{{ $row->subject_name }}</td>
                                <td>{{ $row->count_A }}</td>
                                <td>{{ $row->count_B }}</td>
                                <td>{{ $row->count_C }}</td>
                                <td>{{ $row->count_D }}</td>
                                <td>{{ $row->count_TH }}</td>
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
    const labels = @json($data->pluck('subject_name')); // Use subject names as labels
    const datasets = [
        {
            label: 'Total A',
            data: @json($data->pluck('count_A')), // Data for Count A
            backgroundColor: 'rgba(75, 192, 192, 0.7)', // Color for Count A
        },
        {
            label: 'Total B',
            data: @json($data->pluck('count_B')), // Data for Count B
            backgroundColor: 'rgba(54, 162, 235, 0.7)', // Color for Count B
        },
        {
            label: 'Total C',
            data: @json($data->pluck('count_C')), // Data for Count C
            backgroundColor: 'rgba(255, 206, 86, 0.7)', // Color for Count C
        },
        {
            label: 'Total D',
            data: @json($data->pluck('count_D')), // Data for Count D
            backgroundColor: 'rgba(255, 99, 132, 0.7)', // Color for Count D
        },
        {
            label: 'Total TH',
            data: @json($data->pluck('count_TH')), // Data for Count TH
            backgroundColor: 'rgba(153, 102, 255, 0.7)', // Purple for Count TH

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

<script>
    document.getElementById('academic_year_id').addEventListener('change', function () {
    const academicYearId = this.value;
    const classDropdown = document.getElementById('class_id');
    classDropdown.innerHTML = '<option>Loading...</option>';

    if (academicYearId) {
        fetch("{{ route('teacher.getClasses') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({ academic_year_id: academicYearId })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch classes');
            }
            return response.json();
        })
        .then(data => {
            classDropdown.innerHTML = '<option value="">-- Select Class --</option>';
            data.forEach(classItem => {
                const option = document.createElement('option');
                option.value = classItem.id;
                option.textContent = classItem.name;
                classDropdown.appendChild(option);
            });
        })
        .catch(error => {
            console.error(error);
            classDropdown.innerHTML = '<option value="">Failed to load classes</option>';
        });
    }
});
</script>
@endsection
