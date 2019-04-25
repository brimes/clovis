<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Cache;

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

        $body = '';

        error_log('REDIS: ' . Cache::store('redis')->get($to));

        if (Cache::store('redis')->get($to) != 0) {
            $body = $_POST['Body'];
        }

        $content = $this->getResponse($body, $to);
        Cache::store('redis')->put($to, 1, 600); // 10 Minute


        $message = $twilio->messages
            ->create($to, // to
                array(
                    "from" => "whatsapp:+14155238886",
                    "body" => $content
                )
            );

        error_log($message->sid);
    }

    protected function getResponse(string $input, $to)
    {
        if (empty($input)) {
            return 'Olá, sou o clóvis. Bem vindo ao whatsapp da funcional';
        }

        preg_match('/(\w+)\s*(\d*)/i', $input, $output);

        if (empty($output)) {
            return 'Desculpe, não entendi a sua mensagem';
        }

        switch (strtolower($output[1])) {
            case 'clear':
                Cache::store('redis')->put($to, 0, 600);
                return 'Histórico de conversas apagado';
            case 'status':
                return $this->consultaStatusPedido($output[2]);
            default:
                return 'Desculpe, não entendi a sua mensagem';
        }
    }

    protected function consultaStatusPedido($cnpj)
    {
        $array = [
            0 => 'CNPJ INVÁLIDO',
            1 => 'PEDIDO ACEITO COM SUCESSO',
            2 => 'PEDIDO PARCIALMENTE ACEITO',
            3 => 'PEDIDO REJEITADO',
        ];

        return $array[$cnpj] ?? 'ERRO DESCONHECIDO';
    }
}
