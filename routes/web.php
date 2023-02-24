<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use \App\Http\Controllers\DashboardController;

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

Route::get('/', function () {
    return view('auth/login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::post('/dashboard', [DashboardController::class, 'store'])->middleware(['auth', 'verified'])->name('dashboard');
Route::post('/dashboard/sickness', [DashboardController::class, 'storeSick'])->middleware(['auth', 'verified'])->name('dashboard-sickness');

Route::get('/admin', [AdminController::class, 'index'])->middleware(['auth','verified'])->name('admin');
Route::post('/admin/{id}', [AdminController::class, 'update'])->middleware(['auth','verified'])->name('role.update');
Route::post('/admin/submitAnswer', [AdminController::class, 'updateAnswerDB'])->middleware(['auth','verified'])->name('updateAnswerDB');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile', [ProfileController::class, 'store'])->name('profile.store');
});

require __DIR__.'/auth.php';
