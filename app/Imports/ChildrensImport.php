<?php
namespace App\Imports;

use Carbon\Carbon;
use App\Models\Children;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;

class ChildrensImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        set_time_limit(300);
        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // Omitimos la cabecera del Excel

            $id = $row[0]; // Suponiendo que la columna 0 es el ID
            $name = $row[1]; // Suponiendo que la columna 1 es el
            $lastname = $row[2]; // Suponiendo que la columna 2 es el
            $raw_birthdate = $row[3]; // La fecha tal como viene del Excel
            if (is_numeric($raw_birthdate)) {
                // Si es un número, es una fecha en formato de Excel
                $birthdate = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($raw_birthdate))->format('Y-m-d');
            } else {
                // Si es texto, intenta convertirlo directamente
                $birthdate = Carbon::parse($raw_birthdate)->format('Y-m-d');
            }
            $responsible = $row[4]; // Suponiendo que la columna 4 es el
            $phone_responsible = $row[5]; // Suponiendo que la columna 5 es el

            Log::info("Usuario ID {$id} - Nombre: {$name} - Apellido: {$lastname} - Fecha de nacimiento: {$birthdate} - Responsable: {$responsible} - Teléfono responsable: {$phone_responsible}");

            $children = Children::find($id);

            Log::info("Children {$children} ");

            if ($children) {
                $children->name = $name ?? '';
                $children->lastname = $lastname ?? '';
                $children->birthdate = $birthdate ?? '';
                $children->responsible = $responsible ?? '';
                $children->phone_responsible = $phone_responsible ?? '';
                $children->updated_at = Carbon::now();

                // Intentar guardar directamente
                $children->save();
            }
             else {
                // Si el usuario no existe, lo creamos
                Children::create([
                    'name' => $this->cleanValue($row[1]) ?? '',
                    'lastname' => $this->cleanValue($row[2]) ?? '',
                    'birthdate' => $this->cleanValue($row[3]) ?? '',
                    'responsible' => $this->cleanValue($row[4]) ?? '',
                    'phone_responsible' => $this->cleanValue($row[5]) ?? '',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                Log::info("Nuevo usuario agregado: ID {$id}");

            }

            die();
        }
    }

    /**
     * Limpia los valores eliminando saltos de línea y caracteres invisibles
     */
    private function cleanValue($value)
    {
        return trim(preg_replace('/[\r\n\x0B\x0C\x0D]+/', ' ', $value));
    }
    }


