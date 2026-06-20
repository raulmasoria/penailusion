<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntolerancesChildrens extends Model
{
    use HasFactory;

    public function intolerances()
    {
        return $this->belongsTo(IntolerancesChildrens::class);
    }
}
