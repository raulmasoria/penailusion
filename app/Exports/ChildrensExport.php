<?php
namespace App\Exports;

use App\Models\Childrens;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMapping;


class ChildrensExport implements FromQuery, WithMapping
{
    use Exportable;

    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Childrens::query()
            ->select('childrens.id', DB::raw('UPPER(childrens.name) as name'), DB::raw('UPPER(childrens.lastname) as lastname'),DB::raw('UPPER(CONCAT(users.name, " ", users.lastname)) as responsible_fullname'), 'users.phone as responsiblephone', 'childrens.birthdate', DB::raw("TIMESTAMPDIFF(YEAR, childrens.birthdate, CURDATE()) as edad"))
            ->leftJoin('childrens_responsible', 'childrens.id', '=', 'childrens_responsible.children_id')
            ->leftJoin('users', 'childrens_responsible.user_id', '=', 'users.id')
            ->groupBy('childrens.id', 'childrens.name', 'childrens.lastname', 'users.name', 'users.lastname', 'users.phone', 'childrens.birthdate');

        if (!empty($this->filters['filter_name'])) {
            $query->where('childrens.name', 'like', '%' . $this->filters['filter_name'] . '%');
        }

        if (!empty($this->filters['filter_lastname'])) {
            $query->where('childrens.lastname', 'like', '%' . $this->filters['filter_lastname'] . '%');
        }
        if (!empty($this->filters['filter_responsible'])) {
            $query->where('users.name', 'like', '%' . $this->filters['filter_responsible'] . '%')
                ->orWhere('users.lastname', 'like', '%' . $this->filters['filter_responsible'] . '%');
        }
        if (!empty($this->filters['filter_responsible_phone'])) {
            $query->where('users.phone', 'like', '%' . $this->filters['filter_responsible_phone'] . '%');
        }

        if (!empty($this->filters['filter_antiquity']) && is_array($this->filters['filter_antiquity'])) {
            $years = $this->filters['filter_antiquity'];
            \Log::info('filter_antiquity export:', $years);

            // Filtrar por los años seleccionados
            $query->whereIn('childrens_antiquities.year', $years)
                ->havingRaw('COUNT(DISTINCT childrens_antiquities.year) = ?', [count($years)])
                ->join('childrens_antiquities', 'childrens.id', '=', 'childrens_antiquities.children_id');
        }

        \Log::info('Consulta SQL EXPORT:', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);

        return $query;
    }

    public function map($row): array
    {
        return [
            $row->name,
            $row->lastname,
            $row->responsible_fullname,
            $row->responsiblephone,
            optional($row->birthdate)->format('d/m/Y'),
            $row->edad,
        ];
    }

}


