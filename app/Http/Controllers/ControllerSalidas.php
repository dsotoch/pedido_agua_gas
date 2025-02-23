<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Salidas;
use App\Models\Stock;
use App\Models\Vehiculo;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ControllerSalidas extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $vehiculoInfo = $request->vehiculo_id;
            $partes = explode("-", $vehiculoInfo);

            $placa = $partes[0] ?? null;
            $repartidor = $partes[1] ?? null;
            $salida_de_hoy = Salidas::where('placa', $placa)->whereDate('fecha', Carbon::now('America/Lima'))->first();
            if ($salida_de_hoy) {
                throw new Exception("La salida del vehiculo ya se registró hoy.");
            }
            $vehiculo_existe = Vehiculo::where('placa', $placa)->where('empresa_id', $request->empresa_id)->first();
            $vehiculo = null;
            if ($vehiculo_existe) {
                $vehiculo_existe->update([
                    'placa' => $placa,
                    'repartidor_id' => $repartidor,
                ]);
                $vehiculo = $vehiculo_existe;
            } else {
                $vehiculo = Vehiculo::create([
                    'placa' => $placa,
                    'repartidor_id' => $repartidor,
                    'empresa_id' => $request->empresa_id
                ]);
            }

            $productosConCantidades = [];

            foreach ($request->productos as $index => $producto) {
                $productosConCantidades[] = [
                    'producto_id' => $producto,
                    'cantidad' => $request->cantidades[$index] ?? 0, // Si no existe cantidad, asigna 0
                ];
            }
            // Crear la salida
            $salida = Salidas::create([
                'placa' => $vehiculo->placa,
                'repartidor' => $vehiculo->repartidor->persona->nombres,
                'productos' => json_encode($productosConCantidades),
                'fecha' => Carbon::now('America/lima'),
                'empresa_id' => $request->empresa_id
            ]);
            $this->procesarStock($salida->id, $productosConCantidades);
            DB::commit();
            return redirect()->back()->with('mensaje', 'Salida registrada correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withErrors([$th->getMessage()]);
        }
    }

    private function procesarStock($salida_id, $productos)
    {
        try {
            Stock::create([
                'salida_id' => $salida_id,
                'productos' => json_encode($productos),
                'fecha' => Carbon::now('America/Lima')
            ]);
        } catch (\Throwable $th) {
            throw new \Exception("Error al procesar el stock: " . $th->getMessage() . " en línea " . $th->getLine());
        }
    }
    public function procesarStockJson(Request $request)
    {

        try {
            $stock = Stock::where('salida_id', $request->salida_id)->first();
            $productosRecibidos = $request->input('productos'); // Array con descripcion y cantidad

            $productosConIds = [];

            foreach ($productosRecibidos as $producto) {
                // Buscar el producto por su descripción
                $nombreProducto = strpos($producto['descripcion'], '_') !== false
                    ? explode('_', $producto['descripcion'], 2)[0]
                    : $producto['descripcion'];

                // Buscar el producto basado en el nombre extraído
                $productoEncontrado = Producto::whereRaw("CONCAT(nombre, ' ', descripcion) = ?", [$nombreProducto])->first();
                if ($productoEncontrado) {
                    $productosConIds[] = [
                        'cantidad' => $producto['cantidad'],
                        'producto_id' => $productoEncontrado->id,
                    ];
                }
            }
            if ($stock) {
                $stock->update([
                    'productos' => json_encode($productosConIds),
                    'fecha' => Carbon::now('America/Lima') // Asegurar que la fecha siempre esté presente
                ]);
            } else {
                Stock::create([
                    'salida_id' => $request->input('salida_id'),
                    'productos' => json_encode($productosConIds),
                    'fecha' => Carbon::now('America/Lima')
                ]);
            }

            return response()->json(['mensaje' => $request->all()]);
        } catch (\Throwable $th) {
            return response()->json(['mensaje' => 'Error al procesar el stock: ' . $th->getMessage() . 'Linea: ' . $th->getLine()]);
        }
    }
}
