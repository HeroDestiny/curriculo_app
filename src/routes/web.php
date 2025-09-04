<?php

use App\Http\Controllers\CurriculoController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rotas para currículos - públicas (não precisam de autenticação)
Route::get('/curriculos/enviar', [CurriculoController::class, 'create'])->name('curriculos.create');
Route::post('/curriculos', [CurriculoController::class, 'store'])->name('curriculos.store');
Route::get('/curriculos/sucesso', [CurriculoController::class, 'sucesso'])->name('curriculos.sucesso');

// Rotas administrativas - protegidas por autenticação
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/curriculos', [CurriculoController::class, 'index'])->name('curriculos.index');
    Route::get('/curriculos/{curriculo}', [CurriculoController::class, 'show'])->name('curriculos.show');
    Route::get('/curriculos/{curriculo}/download', [CurriculoController::class, 'downloadArquivo'])->name('curriculos.download');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
