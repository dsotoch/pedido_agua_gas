<?php

namespace App\Http\Controllers;
use App\Models\FcmToken;
use App\Services\FirebaseService;
use Illuminate\Http\Request;

class NotificacionFirebase extends Controller
{
    public function sendPushNotification($user, $titulo, $cuerpo,$operacion,$pedido_id,$estado,$pedido,$tiempo)
    {
        $appUrl = env('APP_URL'); // Obtener la URL desde .env
        $deviceToken = FcmToken::where('user_id', $user)->value('device_token');
        $firebaseService = new FirebaseService();

        $title = $titulo;
        $body = $cuerpo;

        $firebaseService->sendNotification($deviceToken, $title, $body, $appUrl,$operacion,$pedido_id,$estado,$pedido,$tiempo);

        return response()->json(['message' => 'Notificación enviada con éxito']);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'device_token' => 'required|string',
                'user_id' => 'nullable|exists:users,id',
            ]);

            // Buscar si ya existe el token
            $token = FcmToken::where('device_token', $request->device_token)->first();

            if ($token) {
                // Si el token ya existe, actualizar el usuario asociado
                $token->update(['user_id' => $request->user_id]);
            } else {
                // Si no existe, crear un nuevo registro
                FcmToken::create([
                    'user_id' => $request->user_id,
                    'device_token' => $request->device_token
                ]);
            }

            return response()->json(['message' => 'Token guardado o actualizado con éxito']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error interno en el servidor',
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
