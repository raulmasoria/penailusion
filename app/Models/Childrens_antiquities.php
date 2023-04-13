<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Childrens_antiquities extends Model
{
    use HasFactory;

    protected $fillable = [
        'children_id',
        'year',
    ];
}
