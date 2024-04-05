<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AssistantController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});
/*
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';



//route::get('admin/users',[AdminController::class,'users'])->middleware(['auth','admin',])->name('users');
/*
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::resource('users', 'AdminUsersController');
});*/

// ...
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::get('/users/edit/{id}', [AdminController::class, 'editUser'])->name('user.edit');
    Route::patch('/users/update/{id}', [AdminController::class, 'updateUser'])->name('user.update');
    Route::delete('/users/delete/{id}', [AdminController::class, 'destroyUser'])->name('user.destroy');
    Route::get('/new_dates', [AdminController::class, 'showNewDatesPage'])->name('new-dates');
    Route::get('/riports', [AdminController::class, 'showRiportsPage'])->name('riports');
});


Route::middleware(['auth','doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/riports', [DoctorController::class, 'showRiportsPage'])->name('riports');
    Route::get('/new_dates', [DoctorController::class, 'showNewDatesPage'])->name('new-dates');
});

Route::middleware(['auth','assistant'])->prefix('assistant')->name('assistant.')->group(function () {
    Route::get('/riports', [AssistantController::class, 'showRiportsPage'])->name('riports');
    Route::get('/new_dates', [AssistantController::class, 'showNewDatesPage'])->name('new-dates');
});

Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/own_dates', [UserController::class, 'showOwnDatesPage'])->name('own-dates');
    Route::get('/date_reservation', [UserController::class, 'showDateReservationPage'])->name('date-reservation');
});


// ...
//route::get('admin/users',[AdminController::class,'users'])->middleware(['auth','admin'])->name('users');

/*
route::get('doctor/dashboard',[DoctorController::class,'index'])->middleware(['auth','doctor']);
route::get('assistant/dashboard',[AssistantController::class,'index'])->middleware(['auth','assistant']);
*/