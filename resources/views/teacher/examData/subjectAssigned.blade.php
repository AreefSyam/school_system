{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <h2>Subject Assignment</h2>

    <!-- Academic Year Dropdown -->
    <div class="form-group">
        <label for="academicYearDropdown">Select Academic Year: {{ $year->academic_year_name }}
        </label>
        <select id="academicYearDropdown" class="form-control">
            @foreach($academicYears as $year)
            <option value="{{ $year->id }}">
                {{ $year->academic_year_name }}
            </option>
            @endforeach
        </select>
    </div>

    <p>Assign subjects based on the selected academic year.</p>
</div>

@endsection --}}

{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <h2>Subject Assignment</h2>

    <!-- Display the current academic year -->
    <div class="form-group">
        <label for="academicYearDropdown">Select Academic Year: {{ $currentAcademicYear->academic_year_name }}</label>
    </div>

    <!-- Academic Year Dropdown -->
    <div class="form-group">
        <label for="academicYearDropdown">Choose an Academic Year</label>
        <select id="academicYearDropdown" class="form-control">
            @foreach($academicYears as $year)
                <option value="{{ $year->id }}" {{ $year->id == $currentAcademicYear->id ? 'selected' : '' }}>
                    {{ $year->academic_year_name }}
                </option>
            @endforeach
        </select>
    </div>

    <p>Assign subjects based on the selected academic year.</p>

    <!-- Example of marks data (you can display the data from $marks here) -->
    <div>
        <h4>Marks for the Selected Year</h4>
        <ul>
            @foreach($marks as $mark)
                <li>{{ $mark->subject_name }}: {{ $mark->marks }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endsection --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Subject Assignment</h2>

    <!-- Display the Current Academic Year -->
    <div class="form-group">
        <label for="academicYearDropdown">Current Academic Year: {{ $currentAcademicYear->academic_year_name }}</label>
    </div>
</div>
@endsection


