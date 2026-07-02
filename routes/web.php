<?php

use App\Http\Controllers\AuthController as UserAuthController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\PemesananTiketController as UserPemesananTiketController;
use App\Http\Controllers\User\ProfilController as UserProfilController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('user.home.index');
})->name('home');

Route::get('/home', function () {
    return view('user.home.index');
})->name('user.home.index');

Route::get('/login', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'penumpang') {
            return redirect()->route('user.dashboard');
        }

        if (in_array(Auth::user()->role, ['super_admin', 'admin', 'pimpinan'])) {
            return redirect('/admin');
        }
    }

    return redirect()->route('user.login');
})->name('login');

Route::prefix('user')->name('user.')->group(function () {
    Route::get('/login', [UserAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [UserAuthController::class, 'login'])->name('login.process');

    Route::get('/register', [UserAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [UserAuthController::class, 'register'])->name('register.process');
});

Route::middleware(['auth', 'role:penumpang'])->prefix('user')->name('user.')->group(function () {
    Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    Route::get('/profil', [UserProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [UserProfilController::class, 'update'])->name('profil.update');

    Route::get('/pemesanan', [UserPemesananTiketController::class, 'index'])->name('pemesanan.index');
    Route::get('/pemesanan/create', [UserPemesananTiketController::class, 'create'])->name('pemesanan.create');
    Route::post('/pemesanan', [UserPemesananTiketController::class, 'store'])->name('pemesanan.store');
    Route::get('/pemesanan/{pemesanan}', [UserPemesananTiketController::class, 'show'])->name('pemesanan.show');
    Route::get('/pemesanan/{pemesanan}/edit', [UserPemesananTiketController::class, 'edit'])->name('pemesanan.edit');
    Route::put('/pemesanan/{pemesanan}', [UserPemesananTiketController::class, 'update'])->name('pemesanan.update');
});