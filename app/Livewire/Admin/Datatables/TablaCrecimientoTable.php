<?php

namespace App\Livewire\Admin\Datatables;

use App\Models\TablaCrecimiento;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class TablaCrecimientoTable extends DataTableComponent
{
    protected $model = TablaCrecimiento::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Tipo')
                ->options(['' => 'Todos'] + array_combine(
                    array_keys(TablaCrecimiento::$tipos),
                    TablaCrecimiento::$tipos
                ))
                ->filter(function ($query, $value) {
                    if ($value) $query->where('tipo', $value);
                }),
            SelectFilter::make('Sexo')
                ->options(['' => 'Todos', 'M' => 'Masculino', 'F' => 'Femenino'])
                ->filter(function ($query, $value) {
                    if ($value) $query->where('sexo', $value);
                }),
        ];
    }

    public function columns(): array
    {
        return [
            Column::make('Tipo', 'tipo')->sortable()->searchable()
                ->format(fn($v) => TablaCrecimiento::$tipos[$v] ?? $v),
            Column::make('Sexo', 'sexo')->sortable()
                ->format(fn($v) => $v === 'M' ? 'Masculino' : 'Femenino'),
            Column::make('Edad (meses)', 'edad_meses')->sortable(),
            Column::make('P3', 'p3'),
            Column::make('P50', 'p50'),
            Column::make('P97', 'p97'),
            Column::make('Acciones')->label(
                fn($row) => view('admin.catalogos.tablas-crecimiento.actions', ['tablaCrecimiento' => $row])
            ),
        ];
    }
}
