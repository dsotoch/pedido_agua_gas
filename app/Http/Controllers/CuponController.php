<?php

namespace App\Http\Controllers;

use App\Models\Cupones;
use App\Models\CuponUser;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CuponController extends Controller
{
    public function aplicarCupon($codigoCupon, $total, $empresa)
    {
        // Buscar el cupón en la base de datos
        $cupon = Cupones::where('codigo', $codigoCupon)->where('empresa_id', $empresa)->first();

        if (!$cupon || !$cupon->esValido()) {
            throw new Exception('El cupon no es valido o ha expirado.', 400);
        }
        $usoCliente = null;
        if ($cupon->especial) {
            // Verificar si el usuario ya usó el cupón hoy
            $usoCliente = CuponUser::where('cupon_id', $cupon->id)
                ->where('usuario_id', auth()->id())
                ->whereDate('created_at', now()->toDateString()) // Filtra por la fecha actual
                ->first();
        }

        // Verificar si el usuario ha alcanzado su límite de uso
        $usoCliente = CuponUser::where('cupon_id', $cupon->id)
            ->where('usuario_id', auth()->id())
            ->first();

        if ($usoCliente && $usoCliente->veces_usado >= $cupon->limite_uso_por_cliente && !$cupon->especial) {
            throw new Exception('El cliente ya alcanzó el límite de uso del cupón.', 400);
        }

        // Si el usuario ya ha usado el cupón, incrementamos su uso
        if ($usoCliente) {
            $usoCliente->veces_usado = 0;
            $usoCliente->save();
        } else {
            // Si es la primera vez que lo usa, creamos el registro
            CuponUser::create([
                'cupon_id' => $cupon->id,
                'usuario_id' => auth()->id(),
                'veces_usado' => 1
            ]);
        }

        // Obtener el descuento y el total con descuento
        $resultado = $this->calcularDescuento($cupon, $total);

        // Incrementar el uso total del cupón
        $cupon->increment('usado');

        return [
            'descuento' => $resultado['descuento'],
            'total_con_descuento' => $resultado['total_con_descuento']
        ];
    }






    /**
     * Función reutilizable para calcular el descuento y el total con descuento.
     */
    public function calcularDescuento($cupon, $total)
    {
        $descuento = 0;

        if ($cupon->tipo == 'porcentaje') {
            $descuento = ($total * $cupon->valor) / 100;
        } elseif ($cupon->tipo == 'fijo') {
            $descuento = $cupon->valor;
        }

        $totalConDescuento = max(0, $total - $descuento);

        return [
            'descuento' => $descuento,
            'total_con_descuento' => $totalConDescuento
        ];
    }


    public function calcularTotal(Request $request)
    {
        try {
            // Validar que el cupón existe
            $codigo = Cupones::where('codigo', $request->cupon)->where('empresa_id', $request->empresa ?? 0)->first();

            if (!$codigo) {
                throw new \Exception('El código ingresado no existe en la Distribuidora.', 404);
            }
            if (!$codigo || !$codigo->esValido()) {
                throw new Exception('El cupon no es valido o ha expirado.', 400);
            }
            $usoCliente = null;
            if ($codigo->especial) {
                // Verificar si el usuario ya usó el cupón hoy
                $usoCliente = CuponUser::where('cupon_id', $codigo->id)
                    ->where('usuario_id', auth()->id())
                    ->whereDate('created_at', now()->toDateString()) // Filtra por la fecha actual
                    ->first();
            }

            if (!$codigo->especial) {
                // Validar si el cupón ya ha alcanzado su límite de uso
                $usosRestantesGlobal = $codigo->limite_uso - $codigo->usado;
                if ($usosRestantesGlobal <= 0) {
                    throw new \Exception('Código ya no es válido', 400);
                }

                // Verificar si el usuario ha alcanzado su límite de uso
                $usoCliente = CuponUser::where('cupon_id', $codigo->id)
                    ->where('usuario_id', auth()->id())
                    ->first();
            }


            $usosRestantesCliente = $codigo->limite_uso_por_cliente - ($usoCliente->veces_usado ?? 0);
            if ($usosRestantesCliente <= 0) {
                if (!$codigo->especial) {
                    throw new \Exception('Has alcanzado el límite de uso para este código', 400);

                }
            }

            // Validar que el total de la compra es un número válido
            if (!is_numeric($request->total) || $request->total <= 0) {
                throw new \Exception('El total de la compra debe ser un número válido', 422);
            }

            // Obtener el total de la compra
            $totalCompra = (float) $request->total;
            $descuento = 0;

            // Calcular el descuento en memoria
            if ($codigo->tipo == 'porcentaje') {
                if ($codigo->valor < 0 || $codigo->valor > 100) {
                    throw new \Exception('El porcentaje de descuento no es válido', 422);
                }
                $descuento = ($totalCompra * $codigo->valor) / 100;
            } elseif ($codigo->tipo == 'fijo') {
                if ($codigo->valor < 0) {
                    throw new \Exception('El monto de descuento no puede ser negativo', 422);
                }
                $descuento = $codigo->valor;
            }

            // Calcular el nuevo total asegurando que no sea negativo
            $nuevoTotal = max(0, $totalCompra - $descuento);

            // Simular la reducción de uso sin modificar la base de datos
            if (!$codigo->especial) {
                $usosRestantesGlobal--;
            }
            $usosRestantesCliente--;

            return response()->json([
                'mensaje' => 'Descuento aplicado correctamente',
                'descuento' => $descuento,
                'nuevo_total' => $nuevoTotal,
                'usos_restantes_global' => $usosRestantesGlobal ?? '',
                'usos_restantes_cliente' => $usosRestantesCliente
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], $e->getCode() ?: 400);
        }
    }


    public function crearCupon(Request $request)
    {
        if (!Auth::check()) {
            abort(403, "Usuario no autenticado");
        }
        try {
            $request->validate([
                'codigo' => 'required|string|'
            ]);
            $existe = Cupones::where('codigo', $request->codigo)->where('empresa_id', $request->empresa_id)->first();
            if ($existe) {
                return response()->json([
                    'mensaje' => 'Hubo un problema al crear el cupón. Codigo ya Registrado',
                ], 403);
            }
            // Crear el cupón en la base de datos
            $cupon = Cupones::create([
                'codigo' => strtoupper($request->codigo), // Guardar en mayúsculas
                'tipo' => $request->tipo,
                'valor' => $request->valor,
                'especial' => $request->has('especial') ? true : false,
                'limite_uso' => $request->limite_uso,
                'limite_uso_por_cliente' => $request->limite_uso_cliente,
                'expira_en' => $request->expira_en,
                'empresa_id' => $request->empresa_id
            ]);

            return response()->json([
                'mensaje' => 'Cupón creado con éxito.',
                'cupon' => $cupon
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Hubo un problema al crear el cupón. codigo ya registrado',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }
    public function eliminar($id)
    {
        if (!Auth::check()) {
            abort(403, "Usuario no autenticado");
        }
        try {
            $cupon = Cupones::findOrFail($id);
            $cupon->delete();
            return redirect()->route('empresa.cupones')->with(['mensaje' => 'Cupón eliminado correctamente.']);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->withErrors("Cupón no encontrado.");
        } catch (\Exception $e) {
            return redirect()->back()->withErrors("Error al eliminar el cupón: " . $e->getMessage());
        }
    }
}
