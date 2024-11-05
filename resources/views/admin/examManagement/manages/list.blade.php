@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Examination Management</h1>
                </div>
                <div class="col-sm-6" style="text-align: right;">
                    <a class="btn btn-primary" href="{{ route('examManagement.add') }}">Add New Examination</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Search Examination</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form method="get" action="">
                            <div class="card-body">
                                <div class="row">
                                    <!-- Examination Name -->
                                    <div class="form-group col-md-3">
                                        <label>Examination Name</label>
                                        <input type="text" name="exam_name" class="form-control"
                                            placeholder="Enter examination name"
                                            value="{{ Request::get('exam_name') }}">
                                    </div>

                                    <!-- Exam Type -->
                                    <div class="form-group col-md-3">
                                        <label>Exam Type</label>
                                        <select class="form-control" name="exam_type_id">
                                            <option value="" disabled selected>-- Select Exam Type --</option>
                                            @foreach($exam_types as $exam_type)
                                            <option value="{{ $exam_type->id }}" {{
                                                Request::get('exam_type_id')==$exam_type->id ? 'selected' : '' }}>
                                                {{ $exam_type->exam_type_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Syllabus -->
                                    <div class="form-group col-md-3">
                                        <label>Syllabus</label>
                                        <select class="form-control" name="syllabus_id">
                                            <option value="" disabled selected>-- Select Syllabus --</option>
                                            @foreach($syllabuses as $syllabus)
                                            <option value="{{ $syllabus->id }}" {{
                                                Request::get('syllabus_id')==$syllabus->id ? 'selected' : '' }}>
                                                {{ $syllabus->syllabus_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <button class="btn btn-primary" type="submit"
                                            style="margin-top: 30px">Search</button>
                                        <a href="{{ route('examManagement.list') }}" class="btn btn-success"
                                            style="margin-top: 30px">Reset</a>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @include('messages.alert')

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Examination List</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Examination Name</th>
                                        <th>Exam Type</th>
                                        <th>Syllabus</th>
                                        <th>Academic Year</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($get_record as $value)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $value->exam_name }}</td>
                                        <td>{{ $value->examType->exam_type_name }}</td>
                                        <td>{{ $value->syllabus->syllabus_name }}</td>
                                        <td>{{ $value->academicYear->academic_year_name }}</td>
                                        <td>{{ date('d-m-Y', strtotime($value->start_date)) }}</td>
                                        <td>{{ date('d-m-Y', strtotime($value->end_date)) }}</td>
                                        <td>
                                            <a href="{{ route('examManagement.edit', $value->id) }}"
                                                class="btn btn-primary">Edit</a>
                                            <a href="{{ route('examManagement.delete', $value->id) }}"
                                                class="btn btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this examination? This action cannot be undone.');">Delete</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div style="padding: 10px; float: right;">
                                {!! $get_record->appends(Illuminate\Support\Facades\Request::except('page'))->links()
                                !!}
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>
@endsection
