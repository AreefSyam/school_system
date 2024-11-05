<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\ExportController;

// Public (Guest) Routes
Route::group(['middleware' => 'guest'], function () {
    // Home or welcome page
    Route::get('/', function () {
        return view('pages.welcome');
    });
    Route::get('/welcome', function () {
        return view('pages.welcome');
    });

    // Login routes
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'postLogin'])->name('login.post');

    // Registration routes
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'postRegister'])->name('register.post');

    // Forgot password
    Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
    Route::post('/forgot-password', [AuthController::class, 'postForgotPassword'])->name('forgot-password.post');

    // Reset password
    Route::get('/reset/{token}', [AuthController::class, 'reset'])->name('reset');
    Route::post('/reset/{token}', [AuthController::class, 'postReset'])->name('reset.post');

});


// Protected Routes for Logged-In Users
Route::group(['middleware' => 'auth'], function () {
    // Admin Routes
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');

        // Admin Management
        Route::prefix('userManagement/admin')->group(function () {
            Route::get('/list', [AdminController::class, 'list'])->name('admin.list');
            Route::get('/add', [AdminController::class, 'add'])->name('admin.add');
            Route::post('/add', [AdminController::class, 'postAdd'])->name('admin.add.post');
            Route::get('/edit/{id}', [AdminController::class, 'edit'])->name('admin.edit');
            Route::put('/edit/{id}', [AdminController::class, 'update'])->name('admin.edit.post');
            Route::get('/delete/{id}', [AdminController::class, 'delete'])->name('admin.delete');
        });

        // Teacher Management
        Route::prefix('userManagement/teacher')->group(function () {
            Route::get('/list', [TeacherController::class, 'list'])->name('teacher.list');
            Route::get('/add', [TeacherController::class, 'add'])->name('teacher.add');
            Route::post('/add', [TeacherController::class, 'postAdd'])->name('teacher.add.post');
            Route::get('/edit/{id}', [TeacherController::class, 'edit'])->name('teacher.edit');
            Route::put('/edit/{id}', [TeacherController::class, 'update'])->name('teacher.edit.post');
            Route::get('/delete/{id}', [TeacherController::class, 'delete'])->name('teacher.delete');
        });

        // Class Management
        Route::prefix('classManagement')->group(function () {
            Route::get('/list', [ClassController::class, 'list'])->name('class.list');
            Route::get('/add', [ClassController::class, 'add'])->name('class.add');
            Route::post('/add', [ClassController::class, 'postAdd'])->name('class.add.post');
            Route::get('/edit/{id}', [ClassController::class, 'edit'])->name('class.edit');
            Route::put('/edit/{id}', [ClassController::class, 'update'])->name('class.edit.post');
            Route::get('/delete/{id}', [ClassController::class, 'delete'])->name('class.delete');
            Route::get('/{classId}/assign-students', [ClassController::class, 'assignStudents'])->name('class.assignStudents');
            Route::post('/{classId}/assign-students', [ClassController::class, 'postAssignStudents'])->name('class.assignStudents.post');
            Route::delete('/{classId}/remove-student/{studentId}', [ClassController::class, 'removeStudent'])->name('class.removeStudent');
        });

        // Academic Year Management
        Route::prefix('academicYearManagement')->group(function () {
            Route::get('/list', [AcademicYearController::class, 'list'])->name('academicYear.list');
            Route::get('/add', [AcademicYearController::class, 'add'])->name('academicYear.add');
            Route::post('/add', [AcademicYearController::class, 'postAdd'])->name('academicYear.add.post');
            Route::get('/edit/{id}', [AcademicYearController::class, 'edit'])->name('academicYear.edit');
            Route::put('/edit/{id}', [AcademicYearController::class, 'update'])->name('academicYear.edit.post');
            Route::get('/delete/{id}', [AcademicYearController::class, 'delete'])->name('academicYear.delete');
        });

        // Student Management
        Route::prefix('studentManagement')->group(function () {
            Route::get('/list', [StudentController::class, 'list'])->name('studentManagement.list');
            Route::get('/add', [StudentController::class, 'add'])->name('studentManagement.add');
            Route::post('/add', [StudentController::class, 'postAdd'])->name('studentManagement.add.post');
            Route::get('/edit/{id}', [StudentController::class, 'edit'])->name('studentManagement.edit');
            Route::put('/edit/{id}', [StudentController::class, 'update'])->name('studentManagement.edit.post');
            Route::get('/delete/{id}', [StudentController::class, 'delete'])->name('studentManagement.delete');
        });

        // Subject Management
        Route::prefix('subjectManagement')->group(function () {
            Route::get('/list', [SubjectController::class, 'list'])->name('subjectManagement.list');
            Route::get('/add', [SubjectController::class, 'add'])->name('subjectManagement.add');
            Route::post('/add', [SubjectController::class, 'postAdd'])->name('subjectManagement.add.post');
            Route::get('/edit/{id}', [SubjectController::class, 'edit'])->name('subjectManagement.edit');
            Route::put('/edit/{id}', [SubjectController::class, 'update'])->name('subjectManagement.edit.post');
            Route::get('/delete/{id}', [SubjectController::class, 'delete'])->name('subjectManagement.delete');
        });

        // Exam Management
        Route::prefix('examManagement')->group(function () {

            // Search Exam
            Route::get('/manages/list', [ExamController::class, 'list'])->name('examManagement.list');
            Route::get('/manages/add', [ExamController::class, 'add'])->name('examManagement.add');
            Route::post('/manages/add', [ExamController::class, 'postAdd'])->name('examManagement.add.post');
            Route::get('/manages/edit/{id}', [ExamController::class, 'edit'])->name('examManagement.edit');
            Route::put('/manages/edit/{id}', [ExamController::class, 'update'])->name('examManagement.edit.post');
            Route::get('/manages/delete/{id}', [ExamController::class, 'delete'])->name('examManagement.delete');

            // Exam Folders
            // Exam Management Routes

            // Display available academic years
            Route::get('/exams/years', [ExamController::class, 'yearList'])->name('exams.yearList');
            // Display available exam types (PPT, PAT) for a specific academic year
            Route::get('/exams/{yearId}/types', [ExamController::class, 'examTypeList'])->name('exams.examTypeList');
            // Display available syllabi (KAFA, YTP) for the selected academic year and exam type
            Route::get('/exams/{yearId}/{examTypeID}/syllabus', [ExamController::class, 'syllabusList'])->name('exams.syllabusList');
            // Display classes for the selected academic year, exam type, and syllabus
            Route::get('/exams/{yearId}/{examTypeID}/{syllabusID}/classes', [ExamController::class, 'classList'])->name('exams.classList');
            // Display marks for a specific class, syllabus, and exam type
            Route::get('/exams/{yearId}/{examTypeID}/{syllabusID}/{classId}/marks', [ExamController::class, 'marks'])->name('exams.marks');

            Route::prefix('exports')->group(function () {
                Route::get('/marks/csv', [ExportController::class, 'exportCSV'])->name('marks.csv');
                Route::get('/marks/excel', [ExportController::class, 'exportExcel'])->name('marks.excel');
                Route::get('/pdf/markPDF', [ExportController::class, 'exportPDF'])->name('exports.pdf.markPDF');
            });


        });
    });

    // Teacher Routes
    Route::middleware('teacher')->group(function () {
        Route::get('/teacher/dashboard', [DashboardController::class, 'dashboard'])->name('teacher.dashboard');
    });

    // Logout Route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// // Protected Routes for Logged-In Users (Admin/Teacher/User)
// Route::group(['middleware' => 'auth'], function () {

//     // Admin-specific routes (restricted by role via middleware)
//     Route::middleware('admin')->group(function () {
//         // Admin Dashboard Route
//         Route::get('/admin/dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');

//         // Admin Management Route
//         Route::get('/admin/userManagement/admin/list', [AdminController::class, 'list'])->name('admin.list');
//         Route::get('/admin/userManagement/admin/add', [AdminController::class, 'add'])->name('admin.add');
//         Route::post('/admin/userManagement/admin/add', [AdminController::class, 'postAdd'])->name('admin.add.post');
//         Route::get('/admin/userManagement/admin/edit/{id}', [AdminController::class, 'edit'])->name('admin.edit');
//         Route::put('/admin/userManagement/admin/edit/{id}', [AdminController::class, 'update'])->name('admin.edit.post');
//         Route::get('/admin/userManagement/admin/delete/{id}', [AdminController::class, 'delete'])->name('admin.delete');

//         // Teacher Management Route
//         Route::get('/admin/userManagement/teacher/list', [TeacherController::class, 'list'])->name('teacher.list');
//         Route::get('/admin/userManagement/teacher/add', [TeacherController::class, 'add'])->name('teacher.add');
//         Route::post('/admin/userManagement/teacher/add', [TeacherController::class, 'postAdd'])->name('teacher.add.post');
//         Route::get('/admin/userManagement/teacher/edit/{id}', [TeacherController::class, 'edit'])->name('teacher.edit');
//         Route::put('/admin/userManagement/teacher/edit/{id}', [TeacherController::class, 'update'])->name('teacher.edit.post');
//         Route::get('/admin/userManagement/teacher/delete/{id}', [TeacherController::class, 'delete'])->name('teacher.delete');

//         // Class Management Route
//         Route::get('/admin/classManagement/list', [ClassController::class, 'list'])->name('class.list');
//         Route::get('/admin/classManagement/add', [ClassController::class, 'add'])->name('class.add');
//         Route::post('/admin/classManagement/add', [ClassController::class, 'postAdd'])->name('class.add.post');
//         Route::get('/admin/classManagement/edit/{id}', [ClassController::class, 'edit'])->name('class.edit');
//         Route::put('/admin/classManagement/edit/{id}', [ClassController::class, 'update'])->name('class.edit.post');
//         Route::get('/admin/classManagement/delete/{id}', [ClassController::class, 'delete'])->name('class.delete');

//         // Academic Year Management Route
//         Route::get('/admin/academicYearManagement/list', [AcademicYearController::class, 'list'])->name('academicYear.list');
//         Route::get('/admin/academicYearManagement/add', [AcademicYearController::class, 'add'])->name('academicYear.add');
//         Route::post('/admin/academicYearManagement/add', [AcademicYearController::class, 'postAdd'])->name('academicYear.add.post');
//         Route::get('/admin/academicYearManagement/edit/{id}', [AcademicYearController::class, 'edit'])->name('academicYear.edit');
//         Route::put('/admin/academicYearManagement/edit/{id}', [AcademicYearController::class, 'update'])->name('academicYear.edit.post');
//         Route::get('/admin/academicYearManagement/delete/{id}', [AcademicYearController::class, 'delete'])->name('academicYear.delete');

//         // Academic Year Management Route
//         Route::get('/admin/academicYearManagement/list', [AcademicYearController::class, 'list'])->name('academicYear.list');
//         Route::get('/admin/academicYearManagement/add', [AcademicYearController::class, 'add'])->name('academicYear.add');
//         Route::post('/admin/academicYearManagement/add', [AcademicYearController::class, 'postAdd'])->name('academicYear.add.post');
//         Route::get('/admin/academicYearManagement/edit/{id}', [AcademicYearController::class, 'edit'])->name('academicYear.edit');
//         Route::put('/admin/academicYearManagement/edit/{id}', [AcademicYearController::class, 'update'])->name('academicYear.edit.post');
//         Route::get('/admin/academicYearManagement/delete/{id}', [AcademicYearController::class, 'delete'])->name('academicYear.delete');

//         // Academic Year Management Route
//         Route::get('/admin/studentManagement/list', [StudentController::class, 'list'])->name('studentManagement.list');
//         Route::get('/admin/studentManagement/add', [StudentController::class, 'add'])->name('studentManagement.add');
//         Route::post('/admin/studentManagement/add', [StudentController::class, 'postAdd'])->name('studentManagement.add.post');
//         Route::get('/admin/studentManagement/edit/{id}', [StudentController::class, 'edit'])->name('studentManagement.edit');
//         Route::put('/admin/studentManagement/edit/{id}', [StudentController::class, 'update'])->name('studentManagement.edit.post');
//         Route::get('/admin/studentManagement/delete/{id}', [StudentController::class, 'delete'])->name('studentManagement.delete');
//     });

//     // Teacher-specific routes (restricted by role via middleware)
//     Route::middleware('teacher')->group(function () {
//         // Teacher Dashboard Route
//         Route::get('/teacher/dashboard', [DashboardController::class, 'dashboard'])->name('teacher.dashboard');

//         // Other teacher-specific routes can go here
//     });

//     // Logout Route (available to all authenticated users)
//     Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// });