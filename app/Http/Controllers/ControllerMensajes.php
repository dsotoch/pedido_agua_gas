<?php

namespace App\Http\Controllers;

use App\Events\MensajeEntendido;
use App\Jobs\SendMessage;
use App\Models\Mensajes;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ControllerMensajes extends Controller
{

    public function mensajes()
    {
        try {
            if (Auth::check()) {
                $messages = Mensajes::with('usuario', 'pedido')
                    ->where('user_id', Auth::user()->id)
                    ->where('estado', false)
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->append('time');
            } else {
                $messages = collect(); // Devuelve una colección vacía si no está autenticado
            }
            return response()->json($messages);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function mensajeAsignado(string $id)

    {
        try {
            if (Auth::check()) {
                $pedido = Pedido::with(['detalles.producto', 'repartidor','repartidor.persona' ,'empresa'])
                    ->where('id', $id)
                    ->firstOrFail();
            } else {
                $pedido = collect(); // Devuelve una colección vacía si no está autenticado
            }
            return response()->json($pedido);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }



    public function crearmensaje($message, $receiver_id, $pedido_id)
    {

        try {
            // Crear el mensaje en la base de datos
            Mensajes::create([
                'user_id' => $receiver_id,
                'pedido_id' => $pedido_id,
                'mensaje' => $message,
            ]);
            $mensaje = ['operacion' => 'nuevopedido', 'mensaje' => 'Nuevo Pedido para la Empresa.', 'pedido_id' => $pedido_id];

            SendMessage::dispatch($mensaje, $receiver_id);

            return;
        } catch (\Throwable  $e) {
            return;
        }
    }

    public function actualizarEstado(string $id)
    {
        try {
            $mensaje = Mensajes::where('pedido_id', $id)->first();
            if ($mensaje) {
                $mensaje->update([
                    'estado' => true, // Se corrige el punto y coma por una coma
                ]);
                return response()->json(['mensaje' => 'Estado actualizado con éxito.'], 200);
            } else {
                return response()->json(['mensaje' => 'No existe un mensaje con ese Identificador.'], 400);
            }
        } catch (\Throwable $th) {
            return response()->json(['mensaje' => $th->getMessage()], 500);
        }
    }
}
