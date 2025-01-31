@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header bg-dark">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Admin Management</h1>
                </div>
                <div class="col-sm-6" style="text-align: right;">
                    <a class="btn btn-primary" href="{{ route('admin.add') }}">Add new Admin</a>
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
                <a href="{{ route('admin.list') }}">Admin List </a>
            </li>
        </ol>
    </nav>


    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <!-- card-header -->
                        <div class="card-header">
                            <h3 class="card-title">Search Admin</h3>
                        </div>
                        <!-- form start -->
                        <form method="get" action="">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label>Name</label>
                                        <input type="text" name="name" class="form-control" placeholder="Enter name"
                                            value="{{ Request::get('name') }}">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Email</label>
                                        <input type="text" name="email" class="form-control" placeholder="Enter email"
                                            value="{{ Request::get('email') }}">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Date</label>
                                        <input type="date" name="date" class="form-control" placeholder="Enter date"
                                            value="{{ Request::get('date') }}">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <button class="btn btn-primary" type="submit"
                                            style="margin-top: 30px">Search</button>
                                        <a href="{{ route('admin.list') }}" class="btn btn-success"
                                            style="margin-top: 30px">Reset</a>
                                    </div>
                                </div>
                            </div>
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
                            <h3 class="card-title">Admin List (Total : {{ $get_record->total() }})</h3>
                        </div>
                        <div class="card-body p-0">
                            <div style="overflow-x: auto;">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Created Date</th>
                                            <th>Updated Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($get_record as $value)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $value->name }}</td>
                                            <td>{{ $value->email }}</td>
                                            <td>{{ $value->role }}</td>
                                            <td>{{ date('d-m-Y H:i A', strtotime($value->created_at)) }}</td>
                                            <td>{{ date('d-m-Y H:i A', strtotime($value->updated_at)) }}</td>
                                            <td><a href="{{ route('admin.edit', $value->id) }}"
                                                    class="btn btn-primary">Edit</a>
                                                <!-- Add confirmation on delete button -->
                                                <a href="{{ route('admin.delete', $value->id) }}" class="btn btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this admin? This action cannot be undone.');">
                                                    Delete
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div style="padding: 10px; float: right;">
                                {!! $get_record->appends(Illuminate\Support\Facades\Request::except('page'))->links()
                                !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
