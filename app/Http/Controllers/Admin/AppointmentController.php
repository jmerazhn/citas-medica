<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AppointmentController extends Controller
{
    public function index()
    {
        return view('admin.appointments.index');
    }

    public function create()
    {
        $doctors = User::role('Doctor')->orderBy('name')->get();
        $patients = Patient::with('user')->orderBy('id')->get();

        return view('admin.appointments.create', compact('doctors', 'patients'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id'  => 'required|exists:users,id',
            'date'       => 'required|date|after_or_equal:today',
            'time'       => 'required|date_format:H:i',
            'reason'     => 'required|string|max:255',
            'notes'      => 'nullable|string',
        ]);

        $scheduledAt = $data['date'] . ' ' . $data['time'] . ':00';

        Appointment::create([
            'patient_id'   => $data['patient_id'],
            'doctor_id'    => $data['doctor_id'],
            'scheduled_at' => $scheduledAt,
            'duration'     => config('appointment.duration', 30),
            'reason'       => $data['reason'],
            'notes'        => $data['notes'] ?? null,
        ]);

        Session::flash('swal', [
            'icon'  => 'success',
            'title' => 'Cita creada',
            'text'  => 'La cita se ha registrado correctamente.',
        ]);

        return redirect()->route('admin.appointments.index');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient.user', 'doctor']);

        return view('admin.appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $appointment->load(['patient.user', 'doctor']);
        $doctors = User::role('Doctor')->orderBy('name')->get();
        $patients = Patient::with('user')->orderBy('id')->get();

        return view('admin.appointments.edit', compact('appointment', 'doctors', 'patients'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id'  => 'required|exists:users,id',
            'date'       => 'required|date',
            'time'       => 'required|date_format:H:i',
            'reason'     => 'required|string|max:255',
            'notes'      => 'nullable|string',
            'status'     => 'sometimes|in:pending,confirmed,completed,cancelled',
        ]);

        $scheduledAt = $data['date'] . ' ' . $data['time'] . ':00';

        $appointment->update([
            'patient_id'   => $data['patient_id'],
            'doctor_id'    => $data['doctor_id'],
            'scheduled_at' => $scheduledAt,
            'reason'       => $data['reason'],
            'notes'        => $data['notes'] ?? null,
            'status'       => $data['status'] ?? $appointment->status,
        ]);

        Session::flash('swal', [
            'icon'  => 'success',
            'title' => 'Cita actualizada',
            'text'  => 'Los datos de la cita se han actualizado correctamente.',
        ]);

        return redirect()->route('admin.appointments.show', $appointment);
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        Session::flash('swal', [
            'icon'  => 'success',
            'title' => 'Cita eliminada',
            'text'  => 'La cita se ha eliminado correctamente.',
        ]);

        return redirect()->route('admin.appointments.index');
    }

    public function confirm(Appointment $appointment)
    {
        $appointment->update(['status' => 'confirmed']);

        Session::flash('swal', [
            'icon'  => 'success',
            'title' => 'Cita confirmada',
            'text'  => 'La cita ha sido confirmada.',
        ]);

        return redirect()->route('admin.appointments.show', $appointment);
    }

    public function complete(Appointment $appointment)
    {
        $appointment->update(['status' => 'completed']);

        Session::flash('swal', [
            'icon'  => 'success',
            'title' => 'Cita completada',
            'text'  => 'La cita ha sido marcada como completada.',
        ]);

        return redirect()->route('admin.appointments.show', $appointment);
    }

    public function cancel(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'cancelled_reason' => 'required|string|max:255',
        ]);

        $appointment->update([
            'status'           => 'cancelled',
            'cancelled_reason' => $data['cancelled_reason'],
            'cancelled_at'     => now(),
        ]);

        Session::flash('swal', [
            'icon'  => 'warning',
            'title' => 'Cita cancelada',
            'text'  => 'La cita ha sido cancelada.',
        ]);

        return redirect()->route('admin.appointments.show', $appointment);
    }
}
