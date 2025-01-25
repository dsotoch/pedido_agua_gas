<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promociones extends Model
{
    use HasFactory;
    protected $fillable = [
        'producto_id',
        'cantidad',
        'precio_promocional',
    ];

    // Relación con el modelo Producto
    public function producto()
    {
        return $this->belongsTo(Producto::class,'producto_id');
    }
}
