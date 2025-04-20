<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

class YearHelperController extends Controller
{
    public static function currentYear()
    {
        return now()->year;
    }

    public static function lastYear()
    {
        return now()->subYear()->year;
    }

    public static function lastLastYear()
    {
        return now()->subYear(2)->year;
    }

    public static function lastLastLastYear()
    {
        return now()->subYear(3)->year;
    }

    public static function lastLastLastLastYear()
    {
        return '2019';
        //pongo esto fijo porque ni el 2020 ni el 2021 tuvimos fiestas y estos años no cuentan para antiguedad
        //return now()->subYear(4)->year;
    }

    public static function obtener_edad_segun_fecha($fecha_nacimiento)
    {
        $nacimiento = Carbon::parse($fecha_nacimiento)->format('d-m-Y');
        $diferencia = Carbon::now()->diffInYears($nacimiento);
        return $diferencia;
    }


}