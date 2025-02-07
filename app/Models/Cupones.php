<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cupones extends Model
{
    use HasFactory;

    protected $fillable = ['codigo', 'tipo', 'valor', 'limite_uso', 'usado', 'expira_en', 'producto_id', 'empresa_id', 'limite_uso_por_cliente'];

    public function empresa()
    {
        return $this->hasOne(empresa::class, 'id');
    }
    // Verifica si el cupón es válido
    public function esValido()
    {
        // Verificar si ha expirado
        if ($this->expira_en && Carbon::now()->gte($this->expira_en)) {
            return false;
        }

        // Verificar si ha excedido el límite de uso
        if (!is_null($this->limite_uso) && $this->usado >= $this->limite_uso) {
            return false;
        }

        return true;
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
