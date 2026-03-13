<?php

namespace App\Livewire\Admin;

use App\Models\Appointment;
use Carbon\Carbon;
use Livewire\Component;

class AppointmentCalendar extends Component
{
    public string $calendarView = 'list';
    public string $currentDate  = '';

    public function mount(): void
    {
        $this->currentDate = now()->format('Y-m-d');
    }

    public function setView(string $view): void
    {
        $this->calendarView = $view;
    }

    public function previous(): void
    {
        $date = Carbon::parse($this->currentDate);
        $this->currentDate = match ($this->calendarView) {
            'daily'   => $date->subDay()->format('Y-m-d'),
            'weekly'  => $date->subWeek()->format('Y-m-d'),
            'monthly' => $date->subMonth()->startOfMonth()->format('Y-m-d'),
            default   => $this->currentDate,
        };
    }

    public function next(): void
    {
        $date = Carbon::parse($this->currentDate);
        $this->currentDate = match ($this->calendarView) {
            'daily'   => $date->addDay()->format('Y-m-d'),
            'weekly'  => $date->addWeek()->format('Y-m-d'),
            'monthly' => $date->addMonth()->startOfMonth()->format('Y-m-d'),
            default   => $this->currentDate,
        };
    }

    public function today(): void
    {
        $this->currentDate = now()->format('Y-m-d');
    }

    public function goToDay(string $date): void
    {
        $this->currentDate  = $date;
        $this->calendarView = 'daily';
    }

    public function render()
    {
        $date  = Carbon::parse($this->currentDate);
        $today = now()->toDateString();
        $data  = compact('date', 'today');

        if ($this->calendarView === 'daily') {
            $data['appointments'] = Appointment::with(['patient', 'doctor', 'motivoConsulta'])
                ->whereDate('scheduled_at', $date)
                ->orderBy('scheduled_at')
                ->get();

            $data['periodLabel'] = ucfirst($date->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY'));

        } elseif ($this->calendarView === 'weekly') {
            $weekStart = $date->copy()->startOfWeek(Carbon::MONDAY);
            $weekEnd   = $date->copy()->endOfWeek(Carbon::SUNDAY);

            $appointments = Appointment::with(['patient', 'doctor'])
                ->whereBetween('scheduled_at', [$weekStart->copy()->startOfDay(), $weekEnd->copy()->endOfDay()])
                ->orderBy('scheduled_at')
                ->get();

            $days = [];
            $d    = $weekStart->copy();
            for ($i = 0; $i < 7; $i++) {
                $days[] = $d->copy();
                $d->addDay();
            }

            $data += [
                'weekStart'    => $weekStart,
                'weekEnd'      => $weekEnd,
                'days'         => $days,
                'appointments' => $appointments,
                'byDate'       => $appointments->groupBy(fn($a) => $a->scheduled_at->format('Y-m-d')),
                'periodLabel'  => ucfirst($weekStart->locale('es')->isoFormat('D MMM'))
                                  . ' — '
                                  . ucfirst($weekEnd->locale('es')->isoFormat('D MMM YYYY')),
            ];

        } elseif ($this->calendarView === 'monthly') {
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd   = $date->copy()->endOfMonth();

            $appointments = Appointment::with(['patient', 'doctor'])
                ->whereBetween('scheduled_at', [$monthStart->copy()->startOfDay(), $monthEnd->copy()->endOfDay()])
                ->orderBy('scheduled_at')
                ->get();

            $weeks = [];
            $d     = $monthStart->copy()->startOfWeek(Carbon::MONDAY);
            $end   = $monthEnd->copy()->endOfWeek(Carbon::SUNDAY);
            while ($d->lte($end)) {
                $week = [];
                for ($i = 0; $i < 7; $i++) {
                    $week[] = $d->copy();
                    $d->addDay();
                }
                $weeks[] = $week;
            }

            $data += [
                'monthStart'   => $monthStart,
                'weeks'        => $weeks,
                'appointments' => $appointments,
                'byDate'       => $appointments->groupBy(fn($a) => $a->scheduled_at->format('Y-m-d')),
                'periodLabel'  => ucfirst($date->locale('es')->isoFormat('MMMM YYYY')),
            ];
        }

        return view('livewire.admin.appointment-calendar', $data);
    }
}
