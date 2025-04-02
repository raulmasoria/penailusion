<?php

namespace App\Imports;

use App\Models\Antiquity;
use App\Models\Childrens_antiquities_old;
use App\Models\Permanence;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AntiquitiesPermanencesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $userId = $row["id"] ?? null;
        $permanenceYears = isset($row["permanencia"]) ? explode('-', $row["permanencia"]) : [];
        $antiquityYears = isset($row["antiguedad"]) ? explode('-', $row["antiguedad"]) : [];
        $childrensOldYears = isset($row["ninos_antiguedad"]) ? explode('-', $row["ninos_antiguedad"]) : [];

        if (!$userId) {
            Log::warning('Fila ignorada: user_id no encontrado', $row);
            return null;
        }

        $years = array_unique(array_merge($permanenceYears, $antiquityYears, $childrensOldYears));

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

            if (in_array($year, $childrensOldYears)) {
                $antiquity = Childrens_antiquities_old::updateOrCreate(
                    ['user_id' => $userId, 'year' => $year]
                );

                if ($antiquity->wasRecentlyCreated) {
                    Log::info("Antiquity children old CREADO: user_id=$userId, year=$year");
                } elseif ($antiquity->wasChanged()) {
                    Log::info("Antiquity children old ACTUALIZADO: user_id=$userId, year=$year");
                }
            }
        }
    }

}
