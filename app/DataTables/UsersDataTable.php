<?php

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\DB;

class UsersDataTable extends DataTable
{
    protected string $exportClass = 'App\Exports\UsersExport';

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', 'users.action');
    }

    public function query()
    {
        $query = User::query()
            ->select('users.id', DB::raw('UPPER(users.name) as name'), DB::raw('UPPER(users.lastname) as lastname'), 'users.phone', 'users.email')
            ->groupBy('users.id', 'users.name', 'users.lastname', 'users.phone', 'users.email');

        if (!empty(request('filter_name'))) {
            $query->where('users.name', 'like', '%' . request('filter_name') . '%');
        }

        if (!empty(request('filter_lastname'))) {
            $query->where('users.lastname', 'like', '%' . request('filter_lastname') . '%');
        }

        if (!empty(request('filter_email'))) {
            $query->where('users.email', 'like', '%' . request('filter_email') . '%');
        }

        if (!empty(request('filter_phone'))) {
            $query->where('users.phone', 'like', '%' . request('filter_phone') . '%');
        }

        if (!empty(request('filter_antiquity')) && is_array(request('filter_antiquity'))) {
            $years = request('filter_antiquity');
            \Log::info('filter_antiquity:', $years);

            // Filtrar por los años seleccionados
            $query->whereIn('antiquities.year', $years)
                ->havingRaw('COUNT(DISTINCT antiquities.year) = ?', [count($years)])
                ->join('antiquities', 'users.id', '=', 'antiquities.user_id');
        }

        if (!empty(request('filter_permanence')) && is_array(request('filter_permanence'))) {
            $years = request('filter_permanence');
            \Log::info('filter_permanence:', $years);

            // Filtrar por los años seleccionados
            $query->whereIn('permanences.year_permanence', $years)
                ->havingRaw('COUNT(DISTINCT permanences.year_permanence) = ?', [count($years)])
                ->join('permanences', 'users.id', '=', 'permanences.user_id');
        }

        \Log::info('Consulta SQL:', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);

        return $query;
    }


    public function html()
    {
        return $this->builder()
            ->setTableId('users-table')
            ->columns($this->getColumns())
            ->pageLength(50)
            ->parameters([
                'lengthMenu' => [[10, 25, 50, 75, 100, -1], [10, 25, 50, 75, 100, 'Todos']],
                'language' => [
                    'lengthMenu' => "Mostrar _MENU_ registros por página",
                    'zeroRecords' => "No se encontraron resultados",
                    'info' => "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    'infoEmpty' => "No hay registros disponibles",
                    'infoFiltered' => "(filtrado de _MAX_ registros en total)",
                ],
                'dom' => 'Blfrtip',
                'buttons' => [
                    [
                        'extend' => 'excel',
                        'text' => 'Exportar a Excel',
                        'action' => 'function(e, dt, button, config) {
                            var filters = {
                                filter_name: $("input[name=\'filter_name\']").val(),
                                filter_lastname: $("input[name=\'filter_lastname\']").val(),
                                filter_email: $("input[name=\'filter_email\']").val(),
                                filter_phone: $("input[name=\'filter_phone\']").val(),
                                filter_antiquity : $("input[name=\'filter_antiquity\']").val(),
                            };
                            var url = "' . route('users.export.excel') . '?" + $.param(filters);
                            window.location.href = url;
                        }'
                    ],
                    [
                        'extend' => 'print',
                        'text' => 'Imprimir',
                    ],
                    [
                        'extend' => 'copy',
                        'text' => 'Copiar',
                    ]
                ]
            ])
            ->minifiedAjax();
    }


    protected function getColumns()
    {
        return [
            [
                'data' => 'name',
                'title' => 'Nombre',
            ],
            [
                'data' => 'lastname',
                'title' => 'Apellido',
            ],
            [
                'data' => 'email',
                'title' => 'Correo Electrónico',
            ],
            [
                'data' => 'phone',
                'title' => 'Teléfono',
            ],
        ];
    }

}
