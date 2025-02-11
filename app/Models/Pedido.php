<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;
    protected $table = "pedidos";
    protected $fillable = [
        'cliente_id',
        'fecha',
        'total',
        'estado',
        'nota',
        'pago',
        'empresa_id',
        'metodo',
        'repartidor_id',
        'nombres',
        'direccion',
        'celular',
        'nota_interna',
        'actor',
        'descuento',
        'cupon'
    ];


    public function repartidor()
    {
        return $this->belongsTo(User::class, 'repartidor_id');
    }
    /**
     * Relación con el modelo Detalles 
     * Un pedido tiene muchos detalles.
     */
    public function detalles()
    {
        return $this->hasMany(Detalles::class, 'pedido_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function mensaje()
    {
        return $this->hasMany(Mensajes::class);
    }
    public function entregaPromociones()
    {
        return $this->hasMany(EntregaPromociones::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }
}
