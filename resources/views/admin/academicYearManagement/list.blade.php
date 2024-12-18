@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header bg-dark">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Academic Year Management</h1>
                </div>
                <div class="col-sm-6" style="text-align: right;">
                    <a class="btn btn-primary" href="{{ route('academicYear.add') }}">Add New Academic Year</a>
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
                <a href="{{ route('academicYear.list') }}">Academic Year List </a>
            </li>
        </ol>
    </nav>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Search Academic Year</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form method="get" action="">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label>Academic Year Name</label>
                                        <input type="text" name="academic_year_name" class="form-control"
                                            placeholder="Enter Academic Year"
                                            value="{{ Request::get('academic_year_name') }}">
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>Status</label>
                                        <select class="form-control" name="status">
                                            <option value="" disabled selected>-- Select Status --</option>
                                            <option value="0" {{ Request::get('status')==='0' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="1" {{ Request::get('status')==='1' ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <button class="btn btn-primary" type="submit"
                                            style="margin-top: 30px">Search</button>
                                        <a href="{{ route('academicYear.list') }}" class="btn btn-success"
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
                            <h3 class="card-title">Academic Year List</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Year</th>
                                        <th>Status</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Created By</th>
                                        <th>Created Date</th>
                                        <th>Updated Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($get_record as $value)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $value->academic_year_name }}</td>
                                        <td>
                                            @if ($value->status == 0)
                                            <span class="text-success">Active</span> <!-- Green text -->
                                            @else
                                            <span class="text-danger">Inactive</span> <!-- Red text -->
                                            @endif
                                        </td>
                                        <td>{{ date('d-m-Y', strtotime($value->start_date)) }}</td>
                                        <td>{{ date('d-m-Y', strtotime($value->end_date)) }}</td>
                                        <td>{{ $value->created_by_name }}</td>
                                        <td>{{ date('d-m-Y H:i A', strtotime($value->created_at)) }}</td>
                                        <td>{{ date('d-m-Y H:i A', strtotime($value->updated_at)) }}</td>
                                        <td>
                                            <a href="{{ route('academicYear.edit', $value->id) }}"
                                                class="btn btn-primary">Edit</a>
                                            <a href="{{ route('academicYear.delete', $value->id) }}"
                                                class="btn btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this academic year? This action cannot be undone.');">
                                                Delete
                                            </a>
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
            </div>
        </div>
    </section>
</div>
@endsection
