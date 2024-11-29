@extends('layouts.app')

@section('content')
<div class="content-wrapper">

    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Individual Performance Analytics</h1>
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
                <form method="get" action="{{ route('analytic.individualPerformance') }}">
                    <div class="card-body">
                        <div class="row">
                            <!-- Academic Year -->
                            <div class="form-group col-md-2">
                                <label for="academic_year_id">Academic Year (First)</label>
                                <select class="form-control" id="academic_year_id" name="academic_year_id">
                                    <option value="" disabled selected>-- Select Year --</option>
                                    @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}" {{ request('academic_year_id')==$year->id ?
                                        'selected' : '' }}>
                                        {{ $year->academic_year_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Class -->
                            <div class="form-group col-md-2">
                                <label for="class_id">Class</label>
                                <select class="form-control" id="class_id" name="class_id">
                                    <option value="" disabled selected>-- Select Class --</option>
                                    @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ request('class_id')==$class->id ? 'selected' :
                                        '' }}>
                                        {{ $class->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Student -->
                            <div class="form-group col-md-2">
                                <label for="student_id">Student</label>
                                <select class="form-control" id="student_id" name="student_id">
                                    <option value="" disabled selected>-- Select Student --</option>
                                    @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ request('student_id')==$student->id ?
                                        'selected' : '' }}>
                                        {{ $student->full_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Syllabus -->
                            <div class="form-group col-md-2">
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
                            <div class="form-group col-md-2">
                                <button type="submit" class="btn btn-primary" style="margin-top: 30px;">Filter</button>
                                <a href="{{ route('analytic.individualPerformance') }}" class="btn btn-success"
                                    style="margin-top: 30px;">Reset</a>
                            </div>
                        </div>
                        <div class="row mt-3">

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Individual Performance Chart -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Individual Performance Chart</h3>
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

    <!-- Performance Data Table -->
    <section class="content">
        <div class="container-fluid">
            <!-- Table for Exam Type: PPT -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Individual Performance for Exam Type: PPT</h3>
                </div>
                <div class="card-body">
                    @if($data->isNotEmpty())
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Student Name</th>
                                @foreach($subjects as $subject)
                                <th>{{ $subject->subject_name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $index => $student)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $student->full_name }}</td>
                                @foreach($subjects as $subject)
                                <td>{{ $marksByStudent[$student->id][$subject->id]['PPT'] ?? 'N/A' }}</td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p class="text-center">No data available for PPT.</p>
                    @endif
                </div>
            </div>

            <!-- Table for Exam Type: PAT -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Individual Performance for Exam Type: PAT</h3>
                </div>
                <div class="card-body">
                    @if($data->isNotEmpty())
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Student Name</th>
                                @foreach($subjects as $subject)
                                <th>{{ $subject->subject_name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $index => $student)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $student->full_name }}</td>
                                @foreach($subjects as $subject)
                                <td>{{ $marksByStudent[$student->id][$subject->id]['PAT'] ?? 'N/A' }}</td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p class="text-center">No data available for PAT.</p>
                    @endif
                </div>
            </div>
        </div>
    </section>



</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    @if($chartData)
        const chartData = @json($chartData);
        const labels = Object.keys(chartData[1] || {}); // Assuming '1' is for PPT
        const pptMarks = Object.values(chartData[1] || {});
        const patMarks = Object.values(chartData[2] || {});

        new Chart(document.getElementById('gradePerformanceChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'PPT Marks',
                        data: pptMarks,
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                    },
                    {
                        label: 'PAT Marks',
                        data: patMarks,
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                },
                scales: {
                    y: { beginAtZero: true },
                },
            },
        });
    @else
        console.error("No chart data available.");
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

document.getElementById('class_id').addEventListener('change', function () {
    const classId = this.value;
    const academicYearId = document.getElementById('academic_year_id').value;
    const studentDropdown = document.getElementById('student_id');

    studentDropdown.innerHTML = '<option>Loading...</option>';

    if (classId && academicYearId) {
        fetch("{{ route('teacher.getStudents') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({ class_id: classId, academic_year_id: academicYearId })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch students');
            }
            return response.json();
        })
        .then(data => {
            studentDropdown.innerHTML = '<option value="">-- Select Student --</option>';
            if (data.length === 0) {
                studentDropdown.innerHTML = '<option value="">No students available</option>';
            } else {
                data.forEach(student => {
                    const option = document.createElement('option');
                    option.value = student.id;
                    option.textContent = student.full_name;
                    studentDropdown.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error(error);
            studentDropdown.innerHTML = '<option value="">Failed to load students</option>';
        });
    }
});

</script>
@endsection