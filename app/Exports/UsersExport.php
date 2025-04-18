<?php
namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class UsersExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = User::query()
            ->select('users.id', DB::raw('UPPER(users.name) as name'), DB::raw('UPPER(users.lastname) as lastname'), 'users.phone', 'users.email', 'users.RGPD')
            ->groupBy('users.id', 'users.name', 'users.lastname', 'users.phone', 'users.email', 'users.RGPD');


        \Log::info('filters export:', $this->filters );

        if (!empty($this->filters['filter_name'])) {
            $query->where('users.name', 'like', '%' . $this->filters['filter_name'] . '%');
        }

        if (!empty($this->filters['filter_lastname'])) {
            $query->where('users.lastname', 'like', '%' . $this->filters['filter_lastname'] . '%');
        }

        if (!empty($this->filters['filter_email'])) {
            $query->where('users.email', 'like', '%' . $this->filters['filter_email'] . '%');
        }

        if (!empty($this->filters['filter_phone'])) {
            $query->where('users.phone', 'like', '%' . $this->filters['filter_phone'] . '%');
        }

        if (!empty($this->filters['filter_antiquity']) && is_array($this->filters['filter_antiquity'])) {
            $years = $this->filters['filter_antiquity'];
            \Log::info('filter_antiquity:', $years);

            $query->whereIn('antiquities.year', $years)
                ->havingRaw('COUNT(DISTINCT antiquities.year) = ?', [count($years)])
                ->join('antiquities', 'users.id', '=', 'antiquities.user_id');
        }

        if (!empty($this->filters['filter_permanence']) && is_array($this->filters['filter_permanence'])) {
            $years = $this->filters['filter_permanence'];
            \Log::info('filter_permanence:', $years);

            $query->whereIn('permanences.year_permanence', $years)
                ->havingRaw('COUNT(DISTINCT permanences.year_permanence) = ?', [count($years)])
                ->join('permanences', 'users.id', '=', 'permanences.user_id');
        }

        \Log::info('Consulta SQL export:', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);

        return $query;
    }

    public function map($user): array
    {
        return [
            $user->name,
            $user->lastname,
            $user->email,
            $user->phone,
            $user->RGPD ? 'Sí' : 'No',
        ];
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Apellido',
            'Correo Electrónico',
            'Teléfono',
            'RGPD',
        ];
    }

}


