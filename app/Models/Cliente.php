<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    protected $table ='cliente';
    
    protected $fillable=[
                'telefono','nombres','apellidos','direccion','nota'
    ];

    public function Usuario(){
        return $this->belongsTo(User::class);
    }

    public function pedido(){
        return $this->hasMany(Pedido::class,'cliente_id');
    }
}
