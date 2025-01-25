<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntregaPromociones extends Model
{
    use HasFactory;

    protected $table = 'entregapromociones';
    protected $fillable = ['pedido_id', 'producto', 'cantidad', 'estado', 'user_id', 'producto_id'];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
