<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Builder;

class PatientTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return Patient::query();
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
            Column::make('Nombres', 'nombres')
                ->sortable()
                ->searchable(),
            Column::make('Apellidos', 'apellidos')
                ->sortable()
                ->searchable(),
            Column::make('Teléfono', 'telefono')
                ->sortable()
                ->searchable(),
            Column::make('Ciudad', 'ciudad')
                ->sortable()
                ->searchable(),
            Column::make('Acciones')
                ->label(
                    fn ($row) => view('admin.patients.actions', ['patient' => $row])
                ),
        ];
    }
}
