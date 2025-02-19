<?php
namespace App\Imports;

use App\Models\User;
use App\Models\Godfather;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class GodFathersImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        set_time_limit(300);

        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // Omitimos la cabecera del Excel

            $user_id = $row[0];
            $father1 = $this->cleanAndNormalize($row[20],true) ?? null;
            $father2 = $this->cleanAndNormalize($row[21]) ?? null;

            if(!empty($father1 && $father2)) {

                Log::info("Usuario ID {$user_id}, Padrinos: {$father1} {$father2} ");

                $user = User::find($user_id);

                if (!$user) {
                    Log::warning("Usuario con ID {$user_id} no encontrado.");
                    continue;
                }

                Godfather::create([
                    'user_new' => $user_id,
                    'user_godfather_1' => $father1,
                    'user_godfather_2' => $father2,
                    'year_godfather' => 2024
                ]);

                Log::info("Nuevos padrinos creados para el usuario ID {$user_id}");

            }

        }
    }


   /**
     * Limpia y normaliza valores eliminando saltos de línea, caracteres invisibles,
     * corrige formatos de dirección y decodifica caracteres Unicode.
     */
    private function cleanAndNormalize($value, $isVia = false)
    {
        if (!$value) return null;

        // Eliminar saltos de línea y caracteres invisibles
        $value = preg_replace('/[\r\n\x0B\x0C\x0D]+/', ' ', trim($value));

        // Decodificar caracteres Unicode como "º" y "°"
        $decodedValue = json_decode('"' . $value . '"');
        if ($decodedValue !== null) {
            $value = $decodedValue;
        }

        // Si es un campo 'via', normalizamos su formato
        if ($isVia) {
            $value = str_replace(['C\/', 'C\\'], 'C/', $value);
        }

        return trim($value);
    }

}


