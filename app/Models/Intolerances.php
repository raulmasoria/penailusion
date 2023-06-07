<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intolerances extends Model
{
    use HasFactory;

    public function intolerances_user()
    {
        return $this->hasMany(Intolerances::class);
    }
}


