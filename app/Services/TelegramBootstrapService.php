<?php
namespace App\Services;

use BotMan\BotMan\BotMan;

class TelegramBootstrapService extends ClovisAbstractBootstrapService {

    public function __construct(){
        $config = [
                "telegram" => [
                    "token" => env("TOKEN_TELEGRAM")
                ]
            ];
        parent::__construct(\BotMan\Drivers\Telegram\TelegramDriver::class, $config);
    }

    public function flow() {

        // Give the bot something to listen for.
        $this->botman->hears('hello', function (BotMan $bot) {
            //$ttClient = new App\GraphQL\Client\TradeToolsClient('');
            //$bot->reply('Hello: ' . $ttClient->getStatusOrder("42225938007839"));
            $bot->reply('Hello: ' . '  Leprechown');
        });

    }

}