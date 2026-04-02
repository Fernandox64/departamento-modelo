<?php

use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/noticias', [HomeController::class, 'news'])->name('news.index');
Route::get('/editais', [HomeController::class, 'editais'])->name('editais.index');
Route::get('/conteudo/{slug}', [HomeController::class, 'show'])->name('content.show');

Route::get('/dashboard', [ContentController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::resource('contents', ContentController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
