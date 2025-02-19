<?php

namespace App\Imports;

use App\Models\Permanence;
use App\Models\Antiquity;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class AntiquitiesPermanencesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $userId = $row["id"] ?? null;
        $permanenceYears = isset($row["permanencia"]) ? explode('-', $row["permanencia"]) : [];
        $antiquityYears = isset($row["antiguedad"]) ? explode('-', $row["antiguedad"]) : [];

        if (!$userId) {
            Log::warning('Fila ignorada: user_id no encontrado', $row);
            return null;
        }

        $years = array_unique(array_merge($permanenceYears, $antiquityYears));

        foreach ($years as $year) {
            if (in_array($year, $permanenceYears)) {
                $permanence = Permanence::updateOrCreate(
                    ['user_id' => $userId, 'year_permanence' => $year]
                );

                if ($permanence->wasRecentlyCreated) {
                    Log::info("Permanence CREADO: user_id=$userId, year=$year");
                } elseif ($permanence->wasChanged()) {
                    Log::info("Permanence ACTUALIZADO: user_id=$userId, year=$year");
                }
            }

            if (in_array($year, $antiquityYears)) {
                $antiquity = Antiquity::updateOrCreate(
                    ['user_id' => $userId, 'year' => $year]
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
