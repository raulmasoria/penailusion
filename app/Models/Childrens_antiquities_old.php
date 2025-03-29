<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Childrens_antiquities_old extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'year',
    ],
    $table = 'childrens_antiquities_old';
}
