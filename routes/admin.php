<?php

use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\DoctorScheduleController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function(){
    return view('admin.dashboard');
})->name('dashboard');

Route::resource('roles', RoleController::class);
Route::resource('users', UserController::class);
Route::resource('patients', PatientController::class);

Route::resource('appointments', AppointmentController::class);
Route::patch('appointments/{appointment}/confirm', [AppointmentController::class, 'confirm'])->name('appointments.confirm');
Route::patch('appointments/{appointment}/complete', [AppointmentController::class, 'complete'])->name('appointments.complete');
Route::patch('appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');

Route::get('doctors/{user}/schedules', [DoctorScheduleController::class, 'index'])->name('doctors.schedules.index');
Route::put('doctors/{user}/schedules', [DoctorScheduleController::class, 'update'])->name('doctors.schedules.update');