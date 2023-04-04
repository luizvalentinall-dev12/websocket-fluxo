<?php

namespace App\Listeners;

use App\Events\SendMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class WebSocketMessageReceivedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(WebSocketMessageReceived $event)
    {
        $mensagem = $event->mensagem;
        broadcast(new SendMessage($event->mensagem))->toOthers();
        echo "Mensagem recebida: {$mensagem}";
    }
}
