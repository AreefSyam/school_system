<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-flex align-items-center">
            @if(auth()->user()->hasRole('admin'))
            <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center">
                <img src="{{ url('dist/img/LOGO_KAJILAH.svg')  }}" alt="Logo" class="brand-logo"
                    style="height: 50px; margin-right: 15px;">
            </a>
            @endif

            @if(auth()->user()->hasRole('teacher'))
            <a href="{{ route('teacher.dashboard') }}" class="d-flex align-items-center">
                <img src="{{ url('dist/img/LOGO_KAJILAH.svg')  }}" alt="Logo" class="brand-logo"
                    style="height: 50px; margin-right: 15px;">
            </a>
            @endif
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <!-- Academic Year Selector -->
        @if(auth()->user()->hasRole('teacher'))
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button">
                <i class="fas fa-calendar-alt"></i>
                {{ session('academic_year', 'Select Year') }}
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                @foreach($academicYears as $year)
                <a href="{{ route('navBar.setAcademicYear', $year->id) }}" class="dropdown-item">
                    {{ $year->academic_year_name }}
                    <!-- Ensure this matches your DB column -->
                </a>
                @endforeach
            </div>
        </li>
        @endif

        <!-- User Profile Dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#" role="button">
                <!-- User Image -->
                <img src="{{ url('dist/img/user2-160x160.jpg') }}" class="img-circle img-bordered-sm" alt="User Image"
                    style="width: 30px; height: 30px; margin-right: 8px;">
                <!-- Full Name -->
                <span class="font-weight-bold username">{{ Auth::user()->name }}</span>
                <!-- Dropdown Icon -->
                <i class="fas fa-caret-down ml-2"></i>
            </a>
            <!-- Dropdown Menu -->
            <div class="dropdown-menu dropdown-menu-right">
                <a href="{{ route('profile.show') }}" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> Profile
                </a>
                <div class="dropdown-divider"></div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->

<!-- Floating Flash Messages -->
<div class="flash-messages-container">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if(session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        {{ session('info') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.change-year').forEach(item => {
        item.addEventListener('click', function (event) {
            event.preventDefault();
            const yearId = this.getAttribute('data-year-id');
            fetch(`/set-academic-year/${yearId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ yearId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload(); // Reload the page to fetch new data
                }
            })
            .catch(error => {
                console.error('Error updating academic year:', error);
            });
        });
    });
});
</script>

<!-- JavaScript to auto-dismiss alerts after 3 seconds -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.classList.add('fade-out'); // Add fade-out animation
                setTimeout(() => alert.remove(), 500); // Remove element after animation
            });
        }, 3000); // Dismiss after 3 seconds
    });
</script>

<!-- CSS for floating and animations -->
<style>
    @media (max-width: 767.98px) {
        .username {
            display: none;
        }
    }

    .flash-messages-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1050;
        /* Ensure it appears above other elements */
        width: 300px;
    }

    .alert {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        margin-bottom: 10px;
        transition: opacity 0.5s ease, transform 0.5s ease;
    }

    .alert.fade-out {
        opacity: 0;
        transform: translateY(-20px);
        /* Slight upward animation */
    }
</style>
