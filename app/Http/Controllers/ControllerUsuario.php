<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestPersona;
use App\Models\Direcciones;
use App\Models\Empresa;
use App\Models\Pedido;
use App\Models\Persona;
use App\Models\Producto;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class ControllerUsuario extends Controller
{


    public function obtenerReferencia(Request $request)
    {
        try {
            // Buscar la dirección del usuario
            $direccion = Direcciones::where('user_id', Auth::id())
                ->where('direccion', $request->direccion)
                ->first();

            if ($direccion) {
                $respuesta = $direccion->referencia;
            } else {
                // Si no encuentra la dirección, buscar en Persona
                $persona = Persona::where('user_id', Auth::id())->first();
                $respuesta = $persona ? $persona->nota : ''; // Evitar error si Persona es null
            }

            return response()->json(['mensaje' => $respuesta], 200);
        } catch (\Throwable $th) {
            return response()->json(['mensaje' => 'Error en el servidor'], 500);
        }
    }


    public function validateUser(Request $request)
    {
        try {
            $request->validate([
                'telefono' => 'required|exists:users,usuario',
            ]);

            $user = User::where('usuario', $request->telefono)->first();


            if (!$user) {
                return response()->json(['mensaje' => 'Los datos no coinciden.'], 400);
            }
            return response()->json(['mensaje' => $user->id], 200);
        } catch (\Exception $e) {
            return response()->json(['mensaje' =>  $e->getMessage()], 500);
        }
    }


    public function datosFavoritos(Request $request)
    {
        try {
            $user = User::findOr($request->usuario_id);
            $user->update([
                'favoritos' => $request->favoritos
            ]);
            return response()->json(['mensaje' => 'Operacion exitosa favoritos']);
        } catch (\Throwable $th) {
            return response()->json(['mensaje' => 'Error al guardar favoritos ']);
        }
    }
    public function datosPredeterminada(Request $request)
    {
        try {
            $user = User::findOr($request->usuario_id);
            $url = $request->predeterminado;
            // Extraer la parte después del dominio
            $path = ltrim(parse_url($url, PHP_URL_PATH), '/');
            $empresa = Empresa::where('dominio', $path)->first();

            $user->update([
                'predeterminada' => $empresa->id,

            ]);
            return response()->json(['mensaje' => 'Operacion exitosa predeterminado']);
        } catch (\Throwable $th) {
            return response()->json(['mensaje' => 'Error al guardar predeterminado']);
        }
    }
    public function guardarFavorito(Request $request)
    {
        try {
            $user = User::findOr($request->usuario_id); // Buscar usuario
            $favoritos = json_decode($user->favoritos, true) ?? []; // Convertir a array
            $empresa = Empresa::where('dominio', $request->empresa_id)->first();
            // Si el ID ya está en la lista, no hacer nada
            if (in_array($empresa->id, $favoritos)) {
                return response()->json(['mensaje' => 'Ya es un favorito']);
            }

            // Si hay 3 favoritos, eliminar el más antiguo (primer elemento)
            if (count($favoritos) >= 3) {
                array_shift($favoritos);
            }

            // Agregar el nuevo favorito al final
            $favoritos[] = $empresa->id;

            // Guardar la lista actualizada
            $user->favoritos = json_encode($favoritos);
            $user->save();

            return response()->json(['mensaje' => 'Favorito guardado correctamente']);
        } catch (\Throwable $th) {
            return response()->json(['mensaje' => $th->getMessage()]);
        }
    }

    public function getFavoritos()
    {
        try {
            $usuario_actual = Auth::user();
            $user = User::findOr($usuario_actual->id);
            $favoritos = json_decode($user->favoritos, true); // Convertir JSON a array
            if (!empty($favoritos)) {
                $empresas = Empresa::whereIn('id', $favoritos)->get(['dominio', 'nombre', 'logo']);
                return response()->json(['mensaje' => $empresas]);
            }
            return response()->json(['mensaje' => null]); // Si no hay favoritos

        } catch (\Throwable $th) {
            return response()->json(['mensaje' => null]);
        }
    }
    public function eliminarFavorito(Request $request)
    {
        try {
            $usuario_actual = Auth::user();
            $user = User::findOr($usuario_actual->id); // Buscar usuario
            $favoritos = json_decode($user->favoritos, true) ?? []; // Convertir a array
            $empresa = Empresa::where('dominio', $request->empresa_id)->first();
            // Buscar y eliminar el ID
            $favoritos = array_filter($favoritos, fn($id) => $id != $empresa->id);

            // Guardar nueva lista de favoritos
            $user->favoritos = json_encode(array_values($favoritos));
            $user->save();

            return response()->json(['mensaje' => 'Favorito eliminado correctamente']);
        } catch (\Throwable $th) {
            return response()->json(['mensaje' => $th->getMessage() . $empresa]);
        }
    }

    public function getPredeterminada()
    {
        try {
            $usuario_actual = Auth::user();
            $user = User::findOr($usuario_actual->id);
            if ($user->predeterminada) {
                $empresa = Empresa::findOr($user->predeterminada);
                return response()->json(['mensaje' => $empresa->dominio]);
            } else {
                return response()->json(['mensaje' => null]);
            }
        } catch (\Throwable $th) {
            return response()->json(['mensaje' => null]);
        }
    }
    public function eliminarPredeterminada()
    {
        try {
            $usuario_actual = Auth::user();
            $user = User::findOr($usuario_actual->id);
            $user->predeterminada = null;
            $user->save();
            return response()->json(['mensaje' => 'Empresa predeterminada eliminada']);
        } catch (\Throwable $th) {
            return response()->json(['mensaje' => 'Error al obtener predeterminado']);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            // Validación de la solicitud
            $request->validate([
                'password' => 'required|string|min:6',
                'user_id_pass' => 'required|exists:users,id',
            ]);

            // Buscar el usuario
            $user = User::find($request->user_id_pass);
            if (!$user) {
                return response()->json(['mensaje' => 'Usuario no encontrado.'], 404);
            }

            // Actualizar la contraseña
            $user->password = bcrypt($request->password);
            $user->save();

            return response()->json(['mensaje' => 'Contraseña restablecida correctamente.'], 200);
        } catch (\Exception $e) {
            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
    }




    public function index()
    {
        // Verificar autenticación
        if (!Auth::check()) {
            abort(403, 'Usuario no autenticado.');
        }

        $user_actual = Auth::user();
        $usuario = User::with('persona')->find($user_actual->id);

        // Validar que el usuario exista
        if (!$usuario) {
            abort(404, 'Usuario no encontrado.');
        }

        $empresa = $usuario->empresas()->first() ?? null;
        $salidas = collect();
        if ($usuario->tipo != 'cliente') {
            $salidas = $empresa->salidas()->with('stock')->whereDate('fecha', Carbon::now('America/Lima'))->get();
        }
        $pedidos = $usuario->tipo === 'admin'
            ? Pedido::with('detalles', 'empresa', 'repartidor', 'repartidor.persona', 'entregaPromociones')
            ->where('empresa_id', $empresa->id ?? 0)
            ->where('estado', '!=', 'Entregado')
            ->where('estado', '!=', 'Anulado')
            ->orderByRaw("
                CASE 
                    WHEN estado = 'En Camino' THEN 2
                    WHEN estado = 'Pendiente' THEN 1
                    ELSE 3
                END
            ")
            ->orderByDesc('fecha')
            ->get()
            : ($usuario->tipo === 'repartidor'
                ? Pedido::with('detalles', 'empresa', 'usuario', 'entregaPromociones')
                ->where('repartidor_id', $usuario->id)
                ->where('estado', '!=', 'Entregado')
                ->where('estado', '!=', 'Anulado')
                ->orderByRaw("
                    CASE 
                        WHEN estado = 'Pendiente' THEN 1
                        WHEN estado = 'En Camino' THEN 2
                        ELSE 3
                    END
                ")
                ->orderByDesc('fecha')
                ->get()
                : Pedido::with('detalles', 'empresa', 'usuario', 'entregaPromociones')
                ->where('cliente_id', $usuario->id)
                ->orderByRaw("
                    CASE 
                        WHEN estado = 'Pendiente' THEN 1
                        WHEN estado = 'En Camino' THEN 2
                        ELSE 3
                    END
                ")
                ->orderByDesc('fecha')
                ->get()
            );
        $pedido = Pedido::where('cliente_id', $usuario->id)->latest()->first();


        return view('micuenta', compact('salidas', 'pedido', 'pedidos', 'empresa', 'usuario'));
    }

    public function eliminarUsuario(Request $request){
        try {
            $persona=User::findOrFail($request->input('persona_id'));
            $persona->delete();
            return redirect()->back()->with(['mensaje'=>'Repartidor eliminado Correctamente.']);
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors('Ocurrio un error al eliminar. Recarga la pagina e intentalo nuevamente.');

        }
    }
    public function update(Request $request, $id)


    {

        // Validar los datos del formulario
        try {
            $request->validate([
                'celular' => 'required|string|max:9|unique:users,usuario,' . $id,
                'nombre' => 'required|string|max:255',
                'dni' => 'nullable|string|min:8|max:8|unique:persona,dni,' . $id,
                'email' => 'nullable|email|max:255|unique:persona,correo,' . $id,
                'direccion' => 'nullable|string|max:255',
                'direccion2' => 'nullable|array|max:255',
                'nota' => 'nullable|string|max:500',
                'password' => 'nullable|string|min:8',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
        // Obtener el usuario
        $usuario = User::find($id);
        $persona = $usuario->persona;

        // Verificar si el usuario existe
        if (!$usuario) {
            return redirect()->back()->withErrors(['error' => 'El usuario no fue encontrado.']);
        }
        // Actualizar los datos
        $usuario->usuario = $request->input('celular');
        $persona->nombres = $request->input('nombre');
        $persona->correo = $request->input('email');
        $persona->dni = $request->input('dni');
        $persona->direccion = $request->input('direccion');
        $persona->nota = $request->input('nota');

        // Actualizar la contraseña solo si se proporciona
        if ($request->filled('password')) {
            $usuario->password = bcrypt($request->input('password'));
        }

        $persona->save();
        // Guardar cambios
        $usuario->save();

        if ($request->has('direcciones')) {
            // Eliminar direcciones previas (si aplica)
            $usuario->direcciones()->delete();

            // Guardar nuevas direcciones
            foreach ($request->direcciones as $direccion) {
                Direcciones::create([
                    'user_id' => $usuario->id,
                    'direccion' => $direccion['direccion'],
                    'referencia' => $direccion['referencia'] ?? null,
                ]);
            }
        }

        // Redirigir con mensaje de éxito
        return redirect()->back()->with('success', 'Datos actualizados correctamente.');
    }
    public function buscarUsuario($telefono)
    {
        $usuario = User::with('persona')->where('usuario', $telefono)->first();
        if ($usuario) {
            return response()->json(['mensaje' => $usuario], 200);
        } else {
            return response()->json(['mensaje' => 'No existe Ningun Usuario Asociado a ese Numero.'], 400);
        }
    }
    public function login(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'telefono' => 'required|string',
            'password' => 'required|string',
        ]);

        // Credenciales para autenticar
        $credentials = [
            'usuario' => $request->telefono,
            'password' => $request->password,
        ];
        $usuario = User::where('usuario', $request->telefono)->first();
        if ($usuario) {
            if ($usuario->persona->estado == false) {
                return response()->json([
                    'mensaje' => 'Estas inhabilitado, no puedes entrar al sistema.',
                ], 403);
            }
        }
        $remember = $request->has('remember'); // Revisa si el usuario marcó "recordarme"

        // Intentar autenticación
        if (Auth::attempt($credentials, $remember)) {
            // Regenerar sesión para proteger contra ataques de fijación de sesión
            $request->session()->regenerate();

            return  response()->json([
                'mensaje' => 'Usuario Logueado Correctamente.',
            ], 200);
        }
        // Si falla la autenticación, redirigir de vuelta con un mensaje de error
        return response()->json([
            'mensaje' => 'Las credenciales no coinciden con nuestros registros.',
        ], 404);
    }
    public function login_no_aut(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'telefono' => 'required|string',
            'password' => 'required|string',
        ]);

        // Credenciales para autenticar
        $credentials = [
            'usuario' => $request->telefono,
            'password' => $request->password,
        ];
        $usuario = User::where('usuario', $request->telefono)->first();
        if ($usuario) {
            if ($usuario->persona->estado == false) {
                return response()->json([
                    'mensaje' => 'Estas inhabilitado, no puedes entrar al sistema.',
                ], 403);
            }
        }
        $remember = $request->has('remember'); // Revisa si el usuario marcó "recordarme"

        // Intentar autenticación
        if (Auth::attempt($credentials, $remember)) {
            // Regenerar sesión para proteger contra ataques de fijación de sesión
            $request->session()->regenerate();

            return  response()->json([
                'mensaje' => 'Usuario Logueado Correctamente.',
                'cliente_id' => $usuario->usuario
            ], 200);
        }

        // Si falla la autenticación, redirigir de vuelta con un mensaje de error
        return response()->json([
            'mensaje' => 'Las credenciales no coinciden con nuestros registros.',
        ], 404);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        // Invalida la sesión y regenera el token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('index');
    }

    public function getRepartidores()
    {
        if (!Auth::check()) {
            abort(403, 'Usuario no Autenticado.');
        }

        try {
            $usuario_actual = User::find(Auth::user()->id);
            $empresa = $usuario_actual->empresas()->first();

            if (!$empresa) {
                return response()->json(['error' => 'Empresa no encontrada'], 404);
            }

            $repartidores = $empresa->usuarios()
                ->where('tipo', 'repartidor')
                ->whereHas('persona', function ($query) {
                    $query->where('estado', true);
                })
                ->with('persona') // Opcional si quieres cargar la relación
                ->get();


            return response()->json(['repartidores' => $repartidores], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function store(RequestPersona $request)
    {
        DB::beginTransaction();

        try {
            // Crear el usuario
            $usuario = User::create([
                "usuario" => $request->telefono,
                "password" => bcrypt($request->password),
                "tipo" => $request->rol ?? 'cliente', // Usa null coalescing operator
            ]);

            // Crear la persona asociada al usuario
            Persona::create([
                "nombres" => $request->nombres . ' ' . $request->apellidos,
                "dni" => $request->dni,
                "correo" => $request->correo,
                'nota' => $request->nota,
                'direccion' => $request->direccion,
                "user_id" => $usuario->id,
            ]);

            // Asociar usuario con la empresa si se proporciona
            if ($request->empresa) {
                $empresa = Empresa::findOrFail($request->empresa); // Lanza excepción si no existe
                $usuario->empresas()->attach($empresa);
            }

            DB::commit(); // Confirmar la transacción

            // Respuesta exitosa
            return response()->json([
                "mensaje" => "Usuario creado correctamente",
                "usuario" => $usuario->load('persona', 'empresas'), // Carga relaciones para el front
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir la transacción en caso de error


            // Respuesta de error
            return response()->json([
                "mensaje" => "Hubo un error al crear el usuario",
                "error" => $e->getMessage(),
            ], 500);
        }
    }

    public function cambiarestado(string $id)
    {
        DB::beginTransaction();
        try {
            // Buscar el usuario por ID
            $persona = Persona::where('id', $id)->first();
            $persona->update([
                'estado' => !$persona->estado // Cambia el estado (true -> false, false -> true)
            ]);

            DB::commit();
            return response()->json([
                'mensaje' => 'Estado actualizado correctamente.',
                'nuevo_estado' => $persona->estado
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'mensaje' => 'Error al actualizar el estado.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
