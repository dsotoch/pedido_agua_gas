<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'usuario',
        'tipo',
        'empresa_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function pedido()
    {
        return $this->hasOne(Pedido::class, 'repartidor_id');
    }
    public function pedido_usuario()
    {
        return $this->hasOne(Pedido::class);
    }
    public function Cliente()
    {
        return $this->hasOne(Cliente::class);
    }

    public function username()
    {
        return 'usuario'; // Cambia 'usuario' por el campo que desees usar
    }
    public function empresas()
    {
        return $this->belongsToMany(Empresa::class, 'empresa_user');
    }

    public function persona()
    {
        return $this->hasOne(persona::class);
    }
    public function mensaje()
    {
        return $this->hasMany(Mensajes::class);
    }
    public function clientepedido()
    {
        return $this->hasMany(ClientePedidoProductos::class,'cliente_id');
    }
}
