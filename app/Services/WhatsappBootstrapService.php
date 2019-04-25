<?php

namespace App\Services;

use Twilio\Rest\Client;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class WhatsappBootstrapService
{
    protected $config;

    public function __construct()
    {
        $this->config = [
            'whatsapp' => [
                'token' => env('TWILIO_WHATSAPP_TOKEN')  ,
                'sid' => env('TWILIO_WHATSAPP_SID'),
            ]
        ];
    }

    public function run()
    {
        error_log(print_r($_POST, true));

        $sid      = $this->config['whatsapp']['sid'];
        $token      = $this->config['whatsapp']['token'];

        $twilio = new Client($sid, $token);
        $to = $_POST['From'];

        $content = $this->getResponse($_POST['Body'] ?? '');
        $message = $twilio->messages
            ->create($to, // to
                array(
                    "from" => "whatsapp:+14155238886",
                    "body" => $content
                )
            );

        error_log($message->sid);
    }

    protected function getResponse(string $input)
    {
        if (empty($input)) {
            return 'Desculpe, não entendi a sua mensagem';
        }

        return 'Olá, sou o clóvis. Bem vindo ao whatsapp da funcional';
    }
}
