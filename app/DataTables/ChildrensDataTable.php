<?php

namespace App\DataTables;

use App\Models\Childrens;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\DB;

class ChildrensDataTable extends DataTable
{
    protected string $exportClass = 'App\Exports\ChildrensExport';

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('birthdate', function ($row) {
                return optional($row->birthdate)->format('d/m/Y');
            })
            ->addColumn('edad', function ($row) {
                return $row->birthdate ? $row->birthdate->diffInYears(now()) : null;
            })
            ->addColumn('action', 'childrens.action');
    }

    public function query()
    {
        $query = Childrens::query()
            ->select('childrens.id', DB::raw('UPPER(childrens.name) as name'), DB::raw('UPPER(childrens.lastname) as lastname'),DB::raw('UPPER(CONCAT(users.name, " ", users.lastname)) as responsible_fullname'), 'users.phone as responsiblephone', 'childrens.birthdate')
            ->leftJoin('childrens_responsible', 'childrens.id', '=', 'childrens_responsible.children_id')
            ->leftJoin('users', 'childrens_responsible.user_id', '=', 'users.id')
            ->groupBy('childrens.id', 'childrens.name', 'childrens.lastname', 'users.name', 'users.lastname', 'users.phone', 'childrens.birthdate');

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

        if (!empty(request('filter_antiquity')) && is_array(request('filter_antiquity'))) {
            $years = request('filter_antiquity');
            \Log::info('filter_antiquity:', $years);

            // Filtrar por los años seleccionados
            $query->whereIn('childrens_antiquities.year', $years)
                ->havingRaw('COUNT(DISTINCT childrens_antiquities.year) = ?', [count($years)])
                ->join('childrens_antiquities', 'childrens.id', '=', 'childrens_antiquities.children_id');
        }

        //\Log::info('Consulta SQL:', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);

        return $query;
    }


    public function html()
    {
        return $this->builder()
            ->setTableId('childrens-table')
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
                                filter_antiquity: $("select[name=\'filter_antiquity[]\']").val() || [],
                            };

                            var url = "' . route('childrens.export.excel') . '?" + $.param(filters);
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
                'data' => 'birthdate',
                'title' => 'Fecha de Nacimiento',
            ],
            [
                'data' => 'edad',
                'title' => 'Edad',
            ],
        ];
    }

}
