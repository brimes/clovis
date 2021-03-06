<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Twilio\Rest\Client;

class WhatsappBootstrapService {
	protected $config;

	public function __construct() {
		$this->config = [
			'whatsapp' => [
				'token' => env('TWILIO_WHATSAPP_TOKEN'),
				'sid' => env('TWILIO_WHATSAPP_SID'),
			],
		];
	}

	public function run() {
		error_log(print_r($_POST, true));

		$sid = $this->config['whatsapp']['sid'];
		$token = $this->config['whatsapp']['token'];

		$twilio = new Client($sid, $token);
		$to = $_POST['From'];

		$body = '';

		if (Cache::has($to)) {
			error_log('REDIS: ' . Cache::get($to));
			$body = $_POST['Body'];
		}
		Cache::put($to, '1', 600);

		$content = $this->getResponse($body, $to);

		$message = $twilio->messages
			->create($to, // to
				array(
					"from" => "whatsapp:+14155238886",
					"body" => $content,
				)
			);

		error_log($message->sid);
	}

	protected function getResponse(string $input, $to) {
		if (empty($input)) {
			return 'Olá, sou o clóvis. Bem vindo ao whatsapp da funcional';
		}

		preg_match('/(\w+)\s*(\d*)/i', $input, $output);

		if (empty($output)) {
			return 'Desculpe, não entendi a sua mensagem';
		}

		switch (strtolower($output[1])) {
		case 'clear':
			Cache::forget($to);
			return 'Histórico de conversas apagado';
		case 'status':
			return $this->consultaStatusPedido($output[2]);
		default:
			return 'Desculpe, não entendi a sua mensagem';
		}
	}

	protected function consultaStatusPedido($cnpj) {
		$array = [
			0 => 'CNPJ INVÁLIDO',
			1 => 'PEDIDO ACEITO COM SUCESSO',
			2 => 'PEDIDO PARCIALMENTE ACEITO',
			3 => 'PEDIDO REJEITADO',
		];

		return $array[$cnpj] ?? 'ERRO DESCONHECIDO';
	}
}
