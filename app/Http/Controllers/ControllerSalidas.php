<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Salidas;
use App\Models\Stock;
use App\Models\Vehiculo;
use Carbon\Carbon;
use Doctrine\DBAL\Query\QueryException;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ControllerSalidas extends Controller
{


    public function eliminarVehiculo(Request $request)
    {
        try {
            $vehiculo = Vehiculo::where('placa', $request->id)
                ->where('empresa_id', $request->empresa_id)
                ->first();

            if (!$vehiculo) {
                return response()->json(['mensaje' => 'Vehículo no encontrado.'], 404);
            }

            $vehiculo->delete();
            return response()->json(['mensaje' => 'Vehículo eliminado correctamente.'], 200);
        } catch (\Throwable $th) {
            return response()->json(['mensaje' => 'Error al eliminar el vehículo: ' . $th->getMessage()], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            // Validar que el ID sea válido
            $request->validate([
                'salida_id' => 'required|integer|exists:salidas,id'
            ]);

            // Buscar y eliminar la salida
            $salida = Salidas::findOrFail($request->salida_id);
            $salida->delete();

            return redirect()->back()->with(['mensaje' => "Salida Eliminada Correctamente."]);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->withErrors(["La salida no fue encontrada."]);
        } catch (QueryException $e) {
            return redirect()->back()->withErrors(["Error de base de datos al eliminar la salida."]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(["Ocurrió un error inesperado al eliminar la salida."]);
        }
    }
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $vehiculoInfo = $request->vehiculo_id;
            $partes = explode("-", $vehiculoInfo);
            $placa_modificada = $request->placa_modificada;
            $placa = $partes[0] ?? null;
            $repartidor = $partes[1] ?? null;
            $salida_de_hoy = Salidas::where('placa', $placa)->whereDate('fecha', Carbon::now('America/Lima'))->first();
            if ($salida_de_hoy) {
                throw new Exception("La salida del vehiculo ya se registró hoy.");
            }
            $placa_antigua = null;
            $actualizar = false;
            if (!empty($placa_modificada)) {
                $placa_antigua = $placa_modificada;
                $actualizar = true;
            }
            if ($actualizar) {
                $vehiculo_existe = Vehiculo::where('placa', $placa_antigua)->where('empresa_id', $request->empresa_id)->first();
            } else {
                $vehiculo_existe = Vehiculo::where('placa', $placa)->where('empresa_id', $request->empresa_id)->first();
            }
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
            $repartidorNombre = optional($vehiculo->repartidor)->persona->nombres ?? 'Desconocido';

            // Crear la salida
            $salida = Salidas::create([
                'placa' => $vehiculo->placa,
                'repartidor' => $repartidorNombre,
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
                $tipo = strpos($producto['descripcion'], '_') !== false
                    ? explode('_', $producto['descripcion'], 2)[1]
                    : '';
                // Buscar el producto basado en el nombre extraído
                $productoEncontrado = Producto::where("nombre", [$nombreProducto])->first();
                if ($productoEncontrado) {
                    $productosConIds[] = [
                        'cantidad' => $producto['cantidad'],
                        'producto_id' => $productoEncontrado->id . ($tipo != '' ? '_' . $tipo : ''),
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

    public function update(Request $request)
    {
        try {
            // Validación de los datos
            $request->validate([
                'salida_id' => 'required|exists:salidas,id',
                'productos' => 'required|array',
                'productos.*' => 'exists:productos,id',
                'cantidades' => 'required|array',
                'cantidades.*' => 'integer|min:0'
            ]);

            // Obtener la salida
            $salida = Stock::where('salida_id', $request->salida_id)->first();

            // Decodificar el JSON actual de productos en el stock
            $productosStock = json_decode($salida->productos, true) ?? [];

            // Crear un mapa para acceder rápidamente a los productos existentes
            $productosMap = [];
            foreach ($productosStock as &$producto) {
                $productosMap[$producto['producto_id']] = &$producto;
            }

            $productosModificados = [];
            // Recorrer los productos del request
            foreach ($request->productos as $index => $producto_id) {
                $cantidadNueva = $request->cantidades[$index] ?? 0;

                if (isset($productosMap[$producto_id])) {
                    // Si el producto ya existe en el stock, sumamos la cantidad
                    $productosMap[$producto_id]['cantidad'] += $cantidadNueva;
                    $productosModificados[] = [
                        'producto_id' => $producto_id,
                        'cantidad' => $productosMap[$producto_id]['cantidad'],
                    ];
                } else {
                    // Si el producto no existe, lo agregamos al array
                    $productosStock[] = [
                        'cantidad' => $cantidadNueva,
                        'producto_id' => $producto_id,

                    ];
                }
            }
          
            // Guardar los productos en formato JSON
            $salida->productos = json_encode($productosModificados);
            $salida->save(); // Guardar en la BD
            $this->sumarStockActual($request->salida_id, $request->productos, $request->cantidades);
            return redirect()->back()->with('mensaje', 'Salida actualizada correctamente');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['mensaje' => 'Error: ' . $th->getMessage()]);
        }
    }

    private function sumarStockActual($salida_id, $productos, $cantidades)
    {
        try {
            $salida = Salidas::findOrFail($salida_id);
            // Decodificar el JSON actual de productos en el stock
            $productosStock = json_decode($salida->productos, true) ?? [];

            // Crear un mapa de productos existentes
            $productosMap = [];
            foreach ($productosStock as &$producto) {
                $productosMap[$producto['producto_id']] = &$producto;
            }
            $productosModificados = [];

            // Recorrer los productos y actualizar solo los que existen en el stock
            foreach ($productos as $index => $producto_id) {
                $cantidadNueva = $cantidades[$index] ?? 0;

                if (isset($productosMap[$producto_id])) {
                    // Si el producto ya existe en el stock, sumamos la cantidad
                    $productosMap[$producto_id]['cantidad'] += $cantidadNueva;
                    // Guardamos solo los productos que existen en productosMap
                    $productosModificados[] = [
                        'producto_id' => $producto_id,
                        'cantidad' => $productosMap[$producto_id]['cantidad'],
                    ];
                }
            }




            // Reindexar el array
            $productosStock = array_values($productosModificados);

            // Guardar el JSON actualizado
            $salida->productos = json_encode($productosModificados);
            $salida->save();

            // Retornar los productos que fueron actualizados
            return response()->json($productosModificados);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }


    public function restarStockActual($salida_id, $productosIds)
    {
        try {
            $salida = Stock::where('id', $salida_id)->first();
            if (!$salida) {
                throw new \Exception("Stock no encontrado.");
            }

            // Decodificar el JSON actual de productos en el stock
            $productosStock = json_decode($salida->productos, true) ?? [];

            // Crear un mapa para acceso rápido a productos en stock
            $productosMap = [];
            foreach ($productosStock as &$producto) {
                $productosMap[$producto['producto_id']] = &$producto;
            }

            // Recorrer los productos a restar
            foreach ($productosIds as $productoData) {
                $producto_id = $productoData['producto_id'];
                $cantidadRestar = $productoData['cantidad'] ?? 0;

                if (isset($productosMap[$producto_id])) {
                    // Restar la cantidad al stock
                    $productosMap[$producto_id]['cantidad'] -= $cantidadRestar;

                    // Si la cantidad es menor o igual a 0, eliminar el producto del stock
                    if ($productosMap[$producto_id]['cantidad'] <= 0) {
                        unset($productosMap[$producto_id]);
                    }
                }
            }

            // Guardar el stock actualizado
            $salida->productos = json_encode(array_values($productosMap));
            $salida->save();
        } catch (\Throwable $th) {
            throw new Exception("Error al restar stock: " . $th->getMessage());
        }
    }
    public function sumarStockActual_cuando_anula_pedido($usuario, $productosIds)
    {
        try {
            $salida = Salidas::whereDate('fecha', Carbon::now('America/Lima'))
                ->where('repartidor', $usuario)
                ->first();

            if (!$salida) {
                throw new \Exception("No se encontró una salida para el repartidor en la fecha actual.");
            }

            $stock = Stock::where('salida_id', $salida->id)->first();
            if (!$stock) {
                throw new \Exception("Stock no encontrado.");
            }

            // Decodificar el JSON actual de productos en el stock
            $productosStock = json_decode($stock->productos, true) ?? [];

            // Crear un mapa para acceso rápido a productos en stock
            $productosMap = [];
            foreach ($productosStock as &$producto) {
                $productosMap[$producto['producto_id']] = &$producto;
            }

            // Recorrer los productos a sumar
            foreach ($productosIds as $productoData) {
                $producto_id = $productoData['producto_id'] ?? null;
                $cantidadSumar = $productoData['cantidad'] ?? 0;

                if (!$producto_id) {
                    throw new \Exception("Producto ID inválido.");
                }

                if (isset($productosMap[$producto_id])) {
                    // Si ya existe, sumamos la cantidad
                    $productosMap[$producto_id]['cantidad'] += $cantidadSumar;
                } else {
                    // Si no existe en stock, lo agregamos
                    $productosMap[$producto_id] = [
                        'producto_id' => $producto_id,
                        'cantidad' => $cantidadSumar
                    ];
                }
            }

            // Guardar el stock actualizado
            $stock->productos = json_encode(array_values($productosMap));
            $stock->save();
        } catch (\Throwable $th) {
            throw new Exception("Error al sumar stock: " . $th->getMessage());
        }
    }



    public function show($id)
    {
        try {
            $salida = Salidas::findOrFail($id);
            $productosSalida = json_decode($salida->productos, true) ?? [];

            foreach ($productosSalida as &$item) {
                // Extraer el ID del producto
                $productoId = strpos($item['producto_id'], '_') !== false
                    ? explode('_', $item['producto_id'])[0]
                    : $item['producto_id'];

                $tipo = '';
                if (strpos($item['producto_id'], '_') !== false) {
                    $tipo = substr($item['producto_id'], strpos($item['producto_id'], '_'));
                }
                // Buscar el producto en la base de datos
                $producto = Producto::find($productoId);

                // Si se encuentra el producto, agregar su información
                if ($producto) {
                    $item['nombre'] = $producto->nombre;
                    $item['descripcion'] = $producto->descripcion . $tipo;
                } else {
                    $item['nombre'] = 'Producto no encontrado';
                    $item['descripcion'] = '';
                }
            }

            // Retornar los productos como JSON
            return response()->json(['productos' => $productosSalida], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
