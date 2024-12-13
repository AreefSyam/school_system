<aside class="main-sidebar sidebar-dark-olive bg-navy elevation-4">

    <!-- Brand Logo -->
    @if(auth()->user()->hasRole('admin'))
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <img src="{{ url('dist/img/AbimPahang.png') }}" alt="Logo" class="brand-image img-circle elevation-3"
            style="background-color: white; opacity: 100">
        <span class="brand-text font-weight-bold">KAFA As-Saadah</span>
    </a>
    @endif
    @if(auth()->user()->hasRole('teacher'))
    <a href="{{ route('teacher.dashboard') }}" class="brand-link">
        <img src="{{ url('dist/img/AbimPahang.png') }}" alt="Logo" class="brand-image img-circle elevation-3"
            style="background-color: white; opacity: 100">
        <span class="brand-text font-weight-bold">KAFA As-Saadah</span>
    </a>
    @endif

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                {{-- Admin Modules --}}
                @if (Auth::user()->role == 'admin')
                <li class="nav-header">MENU</li>
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link @if (Request::segment(2) == 'dashboard') active @endif">
                        <i class="nav-icon far bi bi-speedometer"></i>
                        <p>
                            Home {{ Request::segment(1) }}
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
                <li class="nav-item @if (Request::segment(2) == 'examManagement') menu-is-opening menu-open @endif">
                    <a href="#" class="nav-link @if (Request::segment(2) == 'examManagement') active @endif">
                        <i class="nav-icon far bi bi-journal-bookmark"></i>
                        <p>
                            Examination
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" @if (Request::segment(2) !='examManagement' ) style="display: none;"
                        @endif>
                        <!-- Report Exam Data -->
                        <li class="nav-item">
                            <a href="{{ route('exams.yearList') }}"
                                class="nav-link @if (Request::segment(3) == 'exams') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Report Exam Data</p>
                            </a>
                        </li>
                        <!-- Manage Exam -->
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
                <li class="nav-item @if (Request::segment(2) == 'userManagement') menu-is-opening menu-open @endif">
                    <a href="#" class="nav-link @if (Request::segment(2) == 'userManagement') active @endif">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            User
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" @if (Request::segment(2) !='userManagement' ) style="display: none;"
                        @endif>
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
                {{-- Subject Management --}}
                <li class="nav-item">
                    <a href="{{ route('subjectManagement.list') }}"
                        class="nav-link @if (Request::segment(2) == 'subjectManagement') active @endif">
                        <i class="nav-icon far bi bi-book active"></i>
                        <p>
                            Subject
                        </p>
                    </a>
                </li>
                {{-- Class Management --}}
                <li class="nav-item">
                    <a href="{{ route('class.list') }}"
                        class="nav-link @if (Request::segment(2) == 'classManagement') active @endif">
                        <i class="nav-icon far fa-copy active"></i>
                        <p>
                            Class
                        </p>
                    </a>
                </li>
                {{-- Student Management --}}
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
                <li class="nav-item @if (Request::segment(2) == 'analyticManagement') menu-is-opening menu-open @endif">
                    <a href="#" class="nav-link @if (Request::segment(2) == 'analyticManagement') active @endif">
                        <i class="nav-icon far bi bi-bar-chart-fill"></i>
                        <p>
                            Graph Analytic
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" @if (Request::segment(2) !='analyticManagement' )
                        style="display: none;" @endif>
                        <li class="nav-item">
                            <a href="{{ route('analytic.subjectPerformance') }}"
                                class="nav-link @if (Request::segment(3) == 'bySubject') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Performance Subject</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('analytic.classPerformance') }}"
                                class="nav-link @if (Request::segment(3) == 'byClass') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Performance Class</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('analytic.individualPerformance') }}"
                                class="nav-link @if (Request::segment(3) == 'byIndividual') active @endif">
                                <i class="far fa-circle nav-icon"></i>
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
                    <a href="{{ route('teacher.dashboard') }}"
                        class="nav-link @if (Request::segment(2) == 'dashboard') active @endif">
                        <i class="nav-icon far bi bi-speedometer"></i>
                        <p>
                            Home {{ Request::segment(1) }}
                        </p>
                    </a>
                </li>

                {{-- Exam Data --}}
                <li class="nav-item">
                    <a href="{{ route('teacher.exams.examTypeList', ['yearId' => $currentAcademicYear->id ?? '']) }}"
                        class="nav-link @if (Request::segment(2) == 'examData') active @endif">
                        <i class="nav-icon far bi bi-clipboard-data active"></i>
                        <p>
                            Exam Data
                        </p>
                    </a>
                </li>

                {{-- Teacher Class --}}
                <li class="nav-item">
                    <a href="{{ route('teacher.classTeacher.examTypeList', ['yearId' => $currentAcademicYear->id ?? '']) }}"
                        class="nav-link @if (Request::segment(2) == 'classTeacher') active @endif">
                        <i class="nav-icon far bi bi-book active"></i>
                        <p>
                            Class Report
                        </p>
                    </a>
                </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
