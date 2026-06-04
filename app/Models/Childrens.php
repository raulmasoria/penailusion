<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Childrens_bracelet;

class Childrens extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'lastname',
        'birthdate',
        'responsible',
        'phone_responsible',
    ];

    protected $casts = [
        'birthdate' => 'date',
    ];

    public function bracelets()
    {
        return $this->hasMany(Childrens_bracelet::class, 'id_children');
    }
}
