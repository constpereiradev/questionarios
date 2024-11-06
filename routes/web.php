<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionarioController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/questionarios', [QuestionarioController::class, 'index'])->name('questionario.index');
    Route::post('/questionario/registrar', [QuestionarioController::class, 'store'])->name('questionario.registrar');
    Route::post('/questionario/api', [QuestionarioController::class, 'api'])->name('questionario.api');
    Route::get('/questionario/ver/{id}', [QuestionarioController::class, 'show'])->name('questionario.ver');
});

Route::controller(ClienteController::class)->group(function () {
    Route::get('/cliente/ver/questionario/{id}', [ClienteController::class, 'verQuestionario'])->name('cliente.questionario.ver');
});


require __DIR__.'/auth.php';
