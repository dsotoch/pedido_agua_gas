<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensajes extends Model
{
    use HasFactory;
    protected $table = 'mensajes';

    protected $fillable = [
        'id',
        'user_id',
        'mensaje',
        'pedido_id',
        'estado'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }
    public function getTimeAttribute(): string
    {
        return date(
            "d M Y, H:i:s",
            strtotime($this->attributes['created_at'])
        );
    }
}
