@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header bg-dark">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Kelas Pemulihan - Students Below 61%</h1>
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
                <a href="{{ route('analytic.reportStudentLess60Percent') }}"> Less Than 60% Performance </a>
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
                <form method="get" action="{{ route('analytic.reportStudentLess60Percent') }}">
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
                            <!-- Exam Type -->
                            <div class="form-group col-md-2">
                                <label>Exam Type</label>
                                <select class="form-control" name="exam_type_id">
                                    <option value="">-- Select Exam --</option>
                                    @foreach($examTypes as $examType)
                                    <option value="{{ $examType->id }}" {{ request('exam_type_id')==$examType->id ?
                                        'selected' : '' }}>
                                        {{ $examType->exam_type_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Syllabus -->
                            <div class="form-group col-md-2">
                                <label>Syllabus</label>
                                <select class="form-control" name="syllabus_id">
                                    <option value="">-- Select Syllabus --</option>
                                    @foreach($syllabuses as $syllabus)
                                    <option value="{{ $syllabus->id }}" {{ request('syllabus_id')==$syllabus->id ?
                                        'selected' : '' }}>
                                        {{ $syllabus->syllabus_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Filter Button -->
                            <div class="form-group col-md-3">
                                <button type="submit" class="btn btn-primary" style="margin-top: 30px">Filter</button>
                                <a href="{{ route('analytic.reportStudentLess60Percent') }}" class="btn btn-success"
                                    style="margin-top: 30px">Reset</a>
                                <button id="saveImage" type="button" class="btn btn-info" style="margin-top: 30px">Save
                                    as Image</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Students Table -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Students Needing Kelas Pemulihan</h3>
                </div>
                <div class="card-body">
                    @if($data->isNotEmpty())
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Student Name</th>
                                <th>Year</th>
                                <th>Class</th>
                                <th>Percentage</th>
                                <th>Total Marks</th>
                                <th>Grade</th>
                                <th>Failed Subjects</th>
                                <th>Absent Subjects</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $index => $student)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $student->student_name }}</td>
                                <td>{{ $student->academic_year_name }}</td>
                                <td>{{ $student->class_name }}</td>
                                <td>
                                    <span
                                        class="@if($student->percentage < 40) text-danger @elseif($student->percentage > 79) text-success @endif">
                                        {{ round($student->percentage, 2) }}%
                                    </span>
                                </td>
                                <td>{{ $student->total_marks }}</td>
                                <td>{{ $student->total_grade ?? 'N/A' }}</td>
                                <td class="{{ $student->failed_subjects ? 'text-danger' : 'text-dark' }}">
                                    {{ $student->failed_subjects ? implode(', ', explode(',',
                                    $student->failed_subjects)) : 'None' }}
                                </td>
                                <td class="{{ $student->absent_subjects ? 'text-warning' : 'text-dark' }}">
                                    {{ $student->absent_subjects ? implode(', ', explode(',',
                                    $student->absent_subjects)) : 'None' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p class="text-center">
                        No students require Kelas Pemulihan at this time. Please apply filters to refine your search or
                        verify the selected criteria.
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
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
