<?php

namespace App\Providers;

use App;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (App::environment('local') && env('APP_TIME_TRAVEL')) {
            Carbon::setTestNow(Carbon::parse(env('APP_TIME_TRAVEL')));
        }
    }
}
