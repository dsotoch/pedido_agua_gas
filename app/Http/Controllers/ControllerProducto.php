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
            $nombre = $request->input('nombre');
            $precio = $request->input('precio');
            $descripcion = $request->input('descripcion');
            $promociones = $request->input('promociones');
            $productos_por_cada = $request->input('productos_por_cada');
            $productos_gratis = $request->input('productos_gratis');
            $comercializable = $request->has('estado');
            $categoria = $request->input('categoria');
           
            $imagen = null;
            if ($request->hasFile('imagen')) {
                $logo = $request->file('imagen');
                $uniqueLogoName = uniqid() . '_' . $logo->getClientOriginalName();
                $imagen = $logo->storeAs('productos', $uniqueLogoName, 'public');
            }
            // Crear el producto con los datos combinados
            $producto = Producto::create([
                'nombre' => $nombre,
                'categoria' => $categoria,
                'tipo' => $categoria == 'gas' ? $request->tipo : null,
                'imagen' => $imagen,
                'descripcion' => $descripcion, // Concatenación con comillas dobles
                'precio' => floatval($precio),              // Asegurar conversión a float
                'empresa_id' => $user->empresas->first()->id,
                'comercializable' => $comercializable,

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


    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            // Buscar el producto
            $producto = Producto::findOrFail($request->id);

            // Obtener datos del request
            $precio = $request->precio;
            $descripcion = $request->descripcion;
            $promociones = $request->promociones;
            $productos_por_cada = $request->productos_por_cada;
            $productos_gratis = $request->productos_gratis;
            $comercializable = $request->has('estado');

            // Actualizar los datos del producto
            $producto->update([
                'descripcion' => "{$descripcion}", // Concatenación
                'precio' => floatval($precio),
                'comercializable' => $comercializable
            ]);

            // Verificar si existe una promoción y actualizarla o crearla
            $promocionUnitario = PromocionesUnitario::where('producto_id', $producto->id)->first();

            if (!is_null($productos_por_cada) && !is_null($productos_gratis)) {
                if ($promocionUnitario) {
                    $promocionUnitario->update([
                        'cantidad' => $productos_por_cada,
                        'producto_gratis' => $productos_gratis == 'mismo' ? $producto->descripcion : $productos_gratis,
                    ]);
                } else {
                    PromocionesUnitario::create([
                        'producto_id' => $producto->id,
                        'cantidad' => $productos_por_cada,
                        'producto_gratis' => $productos_gratis == 'mismo' ? $producto->descripcion : $productos_gratis,
                    ]);
                }
            }

            // Procesar promociones múltiples
            Promociones::where('producto_id', $producto->id)->delete(); // Eliminar promociones anteriores
            if (is_array($promociones) && count($promociones) > 0) {
                foreach ($promociones as $value) {
                    Promociones::create([
                        'producto_id' => $producto->id,
                        'cantidad' => intval($value['cantidad']),
                        'precio_promocional' => floatval($value['precio_promocional']),
                    ]);
                }
            }


            DB::commit();
            return response()->json([
                'mensaje' => "Producto actualizado correctamente.",
                'id' => $producto->id,
                'comercializable' => $producto->comercializable
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'mensaje' => 'Ocurrió un error al actualizar el producto.',
                'error' => $e->getMessage() . " en la línea " . $request->id . '' . $e->getLine()
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
