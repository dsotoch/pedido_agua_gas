<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestProducto;
use App\Http\Requests\RequestPromociones;
use App\Models\EntregaPromociones;
use App\Models\Producto;
use App\Models\Promociones;
use App\Models\PromocionesUnitario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ControllerProducto extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestProducto $request)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {
            // Obtener los datos del request
            $nombre = $request->nombre;
            $precio = $request->precio;
            $descripcion = $request->descripcion;
            $promociones = $request->promociones;
            $productos_por_cada = $request->productos_por_cada;
            $productos_gratis = $request->productos_gratis;
            $comercializable = $request->has('estado');

            // Crear el producto con los datos combinados
            $producto = Producto::create([
                'descripcion' => "{$nombre} {$descripcion}", // Concatenación con comillas dobles
                'precio' => floatval($precio),              // Asegurar conversión a float
                'empresa_id' => $user->empresas->first()->id,
                'comercializable' => $comercializable
            ]);

            if ($comercializable) {
                // Verificar si hay datos para la promoción
                if (!is_null($productos_por_cada) && !is_null($productos_gratis)) {
                    // Crear la promoción si los datos están disponibles

                    PromocionesUnitario::create([
                        'producto_id' => $producto->id,
                        'cantidad' => $productos_por_cada,
                        'producto_gratis' => $productos_gratis == 'mismo' ? $producto->descripcion : $productos_gratis,
                    ]);
                    
                }

                // Validar y procesar promociones si existen
                if (is_array($promociones) && count($promociones) > 0) {
                    foreach ($promociones as $key => $value) {
                        // Crear la promoción
                        Promociones::create([
                            'producto_id' => $producto->id,
                            'cantidad' => intval($value['unidades']), // Acceso a los valores correctamente
                            'precio_promocional' => floatval($value['preciopromocion']), // Asegurar conversión a float
                        ]);
                    }
                }
            }
            DB::commit();
            // Retornar respuesta exitosa
            return response()->json([
                'mensaje' => "Producto registrado correctamente.",
                'id' => $producto->id,
                'comercializable' => $producto->comercializable
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            // Manejo de errores
            return response()->json([
                'error' => 'Ocurrió un error al registrar el producto.',
                'mensaje' => $e->getMessage(),
            ], 500);
        }
    }




    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Buscar el producto
        $producto = Producto::find($id);

        if ($producto) {
            // Eliminar el producto
            $producto->delete();

            return response()->json([
                'mensaje' => 'Producto eliminado correctamente.',
                'product_id' => $id,
            ], 201); // Código 200: OK
        }

        // Si no se encuentra el producto
        return response()->json([
            'mensaje' => 'Producto no encontrado.',
            'product_id' => $id,
        ], 404); // Código 404: No encontrado
    }
}
