<?php

namespace App\Livewire\Admin\Datatables;

use App\Models\SocialCoverage;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class SocialCoverageTable extends DataTableComponent
{
    protected $model = SocialCoverage::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->sortable(),
            Column::make('Nombre', 'name')->sortable()->searchable(),
            Column::make('Acciones')->label(
                fn($row) => view('admin.catalogos.coberturas-sociales.actions', ['socialCoverage' => $row])
            ),
        ];
    }
}
