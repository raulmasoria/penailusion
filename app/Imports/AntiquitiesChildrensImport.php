<?php

namespace App\Imports;

use App\Models\Childrens_antiquities;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class AntiquitiesChildrensImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $userId = $row["id"] ?? null;
        $antiquityYears = isset($row["antiguedad"]) ? explode('-', $row["antiguedad"]) : [];

        if (!$userId || !is_numeric($userId)) {
            Log::warning('Fila ignorada: user_id no encontrado', $row);
            return null;
        }

        foreach ($antiquityYears as $year) {
            if (in_array($year, $antiquityYears)) {
                $antiquity = Childrens_antiquities::updateOrCreate(
                    ['children_id' => $userId, 'year' => $year]
                );

                if ($antiquity->wasRecentlyCreated) {
                    Log::info("Antiquity CREADO: user_id=$userId, year=$year");
                } elseif ($antiquity->wasChanged()) {
                    Log::info("Antiquity ACTUALIZADO: user_id=$userId, year=$year");
                }
            }
        }
    }

}
