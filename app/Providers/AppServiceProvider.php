<?php

namespace App\Providers;

use App\Models\AcademicYearModel;
use App\Repositories\MarkRepository;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(MarkRepository::class, function ($app) {
            return new MarkRepository();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        // Share academic years for teachers globally
        View::composer('*', function ($view) {
            if (auth()->check() && auth()->user()->hasRole('teacher')) {
                // Fetch all active academic years
                $academicYears = AcademicYearModel::where('status', 0)->select('id', 'academic_year_name')->get();
                // Retrieve the current academic year from the session
                $currentAcademicYearId = session('academic_year_id');
                $currentAcademicYear = null;
                if ($currentAcademicYearId) {
                    // Try to fetch the current academic year based on the session
                    $currentAcademicYear = AcademicYearModel::find($currentAcademicYearId);
                }
                if (!$currentAcademicYear && $academicYears->isNotEmpty()) {
                    // Fallback to the first available academic year if session is empty or invalid
                    $currentAcademicYear = $academicYears->first();
                    Session::put('academic_year', $currentAcademicYear->academic_year_name);
                    Session::put('academic_year_id', $currentAcademicYear->id);
                }
                // Share the data with all views
                $view->with(compact('academicYears', 'currentAcademicYear'));
            }
        });

    }
}
