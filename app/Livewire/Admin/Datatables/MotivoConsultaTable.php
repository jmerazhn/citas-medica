<?php

namespace App\Livewire\Admin\Datatables;

use App\Models\MotivoConsulta;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class MotivoConsultaTable extends DataTableComponent
{
    protected $model = MotivoConsulta::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->sortable(),
            Column::make('Nombre', 'nombre')->sortable()->searchable(),
            Column::make('Acciones')->label(
                fn($row) => view('admin.catalogos.motivos-consulta.actions', ['motivoConsulta' => $row])
            ),
        ];
    }
}
