<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\InvitationController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect('/dashboard'));

// Static PWA assets: routes keep them reachable even without a web-server static handler
// (php artisan serve and Apache/Nginx serve public/ directly; routes cover tests + odd hosts).
Route::get('/manifest.webmanifest', fn () =>
    response(file_get_contents(public_path('manifest.webmanifest')), 200, ['Content-Type' => 'application/manifest+json']));
Route::get('/sw.js', fn () =>
    response(file_get_contents(public_path('sw.js')), 200, ['Content-Type' => 'application/javascript']));
Route::get('/css/app.css', fn () =>
    response(file_get_contents(public_path('css/app.css')), 200, ['Content-Type' => 'text/css']));
Route::get('/icons/{file}', fn (string $file) =>
    response(file_get_contents(public_path('icons/'.$file)), 200, ['Content-Type' => 'image/png']))->where('file', 'icon-(192|512)\.png');

// Public — login form only
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
});

// Protected — owner dashboard shell + event CRUD (scoped to the owning user)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::resource('events', EventController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
});

// Public — invitation pages reached via the unguessable token; invalid token -> 404
Route::get('/u/{token}', [InvitationController::class, 'show'])->name('invitation.show');
Route::get('/u/{token}/checkin', [InvitationController::class, 'checkin'])->name('invitation.checkin');
