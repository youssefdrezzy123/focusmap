<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\StepController;
use App\Http\Controllers\HomeController;

// Page d'accueil
Route::get('/', function () {
    return view('welcome');
});

// Désactivation des routes Auth par défaut
Auth::routes(['register' => false, 'login' => false]);

// Authentification
Route::middleware('guest')->group(function () {
    // Pages
    Route::view('/login', 'auth.login');
    Route::view('/register', 'auth.register');
    
    // Traitement
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

// Routes protégées
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });
    
    Route::resource('goals', GoalController::class)->except(['index']);
    
    Route::prefix('goals/{goal}')->group(function () {
        Route::resource('steps', StepController::class)->except(['index', 'show']);
    });

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
});

// Route publique
Route::get('goals', [GoalController::class, 'index']);