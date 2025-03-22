<?php
namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Http\Controllers\YearHelperController;

class GodfatherExport implements FromQuery
{
    use Exportable;

    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
       // Definir años dinámicos
       $yearsAntiquity = [
            YearHelperController::lastYear(),
            YearHelperController::lastLastYear(),
            YearHelperController::lastLastLastYear(),
            YearHelperController::lastLastLastLastYear(),
        ];

        $yearsGodfather = [
            YearHelperController::lastYear(),
            YearHelperController::lastLastYear(),
        ];

        // Filtramos solo los usuarios que pueden apadrinar
        $query = User::selectRaw('users.id, UPPER(users.name) as name, UPPER(users.lastname) as lastname')
            ->leftJoin('antiquities', 'users.id', '=', 'antiquities.user_id')
            ->leftJoin('permanences', 'users.id', '=', 'permanences.user_id')
            ->leftJoin('godfathers as g1', 'users.id', '=', 'g1.user_godfather_1')
            ->leftJoin('godfathers as g2', 'users.id', '=', 'g2.user_godfather_2')
            // Asegurar que tiene antigüedad en los años especificados
            ->whereIn('antiquities.year', $yearsAntiquity)
            ->groupBy('users.id', 'users.name', 'users.lastname')
            ->havingRaw('COUNT(DISTINCT antiquities.year) = ?', [count($yearsAntiquity)])
            // Excluir si tiene permanencia en los mismos años de antigüedad
            ->whereNotExists(function($query) use ($yearsAntiquity) {
                $query->select(DB::raw(1))
                    ->from('permanences')
                    ->whereRaw('permanences.user_id = users.id')
                    ->whereIn('permanences.year_permanence', $yearsAntiquity);
            })
            // Excluir si ha sido padrino en los años especificados
            ->whereNotExists(function($query) use ($yearsGodfather) {
                $query->select(DB::raw(1))
                    ->from('godfathers')
                    ->whereRaw('godfathers.user_godfather_1 = users.id')
                    ->whereIn('godfathers.year_godfather', $yearsGodfather);
            })
            ->whereNotExists(function($query) use ($yearsGodfather) {
                $query->select(DB::raw(1))
                    ->from('godfathers')
                    ->whereRaw('godfathers.user_godfather_2 = users.id')
                    ->whereIn('godfathers.year_godfather', $yearsGodfather);
            });

        // Log de la consulta generada
        \Log::info('Consulta SQL exportar padrinos:', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);

        return $query;
    }

}


