<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
     protected $primaryKey = 'matricula';
     protected $keyType = 'string';
     public $incrementing = false;

    const ADMIN_LEVEL = 10;
    const DEFAULT_LEVEL = 0;
    protected $fillable = [
        'matricula',
        'nome_completo',
        'level'
    ];

    public function isAdministrator(){
        return $this->level == User::ADMIN_LEVEL;
    }    

    public function objetos(){
        return $this->hasMany(Objeto::class, 'matricula', 'matricula');
    }

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
}
