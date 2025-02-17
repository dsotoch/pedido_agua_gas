<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direcciones extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'direccion',
        'referencia',
    ];

    public function usuario()
    {
        return  $this->belongsTo(User::class, 'user_id');
    }
}
