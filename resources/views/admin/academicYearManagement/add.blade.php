@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add New Academic Year</h1>
                </div>
                <div class="col-sm-6">
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Insert The Details Below</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form method="post" action="{{ route('academicYear.add.post') }}">
                            @csrf
                            <div class="card-body">
                                <!-- Academic Year Name -->
                                <div class="form-group">
                                    <label>Academic Year Name</label>
                                    <input type="text" name="academic_year_name" class="form-control"
                                        placeholder="Enter Academic Year Name (e.g., 2023-2024)"
                                        value="{{ old('academic_year_name') }}" required>
                                    @if($errors->has('academic_year_name'))
                                        <span class="text-danger">{{ $errors->first('academic_year_name') }}</span>
                                    @endif
                                </div>

                                <!-- Start Date -->
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <input type="date" name="start_date" class="form-control"
                                        value="{{ old('start_date') }}" required>
                                    @if($errors->has('start_date'))
                                        <span class="text-danger">{{ $errors->first('start_date') }}</span>
                                    @endif
                                </div>

                                <!-- End Date -->
                                <div class="form-group">
                                    <label>End Date</label>
                                    <input type="date" name="end_date" class="form-control"
                                        value="{{ old('end_date') }}" required>
                                    @if($errors->has('end_date'))
                                        <span class="text-danger">{{ $errors->first('end_date') }}</span>
                                    @endif
                                </div>

                                <!-- Status -->
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control" name="status" required>
                                        <option value="" disabled selected>-- Select Status --</option>
                                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Active</option>
                                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @if($errors->has('status'))
                                        <span class="text-danger">{{ $errors->first('status') }}</span>
                                    @endif
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Register New Academic Year</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
