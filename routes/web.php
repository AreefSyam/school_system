<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\MarkController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AcademicYearController;

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

            // Exam Management Routes (CRUD operations for managing exams)
            Route::prefix('manages')->group(function () {
                Route::get('/list', [ExamController::class, 'list'])->name('examManagement.list');
                Route::get('/add', [ExamController::class, 'add'])->name('examManagement.add');
                Route::post('/add', [ExamController::class, 'postAdd'])->name('examManagement.add.post');
                Route::get('/edit/{id}', [ExamController::class, 'edit'])->name('examManagement.edit');
                Route::put('/edit/{id}', [ExamController::class, 'update'])->name('examManagement.edit.post');
                Route::get('/delete/{id}', [ExamController::class, 'delete'])->name('examManagement.delete');
            });

            // Routes to display available options for exams (years, types, syllabi, classes, marks)
            Route::prefix('exams')->group(function () {
                // Display available academic years
                Route::get('/years', [ExamController::class, 'yearList'])->name('exams.yearList');
                // Display available exam types (PPT, PAT) for a specific academic year
                Route::get('/{yearId}/types', [ExamController::class, 'examTypeList'])->name('exams.examTypeList');
                // Display available syllabi (KAFA, YTP) for the selected academic year and exam type
                Route::get('/{yearId}/{examTypeId}/syllabus', [ExamController::class, 'syllabusList'])->name('exams.syllabusList');
                // Display classes for the selected academic year, exam type, and syllabus
                Route::get('/{yearId}/{examTypeId}/{syllabusId}/classes', [ExamController::class, 'classList'])->name('exams.classList');

                // View and update marks for a specific class, syllabus, and exam type
                Route::get('/{yearId}/{examTypeId}/{syllabusId}/{classId}/marks', [MarkController::class, 'index'])->name('exams.marks');
                Route::get('/{yearId}/{examTypeId}/{syllabusId}/{classId}/marks/edit', [MarkController::class, 'edit'])->name('exams.marks.edit');
                Route::put('/{yearId}/{examTypeId}/{syllabusId}/{classId}/marks/edit', [MarkController::class, 'updateAll'])->name('exam.marks.edit.updateAll');
                // Route to generate PDF report for a specific student
                Route::get('/examManagement/exams/{yearId}/{examTypeId}/{syllabusId}/{classId}/{studentId}/report', [MarkController::class, 'generateStudentReport'])->name('exams.marks.studentReport');
            });


        });

        // Uncomment and use if you need export functionalities
        // Route::prefix('exports')->group(function () {
        //     Route::get('/marks/csv', [ExportController::class, 'exportCSV'])->name('marks.csv');
        //     Route::get('/marks/excel', [ExportController::class, 'exportExcel'])->name('marks.excel');
        //     Route::get('/pdf/markPDF', [ExportController::class, 'exportPDF'])->name('exports.pdf.markPDF');
        // });
    });

    // Teacher Routes
    Route::middleware('teacher')->group(function () {
        Route::get('/teacher/dashboard', [DashboardController::class, 'dashboard'])->name('teacher.dashboard');
    });

    // Logout Route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
