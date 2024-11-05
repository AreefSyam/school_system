<aside class="main-sidebar sidebar-dark-olive bg-navy elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <img src="{{url('dist/img/AbimPahang.png')}}" alt="logo" class="brand-image img-circle elevation-3"
            style="background-color: white; opacity: 100">
        <span class="brand-text font-weight-bold">KAFA As-Saadah</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{url('dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info font-weight-bold">
                <a href="" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

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



                {{-- <li class="nav-item">
                    <a href="{{ Route('examManagement.list') }}"
                        class="nav-link @if (Request::segment(2) == 'examManagement') active @endif">
                        <i class="nav-icon far bi bi-layout-text-sidebar active"></i>
                        <p>
                            Examination
                        </p>
                    </a>
                </li> --}}

                <!-- User menu with sub-items -->
                <li class="nav-item menu-is-opening menu-open">
                    <a href="" class="nav-link @if (Request::segment(2) == 'examManagement') active @endif">
                        <i class="nav-icon fas fa-users"></i>
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
                                <p>Data Exam</p>
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






                <!-- User menu with sub-items -->
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
                    <a href="{{ route('subjectManagement.list') }}"
                        class="nav-link @if (Request::segment(2) == 'subjectManagement') active @endif">
                        <i class="nav-icon far bi bi-book active"></i>
                        <p>
                            Subject
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('class.list') }}"
                        class="nav-link @if (Request::segment(2) == 'classManagement') active @endif">
                        <i class="nav-icon far fa-copy active"></i>
                        <p>
                            Class
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('studentManagement.list') }}"
                        class="nav-link @if (Request::segment(2) == 'studentManagement') active @endif">
                        <i class="nav-icon far bi bi-people active"></i>
                        <p>
                            Student
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="" class="nav-link @if (Request::segment(2) == 'reportManagement') active @endif">
                        <i class="nav-icon far bi bi-journal-bookmark active"></i>
                        <p>
                            Report
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="" class="nav-link @if (Request::segment(2) == 'performanceManagement') active @endif">
                        <i class="nav-icon far bi bi-bar-chart-fill active"></i>
                        <p>
                            Performance
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
