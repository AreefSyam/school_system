<?php

use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnalyticController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\MarkController;
use App\Http\Controllers\NavBarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

// Public (Guest) Routes
Route::group(['middleware' => 'guest'], function () {
// Home or welcome page
    Route::get('/', function () {
        return view('pages.welcome');
    });

    Route::get('/welcome', function () {
        return view('pages.welcome');
    })->name('welcome');

    // Login routes
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'postLogin'])->name('login.post');

    // Future References: Registration routes (Admin Module: Only Admin Can Add User)
    // Route::get('/register', [AuthController::class, 'register'])->name('register');
    // Route::post('/register', [AuthController::class, 'postRegister'])->name('register.post');

    // Forgot password
    Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
    Route::post('/forgot-password', [AuthController::class, 'postForgotPassword'])->name('forgot-password.post');

    // Reset password
    Route::get('/reset/{token}', [AuthController::class, 'reset'])->name('reset');
    Route::post('/reset/{token}', [AuthController::class, 'postReset'])->name('reset.post');

});

// Protected Routes for Logged-In Users
Route::group(['middleware' => 'auth'], function () {

    // User Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // User Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

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
            // Routes for Teacher Class Assignments
            // View all class assignments for a specific teacher
            Route::get('/teacher/class-assignments/{id}', [TeacherController::class, 'classAssignments'])->name('teacher.classAssignments');
            // Assign a new class to a specific teacher (form page)
            Route::get('/assignClass/{id}', [TeacherController::class, 'assignClass'])->name('teacher.assignClass');
            // Handle form submission for assigning a new class to a teacher
            Route::post('/assignClass/{id}', [TeacherController::class, 'postAssignClass'])->name('teacher.assignClass.post');
            // Delete a specific class assignment for a teacher
            Route::delete('/teacher/class-assignments/{assignmentId}', [TeacherController::class, 'deleteAssignment'])->name('teacher.deleteAssignment');

        });

        // Class Management
        Route::prefix('classManagement')->group(function () {
            Route::get('/list', [ClassController::class, 'list'])->name('class.list');
            Route::get('/add', [ClassController::class, 'add'])->name('class.add');
            Route::post('/add', [ClassController::class, 'postAdd'])->name('class.add.post');
            Route::get('/edit/{id}', [ClassController::class, 'edit'])->name('class.edit');
            Route::put('/edit/{id}', [ClassController::class, 'update'])->name('class.edit.post');
            Route::get('/delete/{id}', [ClassController::class, 'delete'])->name('class.delete');
            // Assign student to class
            Route::get('/{classId}/assign-students', [ClassController::class, 'assignStudents'])->name('class.assignStudents');
            Route::post('/{classId}/assign-students', [ClassController::class, 'postAssignStudents'])->name('class.assignStudents.post');
            Route::delete('/{classId}/remove-student/{studentId}', [ClassController::class, 'removeStudent'])->name('class.removeStudent');
            // Assign class teacher to class
            Route::get('/{classId}/assign-teacher', [ClassController::class, 'assignTeacher'])->name('class.assignTeacher');
            Route::post('/{classId}/assign-teacher', [ClassController::class, 'postAssignTeacher'])->name('class.assignTeacher.post');
            Route::delete('/{classId}/remove-teacher/{teacherId}', [ClassController::class, 'removeTeacher'])->name('class.removeTeacher');
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
                Route::get('/{yearId}/{examTypeId}/{syllabusId}/{classId}/{examId}/marks', [MarkController::class, 'index'])->name('exams.marks');
                Route::get('/{yearId}/{examTypeId}/{syllabusId}/{classId}/{examId}/marks/edit', [MarkController::class, 'edit'])->name('exams.marks.edit');
                Route::put('/{yearId}/{examTypeId}/{syllabusId}/{classId}/{examId}/marks/edit', [MarkController::class, 'updateAll'])->name('exam.marks.edit.updateAll');
            });
        });

        // Analytic Management
        Route::prefix('analyticManagement')->group(function () {
            // Subject analytics
            Route::get('/bySubject', [AnalyticController::class, 'subjectPerformance'])->name('analytic.subjectPerformance');
            // Individual student analytics
            Route::get('/byIndividual', [AnalyticController::class, 'individualPerformance'])->name('analytic.individualPerformance');
            // Class analytics
            Route::get('/byClass', [AnalyticController::class, 'classPerformance'])->name('analytic.classPerformance');
            // Report Student Below 60%
            Route::get('/refinementClass', [AnalyticController::class, 'reportStudentLess60Percent'])->name('analytic.reportStudentLess60Percent');

        });
    });

    // Route to generate PDF report for a specific student
    // Common PDF Generation Route for Admin and Teacher
    Route::get('/examsReport/{yearId}/{examTypeId}/{syllabusId}/{classId}/{studentId}/report', [MarkController::class, 'generateStudentReport'])->name('exams.marks.studentReport');
    Route::get('/examsReport/{yearId}/{examTypeId}/{syllabusId}/{classId}/{examId}/{studentId}/position-in-class', [MarkController::class, 'positionInClassReport'])->name('exams.marks.positionInClass');
    Route::get('/examsReport/{yearId}/{examTypeId}/{syllabusId}/{classId}/{examId}/{studentId}/position-in-year-level', [MarkController::class, 'positionInYearLevelReport'])->name('exams.marks.positionInYearLevel');

    // AJAX Routes for Dynamic Dropdowns
    // Fetch classes dynamically based on the selected academic year
    Route::post('/teacher/get-classes', [TeacherController::class, 'getClassesByAcademicYear'])->name('teacher.getClasses');
    // Fetch subjects dynamically based on the selected academic year
    Route::post('/teacher/get-subjects', [TeacherController::class, 'getSubjectsByAcademicYear'])->name('teacher.getSubjects');
    // Fetch the syllabus dynamically based on the selected subject
    Route::post('/teacher/get-syllabus', [TeacherController::class, 'getSyllabusBySubject'])->name('teacher.getSyllabus');
    // Fetch the grade level dynamically based on the selected class
    Route::post('/teacher/get-grade-level', [TeacherController::class, 'getGradeLevelByClass'])->name('teacher.getGradeLevel');
    // Fetch the student name dynamically based on the selected class
    Route::post('/teacher/get-students', [TeacherController::class, 'getStudentsByClass'])->name('teacher.getStudents');

    // Teacher Routes
    Route::middleware('teacher')->prefix('teacher')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('teacher.dashboard');
        // Set Academic Year on Navigation bar
        Route::get('/get-academic-year/{id}', [NavBarController::class, 'setAcademicYear'])->name('navBar.setAcademicYear');
        Route::post('/set-academic-year/{id}', [NavBarController::class, 'setAcademicYear'])->name('navBar.setAcademicYear.post');

        Route::prefix('examData')->group(function () {
            // Step 1: Display exam types
            Route::get('/{yearId}/types', [ExamController::class, 'examTypeListTeacher'])->name('teacher.exams.examTypeList');
            // Step 2: Display syllabi for the selected exam type
            Route::get('/{yearId}/{examTypeId}/syllabus', [ExamController::class, 'syllabusListTeacher'])->name('teacher.exams.syllabusList');
            // Step 3: Display subjects for the selected syllabus
            Route::get('/{yearId}/{examTypeId}/{syllabusId}/subjects', [ExamController::class, 'subjectListTeacher'])->name('teacher.exams.subjectList');
            // Step 4: Display classes assigned to the teacher
            Route::get('/{yearId}/{examTypeId}/{syllabusId}/{subjectId}/classes', [ExamController::class, 'classListTeacher'])->name('teacher.exams.classList');
            // Step 5: View marks for students in a specific class
            Route::get('/{yearId}/{examTypeId}/{syllabusId}/{subjectId}/{classId}/marks', [MarkController::class, 'teacherSubjectClassMark'])->name('teacher.exams.marks');
            // Update marks for students in a specific class
            Route::put('/{yearId}/{examTypeId}/{syllabusId}/{subjectId}/{classId}/marks', [MarkController::class, 'teacherSubjectClassMarkEdit'])->name('teacher.exams.marks.store');
        });

        Route::prefix('classTeacher')->group(function () {
            // Step 1: Display exam types
            Route::get('/{yearId}/types', [ExamController::class, 'examTypeListClassTeacher'])->name('teacher.classTeacher.examTypeList');
            // Step 2: Display syllabi for the selected exam type
            Route::get('/{yearId}/{examTypeId}/syllabus', [ExamController::class, 'syllabusListClassTeacher'])->name('teacher.classTeacher.syllabusList');
            // Step 3: View all student marks for students, action button to get student summary
            Route::get('/{yearId}/{examTypeId}/{syllabusId}/{examId}/class', [MarkController::class, 'classExamReportClassTeacher'])->name('teacher.classTeacher.classExamReport');
            Route::get('/{yearId}/{examTypeId}/{syllabusId}/{examId}/{classId}/{studentId}/write-summary', [MarkController::class, 'writeSummaryClassTeacher'])->name('teacher.classTeacher.writeSummary');
            Route::post('/{yearId}/{examTypeId}/{syllabusId}/{examId}/{classId}/{studentId}/write-summary', [MarkController::class, 'writeSummaryClassTeacherPost'])->name('teacher.classTeacher.writeSummary.post');
        });

        Route::prefix('analyticTeacher')->group(function () {
            // byIndividual student analytics
            Route::get('/byIndividual/{yearId}', [AnalyticController::class, 'individualPerformanceTeacher'])->name('teacher.analytic.individualPerformance');
            // byClass-level analytics
            Route::get('/byClass/{yearId}', [AnalyticController::class, 'classPerformanceTeacher'])->name('teacher.analytic.classPerformance');
            // Report Student Below 60%
            Route::get('/refinementClass/{yearId}', [AnalyticController::class, 'reportStudentLess60PercentTeacher'])->name('teacher.analytic.reportStudentLess60Percent');
        });
    });
});
