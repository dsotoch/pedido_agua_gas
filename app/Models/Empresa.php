<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
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
        'configuraciones',
        'logo_vertical',
        'hora_inicio',
        'hora_fin',
        'orden_productos',
        'tiempo'
    ];

    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class);
    }
    public function salidas()
    {
        return $this->hasMany(Salidas::class);
    }
    public function cupones()
    {
        return $this->belongsTo(cupones::class, 'empresa_id');
    }
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
