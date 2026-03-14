<?php

namespace App\Livewire\Admin\Datatables;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class AppointmentTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return Appointment::query()
            ->with(['patient', 'doctor'])
            ->join('patients', 'patients.id', '=', 'appointments.patient_id')
            ->join('users', 'users.id', '=', 'appointments.doctor_id')
            ->select('appointments.*');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->sortable(),
            Column::make('Paciente', 'patient_id')
                ->format(fn ($value, $row) => $row->patient?->full_name)
                ->searchable(fn (Builder $query, string $search) =>
                    $query->where(fn ($q) =>
                        $q->whereRaw("CONCAT(patients.nombres, ' ', patients.apellidos) LIKE ?", ["%{$search}%"])
                          ->orWhereRaw("CONCAT(patients.apellidos, ' ', patients.nombres) LIKE ?", ["%{$search}%"])
                    )
                ),
            Column::make('Doctor', 'doctor.name')
                ->sortable()
                ->searchable(fn (Builder $query, string $search) =>
                    $query->orWhere('users.name', 'like', "%{$search}%")
                ),
            Column::make('Fecha / Hora', 'scheduled_at')
                ->sortable()
                ->format(fn ($value) => $value->format('d/m/Y H:i')),
            Column::make('Estado', 'status')
                ->sortable()
                ->format(fn ($value, $row) => view('admin.appointments.status-badge', ['status' => $value])),
            Column::make('Acciones')
                ->label(fn ($row) => view('admin.appointments.actions', ['appointment' => $row])),
        ];
    }
}
