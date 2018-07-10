<?php

namespace App\Services;

use App\BotDriver\WhatsappDriver;
use App\Conversation\AdminConversation;
use App\Conversation\SuggestConversation;
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

        $this->botman->hears($this->negationsText(), function ($bot) {
            $bot->startConversation(new SuggestConversation());

        });

        $this->botman->hears('Sou admin', function ($bot) {
            $bot->startConversation(new AdminConversation());

        });

        $this->botman->hears($this->confirmationsText(), function ($bot) {
            $bot->reply('Posso te indicar uma farmácia próxima. Dê uma olhada nessa abaixo.');
            $bot->reply('Farmácia FARMACIA TESTE. Endereço: Rua sete, 654');
        });

        $this->botman->fallback(
            function ($bot) {
                $bot->reply('Desculpe, não entendi o que você quis dizer.');
            }
        );
    }

    protected function allContacts() {
        $my_apikey = env('TOKEN_WHATSAPP');
        //$number = "";
        $type = "IN";
        $markaspulled = "0";
        $getnotpulledonly = "0";
        $api_url  = "http://panel.apiwha.com/get_messages.php";
        $api_url .= "?apikey=". urlencode ($my_apikey);
        $api_url .= "&type=". urlencode ($type);
        $api_url .= "&markaspulled=". urlencode ($markaspulled);
        $api_url .= "&getnotpulledonly=". urlencode ($getnotpulledonly);
        $my_json_result = file_get_contents($api_url, false);
        $my_php_arr = json_decode($my_json_result);
        $numbers = [];
        foreach($my_php_arr as $item)
        {
            $numbers[$item->from] = 1;
        }
        return ['5521999734777'];
    }

    public function sendInitialMessages($message = null)
    {
        $defaultMessage = 'Oi! Sou o Clóvis seu assistente para tratamento. Vejo que você ainda não comprou seu RELVAR... Posso te ajudar?';
        if (!empty($message)) {
            $defaultMessage = $message;
        }

        foreach ($this->allContacts() as $number) {
            $this->botman->sendRequest(null, [
                'destination' => $number,
                'message' => $defaultMessage
            ], new IncomingMessage("", "", "", ""));
        }
    }

}
