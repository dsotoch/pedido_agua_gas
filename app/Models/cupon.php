<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cupon extends Model
{
    use HasFactory;
    protected $fillable = [
        'codigo',            // Código único del cupón (ejemplo: PROMO10)
        'tipo_descuento',    // Tipo de descuento ('%' o 'fijo')
        'valor_descuento',   // Valor del descuento (porcentaje o monto fijo)
        'valido_desde',      // Fecha desde la que el cupón es válido
        'valido_hasta',      // Fecha hasta la que el cupón es válido
        'limite_uso',        // Número máximo de usos permitidos
        'veces_usado',       // Número de veces que se ha utilizado el cupón
    ];

    // Relación con los productos (opcional, si aplica a productos específicos)
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'cupon_producto');
    }

    // Valida si el cupón es válido
    public function esValido()
    {
        return $this->valido_desde <= now('America/Lima') && $this->valido_hasta >=  now('America/Lima') &&
            $this->veces_usado < $this->limite_uso;
    }
}
