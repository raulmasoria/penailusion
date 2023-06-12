<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Adress;
use App\Models\Antiquity;
use App\Models\Permanence;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'lastname',
        'phone',
        'email',
        'nif',
        'password',
        'rol',
        'notifications',
        'active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];       

    public function adress()
    {
        return $this->hasOne(Adress::class);
    }

    public function antiquity()
    {
        return $this->hasMany(Antiquity::class);
    }

    public function permanence()
    {
        return $this->hasMany(Permanence::class);
    }

    public function device_user_tokens()
    {
        return $this->hasMany(DeviceUserToken::class);
    }

    public function intolerancesUser()
    {
        return $this->hasMany(IntolerancesUser::class,'id_user');
    }

    public function email()
    {
        return $this->hasMany(Email::class);
    }


    public function intolerances()
    {
        //return $this->belongsToMany(RelatedModel, pivot_table_name, foreign_key_of_current_model_in_pivot_table, foreign_key_of_other_model_in_pivot_table);
        return $this->belongsToMany(
            Intolerance::class,
            'intolerances_users',
            'id_user',
            'id_intolerance',
        );
    }

}
