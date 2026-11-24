<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        // MVP-3 privacy gate: named per-purpose limiters (initial numbers proposed in
        // issue #4 and confirmed by test results — adjust after real-usage evidence).
        RateLimiter::for('invitation', fn (Request $r) => Limit::perMinute(120)->by($r->ip()));
        RateLimiter::for('rsvp', fn (Request $r) => Limit::perMinute(10)->by($r->ip()));
        RateLimiter::for('checkin', fn (Request $r) => Limit::perMinute(60)->by($r->ip()));
        RateLimiter::for('upload', fn (Request $r) => Limit::perMinute(20)->by($r->ip()));
    }
}
