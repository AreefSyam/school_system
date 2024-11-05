{{--

@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>All marks for Exam [{{ $examType->exam_type_name }} - {{ $syllabus->syllabus_name }}
                        - {{ $year->academic_year_name }}] : {{ $class->name }}</h1>
                </div>
            </div>
            <a>Data Exam / {{ $year->academic_year_name }} / {{ $examType->exam_type_name }} / {{
                $syllabus->syllabus_name }} / {{ $class->name }}</a>
        </div>
    </section>

    <!-- Marks Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Student Marks for {{ $class->name }}</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="marksTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Student Name</th>
                                @foreach($subjects as $subject)
                                <th>{{ $subject->subject_name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $index => $student)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $student->full_name }}</td>
                                @foreach($subjects as $subject)
                                <td>
                                    @php
                                    // Get the marks for this subject for the current student
                                    $studentMark = $marks->where('student_id', $student->id)->where('subject_id',
                                    $subject->id)->first();
                                    @endphp
                                    {{ $studentMark ? $studentMark->marks : 'N/A' }}
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>No.</th>
                                <th>Student Name</th>
                                @foreach($subjects as $subject)
                                <th>{{ $subject->subject_name }}</th>
                                @endforeach
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </section>

</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#marksTable').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true
        });
    });
</script>
@endpush --}}


@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>All marks for Exam [{{ $examType->exam_type_name }} - {{ $syllabus->syllabus_name }}
                        - {{ $year->academic_year_name }}] : {{ $class->name }}</h1>
                </div>
            </div>
            <a>Data Exam / {{ $year->academic_year_name }} / {{ $examType->exam_type_name }} / {{
                $syllabus->syllabus_name }} / {{ $class->name }}</a>
        </div>
    </section>

    <!-- Marks Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Student Marks for {{ $class->name }}</h3>
                    <!-- Export Buttons -->
                    <div class="float-right">
                        {{-- <a href="{{ route('exports.marks.csv') }}" class="btn btn-secondary">Export CSV</a>
                        <a href="{{ route('exports.marks.excel') }}" class="btn btn-secondary">Export Excel</a> --}}
                        <a href="{{ route('exports.pdf.markPDF') }}" class="btn btn-secondary">Export PDF</a>
                    </div>

                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="marksTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Student Name</th>
                                @foreach($subjects as $subject)
                                <th>{{ $subject->subject_name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $index => $student)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $student->full_name }}</td>
                                @foreach($subjects as $subject)
                                <td>
                                    @php
                                    // Get the marks for this subject for the current student
                                    $studentMark = $marks->where('student_id', $student->id)->where('subject_id',
                                    $subject->id)->first();
                                    @endphp
                                    {{ $studentMark ? $studentMark->marks : 'N/A' }}
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#marksTable').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "buttons": [
                { extend: 'copy', className: 'btn btn-secondary' },
                { extend: 'csv', className: 'btn btn-secondary' },
                { extend: 'excel', className: 'btn btn-secondary' },
                { extend: 'pdf', className: 'btn btn-secondary' },
                { extend: 'print', className: 'btn btn-secondary' },
                { extend: 'colvis', className: 'btn btn-secondary' }
            ],
            "dom": "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
            "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
        }).buttons().container().appendTo('#marksTable_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush
