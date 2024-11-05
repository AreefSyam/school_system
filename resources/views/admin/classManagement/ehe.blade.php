
    <!-- Assign Students Form with Search and Pagination -->
    {{-- <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Select Students to Assign</h3>
                </div>
                <div class="alert alert-info">
                    Note: Assigning a student to this class will automatically remove them from any other class they are
                    currently assigned to.
                </div>

                <!-- Search Form -->
                <form method="get" action="{{ route('class.assignStudents', $class->id) }}" class="mb-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="student_name">Search Student Name</label>
                                <input type="text" name="student_name" class="form-control" id="student_name"
                                    placeholder="Enter student name" value="{{ Request::get('student_name') }}">
                            </div>
                            <div class="form-group col-md-4 align-self-end">
                                <button class="btn btn-primary" type="submit">Search</button>
                                <a href="{{ route('class.assignStudents', $class->id) }}"
                                    class="btn btn-success">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Student Selection Table -->
                <form method="post" action="{{ route('class.assignStudents.post', $class->id) }}">
                    @csrf
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th><input type="checkbox" id="select-all"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($students as $student)
                                    <tr>
                                        <td>{{ $student->full_name }}</td>
                                        <td>
                                            <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" {{
                                                $class->students->contains($student->id) ? 'checked' : '' }}>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($errors->has('student_ids'))
                        <span class="text-danger">{{ $errors->first('student_ids') }}</span>
                        @endif
                    </div>

                    <!-- Pagination -->
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        {{ $students->appends(request()->query())->links() }}
                        <button type="submit" class="btn btn-primary">Assign Students</button>
                    </div>
                </form>
            </div>
        </div>
    </section> --}}