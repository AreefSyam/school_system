@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Write Summary for {{ $student->full_name }}</h1>
                </div>
            </div>
        </div>
    </section>

    {{-- breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('teacher.classTeacher.examTypeList', [$selectedAcademicYear->id]) }}">
                    Class Report
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('teacher.classTeacher.examTypeList', [$selectedAcademicYear->id]) }}">
                    {{ $selectedAcademicYear->academic_year_name }}
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('teacher.classTeacher.syllabusList', [$selectedAcademicYear->id, $examTypeId]) }}">
                    {{ $examType->exam_type_name ?? 'Exam Type' }}
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('teacher.classTeacher.classExamReport', [
                $selectedAcademicYear->id,
                $examTypeId,
                $syllabusId,
                $examId
            ]) }}">
                    {{ $syllabus->syllabus_name ?? 'Syllabus' }}
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('teacher.classTeacher.writeSummary', [
                $selectedAcademicYear->id,
                $examTypeId,
                $syllabusId,
                $examId,
                $classId,
                $student->id,
            ]) }}">
                    Write Summary
                </a>
            </li>

        </ol>
    </nav>


    <section class="content">
        <div class="card">
            <div class="card-body">
                <h4>Marks Summary</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Mark</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($marks as $mark)
                        <tr>
                            <td>{{ $mark->subject->subject_name }}</td>
                            <td
                                class="{{ $mark->status === 'absent' ? 'text-danger' : ($mark->mark >= 80 ? 'text-success' : ($mark->mark < 40 ? 'text-danger' : '')) }}">
                                {{ $mark->status === 'absent' ? 'TH' : $mark->mark }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <h4 class="mt-4">Write Summary</h4>
                <form method="POST"
                    action="{{ route('teacher.classTeacher.writeSummary.post', [$selectedAcademicYear, $examTypeId, $syllabusId, $examId, $classId, $student->id]) }}">
                    @csrf
                    <div class="form-group">
                        <textarea name="summary" class="form-control" rows="6"
                            placeholder="Write the performance summary here...">{{ $studentSummary->summary ?? '' }}</textarea>
                        @if ($errors->has('summary'))
                        <span class="text-danger">{{ $errors->first('summary') }}</span>
                        @endif
                    </div>
                    <div class="form-group d-flex justify-content-end">
                        <a href="{{ route('teacher.classTeacher.classExamReport', [$selectedAcademicYear, $examTypeId, $syllabusId,  $examId]) }}"
                            class="btn btn-secondary me-2 mt-3">
                            Back
                        </a>
                        <button type="submit" class="btn btn-success me-2 mt-3">Save Summary</button>
                    </div>

                </form>
            </div>
        </div>
    </section>
</div>

<script>
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
@endsection
