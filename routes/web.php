<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WooferController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\AtelierController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\TacheController;
use App\Http\Controllers\Auth\PasswordController;

Route::get('/', [HomeController::class, 'index'])->name('home');


Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth'])->group(function () {

   
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/ventes', [VenteController::class, 'index'])->name('ventes.index');
    Route::post('/ventes', [VenteController::class, 'store'])->name('ventes.store');
    Route::get('/ventes/{id}', [VenteController::class, 'show'])->name('ventes.show');

 
    Route::group(['middleware' => function ($request, $next) {
        if ($request->user()->role_id != 1) {
            abort(403, 'Accès réservé aux responsables.');
        }
        return $next($request);
    }], function () {
        Route::get('/ventes/{id}/edit', [VenteController::class, 'edit'])->name('ventes.edit');
        Route::put('/ventes/{vente}', [VenteController::class, 'update'])->name('ventes.update');
        Route::delete('/ventes/{id}', [VenteController::class, 'destroy'])->name('ventes.destroy');
    });

   
    Route::group(['middleware' => function ($request, $next) {
        if ($request->user()->role_id != 1) {
            abort(403, 'Accès réservé aux responsables.');
        }
        return $next($request);
    }], function () {
        Route::post('/produits', [ProduitController::class, 'ajouter'])->name('produits.ajouter');
        Route::post('/produits/supprimer', [ProduitController::class, 'supprimer'])->name('produits.supprimer');
    });

    Route::get('/produits', [ProduitController::class, 'index'])->name('produits.index');
    Route::post('/produits/modifierStock', [ProduitController::class, 'modifierStock'])->name('produits.modifierStock');

    Route::group(['middleware' => function ($request, $next) {
        if ($request->user()->role_id != 1) {
            abort(403, 'Accès réservé aux responsables.');
        }
        return $next($request);
    }], function () {
        Route::get('/ateliers', [AtelierController::class, 'index'])->name('ateliers.index');
        Route::get('/ateliers/create', [AtelierController::class, 'create'])->name('ateliers.create');
        Route::post('/ateliers', [AtelierController::class, 'store'])->name('ateliers.store');
        Route::get('/ateliers/{id}', [AtelierController::class, 'show'])->name('ateliers.show');
        Route::put('/ateliers/{id}', [AtelierController::class, 'update'])->name('ateliers.update');
        Route::delete('/ateliers/{id}', [AtelierController::class, 'destroy'])->name('ateliers.destroy');
        Route::post('/ateliers/{id}/add-participant', [AtelierController::class, 'addParticipant'])->name('ateliers.addParticipant');
    });

    
    Route::group(['middleware' => function ($request, $next) {
        if ($request->user()->role_id != 1) {
            abort(403, 'Accès réservé aux responsables.');
        }
        return $next($request);
    }], function () {
        Route::get('/woofers', [WooferController::class, 'index'])->name('woofers.index');
        Route::post('/woofers', [WooferController::class, 'store'])->name('woofers.store');
        Route::get('/woofers/{id}', [WooferController::class, 'show'])->name('woofers.show');
        Route::delete('/woofers/{id_woofer}', [WooferController::class, 'destroy'])->name('woofers.destroy');
        Route::get('/woofers/{id_woofer}/taches', [WooferController::class, 'showTasks'])->name('woofers.tasks');
    });

    
    Route::post('/taches', [TacheController::class, 'store'])->name('taches.store');


    Route::get('/first-login-change-password', [PasswordController::class, 'showFirstLoginForm'])
        ->name('password.first.form');
    Route::post('/first-login-change-password', [PasswordController::class, 'updateFirstLogin'])
        ->name('password.first.update');
    Route::post('/user/password', [PasswordController::class, 'update'])
        ->name('password.update');
});
