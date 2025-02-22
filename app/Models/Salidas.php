<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salidas extends Model
{
    use HasFactory;
    protected $fillable = [
        'placa',
        'repartidor',
        'productos',
        'fecha',
        'empresa_id'
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
    
    public function stock()
    {
        return $this->hasMany(Stock::class,'salida_id');
    }
}
