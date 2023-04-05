<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\RoutingDashboardController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AuthController::class, 'index'])->name('login.index');
Route::get('/login', function(){
    return redirect()->route('login.index');
})->name('login');
Route::get('/dashboard', [RoutingDashboardController::class, 'index'])->name('dashboard');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    // ADMIN ROLE
    Route::middleware(['is_admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::prefix('dashboard')
                ->name('dashboard.')
                ->group(function () {
                    Route::get('/', [AdminDashboardController::class, 'index'])->name('index');
                });
        });
    // USER ROLE
    Route::middleware(['is_user'])
        ->prefix('user')
        ->name('user.')
        ->group(function () {
            Route::prefix('dashboard')
                ->name('dashboard.')
                ->group(function () {
                    Route::get('/', [UserDashboardController::class, 'index'])->name('index');
                });
        });
    // OPEN REQUEST IN AUTH
    // My Profile
    Route::get('/my-profile', [ProfileController::class, 'index'])->name('profile.index');
});
