<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cupones extends Model
{
    use HasFactory;

    protected $fillable = ['codigo', 'tipo', 'valor', 'limite_uso', 'usado', 'expira_en', 'producto_id', 'empresa_id'];

    public function empresa()
    {
        return $this->hasOne(empresa::class,'id');
    }
    // Verifica si el cupón es válido
    public function esValido()
    {
        return (!$this->expira_en || Carbon::now()->lt($this->expira_en)) // No ha expirado
            && ($this->limite_uso === null || $this->usado < $this->limite_uso); // No excede el límite de uso
    }

    // Aplica el descuento al total de la compra
    public function aplicarDescuento($total)
    {
        if ($this->tipo === 'porcentaje') {
            return $total - ($total * ($this->valor / 100));
        } elseif ($this->tipo === 'fijo') {
            return max(0, $total - $this->valor);
        }
        return $total;
    }
}
