<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Godfather extends Model
{
    use HasFactory;

    protected $fillable = ['user_godfather_1', 'user_godfather_2', 'user_new', 'year_godfather'];
}
