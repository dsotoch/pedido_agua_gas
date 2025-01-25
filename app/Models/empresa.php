<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class empresa extends Model
{
    use HasFactory;

    protected $table = "empresa";

    protected $fillable = [
        'nombre',
        'dominio',
        'logo',
        'email_contacto',
        'direccion',
        'descripcion',
        'imagenes',
        'whatsapp',
        'facebook',
        'telefono',
        'servicios',
        'configuraciones'
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
   
    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'empresa_user');
    }
}
