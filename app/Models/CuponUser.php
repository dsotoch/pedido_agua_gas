<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuponUser extends Model
{
    use HasFactory;
    protected $table ='cupones_users';

    protected $fillable=[
        'usuario_id',
        'cupon_id',
        'veces_usado'
    ];
}
