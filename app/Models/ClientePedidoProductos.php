<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientePedidoProductos extends Model
{
    use HasFactory;

    protected $table ='clientespedidos';

    protected $fillable=[
        'cliente_id',
        'producto_id',
        'cantidad'
    ];

    public function usuario(){
        return $this->belongsTo(User::class,'cliente_id');
    }
    public function productos(){
        return $this->belongsTo(Producto::class,'producto_id');
    }
}
