<aside class="main-sidebar sidebar-dark-olive bg-navy elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <img src="{{url('dist/img/AbimPahang.png')}}" alt="logo" class="brand-image img-circle elevation-3"
            style="background-color: white; opacity: 100">
        <span class="brand-text font-weight-bold">KAFA As-Saadah</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->

                {{-- Admin Modules --}}
                @if (Auth::user()->role == 'admin')
                <li class="nav-header">MENU</li>
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link @if (Request::segment(2) == 'dashboard') active @endif">
                        <i class="nav-icon far bi bi-speedometer"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href=" {{ route('academicYear.list')  }}"
                        class="nav-link @if (Request::segment(2) == 'academicYearManagement') active @endif">
                        <i class="nav-icon far bi bi-calendar-fill active"></i>
                        <p>
                            Academic Year
                        </p>
                    </a>
                </li>

                <!-- Examination Menu -->
                <li class="nav-item menu-is-opening menu-open">
                    <a href="" class="nav-link @if (Request::segment(2) == 'examManagement') active @endif">
                        <i class="nav-icon far bi bi-journal-bookmark active"></i>
                        <p>
                            Examination
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: block;">
                        <!-- Admin as a sub-item -->
                        <li class="nav-item">
                            <a href="{{ route('exams.yearList') }}"
                                class="nav-link @if (Request::segment(3) == 'exams') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Report Exam Data</p>
                            </a>
                        </li>
                        <!-- Teacher sub-item -->
                        <li class="nav-item">
                            <a href="{{ route('examManagement.list') }}"
                                class="nav-link @if (Request::segment(3) == 'manages') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Manage Exam</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- User Management Menu -->
                <li class="nav-item menu-is-opening menu-open">
                    <a href="" class="nav-link @if (Request::segment(2) == 'userManagement') active @endif">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            User
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: block;">
                        <!-- Admin as a sub-item -->
                        <li class="nav-item">
                            <a href="{{ route('admin.list') }}"
                                class="nav-link @if (Request::segment(3) == 'admin') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Manage Admin</p>
                            </a>
                        </li>
                        <!-- Teacher sub-item -->
                        <li class="nav-item">
                            <a href="{{ route('teacher.list') }}"
                                class="nav-link @if (Request::segment(3) == 'teacher') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Manage Teacher</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">

                    {{-- subjectManagement --}}
                    <a href="{{ route('subjectManagement.list') }}"
                        class="nav-link @if (Request::segment(2) == 'subjectManagement') active @endif">
                        <i class="nav-icon far bi bi-book active"></i>
                        <p>
                            Subject
                        </p>
                    </a>
                </li>

                {{-- classManagement --}}
                <li class="nav-item">
                    <a href="{{ route('class.list') }}"
                        class="nav-link @if (Request::segment(2) == 'classManagement') active @endif">
                        <i class="nav-icon far fa-copy active"></i>
                        <p>
                            Class
                        </p>
                    </a>
                </li>

                {{-- studentManagement --}}
                <li class="nav-item">
                    <a href="{{ route('studentManagement.list') }}"
                        class="nav-link @if (Request::segment(2) == 'studentManagement') active @endif">
                        <i class="nav-icon far bi bi-people active"></i>
                        <p>
                            Student
                        </p>
                    </a>
                </li>

                {{-- analyticManagement --}}
                <li class="nav-item menu-is-opening menu-open">
                    <a href="" class="nav-link @if (Request::segment(2) == 'analyticManagement') active @endif">
                        <i class="nav-icon far bi bi-bar-chart-fill active"></i>
                        <p>
                            Graph Analytic
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: block;">
                        <li class="nav-item">
                            <a href="{{  route('analytic.subjectPerformance') }}"
                                class="nav-link @if (Request::segment(3) == 'bySubject') active @endif">
                                <i class="far fa-circle nav-icon active"></i>
                                <p>Performance Subject</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{  route('analytic.classPerformance') }}"
                                class="nav-link @if (Request::segment(3) == 'byClass') active @endif">
                                <i class="far fa-circle nav-icon active"></i>
                                <p>Performance Class</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('analytic.individualPerformance') }}"
                                class="nav-link @if (Request::segment(3) == 'byIndividual') active @endif">
                                <i class="far fa-circle nav-icon active"></i>
                                <p>Performance Individual</p>
                            </a>
                        </li>
                    </ul>
                </li>

                @endif





                {{-- Teacher Modules --}}
                @if (Auth::user()->role == 'teacher')
                <li class="nav-header">MENU</li>
                <li class="nav-item">
                    <a href="{{ url('admin/dashboard') }}" class="nav-link active">
                        <i class="nav-icon far bi bi-speedometer"></i>
                        <p>
                            Dashboard {{ Request::segment(1) }}
                        </p>
                    </a>
                </li>

                <!-- Logout Button as Sidebar Menu Item -->
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST" id="logout-form">
                        @csrf
                        <button type="submit" class="nav-link btn btn-link" style="color: white; text-align: left;">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Logout</p>
                        </button>
                    </form>
                </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
