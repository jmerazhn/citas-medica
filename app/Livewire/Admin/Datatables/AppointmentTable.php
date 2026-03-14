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
            ->with(['patient', 'doctor']);
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
                ->format(fn ($value, $row) => $row->patient?->full_name),
            Column::make('Doctor', 'doctor.name')
                ->sortable(),
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
