<?php

namespace App\Services;

use App\BotDriver\WhatsappDriver;
use BotMan\BotMan\BotMan;
use App\Conversation\OrderConversation;

class WhatsappBootstrapService extends ClovisAbstractBootstrapService
{
    public function __construct()
    {
        $config = [
            'whatsapp' => [
                'token' => '48S2294QIDGI9MY4ZT5B',
            ]
        ];
        parent::__construct(WhatsappDriver::class, $config);
    }

    public function flow()
    {
        $this->botman->hears(
            '([a-zA-Z ]+)',
            function (BotMan $bot) {
                $bot->getMessage()->getText();
                $bot->startConversation(new OrderConversation());
            }
        );

        $this->botman->fallback(
            function ($bot) {
                $bot->reply('Desculpe, não entendi o que você quis dizer.');
            }
        );
    }
}
