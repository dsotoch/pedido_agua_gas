<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromocionesUnitario extends Model
{
    use HasFactory;

    protected $table = 'unitarios';

    protected $fillable = ['producto_id', 'cantidad', 'producto_gratis'];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
