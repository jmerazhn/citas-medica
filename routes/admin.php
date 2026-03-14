<?php

use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\DoctorScheduleController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\Catalogos\MotivoConsultaController;
use App\Http\Controllers\Admin\Catalogos\PlanVacunacionController;
use App\Http\Controllers\Admin\Catalogos\PatologiaCatalogoController;
use App\Http\Controllers\Admin\Catalogos\TablaCrecimientoController;
use App\Http\Controllers\Admin\Catalogos\SocialCoverageController;
use App\Http\Controllers\Admin\Expediente\AtencionController;
use App\Http\Controllers\Admin\Expediente\VacunaController;
use App\Http\Controllers\Admin\Expediente\PatologiaPacienteController;
use App\Http\Controllers\Admin\Expediente\EmbarazoController;
use App\Http\Controllers\Admin\Expediente\PartoController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

// Dashboard — todos los usuarios autenticados
Route::get('/', DashboardController::class)->name('dashboard');

// Roles — solo administradores
Route::middleware('permission:gestionar-roles')->group(function () {
    Route::resource('roles', RoleController::class)->except(['index', 'show']);
});
Route::middleware('permission:ver-roles')->group(function () {
    Route::resource('roles', RoleController::class)->only(['index', 'show']);
});

// Usuarios
Route::middleware('permission:gestionar-usuarios')->group(function () {
    Route::resource('users', UserController::class)->except(['index', 'show']);
});
Route::middleware('permission:ver-usuarios')->group(function () {
    Route::resource('users', UserController::class)->only(['index', 'show']);
});

// Pacientes
Route::middleware('permission:gestionar-pacientes')->group(function () {
    Route::resource('patients', PatientController::class)->except(['index', 'show']);
});
Route::middleware('permission:ver-pacientes')->group(function () {
    Route::resource('patients', PatientController::class)->only(['index', 'show']);
});

// Expediente del paciente (nested, shallow)
Route::middleware('permission:gestionar-expediente')->group(function () {
    Route::resource('patients.vacunas', VacunaController::class)
        ->shallow()->except(['index', 'show']);
    Route::resource('patients.patologias', PatologiaPacienteController::class)
        ->shallow()->except(['index', 'show'])
        ->parameters(['patologias' => 'patientPatologia']);
    Route::resource('patients.embarazos', EmbarazoController::class)
        ->shallow()->except(['index', 'show']);
    Route::resource('patients.partos', PartoController::class)
        ->shallow()->except(['index', 'show']);
});

// Citas — gestionar (crear/editar) — debe ir antes para que /create no sea capturado por {appointment}
Route::middleware('permission:gestionar-citas')->group(function () {
    Route::resource('appointments', AppointmentController::class)->except(['index', 'show', 'destroy']);
});
// Citas — ver
Route::middleware('permission:ver-citas')->group(function () {
    Route::resource('appointments', AppointmentController::class)->only(['index', 'show']);
});
// Citas — acciones de estado
Route::middleware('permission:confirmar-citas')->group(function () {
    Route::patch('appointments/{appointment}/confirm', [AppointmentController::class, 'confirm'])->name('appointments.confirm');
});
Route::middleware('permission:completar-citas')->group(function () {
    Route::patch('appointments/{appointment}/complete', [AppointmentController::class, 'complete'])->name('appointments.complete');
    Route::get('appointments/{appointment}/atencion/create', [AtencionController::class, 'create'])->name('appointments.atencion.create');
    Route::post('appointments/{appointment}/atencion', [AtencionController::class, 'store'])->name('appointments.atencion.store');
    Route::get('atenciones/{atencion}/edit', [AtencionController::class, 'edit'])->name('atenciones.edit');
    Route::put('atenciones/{atencion}', [AtencionController::class, 'update'])->name('atenciones.update');
});
Route::middleware('permission:cancelar-citas')->group(function () {
    Route::patch('appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
});
// Citas — eliminar (solo administradores vía gestionar-citas + cancelar)
Route::middleware('permission:gestionar-citas')->group(function () {
    Route::resource('appointments', AppointmentController::class)->only(['destroy']);
});

// Horarios de doctores
Route::middleware('permission:gestionar-horarios')->group(function () {
    Route::get('doctors/{user}/schedules', [DoctorScheduleController::class, 'index'])->name('doctors.schedules.index');
    Route::put('doctors/{user}/schedules', [DoctorScheduleController::class, 'update'])->name('doctors.schedules.update');
});

// Catálogos — gestionar primero para que /create no sea capturado por {model}
Route::middleware('permission:gestionar-catalogos')->prefix('catalogos')->name('catalogos.')->group(function () {
    Route::resource('motivos-consulta', MotivoConsultaController::class)
        ->parameters(['motivos-consulta' => 'motivoConsulta'])->except(['index', 'show']);
    Route::resource('planes-vacunacion', PlanVacunacionController::class)
        ->parameters(['planes-vacunacion' => 'planVacunacion'])->except(['index', 'show']);
    Route::resource('patologias', PatologiaCatalogoController::class)
        ->parameters(['patologias' => 'patologia'])->except(['index', 'show']);
    Route::resource('coberturas-sociales', SocialCoverageController::class)
        ->parameters(['coberturas-sociales' => 'socialCoverage'])->except(['index', 'show']);
    Route::resource('tablas-crecimiento', TablaCrecimientoController::class)
        ->parameters(['tablas-crecimiento' => 'tablaCrecimiento'])->except(['index', 'show']);
});
Route::middleware('permission:ver-catalogos')->prefix('catalogos')->name('catalogos.')->group(function () {
    Route::resource('motivos-consulta', MotivoConsultaController::class)
        ->parameters(['motivos-consulta' => 'motivoConsulta'])->only(['index', 'show']);
    Route::resource('planes-vacunacion', PlanVacunacionController::class)
        ->parameters(['planes-vacunacion' => 'planVacunacion'])->only(['index', 'show']);
    Route::resource('patologias', PatologiaCatalogoController::class)
        ->parameters(['patologias' => 'patologia'])->only(['index', 'show']);
    Route::resource('coberturas-sociales', SocialCoverageController::class)
        ->parameters(['coberturas-sociales' => 'socialCoverage'])->only(['index', 'show']);
    Route::resource('tablas-crecimiento', TablaCrecimientoController::class)
        ->parameters(['tablas-crecimiento' => 'tablaCrecimiento'])->only(['index', 'show']);
});
