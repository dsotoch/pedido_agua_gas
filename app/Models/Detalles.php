<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalles extends Model
{
    use HasFactory;
    protected $table = "detalles";
    protected $fillable = ['pedido_id', 'producto_id','tipo','cantidad','precioUnitario','total'];

    /**
     * Relación con el modelo detallepedido 
     * Un detalle pertenece un  pedido.
    */
    public function pedido(){
        return $this->belongsto(Pedido::class,'pedido_id');
    }
     
    /**
     * Relación con Producto
     * Un detalle pertenece a un producto.
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
    
}
