<?php
namespace App\Services;

use BotMan\BotMan\BotMan;
use App\Conversation\OrderConversation;

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

        $this->botman->hears('hello', function (BotMan $bot) {
            $bot->startConversation(new OrderConversation());
        });
        
        /*
        // Give the bot something to listen for.
        $this->botman->hears('Ola', function (BotMan $bot) {
            //$ttClient = new App\GraphQL\Client\TradeToolsClient('');
            //$bot->reply('Hello: ' . $ttClient->getStatusOrder("42225938007839"));
            $bot->reply('Ola, qual o seu CNPJ');
        });
        */
        /*
        $this->botman->hears('([0-9]+)', function (BotMan $bot, $number) {
            $ttClient = new App\GraphQL\Client\TradeToolsClient('');
            $bot->reply('Hello: ' . $ttClient->getStatusOrder($number));
            //$bot->reply('O seu cnpj é ' . $number);
        });
        */
        

    }

}