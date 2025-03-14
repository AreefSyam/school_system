@extends('layouts.app')

@section('content')

<div class="content-wrapper">

    <!-- Content Header -->
    <section class="content-header bg-dark">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Class Performance Analytics</h1>
                </div>
            </div>
        </div>
    </section>

    {{-- breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('analytic.classPerformance') }}"> Class Performance </a>
            </li>
        </ol>
    </nav>

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
                            {{-- Academic Year --}}
                            <div class="form-group col-md-2">
                                <label>Academic Year (First)</label>
                                <select id="academic_year_id" name="academic_year_id" class="form-control" required>
                                    <option value="">-- Select Year --</option>
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
                                <select id="class_id" name="class_id" class="form-control" required>
                                    <option value="">-- Select Class --</option>
                                    @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ request('class_id')==$class->id ?
                                        'selected' : '' }}>
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
                            <div class="form-group col-md-3">
                                <button type="submit" class="btn btn-primary" style="margin-top: 30px">Filter</button>
                                <a href="{{ route('analytic.classPerformance') }}" class="btn btn-success"
                                    style="margin-top: 30px">Reset</a>
                                <button id="saveImage" type="button" class="btn btn-info" style="margin-top: 30px">Save
                                    as Image</button>
                            </div>

                            {{-- Appears only filter button clicked --}}
                            @if(request('academic_year_id') || request('class_id') || request('syllabus_id') ||
                            request('exam_type_id'))
                            <div class="form-group col-md-2">
                                <!-- Redirect to reportStudentLess60Percent with filters -->
                                <a href="{{ route('analytic.reportStudentLess60Percent', [
                                    'academic_year_id' => request('academic_year_id'),
                                    'class_id' => request('class_id'),
                                    'syllabus_id' => request('syllabus_id'),
                                    'exam_type_id' => request('exam_type_id')
                                ]) }}" class="btn btn-warning" style="margin-top: 30px">
                                    Students < 61% </a>
                            </div>
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
                    <div style="overflow-x: auto;">
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
                                    <th>Total TH</th> <!-- New column for absent students -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $row->academic_year_name }}</td>
                                    <td>{{ $row->class_name }}</td>
                                    <td>{{ $row->subject_name }}</td>
                                    <td>
                                        <button type="button" class="btn btn-default" data-bs-toggle="modal"
                                            data-bs-target="#studentModal" data-student-list="{{ $row->list_A }}">
                                            {{ $row->count_A }}
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-default" data-bs-toggle="modal"
                                            data-bs-target="#studentModal" data-student-list="{{ $row->list_B }}">
                                            {{ $row->count_B }}
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-default" data-bs-toggle="modal"
                                            data-bs-target="#studentModal" data-student-list="{{ $row->list_C }}">
                                            {{ $row->count_C }}
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-default" data-bs-toggle="modal"
                                            data-bs-target="#studentModal" data-student-list="{{ $row->list_D }}">
                                            {{ $row->count_D }}
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-default" data-bs-toggle="modal"
                                            data-bs-target="#studentModal" data-student-list="{{ $row->list_TH }}">
                                            {{ $row->count_TH }}
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-center">No data available for the selected filters.</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

</div>

{{-- Modal --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

<div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="studentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="studentModalLabel">Student List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul id="studentList"></ul>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const studentModal = document.getElementById('studentModal');
        const studentList = document.getElementById('studentList');

        studentModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Button that triggered the modal
            const students = button.getAttribute('data-student-list'); // Extract student list from data-* attribute

            // Clear previous content
            studentList.innerHTML = '';

            if (students) {
                const studentArray = students.split(','); // Assuming names are comma-separated
                studentArray.forEach(function (student) {
                    const listItem = document.createElement('li');
                    listItem.textContent = student.trim();
                    studentList.appendChild(listItem);
                });
            } else {
                const noData = document.createElement('p');
                noData.textContent = 'No students available.';
                studentList.appendChild(noData);
            }
        });
    });
</script>

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
            data: @json($data->pluck('count_TH')),
            backgroundColor: 'rgba(153, 102, 255, 0.7)', // Purple for Count TH
        }
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
