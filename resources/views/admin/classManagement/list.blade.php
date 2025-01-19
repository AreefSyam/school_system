@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header bg-dark">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Class Management</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a class="btn btn-primary" href="{{ route('class.add') }}">Add New Class</a>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    {{-- breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Home </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('class.list') }}"> Class List </a>
            </li>
        </ol>
    </nav>

    <!-- Search Form -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Search Class</h3>
                </div>
                <form method="get" action="">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label>Class Name</label>
                                <input type="text" name="classname" class="form-control" placeholder="Enter name"
                                    value="{{ Request::get('classname') }}">
                            </div>

                            <div class="form-group col-md-3">
                                <label>Grade Level</label>
                                <select class="form-control" name="grade_level_id">
                                    <option value="" disabled selected>-- Select Grade Level --</option>
                                    @foreach($gradeLevels as $grade)
                                    <option value="{{ $grade->id }}" {{ Request::get('grade_level_id')==$grade->id ?
                                        'selected' : '' }}>
                                        {{ $grade->grade_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Academic Year</label>
                                <select class="form-control" name="academic_year_id">
                                    <option value="" disabled selected>-- Select Year --</option>
                                    @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}" {{ Request::get('academic_year_id')==$year->id ?
                                        'selected' : '' }}>
                                        {{ $year->academic_year_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <button class="btn btn-primary" type="submit" style="margin-top: 30px">Search</button>
                                <a href="{{ route('class.list') }}" class="btn btn-success"
                                    style="margin-top: 30px">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Class List -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Class List</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Grade</th>
                                <th>Year</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Updated Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($get_record as $value)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $value->name }}</td>
                                <td>{{ $value->grade_name }}</td>
                                <td>{{ $value->academic_year_name }}</td> <!-- Displaying Academic Year -->
                                <td>
                                    @if ($value->status == 0)
                                    <span class="text-success">Active</span> <!-- Green text for Active -->
                                    @else
                                    <span class="text-danger">Inactive</span> <!-- Red text for Inactive -->
                                    @endif
                                </td>
                                <td>{{ $value->created_by_name }}</td>
                                <td>{{ date('d-m-Y H:i A', strtotime($value->updated_at)) }}</td>
                                <td>
                                    <a href="{{ route('class.edit', $value->id) }}" class="btn btn-primary">Edit</a>
                                    <a href="{{ route('class.assignStudents', $value->id) }}"
                                        class="btn btn-warning">Assign Students</a>
                                    <a href="{{ route('class.assignTeacher', $value->id) }}" class="btn btn-info">Assign
                                        Teacher</a>
                                    <a href="{{ route('class.delete', $value->id) }}" class="btn btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this class? This action cannot be undone.');">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="pagination-wrapper" style="padding: 10px; float: right;">
                        {!! $get_record->appends(request()->except('page'))->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
