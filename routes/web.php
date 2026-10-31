<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ReportController;

Route::redirect('/', '/login');

// Route untuk pengguna yang belum login
Route::middleware('guest')->group(function () {

    Route::get('/login', [LoginController::class, 'show'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'authenticate'])
        ->name('login.authenticate');

});

// Route untuk pengguna yang sudah login
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');

    // Route khusus Pelapor
    Route::get('/akses/pelapor', function () {
        return 'Akses Pelapor berhasil!';
    })->middleware('role:pelapor');

    // Route khusus Relawan
    Route::get('/akses/relawan', function () {
        return 'Akses Relawan berhasil!';
    })->middleware('role:relawan');

    // Route khusus Koordinator
    Route::get('/akses/koordinator', function () {
        return 'Akses Koordinator berhasil!';
    })->middleware('role:koordinator');

    // Route khusus Pelapor
    Route::middleware('role:pelapor')->group(function () {

        Route::resource('reports', ReportController::class)
            ->only(['create', 'store', 'edit', 'update']);

    });

    // Route daftar dan detail laporan untuk semua role
    Route::middleware('role:pelapor,relawan,koordinator')->group(function () {

        Route::get('/reports', [ReportController::class, 'index'])
            ->name('reports.index');

        Route::get('/reports/{report}', [ReportController::class, 'show'])
            ->name('reports.show');

    });

}); 
