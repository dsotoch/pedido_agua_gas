<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    protected $table = 'stock';
    protected $fillable = ['salida_id', 'productos','fecha'];

    public function salida()
    {
        return $this->belongsTo(Salidas::class, 'salida_id');
    }
}
