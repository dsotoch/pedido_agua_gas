<?php

namespace App\Http\Controllers;

use App\Models\Cupones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CuponController extends Controller
{
    public function aplicarCupon(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|exists:cupons,codigo',
            'total' => 'required|numeric|min:0'
        ]);

        $cupon = Cupones::where('codigo', $request->codigo)->first();

        if (!$cupon->esValido()) {
            return response()->json(['error' => 'El cupón no es válido o ha expirado.'], 400);
        }

        // Aplicar descuento
        $totalConDescuento = $cupon->aplicarDescuento($request->total);

        // Incrementar el uso del cupón
        $cupon->increment('usado');

        return response()->json([
            'mensaje' => 'Cupón aplicado correctamente.',
            'total_con_descuento' => $totalConDescuento
        ]);
    }
    public function crearCupon(Request $request)
    {
        if (!Auth::check()) {
            abort(403, "Usuario no autenticado");
        }
        try {
            $request->validate([
                'codigo' => 'required|string|unique:cupones,codigo'
            ]);
            // Crear el cupón en la base de datos
            $cupon = Cupones::create([
                'codigo' => strtoupper($request->codigo), // Guardar en mayúsculas
                'tipo' => $request->tipo,
                'valor' => $request->valor,
                'limite_uso' => $request->limite_uso,
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
            // Eliminar cupon
            $cupon = Cupones::findOrFail($id);
            $cupon->delete();
            return response()->json([
                'mensaje' => 'Cupón eliminado con éxito.',

            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'mensaje' => 'Cupón creado con éxito.',
                'detalle' => $th->getMessage()
            ], 500);
        }
    }
}
