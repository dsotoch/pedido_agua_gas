<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestPedido;
use App\Jobs\SendMessage;
use App\Models\ClientePedidoProductos;
use App\Models\Detalles;
use App\Models\empresa;
use App\Models\EntregaPromociones;
use App\Models\Pedido;
use App\Models\Persona;
use App\Models\Producto;
use App\Models\PromocionesUnitario;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\isEmpty;

class ControllerPedido extends Controller
{


    public function vista_pedido_confirmado(string $slug, string $id)
    {
        $pedido = Pedido::with(['detalles', 'entregaPromociones', 'entregaPromociones' => function ($query) {
            $query->where('estado', true);
        }])->where('id', $id)->first();

        $empresa = Empresa::find($pedido->empresa_id);
        $detalles = $pedido->detalles;
        $entregaPromociones = $pedido->entregaPromociones;
        return view('exito_pedido', compact('entregaPromociones', 'empresa', 'pedido', 'detalles'));
    }
    public function asignar(Request $request)
    {
        // Validar los datos de entrada
        $validated = $request->validate([
            'pedido_id' => 'required|exists:pedidos,id',
            'repartidor_id' => 'required|exists:persona,id',
        ]);

        try {
            // Buscar el pedido y el repartidor
            $pedido = Pedido::findOrFail($validated['pedido_id']);
            $repartidor = User::findOrFail($validated['repartidor_id']);

            // Actualizar el pedido con el ID del repartidor
            $pedido->update([
                'repartidor_id' => $repartidor->id,
            ]);
            $mensaje = ['operacion' => 'asignacion', 'mensaje' => 'Pedido Asignado.', 'pedido_id' => $validated['pedido_id']];

            SendMessage::dispatch($mensaje, $repartidor->id);
            
            // Respuesta exitosa
            return response()->json([
                'mensaje' => 'Pedido asignado correctamente.',
                'repartidor' => $repartidor->persona->nombres,
            ], 201);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'mensaje' => 'Ocurrió un error al asignar el pedido.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function pedidorecibidorepartidor(string $nombre_empresa, string $id)
    {
        $pedido = Pedido::find($id);
        if ($pedido) {
            $pedido->update([
                "estado" => 'ENVIADO'
            ]);
            $mensaje = ['operacion' => 'confirmacion', 'mensaje' => 'Tu pedido ha sido tomado por el repartidor y está en camino.', 'pedido_id' => $pedido->id];
            SendMessage::dispatch($mensaje, $pedido->cliente->id);
            return response()->json(["mensaje" => "Pedido Confirmado."], 200);
        } else {
            return response()->json(["mensaje" => "Pedido No Encontrado."], 404);
        }
    }

    public function cambiarestadopago(Request $request)
    {
        $pedido = Pedido::find($request->id_pedido);
        if ($pedido) {
            try {
                $metodo = $request->paymentMethod;
                $pago = in_array($metodo, ['yape', 'efectivo', 'account']);

                DB::transaction(function () use ($pedido, $metodo, $pago) {
                    $pedido->update([
                        'estado' => 'ENTREGADO',
                        'pago' => $pago,
                        'metodo' => $metodo
                    ]);
                });

                $detalles = ["pago" => $pago, "metodo" => $metodo];
                return response()->json(["mensaje" => "Pedido Modificado Correctamente.", "detalles" => $detalles], 200);
            } catch (\Exception $th) {
                return response()->json(["mensaje" => $th->getMessage()], 500);
            }
        } else {
            return response()->json(["mensaje" => "Pedido No Encontrado."], 404);
        }
    }

    public function cancelarPedido(Request $request)
    {
        $pedido = Pedido::find($request->pedido_id);
        if ($pedido) {
            try {
                $metodo = $request->paymentMethod;
                DB::transaction(function () use ($pedido, $metodo) {
                    $pedido->update([
                        'metodo' => $metodo
                    ]);
                });

                return response()->json(["mensaje" => "Se Modifico el Pago del Pedido #" . $pedido->id], 200);
            } catch (\Exception $th) {
                return response()->json(["mensaje" => $th->getMessage()], 500);
            }
        } else {
            return response()->json(["mensaje" => "Pedido No Encontrado."], 404);
        }
    }

    private function sinProductos($request, $usuario)
    {
        try {
            $empresa = Empresa::find($request->empresa_id);
            if (!$empresa) {
                return response()->json(['mensaje' => 'Empresa no encontrada.'], 404);
            }
            $pedido = Pedido::create([
                'cliente_id' => $usuario->id,
                'fecha' => Carbon::now('America/Lima'),
                'nombres' => $request->nombres,
                'direccion' => $request->direccion,
                'celular' => $request->celular,
                'total' => 0,
                'estado' => 'Pendiente',
                'nota' => $request->referencia ?? 'sin-nota',
                'empresa_id' => $request->empresa_id,
            ]);

            // Obtener los IDs de los productos de la empresa
            $productosEmpresaIds = $empresa->productos->pluck('id');
            // Filtrar las promociones relacionadas a los productos de la empresa
            $existe_entrega = EntregaPromociones::whereIn('producto_id', $productosEmpresaIds)
                ->where('user_id', $usuario->id)
                ->where('estado', false)
                ->get();

            // Obtener los producto_id de las promociones pendientes
            $productosConPromociones = EntregaPromociones::whereIn('producto_id', $productosEmpresaIds)
                ->where('user_id', $usuario->id)
                ->where('estado', false)
                ->pluck('producto_id');

            // Filtrar los registros de ClientePedidoProductos con esos producto_id
            $cantidad_pedidos = ClientePedidoProductos::where('cliente_id', $usuario->id)
                ->whereIn('producto_id', $productosConPromociones)
                ->get();

            foreach ($cantidad_pedidos as $pe) {
                $productoRelacionado = Producto::find($pe->producto_id);
                if ($productoRelacionado) {
                    // Restar la cantidad
                    $pe->cantidad = 0;
                    // Guardar los cambios
                    $pe->save();
                }
            }
            foreach ($existe_entrega as $promocion) {
                $promocion->update([
                    'pedido_id' => $pedido->id,
                    'estado' => true,
                ]);
            }

            return $pedido;
        } catch (\Throwable $th) {
            return response()->json([
                'mensaje' => 'Ocurrió un error inesperado.',
                'error' => $th->getMessage() . $th->getLine(),
            ], 500);
        }
    }
    public function store(RequestPedido $request)
    {
        DB::beginTransaction();
        try {


            // Validar el usuario
            $usuario = User::find($request->usuario_id);
            if (!$usuario) {
                return response()->json(['mensaje' => 'Usuario no encontrado.'], 401);
            }

            $totalPedido = 0.00;
            $pedido = null;
            // Validar y procesar productos o promociones
            $productos = $request->productos ?? [];
            if (empty($productos)) {
                $pedido = $this->sinProductos($request, $usuario);
            } else {
                $pedido = Pedido::create([
                    'cliente_id' => $usuario->id,
                    'fecha' => Carbon::now('America/Lima'),
                    'nombres' => $request->nombres,
                    'direccion' => $request->direccion,
                    'celular' => $request->celular,
                    'total' => 0,
                    'estado' => 'Pendiente',
                    'nota' => $request->referencia ?? 'sin-nota',
                    'empresa_id' => $request->empresa_id,
                ]);
                $productos = collect($request->productos); // Convertir en colección para operaciones masivas

                // Verificar productos inválidos o con cantidad <= 0
                if ($productos->isEmpty() || $productos->contains(fn($producto) => !$producto['id'] || $producto['cantidad'] <= 0)) {
                    throw new \Exception("Producto inválido o cantidad incorrecta.");
                }

                // Calcular precios y procesar productos
                $preciosYDetalles = $productos->map(function ($productoData) use ($pedido, $usuario, $request, &$totalPedido) {
                    $producto = Producto::find($productoData['id']);
                    if (!$producto) {
                        throw new \Exception("Producto no encontrado.");
                    }

                    // Calcular precio final
                    $precioFinal = $producto->promociones()
                        ->orderBy('cantidad', 'asc')
                        ->get()
                        ->reduce(function ($carry, $promocion) use ($productoData) {
                            return $productoData['cantidad'] >= $promocion->cantidad ? $promocion->precio_promocional : $carry;
                        }, $producto->precio);

                    $totalProducto = $precioFinal * $productoData['cantidad'];
                    $totalPedido += $totalProducto;

                    // Registrar detalles del pedido
                    Detalles::create([
                        'pedido_id' => $pedido->id,
                        'producto_id' => $producto->id,
                        'cantidad' => $productoData['cantidad'],
                        'precioUnitario' => $precioFinal,
                        'total' => $totalProducto,
                    ]);

                    return [
                        'producto' => $producto,
                        'cantidad' => $productoData['cantidad'],
                        'precioFinal' => $precioFinal,
                    ];
                });

                // Procesar promociones de empresa y actualizar registros
                $empresa = Empresa::find($request->empresa_id);
                if (!$empresa) {
                    throw new \Exception("Empresa no encontrada.");
                }

                $productosEmpresaIds = $empresa->productos->pluck('id');

                // Filtrar promociones activas excluyendo los productos procesados
                $existe_entrega = EntregaPromociones::whereIn('producto_id', $productosEmpresaIds)
                    ->where('user_id', $usuario->id)
                    ->whereNotIn('producto_id', $productos->pluck('id'))
                    ->where('estado', false)
                    ->update(['estado' => true, 'pedido_id' => $pedido->id]);

                // Procesar promociones unitarias y actualizar cantidades
                $productos->each(function ($productoData) use ($usuario, $pedido, $empresa) {
                    $producto = Producto::find($productoData['id']);
                    if (!$producto || $productoData['cantidad'] <= 0) {
                        throw new \Exception("Producto inválido o cantidad incorrecta.");
                    }

                    $promocionUnitaria = PromocionesUnitario::where('producto_id', $producto->id)->first();

                    // Buscar o crear registro de cantidad de pedidos del cliente
                    $cantidadPedidos = ClientePedidoProductos::firstOrNew(
                        ['cliente_id' => $usuario->id, 'producto_id' => $producto->id]
                    );

                    $nuevaCantidad = ($cantidadPedidos->exists ? $cantidadPedidos->cantidad : 0) + $productoData['cantidad'];


                    // Verificar si cumple con la promoción unitaria
                    if ($promocionUnitaria && $nuevaCantidad >= $promocionUnitaria->cantidad) {
                        $entregaPromocion = EntregaPromociones::firstOrNew(
                            [
                                'user_id' => $usuario->id,
                                'producto_id' => $producto->id,
                                'estado' => false,
                            ]
                        );

                        if (!$entregaPromocion->exists) {
                            // Crear entrega de promoción
                            $entregaPromocion->fill([
                                'pedido_id' => $pedido->id,
                                'cantidad' => 1,
                                'producto' => $promocionUnitaria->producto_gratis,
                            ]);
                            $entregaPromocion->save();
                            // Reducir cantidad utilizada en la promoción
                            $cantidadPedidos->decrement('cantidad', $promocionUnitaria->cantidad);
                        } else {
                            // Solo actualizar el estado si ya existía
                            $entregaPromocion->update(['estado' => true]);
                        }
                    }
                    $cantidadPedidos->cantidad = $nuevaCantidad;
                    $cantidadPedidos->save();
                });


                // Actualizar el total del pedido
                $pedido->update(['total' => $totalPedido]);

                $empresa = Empresa::find($request->empresa_id);
                // Crear una instancia del otro controlador
                $controlador_mensaje = new ControllerMensajes();
                // Filtrar los usuarios relacionados con la empresa y verificar si el usuario es tipo 'admin'
                // Filtrar los usuarios relacionados con la empresa que tengan el rol de 'admin'
                $admin = $empresa->usuarios
                    ->filter(function ($usuario) {
                        return $usuario->tipo === 'admin'; // Asumiendo que el rol se encuentra en la tabla `users`
                    })
                    ->first(); // Obtener el primer usuario que cumpla la condiciónner el primer usuario que cumpla la condición
                // Llamar al método que necesitas
                $controlador_mensaje->crearmensaje('Nuevo Pedido para la empresa.', $admin->id, $pedido->id);
            }


            // Confirmar la transacción
            DB::commit();


            return response()->json([
                'ruta' => route('pedido.confirmacion', ['slug' => $empresa->dominio, 'id' => $pedido->id]),
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'mensaje' => 'Ocurrió un error inesperado.',
                'error' => $e->getMessage() . $e->getLine(),
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $nombre_empresa, string $id) {}

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
        //
    }
}
