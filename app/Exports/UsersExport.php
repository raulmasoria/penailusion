<?php
namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\DB;

class UsersExport implements FromQuery
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
            ->select('users.id', DB::raw('UPPER(users.name) as name'), DB::raw('UPPER(users.lastname) as lastname'), 'users.phone', 'users.email')
            ->groupBy('users.id', 'users.name', 'users.lastname', 'users.phone', 'users.email');

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
            $query->whereIn('antiquities.year', $this->filters['filter_antiquity'])
                ->havingRaw('COUNT(DISTINCT antiquities.year) = ?', [count($this->filters['filter_antiquity'])])
                ->join('antiquities', 'users.id', '=', 'antiquities.user_id');
        }

        if (!empty($this->filters['filter_permanence']) && is_array($this->filters['filter_permanence'])) {
            $query->whereIn('permanences.year_permanence', $this->filters['filter_permanence'])
                ->havingRaw('COUNT(DISTINCT permanences.year_permanence) = ?', [count($this->filters['filter_permanence'])])
                ->join('permanences', 'users.id', '=', 'permanences.user_id');
        }

        return $query;
    }

}


