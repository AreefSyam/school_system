@extends('layouts.app')

@section('content')
<div class="content-wrapper">

    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h1>Edit Marks and Attendance for {{ $examType->exam_type_name }} - {{ $syllabus->syllabus_name }}
                        ({{ $year->academic_year_name }}) : {{ $class->name }}</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <!-- Home -->
            <li class="breadcrumb-item">
                <a href="{{ route('exams.yearList') }}">Exam Data </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('exams.examTypeList',  ['yearId' => $year->id]) }}">{{ $year->academic_year_name
                    }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('exams.syllabusList',  ['yearId' => $year->id, 'examTypeId' => $examType->id]) }}">{{
                    $examType->exam_type_name }}</a>
            </li>
            <li class="breadcrumb-item">
                <a
                    href="{{ route('exams.classList',  ['yearId' => $year->id, 'syllabusId' => $syllabus->id, 'examTypeId' => $examType->id]) }}">{{
                    $syllabus->syllabus_name }}</a>
            </li>
            <li class="breadcrumb-item">
                <a
                    href="{{ route('exams.marks',  ['yearId' => $year->id, 'syllabusId' => $syllabus->id, 'examTypeId' => $examType->id, 'classId' => $class->id, 'examId' => $exam->id]) }}">{{
                    $class->name }}</a>
            </li>
        </ol>
    </nav>

    <section class="content">
        <div class="card">
            <div class="card-body">
                <form
                    action="{{ route('exam.marks.edit.updateAll', [$year->id, $examType->id, $syllabus->id, $class->id, $exam->id]) }}"
                    method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Hidden fields to send the IDs -->
                    <input type="hidden" name="class_id" value="{{ $class->id }}">
                    <input type="hidden" name="syllabus_id" value="{{ $syllabus->id }}">
                    <input type="hidden" name="exam_type_id" value="{{ $examType->id }}">
                    <input type="hidden" name="academic_year_id" value="{{ $year->id }}">

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                @foreach($subjects as $subject)
                                <th>{{ $subject->subject_name }}</th>
                                @endforeach
                                <th>Total Attendance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                            <tr>
                                <td>{{ $student->full_name }}</td>
                                @foreach($subjects as $subject)
                                @php
                                $studentMark = $marks->get($student->id)?->firstWhere('subject_id',
                                $subject->subject_id);
                                $isAbsent = $studentMark ? $studentMark->status === 'absent' : false;
                                // Ensure $studentMark is not null before accessing status

                                @endphp
                                <td>
                                    <input type="number" id="marks-{{ $student->id }}-{{ $subject->subject_id }}"
                                        name="marks[{{ $student->id }}][{{ $subject->subject_id }}]"
                                        value="{{ $isAbsent ? '0' : $studentMark->mark ?? '' }}" class="form-control"
                                        min="0" max="100" {{ $isAbsent ? '' : '' }} required>

                                    <input type="hidden" name="status[{{ $student->id }}][{{ $subject->subject_id }}]"
                                        value="{{ $isAbsent ? 'absent' : 'present' }}">

                                    <div>
                                        <input type="checkbox" class="absence-checkbox"
                                            data-student-id="{{ $student->id }}"
                                            data-subject-id="{{ $subject->subject_id }}"
                                            onchange="handleAbsenceToggle(this, {{ $student->id }}, {{ $subject->subject_id }})"
                                            {{ $isAbsent ? 'checked' : '' }}> TH
                                    </div>
                                </td>

                                {{-- <td>
                                    <!-- Input for marks -->
                                    <input type="number" id="marks-{{ $student->id }}-{{ $subject->subject_id }}"
                                        name="marks[{{ $student->id }}][{{ $subject->subject_id }}]"
                                        value="{{ $isAbsent ? '0' : $studentMark->mark ?? '' }}" class="form-control"
                                        data-original-value="{{ $isAbsent ? '0' : ($studentMark->mark ?? '') }}" min="0"
                                        max="100">

                                    <!-- Hidden input for status -->
                                    <input type="hidden" name="status[{{ $student->id }}][{{ $subject->subject_id }}]"
                                        value="{{ $isAbsent ? 'absent' : 'present' }}">

                                    <!-- Checkbox for absence -->
                                    <div>
                                        <input type="checkbox" class="absence-checkbox"
                                            data-student-id="{{ $student->id }}"
                                            data-subject-id="{{ $subject->subject_id }}" {{ $isAbsent ? 'checked' : ''
                                            }}>
                                        TH
                                    </div>
                                </td> --}}

                                @endforeach
                                <td>
                                    <input type="number" name="attendance[{{ $student->id }}]"
                                        value="{{ $studentsSummary->firstWhere('student_id', $student->id)?->attendance ?? '' }}"
                                        class="form-control" />
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-end mt-3">
                        <a href="{{ route('exams.marks', ['yearId' => $year->id, 'examTypeId' => $examType->id, 'syllabusId' => $syllabus->id, 'classId' => $class->id, 'examId' => $exam->id]) }}"
                            class="btn btn-secondary me-2 mt-3">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-success mt-3">
                            Save All Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection

{{-- No 2 --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.absence-checkbox');
    const markInputs = document.querySelectorAll('input[type="number"]');

    // Initialize the page: Set marks to 0 if null or empty
    markInputs.forEach(markInput => {
        if (markInput.value === '' || markInput.value === null) {
            markInput.value = '0'; // Set default value to 0
        }
    });

    // Function to handle checkbox changes
    const handleAbsenceToggle = (checkbox) => {
        const studentId = checkbox.dataset.studentId;
        const subjectId = checkbox.dataset.subjectId;
        const markInput = document.getElementById(`marks-${studentId}-${subjectId}`);
        const statusInput = document.querySelector(`input[name='status[${studentId}][${subjectId}]']`);

        if (checkbox.checked) {
            markInput.value = '0'; // Set value to 0
            markInput.setAttribute('readonly', true); // Prevent edits
            statusInput.value = 'absent'; // Set status to absent
        } else {
            markInput.value = ''; // Clear value
            markInput.removeAttribute('readonly'); // Allow edits
            statusInput.value = 'present'; // Set status to present
        }
    };

    // Initialize the page by setting readonly for checked checkboxes
    checkboxes.forEach(checkbox => {
        const studentId = checkbox.dataset.studentId;
        const subjectId = checkbox.dataset.subjectId;
        const markInput = document.getElementById(`marks-${studentId}-${subjectId}`);

        if (checkbox.checked) {
            markInput.setAttribute('readonly', true); // Prevent edits if checkbox is checked
        }

        // Attach event listener for changes
        checkbox.addEventListener('change', function () {
            handleAbsenceToggle(this);
        });
    });

    // Ensure all inputs are valid before form submission
    const form = document.querySelector('form');
    form.addEventListener('submit', function () {
        checkboxes.forEach(checkbox => {
            const studentId = checkbox.dataset.studentId;
            const subjectId = checkbox.dataset.subjectId;
            const markInput = document.getElementById(`marks-${studentId}-${subjectId}`);

            // Ensure numeric value is submitted
            if (markInput.value === '' || markInput.value === null) {
                markInput.value = '0';
            }
            markInput.removeAttribute('readonly'); // Remove readonly to include in submission
        });

        markInputs.forEach(markInput => {
            // Ensure empty or invalid inputs are set to 0
            if (markInput.value === '' || markInput.value === null) {
                markInput.value = '0';
            }
        });
    });
});

</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const markInputs = document.querySelectorAll('input[type="number"]');

    markInputs.forEach(input => {
        input.addEventListener('invalid', function () {
            if (this.value === '' || this.value === null) {
                this.setCustomValidity('Please enter a valid mark or set it to 0.');
            } else {
                this.setCustomValidity('');
            }
        });

        input.addEventListener('input', function () {
            this.setCustomValidity(''); // Clear custom messages on input
        });
    });
});

</script>
