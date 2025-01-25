<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{

    use HasFactory;

    protected $table= "persona";

    protected $fillable = [
        'nombres',
        'dni',
        'estado',
        'correo',
        'direccion',
        'nota',
        'user_id'
    ];

    public function usuario(){
        return $this->belongsTo(User::class,'user_id');
    }
}
