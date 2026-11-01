<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\FollowupController;

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

    Route::get('/reports/data', [ReportController::class, 'data'])
        ->name('reports.data');

     // Route khusus Koordinator
    Route::middleware('role:koordinator')->group(function () {

        Route::get('/follow-ups', [FollowUpController::class, 'index'])
            ->name('follow-ups.index');

        Route::get('/follow-ups/{report}', [FollowUpController::class, 'show'])
            ->name('follow-ups.show');
        
        Route::post('/follow-ups/{report}/forward', [FollowUpController::class, 'forward'])
            ->name('follow-ups.forward');

        Route::post('/follow-ups/{report}/complete', [FollowUpController::class, 'complete'])
            ->name('follow-ups.complete');


    });


    // Route daftar dan detail laporan untuk semua role
    Route::middleware('role:pelapor,relawan,koordinator')->group(function () {

        Route::get('/reports', [ReportController::class, 'index'])
            ->name('reports.index');

        Route::get('/reports/{report}', [ReportController::class, 'show'])
            ->name('reports.show');

    });

    // Route khusus Relawan
    Route::middleware('role:relawan')->group(function () {

        Route::get('/reviews', [ReviewController::class, 'index'])
            ->name('reviews.index');

        Route::get('/reviews/{report}', [ReviewController::class, 'show'])
            ->name('reviews.show');
        
        Route::post('/reviews/{report}/start',[ReviewController::class, 'startReview'])
            ->name('reviews.start');

        Route::post('/reviews/{report}/note',[ReviewController::class, 'store'])
            ->name('reviews.store');

        Route::post('/reviews/{report}/decision',[ReviewController::class, 'decide'])
            ->name('reviews.decide');

    });

}); 
