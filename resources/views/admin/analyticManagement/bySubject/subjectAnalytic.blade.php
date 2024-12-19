@extends('layouts.app')

@section('content')
<div class="content-wrapper">


    <!-- Content Header -->
    <section class="content-header bg-dark">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Subject Performance Analytics</h1>
                </div>
            </div>
        </div>
    </section>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('analytic.subjectPerformance') }}"> Subject Performance </a>
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
                <form method="get" action="{{ route('analytic.subjectPerformance') }}">
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

                            <!-- Subject Dropdown -->
                            <div class="form-group col-md-2">
                                <label>Subject</label>
                                <select id="subject_id" name="subject_id" class="form-control" required>
                                    <option value="">-- Select Subject --</option>
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
                                <label for="syllabus_id">Syllabus</label>
                                <select class="form-control" id="syllabus_id" name="syllabus_id">
                                    <option value="" disabled selected>-- Select Syllabus --</option>
                                    <!-- Dynamic options populated via JavaScript -->
                                    @if(request('syllabus_id') && $syllabuses->isNotEmpty())
                                    @foreach($syllabuses as $syllabus)
                                    <option value="{{ $syllabus->id }}" {{ request('syllabus_id')==$syllabus->id ?
                                        'selected' : '' }}>
                                        {{ $syllabus->syllabus_name }}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>

                            <!-- Exam Type -->
                            <div class="form-group col-md-2">
                                <label for="exam_type_id">Exam Type</label>
                                <select class="form-control" id="exam_type_id" name="exam_type_id">
                                    <option value="" disabled selected>-- Select Exam --</option>
                                    @foreach($examTypes as $examType)
                                    <option value="{{ $examType->id }}" {{ request('exam_type_id')==$examType->id ?
                                        'selected' : '' }}>
                                        {{ $examType->exam_type_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Grade Levels Section -->
                            <div class="form-group col-md-6">
                                <label for="grade_level_id">Grade Levels</label>
                                <div class="card p-3 border">
                                    <!-- Select All Option -->
                                    <div class="form-check mb-2">
                                        <input type="checkbox" class="form-check-input" id="select_all_grade_levels">
                                        <label class="form-check-label font-weight-bold"
                                            for="select_all_grade_levels">Select All</label>
                                    </div>
                                    <!-- Grade Level Checkboxes -->
                                    <div class="d-flex flex-wrap">
                                        @foreach($gradeLevels as $grade)
                                        <div class="form-check mr-3 mb-2">
                                            <input type="checkbox" class="form-check-input grade_level_checkbox"
                                                id="grade_level_{{ $grade->id }}" name="grade_level_id[]"
                                                value="{{ $grade->id }}" {{ in_array($grade->id,
                                            (array)request('grade_level_id')) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="grade_level_{{ $grade->id }}">
                                                {{ $grade->grade_name }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Filter Buttons -->
                            <div class="form-group col-md-3">
                                <button type="submit" class="btn btn-primary" style="margin-top: 30px;">Filter</button>
                                <a href="{{ route('analytic.subjectPerformance') }}" class="btn btn-success"
                                    style="margin-top: 30px;">Reset</a>
                                <button id="saveImage" type="button" class="btn btn-info" style="margin-top: 30px">Save
                                    as Image</button>
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
                                <th>#</th>
                                <th>Year</th>
                                <th>Grade Year</th>
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
                                <td>{{ $row->grade_name }}</td>
                                <td>{{ $row->subject_name }}</td>
                                <td>{{ $row->count_A }}</td>
                                <td>{{ $row->count_B }}</td>
                                <td>{{ $row->count_C }}</td>
                                <td>{{ $row->count_D }}</td>
                                <td>{{ $row->count_TH }}</td> <!-- Display absent count -->
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
                label: 'Total A',
                data: @json($data->pluck('count_A')),
                backgroundColor: 'rgba(75, 192, 192, 0.7)',
            },
            {
                label: 'Total B',
                data: @json($data->pluck('count_B')),
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
            },
            {
                label: 'Total C',
                data: @json($data->pluck('count_C')),
                backgroundColor: 'rgba(255, 206, 86, 0.7)',
            },
            {
                label: 'Total D',
                data: @json($data->pluck('count_D')),
                backgroundColor: 'rgba(255, 99, 132, 0.7)',
            },
            {
                label: 'Total TH',
                data: @json($data->pluck('count_TH')),
                backgroundColor: 'rgba(153, 102, 255, 0.7)', // Purple for Count TH
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

<script>
    document.getElementById('academic_year_id').addEventListener('change', function () {
    const academicYearId = this.value;

    // Clear dropdowns
    const subjectDropdown = document.getElementById('subject_id');
    subjectDropdown.innerHTML = '<option>Loading...</option>';

    if (academicYearId) {

        // Fetch subjects dynamically
        fetch("{{ route('teacher.getSubjects') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({ academic_year_id: academicYearId })
        })
        .then(response => response.json())
        .then(data => {
            subjectDropdown.innerHTML = '<option value="">-- Select Subject --</option>'; // Reset options
            if (data.length === 0) {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'No subjects available';
                subjectDropdown.appendChild(option);
            } else {
                data.forEach(subjectItem => {
                    const option = document.createElement('option');
                    option.value = subjectItem.id;
                    option.textContent = subjectItem.name;
                    subjectDropdown.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error fetching subjects:', error);
            subjectDropdown.innerHTML = '<option value="">Failed to load subjects</option>';
        });
    }
});


document.getElementById('subject_id').addEventListener('change', function () {
    const subjectId = this.value;

    const syllabusDropdown = document.getElementById('syllabus_id');
    syllabusDropdown.innerHTML = '<option>Loading syllabus...</option>';

    if (subjectId) {
        fetch("{{ route('teacher.getSyllabus') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({ subject_id: subjectId })
        })
        .then(response => response.json())
        .then(data => {
            syllabusDropdown.innerHTML = '<option value="">-- Select Syllabus --</option>';
            if (data.error) {
                syllabusDropdown.innerHTML += '<option>No syllabus available</option>';
            } else {
                syllabusDropdown.innerHTML += `<option value="${data.id}">${data.name}</option>`;
            }
        })
        .catch(error => console.error('Error fetching syllabus:', error));
    }
});
document.addEventListener('DOMContentLoaded', function () {
    const selectAllCheckbox = document.getElementById('select_all_grade_levels');
    const gradeLevelCheckboxes = document.querySelectorAll('.grade_level_checkbox');

    // Event listener for "Select All"
    selectAllCheckbox.addEventListener('change', function () {
        gradeLevelCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Sync "Select All" checkbox with individual checkboxes
    gradeLevelCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const allChecked = Array.from(gradeLevelCheckboxes).every(cb => cb.checked);
            selectAllCheckbox.checked = allChecked;

            const anyChecked = Array.from(gradeLevelCheckboxes).some(cb => cb.checked);
            selectAllCheckbox.indeterminate = !allChecked && anyChecked;
        });
    });
});

</script>

@endsection
