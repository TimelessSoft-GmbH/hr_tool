<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UpdateUserController;
use App\Http\Controllers\UserController;
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

Route::get('/admin', [AdminController::class, 'index'])->middleware('admin')->name('admin');
Route::post('/admin/{id}', [AdminController::class, 'roleChange'])->middleware('admin')->name('role.update');
Route::post('/admin/{id}/update', [AdminController::class, 'update'])->middleware('admin')->name('user.update');
Route::get('/admin/user/update/{id}', [UpdateUserController::class, 'index'])->middleware('admin')->name('index.update.user');
Route::get('/users/create', [UserController::class, 'index'])->middleware('admin')->name('users.load.create');
Route::post('/users/created', [UserController::class, 'create'])->middleware('admin')->name('users.create');

Route::post('/admin/user/updated/{id}', [UpdateUserController::class, 'update'])->middleware('admin')->name('index.updated.user');
Route::delete('/admin/user/deleted/{id}', [UpdateUserController::class, 'destroy'])->middleware('admin')->name('index.delete.user');
Route::post('/admin/{id}/submitAnswer', [AdminController::class, 'answerUpdateVacation'])->middleware('admin')->name('vacation.answerUpdate');
Route::post('/admin/{id}/submitAnswer2', [AdminController::class, 'answerUpdateSickness'])->middleware('admin')->name('sickness.answerUpdate');
Route::delete('/admin/{id}', [AdminController::class, 'destroy'])->middleware('admin')->name('user.delete');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile', [ProfileController::class, 'store'])->name('profile.store');
});

require __DIR__.'/auth.php';
