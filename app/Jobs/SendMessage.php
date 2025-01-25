<?php

namespace App\Jobs;

use App\Events\MensajeEntendido;
use App\Models\Mensajes;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $mensaje;
    public $receiverId;
    public function __construct($mensaje, $receiverId)
    {
        $this->mensaje = $mensaje;
        $this->receiverId = $receiverId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
    
        MensajeEntendido::dispatch($this->mensaje, $this->receiverId);

    }
}
