<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'email',
        'asunto',
        'estado',
        'created_at',
        'updated_at'
    ];
}
