<?php

use App\Http\Controllers\DisplayUserController;
use App\Http\Controllers\EnterHoursController;
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


// Load Pages Routes
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/admin', [AdminController::class, 'index'])->middleware('admin')->name('admin');
Route::get('/enterHours', [EnterHoursController::class, 'index'])->middleware('admin')->name('enterHours');
Route::get('/admin/user/update/{id}', [UpdateUserController::class, 'index'])->middleware('admin')->name('index.update.user');
Route::get('/users/create', [UserController::class, 'index'])->middleware('admin')->name('users.load.create');
Route::get('/loadUsers', [DisplayUserController::class, 'index'])->middleware('admin')->name('loadUsers');

// Safe in DB - Routes
Route::post('/dashboard/vacation', [DashboardController::class, 'storeVac'])->middleware(['auth', 'verified'])->name('dashboard-vacation');
Route::post('/dashboard/sickness', [DashboardController::class, 'storeSick'])->middleware(['auth', 'verified'])->name('dashboard-sickness');
Route::post('/send-hours', [DashboardController::class, 'storeHours'])->middleware(['auth', 'verified'])->name('send-hours');
Route::post('/admin/storeVacation', [AdminController::class, 'storePastVac'])->middleware(['admin'])->name('dashboard-vacation-admin');
Route::post('/admin/storeSickness', [AdminController::class, 'storePastSick'])->middleware(['admin'])->name('dashboard-sickness-admin');
Route::post('/users/created', [UserController::class, 'create'])->middleware('admin')->name('users.create');

// Update Routes
Route::post('/update-hours', [EnterHoursController::class, 'updateHoursAsAdmin'])->middleware('admin')->name('update.hours');
Route::post('/admin/{id}/submitAnswer', [AdminController::class, 'updateVacationAnswer'])->middleware('admin')->name('update.vacationAnswer');
Route::post('/admin/{id}/submitAnswer2', [AdminController::class, 'updateSicknessAnswer'])->middleware('admin')->name('update.sicknessAnswer');
Route::get('/update-table', [EnterHoursController::class, 'updateTable'])->middleware('admin')->name('update.table');
Route::post('/admin/user/updated/{id}', [UpdateUserController::class, 'update'])->middleware('admin')->name('update.user');

// Delete Routes
Route::delete('/admin/user/deleted/{id}', [UpdateUserController::class, 'destroy'])->middleware('admin')->name('delete.user');
Route::delete('/admin/user/vacation/{id}', [UpdateUserController::class, 'destroyVacationRequest'])->middleware('admin')->name('delete.vacationRequest');
Route::delete('/dashboard/vacation/{id}', [DashboardController::class, 'destroyVacationRequest'])->middleware('admin')->name('delete.vacationRequest.user');
Route::delete('/dashboard/sickness/{id}', [DashboardController::class, 'destroySicknessRequest'])->middleware('admin')->name('delete.sicknessRequest.user');
Route::delete('/admin/user/sickness/{id}', [UpdateUserController::class, 'destroySicknessRequest'])->middleware('admin')->name('delete.sicknessRequest');
Route::post('/delete-file/{fileHistory}', [UpdateUserController::class, 'deleteFile'])->name('delete.file');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile', [ProfileController::class, 'store'])->name('profile.store');
});

require __DIR__ . '/auth.php';
