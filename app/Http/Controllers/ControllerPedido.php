<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestPedido;
use App\Jobs\SendMessage;
use App\Models\ClientePedidoProductos;
use App\Models\Detalles;
use App\Models\Empresa;
use App\Models\EntregaPromociones;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Promociones;
use App\Models\PromocionesUnitario;
use App\Models\Salidas;
use App\Models\Stock;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ControllerPedido extends Controller
{
    public function editar(Request $request)
    {

        if (!Auth::check()) {
            abort(403, 'Usuario no Autenticado');
        }

        try {
            $usuario = Auth::user();
            $admin = User::find($usuario->id);
            // Validar los datos del formulario
            $request->validate([
                'id_pedido' => 'required|integer|exists:pedidos,id', // Asegura que el ID del pedido exista
                'field_cliente' => 'required|string|max:255',
                'field_Celular' => 'required|digits_between:6,15',
                'field_direccion' => 'required|string|max:255',
                'field_referencia' => 'nullable|string|max:255',
                'estado_pedido' => 'required|in:Pendiente,En Camino,Entregado,Anulado',
                'estado_pago' => 'required|in:Pendiente de pago,Pagado,Deuda pendiente',
                'medio_pago' => 'nullable|in:efectivo,yape,account',
                'notas' => 'nullable|string|max:500',
            ]);

            // Buscar el pedido por ID
            $pedido = Pedido::findOrFail($request->id_pedido);

            // Actualizar los datos del pedido
            $pedido->nombres = $request->field_cliente;
            $pedido->celular = $request->field_Celular;
            $pedido->direccion = $request->field_direccion;
            $pedido->nota = $request->field_referencia;
            $pedido->estado = $request->estado_pedido;
            $pedido->pago = $request->estado_pago == 'Pagado' && $request->medio_pago != 'account' ? true : false;
            $pedido->metodo = $request->medio_pago;
            $pedido->nota_interna = $request->notas;
            $pedido->actor = $admin->persona->nombres;
            // Guardar los cambios
            $pedido->save();

            // Retornar una respuesta (puede ser un redirect o JSON según necesites)
            return response()->json([
                'mensaje' => 'Pedido actualizado correctamente.',
                'pedido' => $pedido,
            ]);
        } catch (\Throwable $th) {
            return response()->json(['mensaje' => $th->getMessage()], 500);
        }
    }

    public function buscar_pedido($id)
    {
        try {
            $pedido = Pedido::findOr($id);
            return response()->json(['mensaje' => $pedido], 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

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
            'repartidor_id' => 'required|exists:users,id',
        ]);

        try {
            // Buscar el pedido y el repartidor
            $pedido = Pedido::findOrFail($validated['pedido_id']);
            $empresa = Empresa::where('id', $pedido->empresa_id)->first();
            $repartidor = User::findOrFail($validated['repartidor_id']);

            // Verificar si el repartidor ya está asignado a este pedido
            if (!is_null($pedido->repartidor_id) && $pedido->repartidor_id === $repartidor->id) {
                return response()->json([
                    'mensaje' => 'El repartidor ya esta asignado a este pedido.',
                ], 409); // Código 409: Conflicto
            }
            // Actualizar el pedido con el ID del repartidor
            $pedido->update([
                'repartidor_id' => $repartidor->id,
            ]);
            $pedido_completo = Pedido::with('detalles', 'detalles.producto', 'entregaPromociones')->find($pedido->id);
            $mensaje = ['operacion' => 'asignacion', 'mensaje' => 'Pedido Asignado.', 'pedido_id' => $validated['pedido_id'], 'pedido' => $pedido_completo, 'tiempo' => $empresa->tiempo];

            SendMessage::dispatch($mensaje, $repartidor->id);

            // Respuesta exitosa
            return response()->json([
                'mensaje' => 'Pedido asignado correctamente.',
                'repartidor' => $repartidor->persona->nombres,

            ], 201);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'mensaje' => 'Ocurrio un error al asignar el pedido.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function pedidorecibidorepartidor($id)
    {
        try {
            $pedido = Pedido::find($id);
            $empresa = Empresa::where('id', $pedido->empresa_id)->first();

            if ($pedido) {
                $pedido->update([
                    "estado" => 'En Camino'
                ]);
                $mensaje = ['operacion' => 'confirmacion', 'mensaje' => 'Tu pedido ha sido tomado por el repartidor y está en camino.', 'pedido_id' => $pedido->id, 'estado' => $pedido->estado, 'tiempo' => $empresa->id];
                $mensaje2 = ['operacion' => 'pedido_tomado', 'mensaje' => '', 'pedido_id' => $pedido->id, 'estado' => $pedido->estado];

                $cliente = $pedido->usuario->id;
                $admin = $pedido->empresa->usuarios()->where('tipo', 'admin')->first();
                if ($admin) {
                    SendMessage::dispatch($mensaje, $cliente);
                    SendMessage::dispatch($mensaje2, $admin->id);
                }


                return response()->json(["mensaje" => "Pedido Confirmado.", 'estado' => $pedido->estado], 200);
            } else {
                return response()->json(["mensaje" => "Pedido No Encontrado."], 404);
            }
        } catch (\Throwable $th) {
            return response()->json(["mensaje" => $th->getMessage() . $th->getLine()], 500);
        }
    }

    public function anular(Request $request)
    {
        if (!Auth::check()) {
            abort(403, 'Usuario no Autenticado.');
        }
        $user_actual = User::with('persona')->where('id', Auth::user()->id)->first();
        try {
            $pedido = Pedido::find($request->id_pedido);
            $pedido->update([
                'estado' => 'Anulado',
                'nota_interna' => $request->notas,
                'actor' => $user_actual->persona->nombres
            ]);
            $array_productos = [];

            foreach ($pedido->detalles as $detalle) {
                array_push($array_productos, [
                    'cantidad' => $detalle->cantidad,
                    'producto_id' => $detalle->producto->id . (!empty($detalle->tipo) ? "_{$detalle->tipo}" : '')
                ]);
            }

            foreach ($pedido->entregaPromociones as $promocion) {
                array_push($array_productos, [
                    'cantidad' => $promocion->cantidad,
                    'producto_id' => Producto::where('nombre',$promocion->producto)->value('id')
                ]);
            }


            $mensaje = ['operacion' => 'anulacion', 'mensaje' => 'El pedido ha sido anulado.', 'pedido_id' => $pedido->id, 'estado' => $pedido->estado];
            $admin = $pedido->empresa->usuarios()->where('tipo', 'admin')->first();
            $salida=new ControllerSalidas();
            $salida->sumarStockActual_cuando_anula_pedido($user_actual->persona?->nombres, $array_productos);
            SendMessage::dispatch($mensaje, $admin->id);
            return response()->json(['mensaje' => 'El pedido #' . $request->id_pedido . ' se anulo Correctamente', 'pedido_id' => $pedido->id], 200);
        } catch (\Throwable $th) {
            return response()->json(['mensaje' => $th->getMessage()], 500);
        }
    }
    public function cambiarestadopago(Request $request)
    {

        if (!Auth::check()) {
            abort(403, 'Usuario no Autenticado.');
        }
        $user_actual = User::with('persona')->where('id', Auth::user()->id)->first();
        // Buscar el pedido

        $pedido = Pedido::find($request->id_pedido);
        $empresa = Empresa::where('id', $pedido->empresa_id)->first();

        if ($pedido) {
            // Obtener el administrador de la empresa relacionada con el pedido
            $admin = $empresa->usuarios()->where('tipo', 'admin')->first();

            try {
                $metodo = $request->paymentMethod; // Método de pago enviado en el request
                $pago = in_array($metodo, ['yape', 'efectivo']); // Validar métodos permitidos
                $notas = $request->notas;
                // Actualizar el pedido dentro de una transacción
                DB::transaction(function () use ($pedido, $metodo, $pago, $notas, $user_actual) {
                    $pedido->update([
                        'estado' => 'Entregado',
                        'pago' => $pago,
                        'metodo' => $metodo,
                        'nota_interna' => $notas,
                        'actor' => $user_actual->persona->nombres

                    ]);
                });



                $mensaje = [
                    'operacion' => 'finalizado',
                    'mensaje' => 'El proceso del pedido ha finalizado correctamente.',
                    'pedido_id' => $pedido->id,
                    'estado' => $pedido->estado,
                    'metodo' => $pedido->metodo,
                ];

                // Enviar notificación al administrador
                if ($user_actual->tipo != 'admin') {
                    SendMessage::dispatch($mensaje, $admin->id);
                }

                return response()->json([
                    "mensaje" => "El pedido ha sido modificado correctamente.",
                    'pedido_id' => $pedido->id,
                    'nuevo_metodo' => $pedido->metodo
                ], 200);
            } catch (\Exception $th) {
                // Manejar errores y devolver un mensaje de error
                return response()->json([
                    "mensaje" => "Ha ocurrido un error al modificar el pedido.",
                ], 500);
            }
        } else {
            // Manejar el caso donde no se encuentre el pedido
            return response()->json([
                "mensaje" => "El pedido no ha sido encontrado.",
            ], 404);
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
                return "empresa";
            }
            // Obtener los IDs de los productos de la empresa
            $productosEmpresaIds = $empresa->productos->pluck('id');
            // Filtrar las promociones relacionadas a los productos de la empresa
            $existe_entrega = EntregaPromociones::whereIn('producto_id', $productosEmpresaIds)
                ->where('user_id', $usuario->id)
                ->where('estado', false)
                ->get();
            if ($existe_entrega->count() === 0) {
                return "productos";
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

            $usuario = Auth::user();
            // Validar el usuario                
            if (User::findOrFail($usuario->id)->tipo == "cliente") {
                $usuario = User::find($request->usuario_id);
                if (!$usuario) {
                    return response()->json(['mensaje' => 'Usuario no encontrado.'], 401);
                }
            }
            $totalPedido = 0.00;
            $pedido = null;
            $empresa = collect();
            // Validar y procesar productos o promociones
            $productos = $request->productos ?? [];
            if (empty($productos)) {
                if (User::findOr($usuario->id)->tipo != "cliente") {
                    return response()->json(['mensaje' => 'Sin productos enviados para procesar.'], 412);
                }
                $pedido = $this->sinProductos($request, $usuario);
                if ($pedido === "productos") {
                    return response()->json(['mensaje' => 'Sin productos enviados para procesar.'], 412);
                }
                if ($pedido === "empresa") {
                    return response()->json(['mensaje' => 'Empresa No encontrada.'], 404);
                }
                $empresa = Empresa::find($request->empresa_id);
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
                $preciosYDetalles = $productos->map(function ($productoData) use ($pedido, &$totalPedido) {
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
                        'tipo' => $productoData['tipo'],
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

                if ($usuario->tipo == 'cliente') {
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
                        // Verificamos si hay una promoción y si se cumple la cantidad requerida
                        $cantidadRestante = $nuevaCantidad; // Valor predeterminado
                        if ($promocionUnitaria) {
                            if ($promocionUnitaria->cantidad == 1) {
                                // Crear o actualizar promoción con estado true
                                $entregaPromocion = EntregaPromociones::firstOrNew([
                                    'user_id' => $usuario->id,
                                    'producto_id' => $producto->id,
                                    'estado' => false
                                ]);

                                if (!$entregaPromocion->exists) {
                                    $entregaPromocion->fill([
                                        'pedido_id' => $pedido->id,
                                        'cantidad' => $productoData['cantidad'], // Ajustar la cantidad de la promoción
                                        'producto' => $promocionUnitaria->producto_gratis,
                                        'estado' => true, // Se entrega automáticamente
                                    ]);
                                    $entregaPromocion->save();
                                } else {
                                    $entregaPromocion->update([
                                        'pedido_id' => $pedido->id,
                                        'cantidad' => $productoData['cantidad'],
                                        'estado' => true
                                    ]);
                                }

                                // Restar la cantidad utilizada en la promoción
                                $cantidadRestante = max(0, $nuevaCantidad - $productoData['cantidad']);
                            } elseif ($nuevaCantidad > $promocionUnitaria->cantidad) {
                                // Buscar o crear la promoción con estado true
                                $entregaPromocion = EntregaPromociones::firstOrNew([
                                    'user_id' => $usuario->id,
                                    'producto_id' => $producto->id,
                                    'estado' => false
                                ]);

                                if (!$entregaPromocion->exists) {
                                    $entregaPromocion->fill([
                                        'pedido_id' => $pedido->id,
                                        'cantidad' => 1,
                                        'producto' => $promocionUnitaria->producto_gratis,
                                        'estado' => true, // Se entrega de inmediato
                                    ]);
                                    $entregaPromocion->save();
                                } else {
                                    // Si ya existía, aseguramos que se activa
                                    $entregaPromocion->update(['pedido_id' => $pedido->id, 'estado' => true]);
                                }

                                // Restar la cantidad utilizada en la promoción
                                $cantidadRestante = max(0, $nuevaCantidad - $promocionUnitaria->cantidad);
                            } elseif ($nuevaCantidad == $promocionUnitaria->cantidad) {
                                // Crear una promoción pero con estado false
                                $entregaPromocion = EntregaPromociones::firstOrNew([
                                    'user_id' => $usuario->id,
                                    'producto_id' => $producto->id,
                                ]);

                                if (!$entregaPromocion->exists) {
                                    $entregaPromocion->fill([
                                        'pedido_id' => $pedido->id,
                                        'cantidad' => 1,
                                        'producto' => $promocionUnitaria->producto_gratis,
                                        'estado' => false, // No se entrega aún
                                    ]);
                                    $entregaPromocion->save();
                                }

                                // Nueva cantidad es igual a la cantidad de la promoción
                                $cantidadRestante = $nuevaCantidad;
                            }
                        } else {
                            $cantidadRestante = $nuevaCantidad;
                        }



                        // Guardar la nueva cantidad después de aplicar la promoción
                        $cantidadPedidos->cantidad = $cantidadRestante;
                        $cantidadPedidos->save();
                    });
                }
                $controlador_cupon = new CuponController();
                if (!empty($request->cupon)) {
                    $resultados = $controlador_cupon->aplicarCupon($request->cupon, $totalPedido, $request->empresa_id);
                    $pedido->update([
                        'total' => $resultados['total_con_descuento'],
                        'descuento' => $resultados['descuento'],
                        'cupon' => $request->cupon
                    ]);
                } else {
                    // Actualizar el total del pedido
                    $pedido->update(['total' => $totalPedido]);
                }


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

                $pedido_completo = Pedido::with('detalles', 'detalles.producto', 'entregaPromociones')->find($pedido->id);
                $controlador_mensaje->crearmensaje('Nuevo Pedido para la empresa.', $admin->id, $pedido->id, $pedido_completo, $empresa->tiempo);
            }
            // Confirmar la transacción
            DB::commit();
            return response()->json([
                'ruta' => route('pedido.confirmacion', ['slug' => $empresa->dominio, 'id' => $pedido->id]),
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'mensaje' => $e->getMessage()  . ' ' . $e->getLine(),
            ], 500);
        }
    }

    public function pedido_rapido(Request $request)
    {
        DB::beginTransaction();

        try {

            $productosIds = [];
            $usuario = Auth::user();
            $usuario = User::findOr($usuario->id);
            $totalPedido = 0.00;
            $pedido = null;
            $empresa = collect();
            // Validar y procesar productos o promociones
            $productos = $request->productos ?? [];
            $pedido = Pedido::create([
                'cliente_id' => $usuario->id ?? null,
                'fecha' => Carbon::now('America/Lima'),
                'nombres' => $request->nombres ?? 'Venta Rapida',
                'direccion' => $request->direccion ?? 'Venta Rapida',
                'celular' => $request->celular ?? 123456789,
                'pago' => true,
                'metodo' => $request->paymentMethod,
                'actor' => $usuario->persona->nombres,
                'total' => 0,
                'estado' => 'Entregado',
                'nota' => $request->referencia ?? 'sin-nota',
                'empresa_id' => $request->empresa_id,
            ]);
            $productos = collect($request->productos); // Convertir en colección para operaciones masivas

            // Verificar productos inválidos o con cantidad <= 0
            if ($productos->isEmpty() || $productos->contains(fn($producto) => !$producto['id'] || $producto['cantidad'] <= 0)) {
                throw new \Exception("Producto inválido o cantidad incorrecta.");
            }

            // Calcular precios y procesar productos
            $preciosYDetalles = $productos->map(function ($productoData) use ($pedido, &$totalPedido, &$productosIds) {
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
                    'tipo' => $productoData['tipo'] ?? '',
                    'cantidad' => $productoData['cantidad'],
                    'precioUnitario' => $precioFinal,
                    'total' => $totalProducto,
                ]);

                $productoId = $producto->id . ($productoData['tipo'] ? '_' . $productoData['tipo'] : '');
                $cantidad = $productoData['cantidad'] ?? 0;

                // Buscar si ya existe en el array
                $index = array_search($productoId, array_column($productosIds, 'producto_id'));

                if ($index !== false) {
                    // Si ya existe, sumamos la cantidad
                    $productosIds[$index]['cantidad'] += $cantidad;
                } else {
                    // Si no existe, lo agregamos con array_push()
                    array_push($productosIds, ['cantidad' => $cantidad, 'producto_id' => $productoId]);
                }

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


            // Procesar promociones unitarias y actualizar cantidades
            $cantidad = 0;
            $productos->each(function ($productoData) use ($usuario, $pedido, $empresa, &$cantidad) {

                $producto = Producto::find($productoData['id']);
                if (!$producto || $productoData['cantidad'] <= 0) {
                    throw new \Exception("Producto inválido o cantidad incorrecta.");
                }

                $promocionUnitaria = PromocionesUnitario::where('producto_id', $producto->id)->first();

                $nuevaCantidad = $productoData['cantidad'];
                // Verificamos si hay una promoción y si se cumple la cantidad requerida
                if ($promocionUnitaria) {
                    if ($nuevaCantidad >= $promocionUnitaria->cantidad) {
                        $cantidad++;
                        $entregaPromocion = EntregaPromociones::updateOrCreate(
                            [
                                'user_id' => $usuario->id,
                                'producto_id' => $producto->id,
                                'pedido_id' => $pedido->id
                            ],
                            [
                                'cantidad' => $cantidad,
                                'producto' => $promocionUnitaria->producto_gratis,
                                'estado' => true, // No se entrega aún
                            ]
                        );
                    }
                }
            });


            $pedido->update(['total' => $totalPedido]);




            $pedido_completo = Pedido::with('entregaPromociones')->find($pedido->id);
            $promocion = optional($pedido_completo->entregaPromociones)
                ->where('pedido_id', $pedido->id)
                ->where('estado', true)
                ->first() ?? null;
            // Confirmar la transacción

            if ($usuario->tipo == 'repartidor') {
                $salida = new ControllerSalidas();
                array_push($productosIds, [
                    'cantidad' => optional($pedido_completo->entregaPromociones)
                        ->where('pedido_id', $pedido->id)
                        ->where('estado', true)
                        ->first()->cantidad ?? 0,
                    'producto_id' => Producto::where('nombre', $promocion)->value('id')

                ]);

                $salida_id = Salidas::whereDate('fecha', Carbon::now('America/Lima'))->where('repartidor', $usuario->persona->nombres)->first();
                $stock = Stock::where('salida_id', $salida_id->id)->first();
                // Restar stock de todos los productos en una sola ejecución
                $salida->restarStockActual($stock->id, $productosIds);
            }



            DB::commit();
            return response()->json(['mensaje' => 'Pedido Finalizado Correctamente', 'promocion' => $promocion], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'mensaje' => $e->getMessage()  . ' ' . $e->getLine(),
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
        try {
            $pedido = Pedido::findOr($id);
            $pedido->delete();
            return response()->json(['mensaje' => 'Pedido' . ' ' . $id . ' ' . 'eliminado Correctamente.']);
        } catch (\Throwable $th) {
            return response()->json(['mensaje' => 'Ocurrio un error al eliminar el pedido']);
        }
    }
}
