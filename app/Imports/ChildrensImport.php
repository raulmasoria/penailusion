<?php
namespace App\Imports;

use Carbon\Carbon;
use App\Models\Childrens;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;


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

            $children = Childrens::find($id);

            Log::info("Children {$children} ");

            if ($children) {
                // Habilitar el log de consultas
                DB::enableQueryLog();

                // Realiza la actualización manual
                $children->name = $name ?? '';
                $children->lastname = $lastname ?? '';
                $children->birthdate = $birthdate ?? '';
                $children->responsible = $responsible ?? '';
                $children->phone_responsible = $phone_responsible ?? '';
                $children->created_at = $children->created_at ?? Carbon::now();

                $updated = $children->save();
                if ($updated) {
                    Log::info("Usuario actualizado correctamente para el ID: {$children->id}");
                } else {
                    Log::error("Error al actualizar el usuario con ID: {$children->id}");
                }
            }
             else {
                // Si el usuario no existe, lo creamos
                Childrens::create([
                    'name' => $name ?? '',
                    'lastname' => $lastname ?? '',
                    'birthdate' => $birthdate ?? '',
                    'responsible' => $responsible ?? '',
                    'phone_responsible' => $phone_responsible ?? ''
                ]);

                Log::info("Nuevo usuario agregado: {$name } {$lastname}");

            }

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


