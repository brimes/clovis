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
            $this->cnpj = $question->getText();
            $this->say("1 minuto, estou procurando o seu pedido");
			
			$ttClient = new \App\GraphQL\Client\TradeToolsClient();
			$status = $ttClient->getStatusOrder($this->cnpj);
			$this->say('O status do seu último pedido é: ' . $status);
			
			return false;
		});
	}
     
    public function dontUnderstand() {
        $this->say('Desculpe, não entendi o que você quis dizer.');
    }
}