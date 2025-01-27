<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = "productos";

    protected $fillable = [
        'descripcion',
        'precio',
        'empresa_id',
        'comercializable'
    ];
    public function scopeFilter($query, $filters)
    {
        // Filtrado por comercializable
        if (isset($filters['comercializable'])) {
            $query->where('comercializable', true);
        }

        return $query;
    }


    public function clientepedido()
    {
        return $this->hasMany(ClientePedidoProductos::class);
    }
    public function unitarios()
    {
        return $this->hasOne(PromocionesUnitario::class);
    }

    public function entregaPromociones()
    {
        return $this->hasOne(Producto::class);
    }
    /**
     * Relación con el modelo Promociones
     * Un producto puede estar en muchas Promociones
     */
    public function promociones()
    {
        return $this->hasMany(Promociones::class);
    }



    /**
     * Relación con el modelo Detalles 
     * Un producto pertenece a muchos detalles.
     */
    public function detalles()
    {
        return $this->hasMany(Detalles::class, 'producto_id');
    }

    public function empresa()
    {
        return $this->belongsTo(empresa::class, 'empresa_id');
    }
}
