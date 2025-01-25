<?php

namespace App\Models;

use GuzzleHttp\Client;
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
        'celular'
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
        return $this->belongsTo(empresa::class, 'empresa_id');
    }

    public function mensaje()
    {
        return $this->hasMany(Mensajes::class);
    }
    public function entregaPromociones()
    {
        return $this->hasMany(entregaPromociones::class);
    }
}
