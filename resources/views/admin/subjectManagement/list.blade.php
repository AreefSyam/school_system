@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header bg-dark">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Subject Management</h1>
                </div>
                <div class="col-sm-6" style="text-align: right;">
                    <a class="btn btn-primary" href="{{ route('subjectManagement.add') }}">Add New Subject</a>
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
                <a href="{{ route('subjectManagement.list') }}">Subject List </a>
            </li>
        </ol>
    </nav>

    <!-- Search Section -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Search Subject</h3>
                        </div>
                        <!-- /.card-header -->
                        <form method="get" action="">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label>Subject Name</label>
                                        <input type="text" name="subject_name" class="form-control"
                                            placeholder="Enter subject name" value="{{ Request::get('subject_name') }}">
                                    </div>
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
                                        <label>Grade Level</label>
                                        <select class="form-control" name="grade_level_id">
                                            <option value="" disabled selected>-- Select Grade Level --</option>
                                            @foreach($gradeLevels as $grade)
                                            <option value="{{ $grade->id }}" {{ Request::get('grade_level_id')==$grade->
                                                id ? 'selected' : '' }}>
                                                {{ $grade->grade_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Academic Year</label>
                                        <select class="form-control" name="academic_year_id">
                                            <option value="" disabled selected>-- Select Academic Year --</option>
                                            @foreach($academic_years as $year)
                                            <option value="{{ $year->id }}" {{ Request::get('academic_year_id')==$year->
                                                id ? 'selected' : '' }}>
                                                {{ $year->academic_year_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <button class="btn btn-primary" type="submit"
                                            style="margin-top: 30px">Search</button>
                                        <a href="{{ route('subjectManagement.list') }}" class="btn btn-success"
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
                    <div class="card">
                        <!-- card-header -->
                        <div class="card-header">
                            <h3 class="card-title">Subject List</h3>
                        </div>
                        <div class="card-body p-0">
                            <div style="overflow-x: auto;">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Subject Name</th>
                                            <th>Syllabus</th>
                                            <th>Grade Levels</th>
                                            <th>Academic Year</th>
                                            <th>Created By</th>
                                            <th>Updated Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($get_record as $index => $value)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $value->subject_name }}</td>
                                            <td>{{ $value->syllabus->syllabus_name ?? 'N/A' }}</td>
                                            <td>
                                                @foreach($value->gradeLevels->where('pivot.active', 1) as $grade)
                                                {{ $grade->grade_order }}{{ !$loop->last ? ', ' : '' }}
                                                @endforeach
                                            </td>
                                            <td>{{ $value->academicYear->academic_year_name ?? 'N/A' }}</td>
                                            <!-- Display Academic Year -->
                                            <td>{{ $value->creator->name ?? 'N/A' }}</td>
                                            <td>{{ date('d-m-Y H:i A', strtotime($value->updated_at)) }}</td>
                                            <td>
                                                <a href="{{ route('subjectManagement.edit', $value->id) }}"
                                                    class="btn btn-primary">Edit</a>
                                                <a href="{{ route('subjectManagement.delete', $value->id) }}"
                                                    class="btn btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this subject? This action cannot be undone.');">
                                                    Delete
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div style="padding: 10px; float: right;">
                                {!! $get_record->appends(Request::except('page'))->links() !!}
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
@endsection
