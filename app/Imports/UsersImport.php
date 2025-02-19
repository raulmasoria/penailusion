<?php
namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class UsersImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        set_time_limit(300);
        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // Omitimos la cabecera del Excel

            $id = $row[0]; // Suponiendo que la columna 0 es el ID
            $phone = $this->cleanValue($row[10]); // Suponiendo que la columna 3 es el teléfono
            $email = $this->cleanValue($row[11]); // Suponiendo que la columna 4 es el email
            Log::info("Usuario ID {$id}, Teléfono: {$phone}, Email: {$email}");

            $user = User::find($id);

            if ($user) {
                // Si el usuario existe, actualizamos si los datos han cambiado
                if ($user->phone !== $phone || $user->email !== $email) {
                    $user->update([
                        'phone' => $phone,
                        'email' => $email,
                    ]);

                    Log::info("Usuario actualizado: ID {$id}, Teléfono: {$phone}, Email: {$email}");
                }
            } else {
                // Si el usuario no existe, lo creamos
                User::create([
                    'id' => $id,
                    'name' => $this->cleanValue($row[2]) ?? '',
                    'lastname' => $this->cleanValue($row[1]) ?? '',
                    'phone' => $phone,
                    'email' => $email,
                    'password' => NULL, // O un valor aleatorio
                    'nif' => $this->cleanValue($row[12]) ?? NULL,
                    'carta' => 0,
                    'rol' => 0,
                    'active' => 1,
                ]);

                Log::info("Nuevo usuario agregado: ID {$id}, Nombre: {$row[2]} {$row[1]}, Teléfono: {$phone}, Email: {$email}");

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


