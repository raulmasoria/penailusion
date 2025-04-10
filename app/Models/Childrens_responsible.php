<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Childrens_responsible extends Model
{
    use HasFactory;


    protected $fillable = [
        'children_id',
        'user_id',
    ],
    $table = 'childrens_responsible';
}
