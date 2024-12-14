@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Summary Metrics Section -->
            <div class="row mb-4">
                <div class="col-12 mb-1">
                    <h4 class="font-weight-bold">Summary Metrics</h4>
                </div>
                <!-- Total Students -->
                <div class="col-md-6 col-lg-4">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ $totalStudents }}</h3>
                            <p>Total Students</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <a href="{{ route('studentManagement.list') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <!-- Total Teachers -->
                <div class="col-md-6 col-lg-4">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $totalTeachers }}</h3>
                            <p>Total Teachers</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <a href="{{ route('teacher.list') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Total Classes -->
                <div class="col-md-6 col-lg-4">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $totalClasses }}</h3>
                            <p>Total Classes</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-school"></i>
                        </div>
                        <a href="{{ route('class.list') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Performance Metrics Section -->
            <div class="row">
                <div class="col-12 mb-1">
                    <h4 class="font-weight-bold">Performance Metrics</h4>
                </div>

                <!-- Class Performance -->
                <div class="col-md-6 col-lg-4">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>Class</h3>
                            <p>Performance</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('analytic.classPerformance') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Individual Performance -->
                <div class="col-md-6 col-lg-4">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>Individual</h3>
                            <p>Performance</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('analytic.individualPerformance') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Subject Performance -->
                <div class="col-md-6 col-lg-4">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>Subject</h3>
                            <p>Performance</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('analytic.subjectPerformance') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

@endsection
