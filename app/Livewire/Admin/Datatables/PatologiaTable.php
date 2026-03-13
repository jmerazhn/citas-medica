<?php

namespace App\Livewire\Admin\Datatables;

use App\Models\Patologia;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PatologiaTable extends DataTableComponent
{
    protected $model = Patologia::class;

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
                fn($row) => view('admin.catalogos.patologias.actions', ['patologia' => $row])
            ),
        ];
    }
}
