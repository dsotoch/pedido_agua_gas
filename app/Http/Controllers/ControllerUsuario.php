<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestPersona;
use App\Models\empresa;
use App\Models\Pedido;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ControllerUsuario extends Controller
{

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
        $pedidos = $usuario->tipo === 'admin'
            ? Pedido::with('detalles', 'empresa', 'repartidor', 'repartidor.persona', 'entregaPromociones')
            ->where('empresa_id', $empresa->id ?? 0)
            ->orderByRaw("
                CASE 
                    WHEN estado = 'finalizado' THEN 1
                    WHEN estado = 'pendiente' THEN 2
                    ELSE 3
                END
            ")
            ->orderByDesc('fecha')
            ->get()
            : ($usuario->tipo === 'repartidor'
                ? Pedido::with('detalles', 'empresa', 'usuario', 'entregaPromociones')
                ->where('repartidor_id', $usuario->id)
                ->where('estado','!=','Entregado')
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
                        WHEN estado = 'finalizado' THEN 1
                        WHEN estado = 'pendiente' THEN 2
                        ELSE 3
                    END
                ")
                ->orderByDesc('fecha')
                ->get()
            );

        return view('micuenta', compact('pedidos', 'empresa', 'usuario'));
    }


    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $request->validate([
            'celular' => 'required|string|max:9',
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:persona,correo,' . $id,
            'direccion' => 'nullable|string|max:255',
            'nota' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:8',
        ]);

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
        $persona->direccion = $request->input('direccion');
        $persona->nota = $request->input('nota');

        // Actualizar la contraseña solo si se proporciona
        if ($request->filled('password')) {
            $usuario->password = bcrypt($request->input('password'));
        }

        $persona->save();
        // Guardar cambios
        $usuario->save();

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

        // Intentar autenticación
        if (Auth::attempt($credentials)) {
            // Regenerar sesión para proteger contra ataques de fijación de sesión
            $request->session()->regenerate();

            // Obtener datos del usuario autenticado
            // Redirigir al índice con un mensaje de éxito

            return  response()->json([
                'mensaje' => 'Usuario Logueado Correctamente.',
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
                ->with('persona')
                ->where('tipo', 'repartidor')
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
