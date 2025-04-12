<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\WebPushConfig;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(storage_path(config('firebase.credentials')));
        $this->messaging = $factory->createMessaging();
    }

    public function sendNotification($deviceToken, $title, $body, $url, $operacion, $pedido_id, $estado, $pedido, $tiempo)
{
    $message = CloudMessage::withTarget('token', $deviceToken)
        ->withNotification(Notification::create($title, $body))
        ->withWebPushConfig(WebPushConfig::fromArray([
            'headers' => [
                'Urgency' => 'high',
            ],
            'notification' => [
                'requireInteraction' => true // 🔔 Mantiene la notificación visible hasta que el usuario interactúe
            ],
        ]))
        ->withData([
            'url' => $url,
            'operacion' => $operacion,
            'pedido_id' => $pedido_id,
            'estado' => $estado,
            'pedido' => $pedido,
            'tiempo' => $tiempo
        ]);

    return $this->messaging->send($message);
}

}
