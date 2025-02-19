<?php
namespace App\Imports;

use App\Models\User;
use App\Models\Adress;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class AdressImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        set_time_limit(300);

        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // Omitimos la cabecera del Excel

            $user_id = $row[0];
            $via = $this->cleanAndNormalize($row[4],true) ?? null;
            $direccion = $this->cleanAndNormalize($row[5]) ?? null;
            $piso = $this->cleanAndNormalize($row[6]) ?? null;
            $cp = $this->cleanAndNormalize($row[7]) ?? null;
            $ciudad = $this->cleanAndNormalize($row[8]) ?? null;
            $provincia = $this->cleanAndNormalize($row[9]) ?? null;

            Log::info("Dirección Excel Usuario ID {$user_id}, Dirección: {$via} {$direccion} {$piso} {$cp} {$ciudad} {$provincia}");

            $user = User::find($user_id);

            if (!$user) {
                Log::warning("Usuario con ID {$user_id} no encontrado.");
                continue;
            }

            // Buscar la dirección correctamente
            $adress = Adress::where('user_id', $user_id)->first();

            if ($adress) {
                // Log para ver la dirección que se encontró
                Log::info("Dirección encontrada para el usuario ID {$user_id}");

                // Normalizamos los valores antes de comparar
                $via_db = $this->cleanAndNormalize($adress->via, true);
                $direccion_db = $this->cleanAndNormalize($adress->direccion);
                $piso_db = $this->cleanAndNormalize($adress->piso);
                $cp_db = $this->cleanAndNormalize($adress->cp);
                $ciudad_db = $this->cleanAndNormalize($adress->ciudad);
                $provincia_db = $this->cleanAndNormalize($adress->provincia);

                // Log para depuración
                Log::info("Comparando valores antes de actualizar para el usuario ID {$user_id}");
                Log::info("BD -> Via: {$via_db}, Dirección: {$direccion_db}, Piso: {$piso_db}, CP: {$cp_db}, Ciudad: {$ciudad_db}, Provincia: {$provincia_db}");
                Log::info("Excel -> Via: {$via}, Dirección: {$direccion}, Piso: {$piso}, CP: {$cp}, Ciudad: {$ciudad}, Provincia: {$provincia}");

                // Si los valores son diferentes, actualizamos
                if (
                    $via_db !== $via ||
                    $direccion_db !== $direccion ||
                    $piso_db !== $piso ||
                    $cp_db !== $cp ||
                    $ciudad_db !== $ciudad ||
                    $provincia_db !== $provincia
                ) {
                    Log::info("Actualizando dirección para el usuario ID {$user_id}");

                    try {
                        $adress->via = $via;
                        $adress->direccion = $direccion;
                        $adress->piso = $piso;
                        $adress->cp = $cp;
                        $adress->ciudad = $ciudad;
                        $adress->provincia = $provincia;

                        if ($adress->isDirty()) {
                            $adress->save();
                            Log::info("✅ Dirección guardada correctamente en la BD para el usuario ID {$user_id}");
                        } else {
                            Log::info("⚠ No se detectaron cambios en la dirección para el usuario ID {$user_id}");
                        }
                    } catch (\Exception $e) {
                        Log::error("❌ ERROR al actualizar dirección del usuario ID {$user_id}: " . $e->getMessage());
                    }

                } else {
                    Log::info("No hay cambios en la dirección para el usuario ID {$user_id}");
                }
            } else {
                Log::info("No se encontró dirección para el usuario ID {$user_id}, creando una nueva...");

                Adress::create([
                    'user_id' => $user_id,
                    'via' => $via,
                    'direccion' => $direccion,
                    'piso' => $piso,
                    'cp' => $cp,
                    'ciudad' => $ciudad,
                    'provincia' => $provincia
                ]);

                Log::info("Nueva dirección creada para el usuario ID {$user_id}");
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


