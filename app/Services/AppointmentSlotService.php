<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;

class AppointmentSlotService
{
    public function getAvailableSlots(User $doctor, Carbon $date): array
    {
        $dayOfWeek = $date->dayOfWeek; // 0=Domingo, 6=Sábado

        $schedule = $doctor->doctorSchedules()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->first();

        if (!$schedule) {
            return [];
        }

        $duration = $schedule->slot_duration ?? config('appointment.duration', 30);

        $start = Carbon::parse($date->format('Y-m-d') . ' ' . $schedule->start_time);
        $end = Carbon::parse($date->format('Y-m-d') . ' ' . $schedule->end_time);

        // Obtener citas existentes del doctor para ese día (excepto canceladas)
        $existingAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('status', '!=', 'cancelled')
            ->whereDate('scheduled_at', $date->format('Y-m-d'))
            ->get();

        $slots = [];
        $current = $start->copy();

        while ($current->copy()->addMinutes($duration)->lte($end)) {
            $slotEnd = $current->copy()->addMinutes($duration);

            $hasConflict = $existingAppointments->contains(function ($appointment) use ($current, $slotEnd) {
                $appointmentStart = $appointment->scheduled_at;
                $appointmentEnd = $appointment->end_time;

                return $current->lt($appointmentEnd) && $slotEnd->gt($appointmentStart);
            });

            if (!$hasConflict) {
                $slots[] = $current->format('H:i');
            }

            $current->addMinutes($duration);
        }

        return $slots;
    }
}
