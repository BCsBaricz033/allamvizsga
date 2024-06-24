<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AssistantController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NewDatesController;
use App\Http\Controllers\RiportsController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/reserve_without_login', [UserController::class, 'showReservationPageWithoutLogin'])->name('reserve_without_login');
Route::get('reserve_without_login/sections', [RiportsController::class, 'getSections'])->name('reserve_without_login.sections');
Route::get('reserve_without_login/doctors', [RiportsController::class, 'getDoctors'])->name('reserve_without_login.doctors');
Route::get('/get_reserve_filtered_dates', [RiportsController::class, 'getFilteredDatesForReservation'])->name('get_reserve_filtered_dates');
Route::get('/reserve_without_login_form', [UserController::class, 'showReservationFormWithoutLogin'])->name('reserve_without_login.form');
Route::post('/reserve_without_login/reserve', [NewDatesController::class, 'reservationWithoutLogin'])->name('reserve_without_login.reserve');


Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/filterUsers', [AdminController::class, 'filterUsers'])->name('filterUsers');
    Route::post('/user/update', [AdminController::class, 'updateUser'])->name('user.update');
    Route::delete('/user/destroy', [AdminController::class, 'destroyUser'])->name('user.destroy');
    Route::delete('/date/destroy', [RiportsController::class, 'destroyDate'])->name('date.destroy');
    Route::get('/new_dates', [AdminController::class, 'showNewDatesPage'])->name('new_dates');
    Route::get('/sections', [RiportsController::class, 'getSections'])->name('sections');
    Route::get('/doctors', [RiportsController::class, 'getDoctors'])->name('doctors');
    Route::post('/insert_dates', [NewDatesController::class, 'insertDates'])->name('insert_dates');
    Route::get('/riports', [AdminController::class, 'showRiportsPage'])->name('riports');
    Route::get('/get_filtered_dates', [RiportsController::class, 'getFilteredDates'])->name('get_filtered_dates');
    Route::get('/patients', [RiportsController::class, 'getPatients'])->name('patients');

});


Route::middleware(['auth','doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/dashboard', [DoctorController::class, 'showRiportsPage'])->name('dashboard');
    Route::get('/riports/filter', [DoctorController::class, 'ownDatesFilter'])->name('riports.filter');
    Route::get('/new_dates', [DoctorController::class, 'showNewDatesPage'])->name('new-dates');
    Route::get('/doctors', [RiportsController::class, 'getDoctors'])->name('doctors');
    Route::get('/sections', [RiportsController::class, 'getSections'])->name('sections');
    Route::post('/insert_dates', [NewDatesController::class, 'insertDates'])->name('insert_dates');
    Route::get('/patients', [RiportsController::class, 'getPatients'])->name('patients');
    Route::get('/get_filtered_dates', [RiportsController::class, 'getFilteredDates'])->name('get_filtered_dates');
    Route::delete('/date/destroy', [RiportsController::class, 'destroyDate'])->name('date.destroy');

});

Route::middleware(['auth','assistant'])->prefix('assistant')->name('assistant.')->group(function () {
    Route::get('/dashboard', [AssistantController::class, 'showRiportsPage'])->name('dashboard');
    Route::get('/new_dates', [AssistantController::class, 'showNewDatesPage'])->name('new_dates');
    Route::get('/doctors', [RiportsController::class, 'getDoctors'])->name('doctors');
    Route::get('/sections', [RiportsController::class, 'getSections'])->name('sections');
    Route::post('/insert_dates', [NewDatesController::class, 'insertDates'])->name('insert_dates');
    Route::get('/riports/filter', [AssistantController::class, 'datesFilter'])->name('riports.filter');
    Route::get('/patients', [RiportsController::class, 'getPatients'])->name('patients');
    Route::get('/get_filtered_dates', [RiportsController::class, 'getFilteredDates'])->name('get_filtered_dates');
    Route::delete('/date/destroy', [RiportsController::class, 'destroyDate'])->name('date.destroy');
});

Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'showDashboard'])->name('dashboard');
    Route::get('/date_reservation', [UserController::class, 'showDateReservationPage'])->name('date_reservation');
    Route::get('/sections', [RiportsController::class, 'getSections'])->name('sections');
    Route::get('/doctors', [RiportsController::class, 'getDoctors'])->name('doctors');
    Route::get('/get_filtered_dates', [RiportsController::class, 'getFilteredDates'])->name('get_filtered_dates');
    Route::get('/get_reserve_filtered_dates', [RiportsController::class, 'getFilteredDatesForReservation'])->name('get_reserve_filtered_dates');
    Route::post('/reserve', [NewDatesController::class, 'reservation'])->name('reserve');
    Route::get('/reserve', [UserController::class, 'showReservationForm'])->name('reservation.form');
    Route::post('/date/cancel', [UserController::class, 'cancelDate'])->name('date.cancel');

});

require __DIR__.'/auth.php';
