@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Student</h1>
                </div>
                <div class="col-sm-6"></div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <!-- General form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Adjust Student Details Below</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- Form start -->
                        <form method="post" action="{{ route('studentManagement.edit.post', $student->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <!-- Full Name -->
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input type="text" name="full_name" class="form-control"
                                        value="{{ old('full_name', $student->full_name) }}" required>
                                    @if($errors->has('full_name'))
                                    <span class="text-danger">{{ $errors->first('full_name') }}</span>
                                    @endif
                                </div>

                                <!-- Date of Birth -->
                                <div class="form-group">
                                    <label>Date of Birth</label>
                                    <input type="date" name="date_of_birth" class="form-control"
                                        value="{{ old('date_of_birth', $student->date_of_birth) }}" required>
                                    @if($errors->has('date_of_birth'))
                                    <span class="text-danger">{{ $errors->first('date_of_birth') }}</span>
                                    @endif
                                </div>

                                <!-- Address -->
                                <div class="form-group">
                                    <label>Home Address</label>
                                    <textarea name="address" class="form-control" rows="2" required>{{ old('address', $student->address) }}</textarea>
                                    @if($errors->has('address'))
                                    <span class="text-danger">{{ $errors->first('address') }}</span>
                                    @endif
                                </div>

                                <!-- Gender -->
                                <div class="form-group">
                                    <label>Gender</label>
                                    <select name="gender" class="form-control" required>
                                        <option value="" disabled>Select Gender</option>
                                        <option value="Male" {{ old('gender', $student->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender', $student->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                    @if($errors->has('gender'))
                                    <span class="text-danger">{{ $errors->first('gender') }}</span>
                                    @endif
                                </div>

                                <!-- Enrollment Date -->
                                <div class="form-group">
                                    <label>Enrollment Date</label>
                                    <input type="date" name="enrollment_date" class="form-control"
                                        value="{{ old('enrollment_date', $student->enrollment_date) }}" required>
                                    @if($errors->has('enrollment_date'))
                                    <span class="text-danger">{{ $errors->first('enrollment_date') }}</span>
                                    @endif
                                </div>

                                <!-- IC Number -->
                                <div class="form-group">
                                    <label>IC Number</label>
                                    <input type="text" name="ic_number" class="form-control"
                                        value="{{ old('ic_number', $student->ic_number) }}" required>
                                    @if($errors->has('ic_number'))
                                    <span class="text-danger">{{ $errors->first('ic_number') }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="card-footer">
                                <a href="{{ route('studentManagement.list') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
