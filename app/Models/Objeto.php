<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objeto extends Model
{
    use HasFactory;

    protected $table = 'objetos';
    protected $fillable = [
        'imagem',
        'descricao',
        'data_encontrada',
        'hora_encontrada',
        'status',
        'matricula'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'matricula', 'matricula');
    }
    
}
