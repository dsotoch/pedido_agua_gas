<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestAdmin;
use App\Http\Requests\RequestEmpresa;
use App\Models\empresa;
use App\Models\Pedido;
use App\Models\Persona;
use App\Models\Promociones;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ControllerEmpresa extends Controller
{
    public function index_distribuidora($slug)
    {
        // Buscar la empresa por dominio
        $empresa = Empresa::where('dominio', $slug)->first();

        if (!$empresa) {
            // Retornar un error si la empresa no existe
            return abort(404, 'Empresa no encontrada');
        }

        $usuario = Auth::user(); // Obtener usuario autenticado
        $productos = $empresa->productos->filter(function ($pro) {
            return $pro->comercializable === 1;
        });
        $colorsjson = $empresa->configuraciones; // Obtén colores en formato ['primary' => '#3498db', ...]
        $colors = json_decode($colorsjson, true);
        $imagenes = json_decode($empresa->imagenes, true);

        //Promociones del cliente actual
        $compras_del_cliente = $usuario?$usuario->clientepedido: collect();
        $compras_filtradas = collect();
        if ($compras_del_cliente) {
            // Obtener IDs de productos con promociones (asegúrate de que 'unitarios' sea una relación cargada)
            $productos_con_promociones = $productos->filter(function ($producto) {
                return $producto->unitarios !== null; // Asegúrate de que 'unitarios' existe
            })->pluck('unitarios', 'id'); // Obtén los 'unitarios' (promociones) con sus cantidades requeridas, indexados por 'id'

            // Filtrar las compras del cliente que coincidan con los productos en promoción
            $compras_filtradas = $compras_del_cliente->filter(function ($compra) use ($productos_con_promociones) {
                return $productos_con_promociones->has($compra->producto_id); // Verifica si el producto tiene promoción
            });

            // Calcular cuántos productos faltan para cumplir las promociones
            $promociones_faltantes = $productos_con_promociones->map(function ($unitarios, $producto_id) use ($compras_filtradas) {
                // Total comprado para este producto
                $total_comprado = $compras_filtradas->where('producto_id', $producto_id)->sum('cantidad');

                // Calcular lo que falta
                $faltante = $unitarios['cantidad'] - $total_comprado;

                // Retornar el producto_id y el faltante
                return [
                    'producto_id' => $producto_id,
                    'faltante' => $faltante > 0 ? $faltante : 0, // Asegurar que no sea negativo
                    'meta' => $unitarios['cantidad'],
                ];
            });
        }



        /*   $cliente = null;
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
        }*/

        // Retornar la vista con los datos
        return view('negocio', compact(
            'promociones_faltantes',
            'empresa',
            'usuario',
            'productos',
            'imagenes',
            'colors'
        ));
    }
    public function index_productos()
    {
        // Obtener el usuario autenticado
        $usuario_auth = Auth::user();

        // Cargar al usuario autenticado con sus empresas y productos
        $usuario = User::with(['empresas.productos', 'empresas.usuarios.persona'])
            ->find($usuario_auth->id);

        // Obtener la primera empresa (o manejar múltiples si es necesario)
        $empresa = $usuario->empresas->first();


        // Retornar la vista con el usuario y la empresa seleccionada
        return view('productos', compact('usuario', 'empresa'));
    }


    public function index_clientes()
    {
        $usuario_auth = Auth::user();

        // Cargar al usuario autenticado con la relación empresa y productos
        $usuario = User::with(['empresas', 'empresas.productos'])->find($usuario_auth->id);

        // Obtener la empresa con usuarios filtrados por activos y su relación con persona
        $empresa = Empresa::with(['usuarios' => function ($query) {
            $query->where('tipo', 'cliente');
        }, 'usuarios.persona'])
            ->where('id', $usuario->empresa_id)
            ->first();

        return view('clientes', compact('usuario', 'empresa'));
    }

    public function index_pagos_del_dia()
    {
        $usuario_auth = Auth::user();
        $usuario = User::with('empresas', 'empresas.productos')->where('id', $usuario_auth->id)->first();
        $empresa = $usuario->empresa;
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

        return view('pagos', compact('desglosepagosdeldia', 'usuario', 'empresa'));
    }


    public function buscarEmpresas(Request $request)
    {
        // Obtén el término de búsqueda
        $filtro = $request->input('filtro');

        // Valida el término
        if (!$filtro) {
            return response()->json(['error' => 'El filtro es obligatorio'], 400);
        }

        // Busca por ciudad o nombre
        $empresas = Empresa::where('direccion', 'like', "%$filtro%")
            ->orWhere('nombre', 'like', "%$filtro%")
            ->get();

        // Devuelve los resultados en formato JSON
        return response()->json($empresas);
    }


    public function conf($id)
    {
        $empresa = empresa::find($id);
        $colorsjson = $empresa->configuraciones; // Obtén colores en formato ['primary' => '#3498db', ...]
        $colors = json_decode($colorsjson, true);
        return view('plantilla', compact('empresa', 'colors'));
    }

    public function configurarColores(Request $request, $id)
    {
        // Validar los datos enviados (solo permite colores hexadecimales o valores nulos)
        $validated = $request->validate([
            'button-color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        // Filtrar valores inválidos (nulos, vacíos, solo ceros)
        $filtered = array_filter($validated, function ($value) {
            // Aceptar solo si no es nulo, no está vacío, y no es solo ceros
            return $value !== null && $value !== '' && preg_match('/^(?!0+$).+/', $value);
        });

        // Verificar si no hay valores válidos después del filtro
        if (empty($filtered)) {
            return redirect()->back()->with('plantilla', 'No se enviaron datos válidos para actualizar.');
        }

        // Buscar la empresa por ID
        $empresa = Empresa::findOrFail($id);

        // Decodificar configuraciones actuales (si existen)
        $configuracionesActuales = $empresa->configuraciones ? json_decode($empresa->configuraciones, true) : [];

        // Actualizar los colores enviados, manteniendo los existentes si no se envían nuevos
        $coloresActualizados = array_merge($configuracionesActuales, [
            'button' => $filtered['button-color'] ?? $configuracionesActuales['button'] ?? null,
        ]);

        // Guardar los colores actualizados en la columna `configuraciones`
        $empresa->configuraciones = json_encode($coloresActualizados);
        $empresa->save();

        // Redirigir con un mensaje de éxito
        return redirect()->back()->with('plantilla', 'Configuraciones guardadas correctamente.');
    }



    public function store(RequestEmpresa $request)
    {
        $codigo_entrega_pe = 'sL!7x@A9zQ#kWR3';


        try {

            if ($request->codigo !== $codigo_entrega_pe) {
                return back()->withErrors(['mensaje' => 'El Codigo Enviado no Corresponde a Entrega.pe , No tienes Autorización  para Registrar tu Distribuidura.', 500]);
            }
            DB::beginTransaction();

            // Crear empresa
            $empresa = new empresa();
            $empresa->nombre = $request->nombre;
            $empresa->dominio = $request->slug;
            $empresa->direccion = $request->direccion;
            $empresa->descripcion = $request->descripcion;

            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $uniqueLogoName = uniqid() . '_' . $logo->getClientOriginalName();
                $empresa->logo = $logo->storeAs('logos', $uniqueLogoName, 'public');
            }

            if ($request->hasFile('imagenes')) {
                $imagenes = [];
                foreach ($request->file('imagenes') as $imagen) {
                    $uniqueImageName = uniqid() . '_' . $imagen->getClientOriginalName();
                    $imagenes[] = $imagen->storeAs('galeria', $uniqueImageName, 'public');
                }
                $empresa->imagenes = json_encode($imagenes); // Convierte a JSON válido
            }

            $empresa->whatsapp = $request->whatsapp;
            $empresa->facebook = $request->facebook;
            $empresa->telefono = $request->telefono;
            $empresa->servicios = $request->has('servicios') ? json_encode($request->servicios) : null;

            $empresa->save();


            DB::commit();

            return redirect()->route('empresa.adminview', ['id' => $empresa->id])
                ->with(['mensaje' => 'La empresa se ha registrado correctamente. Ahora, por favor, registra al usuario administrador para gestionar la empresa.', 'empresa' => $empresa->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['mensaje' => 'Hubo un error al procesar la solicitud. Por favor, intente nuevamente.' . $e->getMessage()]);
        }
    }
    public function crearAdminView()
    {
        return view('registrouseradmin');
    }

    public function crearAdmin(RequestAdmin $request)
    {
        DB::beginTransaction();

        try {
            // Crear el usuario
            $usuario = User::create([
                "usuario" => $request->telefono,
                "password" => bcrypt($request->password),
                "tipo" => $request->tipo ? $request->tipo : 'cliente',
            ]);


            // Crear la persona asociada al usuario
            Persona::create([
                "nombres" => $request->nombres . ' ' . $request->apellidos,
                "dni" => $request->dni,
                "correo" => $request->correo,
                'nota' => $request->nota,
                'direccion' => $request->direccion,
                "user_id" => $usuario->id
            ]);

            $empresa = empresa::find($request->empresa_id);

            if (!$usuario->empresas()->where('empresa_id', $empresa->id)->exists()) {
                $usuario->empresas()->attach($empresa->id);
            }
            DB::commit(); // Confirmar la transacción

            // Respuesta exitosa
            return redirect()->route('empresa.configView')->with([
                'id' => $empresa->id,
                'administrador' => "Administrador creado correctamente. Ahora, configura el color de tu botón para personalizarlo."
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir la transacción en caso de error

            // Manejar el error
            return back()->withErrors(['mensaje' => $e->getMessage(), 'empresa' => $request->empresa_id]);
        }
    }
}
