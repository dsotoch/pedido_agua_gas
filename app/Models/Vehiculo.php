<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;
    protected $table = 'vehiculos';
    protected $fillable = [
        'repartidor_id',
        'placa',
        'empresa_id'
    ];

    public function empresa(){
        return $this->belongsTo(Empresa::class,'empresa_id');
    }
    public function repartidor()
    {
        return $this->belongsTo(User::class, 'repartidor_id');
    }
}
