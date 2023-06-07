<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Companie extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'establecimiento',
        'tipo',
        'imagen',
        'cuota', // 0 - media pagina, 1 - pagina completa
        'created_at',
        'updated_at',
    ];

    public function round()
    {
        return $this->hasMany(Round::class);
    }
}
