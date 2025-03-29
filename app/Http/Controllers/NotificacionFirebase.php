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
        
            // Si el usuario ya tiene otro token, eliminarlo antes de registrar el nuevo
            FcmToken::where('user_id', $request->user_id)->delete();
        
            // Guardar el nuevo token
            FcmToken::updateOrCreate(
                ['device_token' => $request->device_token],  
                ['user_id' => $request->user_id]            
            );
        
            return response()->json(['message' => 'Token guardado con éxito']);
        
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error interno en el servidor',
                'message' => $e->getMessage()
            ], 500);
        }
        
    }

}
