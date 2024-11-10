@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Edit Marks for {{ $examType->exam_type_name }} - {{ $syllabus->syllabus_name }} ({{
            $year->academic_year_name }})
            : {{ $class->name }}</h1>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-body">
                <form
                    action="{{ route('exam.marks.edit.updateAll', [$year->id, $examType->id, $syllabus->id, $class->id]) }}"
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
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                            <tr>
                                <td>{{ $student->full_name }}</td>
                                @foreach($subjects as $subject)
                                @php
                                $studentMark = $marks->get($student->id)?->firstWhere('subject_id', $subject->subject_id);
                                @endphp
                                <td>
                                    <input type="number" name="marks[{{ $student->id }}][{{ $subject->subject_id }}]"
                                        value="{{ $studentMark->mark ?? '' }}" class="form-control" />
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Buttons with spacing and alignment -->
                    <div class="d-flex justify-content-end mt-3">
                        <a href="{{ route('exams.marks', [$year->id, $examType->id, $syllabus->id, $class->id]) }}"
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
