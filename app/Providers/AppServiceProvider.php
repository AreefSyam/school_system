<?php

namespace App\Providers;

use App\Repositories\MarkRepository;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\AcademicYearModel;


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

        // // Share academic years with the navigation bar view
        // View::composer('layouts.header', function ($view) {
        //     $view->with('academicYears', AcademicYearModel::where('status', 0)->select('id', 'academic_year_name')->get());
        // });

    // Share academic years for teachers globally
    View::composer('*', function ($view) {
        // dd(auth()->user());  // This will dump the user data
        if (auth()->check() && auth()->user()->hasRole('teacher')) {
            $academicYears = AcademicYearModel::where('status', 0)->select('id', 'academic_year_name')->get();
            $currentAcademicYear = AcademicYearModel::where('is_current', 1)->first();

            $view->with(compact('academicYears', 'currentAcademicYear'));
        }
    });
    }
}
