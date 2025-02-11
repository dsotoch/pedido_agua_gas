<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestCliente;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ControllerCliente extends Controller
{
    /**
     * Display a listing of the resource.
     */



    public function clientesporempresa()
    {
        $user = Auth::user();

        // Verificar si el usuario tiene una empresa asociada
        if (!$user->empresa) {
            return response()->json([
                'mensaje' => 'No se encontró una empresa asociada al usuario.'
            ], 404);
        }

        // Obtener los usuarios y sus clientes relacionados, con manejo de errores
        try {
            $clientes = $user->empresa
                ->usuarios()
                ->whereHas('Cliente') // Filtra solo los usuarios que tienen relación con Cliente
                ->with('Cliente') // Carga la relación Cliente
                ->get();

            return response()->json([
                'mensaje' => $clientes,
                'data' => $clientes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al obtener los clientes.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function verificardominio()
    {
         $subdominio='';
        // Buscar la empresa por dominio
        $empresa = Empresa::where('dominio', $subdominio)->first();

        if (!$empresa) {
            // Retornar un error si la empresa no existe
            return abort(404, 'Empresa no encontrada');
        }

        $usuario = Auth::user(); // Obtener usuario autenticado
        $cliente = null;
        $pedidos = collect(); // Usar una colección vacía por defecto
        $productos = collect();
        $usuarios = collect();
        $repartidores = collect();
        $perepartidor = collect();
        $pedidosrepartidor = collect();
        $pedidosdeldia = collect();
        $repartidoresConPedidos = collect();
        $desglosepagosdeldia = collect();
        $pedidospendientedepago = collect();
        $total_diario = 0.00;
        if ($usuario) {
            $cliente = $usuario;
            if ($cliente->tipo === "cliente") {
                // Manejar relaciones nulas usando el operador seguro ?->
                $cliente_usuario = $cliente->Cliente;
                if ($cliente_usuario) {
                    // Ordenar los pedidos directamente en el query builder
                    $pedidos = $cliente_usuario->pedido()
                        ->orderByRaw("
                            CASE 
                                WHEN estado = 'RECIBIDO' THEN 0
                                WHEN estado = 'ENTREGADO' THEN 2
                                ELSE 1
                            END
                        ") // Ordenar por prioridad de estado
                        ->orderByDesc('fecha') // Ordenar por fecha en orden descendente
                        ->paginate(20); // Aplicar paginación
                }



                $productos = $empresa->productos()->with('promociones', 'unitarios')->get();
            } else {
                $pedidosdeldia = Pedido::with('repartidor', 'repartidor.persona')->where('empresa_id', $empresa->id)
                    ->whereDate('fecha', Carbon::now('America/Lima')->toDateString())
                    ->get();
                $total_diario = Pedido::where('empresa_id', $empresa->id)
                    ->whereDate('fecha', Carbon::now('America/Lima')->toDateString())
                    ->sum('total');
                $resultado = User::with(['pedido' => function ($query) {
                    $query->whereDate('fecha', Carbon::now('America/Lima')->toDateString());
                }, 'persona'])
                    ->where('empresa_id', $empresa->id)
                    ->where('tipo', 'repartidor')
                    ->get();
                // Procesar los datos para obtener solo el nombre del repartidor y la cantidad de pedidos
                $repartidoresConPedidos = $resultado->map(function ($repartidor) {
                    return [
                        'repartidor' => $repartidor->persona->map(function ($persona) {
                            return $persona->nombres ?? 'Sin Nombre';
                        })->implode(', '),
                        'cantidad_asignados' => $repartidor->pedido->count(),
                        'pedidos' => $repartidor->pedido,

                    ];
                })->filter(function ($repartidor) {
                    // Filtrar solo los repartidores con al menos un pedido asignado
                    return $repartidor['cantidad_asignados'] >= 0;
                })->values();

                $pagosdeldia = Pedido::where('empresa_id', $empresa->id)
                    ->where('pago', true)
                    ->whereDate('fecha', Carbon::now('America/Lima')->toDateString())
                    ->get();

                // Agrupa los pedidos por método de pago y calcula la suma del total por método
                $desglosepagosdeldia = $pagosdeldia->groupBy('metodo')->map(function ($pedidos, $metodo) {
                    return [
                        'metodo' => $metodo,
                        'total' => $pedidos->sum('total'),
                    ];
                });

                // Si quieres, puedes convertir el resultado a un array para usarlo en la vista
                $desglosepagosdeldia = $desglosepagosdeldia->values()->toArray();

                $pedidospendientedepago = Pedido::where('empresa_id', $empresa->id)
                    ->where('metodo', 'account')
                    ->get();
                $pedidosrepartidor = $empresa->pedidos()
                    ->where('repartidor_id', $usuario->id) // Filtra los pedidos del repartidor actual
                    ->orderByRaw("
                    CASE 
                        WHEN estado = 'RECIBIDO' THEN 0
                        WHEN estado = 'ENTREGADO' THEN 2
                        ELSE 1
                    END
                ") // Ordenar por prioridad del estado
                    ->orderByDesc('fecha') // Ordenar por fecha en orden descendente
                    ->paginate(20);

                $pedidos = $empresa->pedidos()
                    ->orderByRaw("
                    CASE 
                        WHEN estado = 'RECIBIDO' THEN 0
                        WHEN estado = 'ENTREGADO' THEN 2
                        ELSE 1
                    END
                ") // Ordenar por prioridad de estado
                    ->orderByDesc('fecha') // Ordenar por fecha en orden descendente
                    ->paginate(20); // Aplicar paginación

                $repartidores = $empresa->usuarios?->filter(function ($user) {
                    return $user->persona && $user->tipo === 'repartidor';
                }) ?? collect();
                // Manejar relaciones de la empresa asociada al usuario
                $empresa = $usuario->empresa ?? $empresa;
                $productos = $empresa->productos()->with('promociones', 'unitarios')->get();
                $usuarios = $empresa->usuarios?->filter(function ($usuario) {
                    return $usuario->tipo !== 'cliente';
                }) ?? collect();
            }
            $colorsjson = $empresa->configuraciones; // Obtén colores en formato ['primary' => '#3498db', ...]
            $colors = json_decode($colorsjson, true);
            $imagen = $empresa->imagenes;
            $imagenes = json_decode($imagen, true);
        } else {
            $colorsjson = $empresa->configuraciones; // Obtén colores en formato ['primary' => '#3498db', ...]
            $colors = json_decode($colorsjson, true);
            $imagen = $empresa->imagenes;
            $imagenes = json_decode($imagen, true);
            // Si no hay usuario autenticado, cargar datos de la empresa
            $productos = $empresa->productos ?? collect();
            $usuarios = $empresa->usuarios?->filter(function ($usuario) {
                return $usuario->tipo !== 'cliente';
            }) ?? collect();
            $pedidos = $empresa->pedidos()
                ->orderByRaw("
                            CASE 
                                WHEN estado = 'RECIBIDO' THEN 0
                                WHEN estado = 'ENTREGADO' THEN 2
                                ELSE 1
                            END
                        ") // Ordenar por prioridad de estado
                ->orderByDesc('fecha') // Ordenar por fecha en orden descendente
                ->paginate(20); // Aplicar paginación
            $repartidores = $empresa->usuarios?->filter(function ($user) {
                return $user->persona && $user->tipo === 'repartidor';
            }) ?? collect();
        }

        // Retornar la vista con los datos
        return view('negocio', compact('imagenes','colors', 'pedidospendientedepago', 'desglosepagosdeldia', 'total_diario', 'repartidoresConPedidos', 'pedidosdeldia', 'productos', 'cliente', 'pedidos', 'empresa', 'usuarios', 'repartidores', 'pedidosrepartidor'));
    }

    public function index(): JsonResponse
    {
        $cliente = Cliente::all();
        return response()->json(['clientes' => $cliente]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestCliente $request)
    {

        try {
            $dominio = $request->dominio;
            $id_empresa = Empresa::where('dominio', $dominio)->first();
            // Crear un nuevo usuario
            $usuario = User::create([
                "usuario" => $request->telefono,
                "password" => bcrypt($request->password),
                "tipo" => 'cliente',
                "empresa_id" => $id_empresa->id
            ]);

            // Crear el cliente relacionado con el usuario
            $cliente = new Cliente();
            $cliente->telefono = $request->telefono;
            $cliente->nombres = $request->nombres;
            $cliente->apellidos = $request->apellidos;
            $cliente->direccion = $request->direccion;
            $cliente->nota = $request->nota ?? ''; // Valor por defecto para `nota` si no está presente
            $cliente->user_id = $usuario->id; // Relación con el usuario
            $cliente->save();

            // Respuesta exitosa
            return response()->json(
                ["mensaje" => "Cliente registrado correctamente."],
                201 // Código de estado HTTP "Creado"
            );
        } catch (\Exception $e) {
            // Respuesta en caso de error
            return response()->json(
                ["error" => "Ocurrió un error al registrar el cliente.", "detalles" => $e->getMessage()],
                500 // Código de estado HTTP "Error interno del servidor"
            );
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
    public function update(RequestCliente $request, string $id): JsonResponse
    {
        try {
            $cliente = Cliente::where('id', $id)->first();
            if ($cliente) {
                $cliente->update([
                    $cliente->telefono = $request->telefono,
                    $cliente->nombres = $request->nombres,
                    $cliente->apellidos = $request->apellidos,
                    $cliente->direccion = $request->direccion,
                    $cliente->nota = $request->nota ?? '',
                ]);

                return response()->json(
                    ["mensaje" => "Cliente modificado correctamente."],
                    201 // Código de estado HTTP para "Creado"
                );
            } else {
                return response()->json(["error" => "Ocurrió un error al actualizar al Cliente", "detalles" => "No se Encontro al Cliente con ese Id"], 500);
            }
        } catch (\Exception $th) {
            return response()->json(["error" => "Ocurrió un error al actualizar al Cliente", "detalles" => $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
