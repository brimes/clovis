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

        $this->botman->hears('([a-zA-Z ]+)', function (BotMan $bot) {
            $bot->getMessage()->getText();
            $bot->startConversation(new OrderConversation());
        });
        
        $this->botman->fallback(function($bot) {
            $bot->reply('Desculpe, não entendi o que você quis dizer');
        });
        

    }

}