<?php

namespace App\DataTables;

use App\Models\Childrens_penalty;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\DB;

class ChildrensPenaltyDataTable extends DataTable
{
    protected string $exportClass = 'App\Exports\ChildrensExport';

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('date', function ($row) {
                return $row->date ? date('d/m/Y H:i', strtotime($row->date)) : '';
            });
    }

    public function query()
    {

        $query = Childrens_penalty::query()
            ->select('childrens.id', DB::raw('UPPER(childrens.name) as name'), DB::raw('UPPER(childrens.lastname) as lastname'),DB::raw('UPPER(CONCAT(users.name, " ", users.lastname)) as responsible_fullname'), 'users.phone as responsiblephone', 'penalties.name as penalizacion', 'childrens_penalties.date_penality as date')
            ->leftJoin('childrens', 'childrens_penalties.id_user', '=', 'childrens.id')
            ->leftJoin('childrens_responsible', 'childrens.id', '=', 'childrens_responsible.children_id')
            ->leftJoin('users', 'childrens_responsible.user_id', '=', 'users.id')
            ->leftJoin('penalties', 'childrens_penalties.id_penalty', '=', 'penalties.id')
            ->groupBy('childrens.id', 'childrens.name', 'childrens.lastname', 'users.name', 'users.lastname', 'users.phone', 'penalties.name', 'childrens_penalties.date_penality');
        if (!empty(request('filter_name'))) {
            $query->where('childrens.name', 'like', '%' . request('filter_name') . '%');
        }

        if (!empty(request('filter_lastname'))) {
            $query->where('childrens.lastname', 'like', '%' . request('filter_lastname') . '%');
        }

        if (!empty(request('filter_responsible'))) {
            $query->where('users.name', 'like', '%' . request('filter_responsible') . '%')
                ->orWhere('users.lastname', 'like', '%' . request('filter_responsible') . '%');
        }

        if (!empty(request('filter_responsible_phone'))) {
            $query->where('users.phone', 'like', '%' . request('filter_responsible_phone') . '%');
        }

        if (!empty(request('filter_penalty')) && is_array(request('filter_penalty'))) {
            $years = request('filter_penalty');

            $query->whereIn(
                \DB::raw('YEAR(childrens_penalties.date_penality)'),
                $years
            );
        }

        \Log::info('Consulta SQL:', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);

        return $query;
    }


    public function html()
    {
        return $this->builder()
            ->setTableId('childrens-penalty-table')
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
                    'search' => "Buscar:",
                    'paginate' => [
                        'first' => "Primero",
                        'last' => "Último",
                        'next' => "Siguiente",
                        'previous' => "Anterior",
                    ],
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
                                filter_responsible: $("input[name=\'filter_responsible\']").val(),
                                filter_responsible_phone: $("input[name=\'filter_responsible_phone\']").val(),
                                filter_penalty: $("select[name=\'filter_penalty[]\']").val() || [],
                            };

                            var url = "' . route('niños.penalities.export.excel') . '?" + $.param(filters);
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
                'data' => 'responsible_fullname',
                'title' => 'Responsable',
            ],
            [
                'data' => 'responsiblephone',
                'title' => 'Teléfono Responsable',
            ],
            [
                'data' => 'penalizacion',
                'title' => 'Tipo de penalización',
            ],
            [
                'data' => 'date',
                'title' => 'Fecha de Penalización',
            ],
        ];
    }

}
