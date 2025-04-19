<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Childrens_godfathers extends Model
{
    use HasFactory;

    protected $fillable = ['user_godfather_1', 'user_godfather_2', 'children_new', 'year_godfather'];
}
