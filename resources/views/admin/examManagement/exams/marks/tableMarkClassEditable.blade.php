@extends('layouts.app')

@section('content')
<div class="content-wrapper">

    <!-- Content Header with dynamic titles -->
    <section class="content-header bg-dark">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h1>Edit Marks and Attendance for {{ $examType->exam_type_name }} - {{ $syllabus->syllabus_name }}
                        ({{ $year->academic_year_name }}) : {{ $class->name }}</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Breadcrumb navigation -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Home</a>
            </li>
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
            <li class="breadcrumb-item">
                <a
                    href="{{ route('exams.marks.edit',  ['yearId' => $year->id, 'syllabusId' => $syllabus->id, 'examTypeId' => $examType->id, 'classId' => $class->id, 'examId' => $exam->id]) }}">
                    Edit Mark</a>
            </li>
        </ol>
    </nav>

    <!-- Main content section for form -->
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

                    <!-- Table for inputting marks -->
                    <div style="overflow-x: auto;">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    @foreach($subjects as $subject)
                                    <th>{{ $subject->subject_name }}</th>
                                    @endforeach
                                    <th> Summary</th>
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
                                    @endphp
                                    <td>
                                        <!-- Input field for marks, disable if absent -->
                                        <input type="number2" id="marks-{{ $student->id }}-{{ $subject->subject_id }}"
                                            name="marks[{{ $student->id }}][{{ $subject->subject_id }}]"
                                            value="{{ $isAbsent ? '0' : $studentMark->mark ?? '' }}"
                                            class="form-control" min="0" max="100" {{ $isAbsent ? '' : '' }}
                                            data-col="{{ $loop->index }}" required>

                                        <!-- Hidden input to manage presence status -->
                                        <input type="hidden"
                                            name="status[{{ $student->id }}][{{ $subject->subject_id }}]"
                                            value="{{ $isAbsent ? 'absent' : 'present' }}">

                                        <!-- Checkbox to toggle absence -->
                                        <div>
                                            <input type="checkbox" class="absence-checkbox"
                                                data-student-id="{{ $student->id }}"
                                                data-subject-id="{{ $subject->subject_id }}"
                                                onchange="handleAbsenceToggle(this, {{ $student->id }}, {{ $subject->subject_id }})"
                                                {{ $isAbsent ? 'checked' : '' }}> TH
                                        </div>
                                    </td>

                                    @endforeach
                                    <td>
                                        <!-- Textarea for additional student performance summary -->
                                        <textarea name="summary[{{ $student->id }}]" class="form-control" rows="2"
                                            maxlength="500"
                                            placeholder="Describe this student's performance here...">{{ $studentsSummary->firstWhere('student_id', $student->id)?->summary ?? '' }}</textarea>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Action buttons for form submission and cancellation -->
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const numberInputs = document.querySelectorAll('input[type="number2"]');
        let currentIndex = 0;

        // Function to navigate to the previous input
        function navigatePrevious() {
          if (currentIndex > 0) {
            currentIndex--;
            numberInputs[currentIndex].focus();
          }
        }

        // Function to navigate to the next input
        function navigateNext() {
          if (currentIndex < numberInputs.length - 1) {
            currentIndex++;
            numberInputs[currentIndex].focus();
          }
        }

        // Function to navigate up
        function navigateUp() {
          const currentInput = numberInputs[currentIndex];
          const currentRow = currentInput.closest('tr');
          const previousRow = currentRow.previousElementSibling;
          if (previousRow) {
            const previousInput = previousRow.querySelector(`input[data-col="${currentInput.dataset.col}"]`);
            if (previousInput) {
              previousInput.focus();
              currentIndex = Array.from(numberInputs).indexOf(previousInput);
            }
          }
        }

        // Function to navigate down
        function navigateDown() {
          const currentInput = numberInputs[currentIndex];
          const currentRow = currentInput.closest('tr');
          const nextRow = currentRow.nextElementSibling;
          if (nextRow) {
            const nextInput = nextRow.querySelector(`input[data-col="${currentInput.dataset.col}"]`);
            if (nextInput) {
              nextInput.focus();
              currentIndex = Array.from(numberInputs).indexOf(nextInput);
            }
          }
        }

        // Add event listeners to the document for arrow keys
        document.addEventListener('keydown', (event) => {
          if (event.key === 'ArrowLeft') {
            navigatePrevious();
          } else if (event.key === 'ArrowRight') {
            navigateNext();
          } else if (event.key === 'ArrowUp') {
            navigateUp();
          } else if (event.key === 'ArrowDown') {
            navigateDown();
          }
        });

        // Add event listeners to handle focus and blur events
        numberInputs.forEach(input => {
          input.addEventListener('focus', function() {
            if (this.value === '0') {
              this.value = '';
            }
          });

          input.addEventListener('blur', function() {
            if (this.value === '') {
              this.value = '0';
            }
          });
        });

        // Initial focus on the first input
        numberInputs[0].focus();
      });
</script>




<script>
    // Script to handle form interactions and data validation
    document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.absence-checkbox');
    const markInputs = document.querySelectorAll('input[type="number2"]');

    // Initialize the page: Set marks to 0 if null or empty
    markInputs.forEach(markInput => {
        if (markInput.value === '' || markInput.value === null) {
            markInput.value = '0'; // Set default value to 0
        }
    });

    // Function to toggle absence state and adjust mark input
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

    // Apply initial readonly state based on absence status
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

    // Ensure valid input data on form submission
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
    const markInputs = document.querySelectorAll('input[type="number2"]');

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

document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');

    // Handle form submission
    form.addEventListener('submit', function () {
        const textareas = document.querySelectorAll('textarea');

        textareas.forEach(textarea => {
            // Check if the textarea is empty
            if (textarea.value.trim() === '') {
                textarea.value = null; // Set to null if empty
            }
        });
    });
});

</script>
