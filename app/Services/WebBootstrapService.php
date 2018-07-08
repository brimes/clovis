<?php

namespace App\Services;

use BotMan\BotMan\BotMan;
use App\Conversation\OrderConversation;

class WebBootstrapService extends ClovisAbstractBootstrapService
{
    public function __construct()
    {
        $config = [
            'web' => [
                'matchingData' => [
                    'driver' => 'web',
                ],
            ]
        ];
        parent::__construct(\BotMan\Drivers\Web\WebDriver::class, $config);
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
