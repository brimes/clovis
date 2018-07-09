<?php

namespace App\Services;

use App\BotDriver\WhatsappDriver;
use BotMan\BotMan\BotMan;
use App\Conversation\OrderConversation;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class WhatsappBootstrapService extends ClovisAbstractBootstrapService
{
    public function __construct()
    {
        $config = [
            'whatsapp' => [
                'token' => env('TOKEN_WHATSAPP'),
            ]
        ];
        parent::__construct(WhatsappDriver::class, $config);
    }

    public function flow()
    {
        $this->botman->hears($this->greattingsText(), function ($bot) {
            $bot->reply('Olá seja bem vindo!');
        });

//        $this->botman->hears(
//            '([a-zA-Z ]+)',
//            function (BotMan $bot) {
//                $bot->getMessage()->getText();
//                $bot->startConversation(new OrderConversation());
//            }
//        );

        $this->botman->fallback(
            function ($bot) {
                $bot->reply('Desculpe, não entendi o que você quis dizer.');
            }
        );
    }

    public function sendInitialMessages()
    {
        $this->botman->sendRequest(null, [
            'destination' => '5521982341547',
            'message' => 'ops'
        ], new IncomingMessage("", "", "", ""));
    }
}
