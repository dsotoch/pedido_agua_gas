<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;
    protected $fillable = ['empresa_id', 'dia', 'hora_inicio', 'hora_fin'];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class,'empresa_id');
    }
}
