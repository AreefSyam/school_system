@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header  bg-dark">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                {{-- <!-- Back Button -->
                <div class="col-auto">
                    <a href="{{ route('teacher.deleteAssignment', $teacher->id) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div> --}}
                <!-- Page Title -->
                <div class="col">
                    <h1 class="m-0">Assign New Subject Class to Teacher: <strong>{{ $teacher->name }}</strong></h1>
                </div>
            </div>
        </div>
    </section>

    {{-- breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Home </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('teacher.list') }}">Teacher List </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('teacher.classAssignments', $teacher->id)}}"> Teaching Assignment </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('teacher.assignClass', $teacher->id) }}"> Assign New Subject </a>
            </li>
        </ol>
    </nav>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Class Assignment Form</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('teacher.assignClass.post', $teacher->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Academic Year</label>
                            <select id="academic_year_id" name="academic_year_id" class="form-control" required>
                                <option value="">-- Please Select Academic Year First --</option>
                                @foreach($academicYears as $year)
                                <option value="{{ $year->id }}">{{ $year->academic_year_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Class</label>
                            <select id="class_id" name="class_id" class="form-control" required>
                                <option value="">-- Select Class --</option>
                                <!-- Classes will be dynamically loaded here -->
                            </select>
                        </div>

                        <!-- Subject Dropdown -->
                        <div class="form-group">
                            <label>Subject</label>
                            <select id="subject_id" name="subject_id" class="form-control" required>
                                <option value="">-- Select Subject --</option>
                                <!-- Subjects will be dynamically loaded here -->
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Grade Level</label>
                            <select id="grade_level_id" name="grade_level_id" class="form-control" required>
                                <option value="">-- Select Grade Level --</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Syllabus</label>
                            <select id="syllabus_id" name="syllabus_id" class="form-control" required>
                                <option value="">-- Select Syllabus --</option>
                            </select>
                        </div>


                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-success">Assign New Class</button>
                </div>
                </form>
            </div>
        </div>
    </section>
</div>

<!-- Add JavaScript for Dynamic Dropdown -->
<script>
    document.getElementById('academic_year_id').addEventListener('change', function () {
    const academicYearId = this.value;

    // Clear dropdowns
    const classDropdown = document.getElementById('class_id');
    const subjectDropdown = document.getElementById('subject_id');
    classDropdown.innerHTML = '<option>Loading...</option>';
    subjectDropdown.innerHTML = '<option>Loading...</option>';

    if (academicYearId) {
        // Fetch classes dynamically
        fetch("{{ route('teacher.getClasses') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({ academic_year_id: academicYearId })
        })
        .then(response => response.json())
        .then(data => {
            classDropdown.innerHTML = '<option value="">-- Select Class --</option>'; // Reset options
            if (data.length === 0) {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'No classes available';
                classDropdown.appendChild(option);
            } else {
                data.forEach(classItem => {
                    const option = document.createElement('option');
                    option.value = classItem.id;
                    option.textContent = classItem.name;
                    classDropdown.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error fetching classes:', error);
            classDropdown.innerHTML = '<option value="">Failed to load classes</option>';
        });

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

    // Clear the syllabus dropdown
    syllabusDropdown.innerHTML = '<option value="">-- Select Syllabus --</option>';

    if (subjectId) {
        // Fetch the syllabus dynamically
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
            if (data.error) {
                alert(data.error);
            } else {
                // Populate the syllabus dropdown with the retrieved data
                const option = document.createElement('option');
                option.value = data.id;
                option.textContent = data.name;
                syllabusDropdown.appendChild(option);
                syllabusDropdown.value = data.id; // Automatically select the value
            }
        })
        .catch(error => console.error('Error fetching syllabus:', error));
    }
});

document.getElementById('class_id').addEventListener('change', function () {
    const classId = this.value;
    const gradeLevelDropdown = document.getElementById('grade_level_id');

    // Clear the grade level dropdown
    gradeLevelDropdown.innerHTML = '<option value="">-- Select Grade Level --</option>';

    if (classId) {
        // Fetch the grade level dynamically
        fetch("{{ route('teacher.getGradeLevel') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({ class_id: classId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
            } else {
                // Populate the grade level dropdown with the retrieved data
                const option = document.createElement('option');
                option.value = data.id;
                option.textContent = data.name;
                gradeLevelDropdown.appendChild(option);
                gradeLevelDropdown.value = data.id; // Automatically select the value
            }
        })
        .catch(error => console.error('Error fetching grade level:', error));
    }
});

</script>
@endsection
