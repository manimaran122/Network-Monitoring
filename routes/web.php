<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\MonitorDetails;
use App\Livewire\AlertCenter;
use App\Livewire\Reports;
use App\Livewire\Config;

Route::get('/login', App\Livewire\Login::class)->name('login');
Route::get('/register', App\Livewire\Register::class)->name('register');
Route::get('/forgot-password', App\Livewire\ForgotPassword::class)->name('password.request');


// Force Password Change — accessible only when authenticated (before the auth middleware group)
Route::get('/password/change', App\Livewire\ChangePassword::class)
    ->middleware('auth')
    ->name('password.change');

// All protected routes go through the ForcePasswordChange middleware
Route::middleware(['auth', App\Http\Middleware\ForcePasswordChange::class])->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/monitors', App\Livewire\Monitors\Index::class)->name('monitors');
    Route::get('/monitor/{monitor}', MonitorDetails::class)->name('monitor.details');
    Route::get('/alerts', AlertCenter::class)->name('alerts');
    Route::get('/reports', Reports::class)->name('reports');
    Route::get('/settings', App\Livewire\Settings::class)->name('settings');
});

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// AI Test Routes
require __DIR__.'/ai-test.php';
