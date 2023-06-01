<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoundDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo',
        'day',
        'hour',
        'description',
        'march',
        'id_companie',
        'active',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'hour' => 'date:hh:mm'
    ];

    public function companie()
    {
        return $this->belongsTo(Companie::class);
    }
}
