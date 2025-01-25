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

    public function buscarUsuario($telefono)
    {
        $usuario = User::with('persona')->where('usuario', $telefono)->first();
        if ($usuario) {
            return response()->json(['mensaje' => $usuario], 200);
        } else {
            return response()->json(['mensaje' => 'No existe Ningun Usuario Asociado a ese Numero.'], 400);
        }
    }
    public function index()
    {
        $usuario = Auth::user();
        $usuario = User::with('persona')->where('id', $usuario->id)->first();
        $pedidos = Pedido::with('detalles', 'empresa', 'repartidor', 'entregaPromociones')
            ->where('cliente_id', $usuario->id)
            ->orderByRaw("CASE WHEN estado = 'finalizado' THEN 1 ELSE 0 END")
            ->orderByDesc('fecha') // Ordenar por fecha en orden descendente
            ->get();

        return view('micuenta', compact('usuario', 'pedidos'));
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
        try {
            $repartidores = User::with('persona')->where('empresa_id', Auth::user()->empresa_id)->where('tipo', 'repartidor')->get();
            return response()->json($repartidores, 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
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
                "tipo" => $request->rol ? $request->rol : 'cliente',
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

            DB::commit(); // Confirmar la transacción

            // Respuesta exitosa
            return response()->json(["mensaje" => "Usuario creado correctamente", 'id' => $usuario->id], 201);
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir la transacción en caso de error

            // Manejar el error
            return response()->json(["mensaje" => $e->getMessage()], 500);
        }
    }

    public function cambiarestado(string $nombre_empresa, string $id)
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
