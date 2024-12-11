@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Manage Marks and Attendance for {{ $subject->subject_name }}</h1>
                </div>
            </div>
        </div>
    </section>

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('teacher.exams.examTypeList', ['yearId' => $selectedAcademicYear->id]) }}">
                    Exam Data
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('teacher.exams.examTypeList', ['yearId' => $selectedAcademicYear->id]) }}">
                    {{ $selectedAcademicYear->academic_year_name ?? 'N/A' }}
                </a>
            </li>
            <li class="breadcrumb-item">
                <a
                    href="{{ route('teacher.exams.syllabusList', ['yearId' => $selectedAcademicYear->id, 'examTypeId' => $examType->id]) }}">
                    {{ $breadcrumbData['examTypeName'] ?? 'N/A' }}
                </a>
            </li>
            <li class="breadcrumb-item">
                <a
                    href="{{ route('teacher.exams.subjectList', ['yearId' => $selectedAcademicYear->id, 'examTypeId' => $examType->id, 'syllabusId' => $syllabus->id]) }}">
                    {{ $breadcrumbData['syllabusName'] ?? 'N/A' }}
                </a>
            </li>
            <li class="breadcrumb-item">
                <a
                    href="{{ route('teacher.exams.classList', ['yearId' => $selectedAcademicYear->id, 'examTypeId' => $examType->id, 'syllabusId' => $syllabus->id, 'subjectId' => $subject->id]) }}">
                    {{ $breadcrumbData['subjectName'] ?? 'N/A' }}
                </a>
            </li>
            <li class="breadcrumb-item">
                <a
                    href="{{ route('teacher.exams.marks', ['yearId' => $selectedAcademicYear->id, 'examTypeId' => $examType->id, 'syllabusId' => $syllabus->id, 'subjectId' => $subject->id, 'classId' => $class->id]) }}">
                    {{ $breadcrumbData['className'] ?? 'N/A' }}
                </a>
            </li>
        </ol>
    </nav>

    <!-- Marks Form -->
    <section class="content">
        <div class="container-fluid">
            @if(!$marks->isEmpty())
            <form
                action="{{ route('teacher.exams.marks.store', [$yearId, $examType->id, $syllabus->id, $subject->id, $class->id]) }}"
                method="POST">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Students and Marks</h3>
                    </div>
                    <div class="card-body">
                        <!-- Hidden input for class_id outside the loop -->
                        <input type="hidden" name="class_id" value="{{ $class->id }}">
                        <input type="hidden" name="syllabus_id" value="{{ $syllabus->id }}">
                        <input type="hidden" name="exam_type_id" value="{{ $examType->id }}">
                        <input type="hidden" name="academic_year_id" value="{{ $yearId }}">
                        <input type="hidden" name="subject_id" value="{{ $subject->id }}">

                        <!-- Table -->
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Marks</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $index => $student)
                                <tr>
                                    <td>{{ $student->full_name }}</td>
                                    @php
                                    // Find the mark for this student
                                    $mark = $marks->firstWhere('student_id', $student->id);
                                    $isAbsent = $mark ? $mark->status === 'absent' : false;
                                    @endphp
                                    <td>
                                        <input type="number" id="marks-{{ $student->id }}-{{ $subject->id }}"
                                            name="marks[{{ $index }}][mark]" class="form-control"
                                            value="{{ $isAbsent ? '0' : $mark->mark ?? '' }}" {{ $isAbsent ? 'readonly'
                                            : '' }} min="0" max="100">
                                        <input type="hidden" name="marks[{{ $index }}][student_id]"
                                            value="{{ $student->id }}">
                                    </td>
                                    <td>
                                        <div>
                                            <input type="checkbox" class="absence-checkbox"
                                                data-student-id="{{ $student->id }}"
                                                data-subject-id="{{ $subject->id }}"
                                                onchange="handleAbsenceToggle(this, {{ $student->id }}, {{ $subject->id }})"
                                                {{ $isAbsent ? 'checked' : '' }}>
                                            Mark Absent
                                        </div>
                                        <input type="hidden" id="status-{{ $student->id }}-{{ $subject->id }}"
                                            name="status[{{ $student->id }}][{{ $subject->id }}]"
                                            value="{{ $isAbsent ? 'absent' : 'present' }}">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('teacher.exams.classList', ['yearId' => $currentAcademicYear->id, 'examTypeId' => $examType->id, 'syllabusId' => $syllabus->id, 'subjectId' => $subject->id])  }}"
                                class="btn btn-secondary me-2 mt-3">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-success mt-3">
                                Save All Changes
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            @else
            <p class="text-center text-red">No marks found for the selected academic year, exam type, syllabus, class,
                or subject.
            </p>
            @endif
        </div>
    </section>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.absence-checkbox');

    const handleAbsenceToggle = (checkbox, studentId, subjectId) => {
        const markInput = document.getElementById(`marks-${studentId}-${subjectId}`);
        const statusInput = document.getElementById(`status-${studentId}-${subjectId}`);

        if (checkbox.checked) {
            markInput.value = '0'; // Set value to 0
            markInput.setAttribute('readonly', true); // Make input readonly
            statusInput.value = 'absent'; // Set status to absent
        } else {
            markInput.value = markInput.dataset.originalValue || ''; // Restore value if available
            markInput.removeAttribute('readonly'); // Make input editable
            statusInput.value = 'present'; // Set status to present
        }

        console.log(`Updated status for student ${studentId}, subject ${subjectId}:`, statusInput.value); // Debugging
    };

    // Initialize and attach event listeners
    checkboxes.forEach(checkbox => {
        const studentId = checkbox.dataset.studentId;
        const subjectId = checkbox.dataset.subjectId;

        // Set initial state for read-only inputs
        if (checkbox.checked) handleAbsenceToggle(checkbox, studentId, subjectId);

        // Attach event listener for changes
        checkbox.addEventListener('change', () => handleAbsenceToggle(checkbox, studentId, subjectId));
    });
});

</script>
