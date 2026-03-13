<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DoctorSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DoctorScheduleController extends Controller
{
    public function index(User $user)
    {
        $schedules = $user->doctorSchedules()->orderBy('day_of_week')->get()->keyBy('day_of_week');

        $days = [
            0 => 'Domingo',
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
        ];

        return view('admin.doctors.schedules', compact('user', 'schedules', 'days'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'schedules'                    => 'nullable|array',
            'schedules.*.day_of_week'      => 'required|integer|between:0,6',
            'schedules.*.start_time'       => 'required|date_format:H:i,H:i:s',
            'schedules.*.end_time'         => 'required|date_format:H:i,H:i:s|after:schedules.*.start_time',
            'schedules.*.slot_duration'    => 'required|integer|min:5|max:180',
            'schedules.*.is_active'        => 'boolean',
        ]);

        DB::transaction(function () use ($user, $request) {
            $user->doctorSchedules()->delete();

            foreach ($request->input('schedules', []) as $scheduleData) {
                DoctorSchedule::create([
                    'user_id'       => $user->id,
                    'day_of_week'   => $scheduleData['day_of_week'],
                    'start_time'    => $scheduleData['start_time'],
                    'end_time'      => $scheduleData['end_time'],
                    'slot_duration' => $scheduleData['slot_duration'],
                    'is_active'     => isset($scheduleData['is_active']) ? (bool) $scheduleData['is_active'] : false,
                ]);
            }
        });

        Session::flash('swal', [
            'icon'  => 'success',
            'title' => 'Horarios guardados',
            'text'  => 'Los horarios del doctor se han actualizado correctamente.',
        ]);

        return redirect()->route('admin.doctors.schedules.index', $user);
    }
}
