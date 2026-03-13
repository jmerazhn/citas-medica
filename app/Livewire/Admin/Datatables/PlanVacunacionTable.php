<?php

namespace App\Livewire\Admin\Datatables;

use App\Models\PlanVacunacion;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PlanVacunacionTable extends DataTableComponent
{
    protected $model = PlanVacunacion::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->sortable(),
            Column::make('Nombre', 'nombre')->sortable()->searchable(),
            Column::make('Descripción', 'descripcion')->searchable(),
            Column::make('Acciones')->label(
                fn($row) => view('admin.catalogos.planes-vacunacion.actions', ['planVacunacion' => $row])
            ),
        ];
    }
}
