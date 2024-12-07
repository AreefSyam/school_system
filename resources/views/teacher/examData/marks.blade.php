@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Manage Marks for {{ $subjectName }}</h1>
                </div>
            </div>
            <p>Exam Data / {{ $selectedAcademicYear->academic_year_name ?? 'N/A' }} / {{ $examTypeName ?? 'N/A' }} / {{
                $syllabusName ?? 'N/A' }} / {{ $className ?? 'N/A' }} / {{ $subjectName ?? 'N/A' }}</p>
        </div>
    </section>

    <!-- Marks Form -->
    <section class="content">
        <div class="container-fluid">
            @if(!$marks->isEmpty())
            <form
                action="{{ route('teacher.exams.marks.store', ['yearId' => $yearId, 'examTypeId' => $examTypeId, 'syllabusId' => $syllabusId, 'classId' => $classId, 'subjectId' => $subjectId]) }}"
                method="POST">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Students and Marks</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Marks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                <tr>
                                    <td>{{ $student->full_name }}</td>
                                    @php
                                    // Find the mark for this student
                                    $mark = $marks->firstWhere('student_id', $student->id);
                                    @endphp
                                    <td>
                                        <input type="number" name="marks[{{ $loop->index }}][score]"
                                            class="form-control" value="{{ $mark->mark ?? '' }}" min="0" max="100">
                                        <input type="hidden" name="marks[{{ $loop->index }}][student_id]"
                                            value="{{ $student->id }}">
                                        <input type="hidden" name="marks[{{ $loop->index }}][subject_id]"
                                            value="{{ $subjectId }}">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Save Marks</button>
                    </div>
                </div>
            </form>
            @else
            <p class="text-center text-red">No marks found for the selected academic year, exam type, syllabus, class, or
                subject.
            </p>
            @endif
        </div>
    </section>
</div>
@endsection
