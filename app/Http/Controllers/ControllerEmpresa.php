<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestAdmin;
use App\Http\Requests\RequestEmpresa;
use App\Models\Cupones;
use App\Models\Empresa;
use App\Models\Pedido;
use App\Models\Persona;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ControllerEmpresa extends Controller
{
    public function index_favoritas()
    {
        if (!Auth::check()) {
            abort(403, "Usuario no autenticado");
        }
        $usuario = Auth::user();
        $usuario = User::find($usuario->id);
        $empresa = $usuario->empresas()->first();
        return view('favoritas', compact('usuario', 'empresa'));
    }
    public function index_cupones()
    {
        if (!Auth::check()) {
            abort(403, "Usuario no autenticado");
        }
        $usuario = Auth::user();
        $usuario = User::find($usuario->id);
        $empresa = $usuario->empresas()->first();
        $cupones = Cupones::where('empresa_id', $empresa->id)->get();
        return view('cupones', compact('usuario', 'empresa', 'cupones'));
    }
    public function modificar_empresa(Request $request)
    {
        try {
            if (!Auth::check()) {
                abort(403, "Usuario no autenticado");
            }

            DB::beginTransaction();

            // Buscar la empresa existente
            $empresa = Empresa::find($request->empresa_id);

            if (!$empresa) {
                return back()->withErrors(['mensaje' => 'La empresa no existe.']);
            }

            // Actualizar datos
            $empresa->nombre = $request->nombre;
            $empresa->dominio = $request->slug;
            $empresa->direccion = $request->direccion;
            $empresa->descripcion = e($request->descripcion);
            $empresa->hora_inicio = date('H:i:s', strtotime($request->hora_inicio));
            $empresa->hora_fin = date('H:i:s', strtotime($request->hora_fin));

            // Actualizar logo
            if ($request->hasFile('logo')) {
                if ($empresa->logo) {
                    Storage::disk('public')->delete($empresa->logo);
                }

                $logo = $request->file('logo');
                $uniqueLogoName = uniqid() . '_' . $logo->getClientOriginalName();
                $empresa->logo = $logo->storeAs('logos', $uniqueLogoName, 'public');
            }

            // Actualizar logo vertical
            if ($request->hasFile('logo_vertical')) {
                if ($empresa->logo_vertical) {
                    Storage::disk('public')->delete($empresa->logo_vertical);
                }

                $logoVertical = $request->file('logo_vertical');
                $uniqueLogoName = uniqid() . '_' . $logoVertical->getClientOriginalName();
                $empresa->logo_vertical = $logoVertical->storeAs('logos', $uniqueLogoName, 'public');
            }

            // Actualizar imágenes de galería
            if ($request->hasFile('imagenes')) {
                // Eliminar imágenes antiguas
                if ($empresa->imagenes) {
                    $imagenesAnteriores = json_decode($empresa->imagenes, true);
                    foreach ($imagenesAnteriores as $img) {
                        Storage::disk('public')->delete($img);
                    }
                }

                // Guardar nuevas imágenes
                $imagenes = [];
                foreach ($request->file('imagenes') as $imagen) {
                    $uniqueImageName = uniqid() . '_' . $imagen->getClientOriginalName();
                    $imagenes[] = $imagen->storeAs('galeria', $uniqueImageName, 'public');
                }
                $empresa->imagenes = json_encode($imagenes);
            }

            // Otros datos
            $empresa->whatsapp = $request->whatsapp;
            $empresa->facebook = $request->facebook;
            $empresa->telefono = $request->telefono;
            $empresa->servicios = $request->has('servicios') ? json_encode($request->servicios) : null;

            $empresa->save();

            DB::commit();

            return redirect()->back()->with([
                'mensaje' => 'Datos de la distribuidora modificados correctamente.',
                'empresa' => $empresa->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors([
                'mensaje' => 'Hubo un error al procesar la solicitud. ' . $e->getMessage()
            ]);
        }
    }


    public function index_empresa()
    {
        $usuario = Auth::user();
        $usuario = User::find($usuario->id);
        $empresa = $usuario->empresas()->first();
        $serviciosSeleccionados = json_decode($empresa->servicios, true) ?? []; // Convertir JSON en array

        return view('datos_empresa', compact('usuario', 'empresa', 'serviciosSeleccionados'));
    }
    public function index_reportes()
    {
        $usuario = Auth::user();
        $usuario = User::find($usuario->id);
        $empresa = $usuario->empresas()->first();
        $pedidos = $empresa->pedidos()->with('usuario', 'repartidor', 'repartidor.persona', 'entregaPromociones')->get();
        return view('reportes', compact('usuario', 'empresa', 'pedidos'));
    }
    public function index_usuarios()
    {
        $usuario = Auth::user();
        $usuario = User::find($usuario->id);
        $empresa = $usuario->empresas()->first();
        $usuarios = $empresa->usuarios()->with('persona')->get(); // Cargar 'persona' para los usuarios de la empresa
        return view('usuarios', compact('usuario', 'usuarios', 'empresa'));
    }
    public function index_mis_datos()
    {
        $usuario = Auth::user();
        $pedido = Pedido::where('cliente_id', $usuario->id)->latest()->first();

        return view('mis_datos', compact('usuario', 'pedido'));
    }
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
        $compras_del_cliente = $usuario ? $usuario->clientepedido : collect();
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
        $horaActual = Carbon::now('America/Lima');
        $horaInicio = Carbon::parse($empresa->hora_inicio, 'America/Lima');
        $horaFin = Carbon::parse($empresa->hora_fin, 'America/Lima');

        $fueraHorario = !$horaActual->between($horaInicio, $horaFin);

        // Retornar la vista con los datos
        return view('negocio', compact(
            'promociones_faltantes',
            'empresa',
            'usuario',
            'productos',
            'imagenes',
            'colors',
            'fueraHorario'
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

        // Cargar al usuario autenticado con la relación empresa y sus pedidos
        $usuario = User::with(['empresas.pedidos'])->find($usuario_auth->id);

        // Obtener la primera empresa asociada al usuario autenticado
        $empresa = $usuario->empresas()->with('pedidos')->first();

        if (!$empresa) {
            return response()->json(['error' => 'No hay empresas asociadas al usuario.'], 404);
        }

        // Obtener los clientes únicos que han hecho pedidos a la empresa
        $clientes = User::whereIn('id', $empresa->pedidos->pluck('cliente_id'))->get();

        return view('clientes', compact('usuario', 'empresa', 'clientes'));
    }

    public function index_pagos_del_dia()
    {
        $usuario_auth = Auth::user();
        $usuario = User::with('empresas', 'empresas.productos')->where('id', $usuario_auth->id)->first();
        $empresa = $usuario->empresas()->first();
        $repartidores = $empresa->usuarios()->with('persona')->where('tipo', 'repartidor')->get();
        $pagosdeldia = Pedido::with(['detalles', 'detalles.producto'])->where('empresa_id', $empresa->id)
            ->where('pago', true)
            ->whereDate('fecha', Carbon::now('America/Lima')->toDateString())
            ->get();
        // Filtrar y agrupar productos comercializables
        $productosVendidos = $pagosdeldia->flatMap(function ($pedido) {
            // Aplanar los detalles de los pedidos
            return $pedido->detalles->filter(function ($detalle) {
                // Filtrar los productos comercializables
                return $detalle->producto->comercializable ?? false;
            })->map(function ($detalle) {
                // Retornar descripción y cantidad del producto
                return [
                    'descripcion' => $detalle->producto->descripcion,
                    'cantidad' => $detalle->cantidad,
                ];
            });
        })->groupBy('descripcion') // Agrupar por descripción
            ->map(function ($group) {
                // Sumar las cantidades de productos en cada grupo
                return $group->sum('cantidad');
            });
        // Agrupa los pedidos por método de pago y calcula la suma del total por método
        $desglosepagosdeldia = $pagosdeldia->groupBy('metodo')->map(function ($pedidos, $metodo) {
            return [
                'metodo' => $metodo,
                'total' => $pedidos->sum('total'),
            ];
        });

        // Si quieres, puedes convertir el resultado a un array para usarlo en la vista
        $desglosepagosdeldia = $desglosepagosdeldia->values()->toArray();

        return view('pagos', compact('repartidores', 'desglosepagosdeldia', 'pagosdeldia', 'usuario', 'empresa', 'productosVendidos'));
    }


    public function buscarEmpresas($filtro)
    {

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
            $empresa->descripcion = e($request->descripcion);

            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $uniqueLogoName = uniqid() . '_' . $logo->getClientOriginalName();
                $empresa->logo = $logo->storeAs('logos', $uniqueLogoName, 'public');
            }
            if ($request->hasFile('logo_vertical')) {
                $logo_vertical = $request->file('logo_vertical');
                $uniqueLogoName = uniqid() . '_' . $logo_vertical->getClientOriginalName();
                $empresa->logo_vertical = $logo_vertical->storeAs('logos', $uniqueLogoName, 'public');
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
            return redirect()->route('empresa.configView', ['id' => $empresa->id])->with([
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
