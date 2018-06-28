<?php 
namespace App\Conversation;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use Log;
class OrderConversation extends Conversation
{
	protected $cnpj;
    
	public function run()
	{
		$this->ask('Ola, qual o seu CNPJ?', function(Answer $question) {
            try {
                $this->cnpj = $question->getText();
                $this->say("1 minuto, estou procurando o seu pedido");
            
                $ttClient = new \App\GraphQL\Client\TradeToolsClient();
                $status = $ttClient->getStatusOrder($this->cnpj);
                if (!$status) {
                    $this->dontUnderstand("seu pedido não encontrado!");
                }
                $statusOrder = $status->status_order;
                $date = $status->date;
                $id = $status->id;
                $this->say('O status do pedido ' . $id. ', enviado em ' .$date. ' é: ' . $statusOrder);
            }catch(\Exception $e){
				$this->dontUnderstand("não podemos encontrar o pedido!");
			}
			return false;
		});
	}
     
    public function dontUnderstand($erro = "") {
		$this->say('Desculpe, ' . $erro);
		return false;
    }
}