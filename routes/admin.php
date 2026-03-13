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
use App\Http\Controllers\Admin\Expediente\ConsultaController;
use App\Http\Controllers\Admin\Expediente\VacunaController;
use App\Http\Controllers\Admin\Expediente\PatologiaPacienteController;
use App\Http\Controllers\Admin\Expediente\EmbarazoController;
use App\Http\Controllers\Admin\Expediente\PartoController;
use Illuminate\Support\Facades\Route;

// Dashboard
Route::get('/', function () {
    return view('admin.dashboard');
})->name('dashboard');

// Gestión
Route::resource('roles', RoleController::class);
Route::resource('users', UserController::class);
Route::resource('patients', PatientController::class);

// Expediente del paciente (nested, shallow)
Route::resource('patients.consultas', ConsultaController::class)
    ->shallow()->except(['index', 'show']);
Route::resource('patients.vacunas', VacunaController::class)
    ->shallow()->except(['index', 'show']);
Route::resource('patients.patologias', PatologiaPacienteController::class)
    ->shallow()->except(['index', 'show'])
    ->parameters(['patologias' => 'patientPatologia']);
Route::resource('patients.embarazos', EmbarazoController::class)
    ->shallow()->except(['index', 'show']);
Route::resource('patients.partos', PartoController::class)
    ->shallow()->except(['index', 'show']);

// Citas
Route::resource('appointments', AppointmentController::class);
Route::get('appointments/{appointment}/atencion/create', [AtencionController::class, 'create'])->name('appointments.atencion.create');
Route::post('appointments/{appointment}/atencion', [AtencionController::class, 'store'])->name('appointments.atencion.store');
Route::get('atenciones/{atencion}/edit', [AtencionController::class, 'edit'])->name('atenciones.edit');
Route::put('atenciones/{atencion}', [AtencionController::class, 'update'])->name('atenciones.update');
Route::patch('appointments/{appointment}/confirm', [AppointmentController::class, 'confirm'])->name('appointments.confirm');
Route::patch('appointments/{appointment}/complete', [AppointmentController::class, 'complete'])->name('appointments.complete');
Route::patch('appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');

Route::get('doctors/{user}/schedules', [DoctorScheduleController::class, 'index'])->name('doctors.schedules.index');
Route::put('doctors/{user}/schedules', [DoctorScheduleController::class, 'update'])->name('doctors.schedules.update');

// Catálogos
Route::prefix('catalogos')->name('catalogos.')->group(function () {
    Route::resource('motivos-consulta', MotivoConsultaController::class)
        ->parameters(['motivos-consulta' => 'motivoConsulta']);
    Route::resource('planes-vacunacion', PlanVacunacionController::class)
        ->parameters(['planes-vacunacion' => 'planVacunacion']);
    Route::resource('patologias', PatologiaCatalogoController::class)
        ->parameters(['patologias' => 'patologia']);
    Route::resource('coberturas-sociales', SocialCoverageController::class)
        ->parameters(['coberturas-sociales' => 'socialCoverage']);
    Route::resource('tablas-crecimiento', TablaCrecimientoController::class)
        ->parameters(['tablas-crecimiento' => 'tablaCrecimiento']);
});
