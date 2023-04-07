<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\PengajuanController;
use App\Http\Controllers\Admin\PermohonanController;
use App\Http\Controllers\RoutingDashboardController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

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
Route::get('/login', function () {
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
            Route::prefix('permohonan')
                ->name('permohonan.')
                ->group(function () {
                    Route::get('/', [PermohonanController::class, 'index'])->name('index');
                    Route::get('/verifikasi/{id}', [PermohonanController::class, 'verifikasi'])->name('verifikasi');
                    Route::post('/tolak-permohonan', [PermohonanController::class, 'tolakPermohonan'])->name('tolak');
                    Route::post('/selesai-permohonan', [PermohonanController::class, 'selesaiPermohonan'])->name('selesai');

                    Route::prefix('berkas')
                        ->name('berkas.')
                        ->group(function () {
                            // Ajax
                            Route::post('/revisi', [PermohonanController::class, 'revisiBerkas'])->name('revisi');
                            Route::post('/valid', [PermohonanController::class, 'validBerkas'])->name('valid');
                        });
                });
        });
    // USER ROLE
    Route::middleware(['is_user'])
        ->prefix('user')
        ->name('user.')
        ->group(function () {
            Route::prefix('pengajuan')
                ->name('pengajuan.')
                ->group(function () {
                    Route::get('/', [PengajuanController::class, 'index'])->name('index');
                    Route::put('/', [PengajuanController::class, 'update'])->name('update');

                    // Ajax
                    Route::post('/upload-berkas', [PengajuanController::class, 'uploadBerkas'])->name('upload-berkas');
                });
        });
    // OPEN REQUEST IN AUTH
    // My Profile
    Route::get('/my-profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/my-profile', [ProfileController::class, 'update'])->name('profile.update');
});
