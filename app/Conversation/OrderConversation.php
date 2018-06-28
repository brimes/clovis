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
        Log::info("Run coversation executado");
		$this->ask('Ola, qual o seu CNPJ do PDV?', function(Answer $question) {
            Log::info("pergunta do CNPJ");
            Log::info("CNPJ: " . $question->getText());
            $this->say("Pesquisando.....");
            $this->cnpj = $question->getText();
            
			if(is_numeric($this->cnpj)) {
                $ttClient = new App\GraphQL\Client\TradeToolsClient('');
                $this->say('O status do seu último pedido é: ' . $ttClient->getStatusOrder($this->cnpj));
                return true;
            }else{
                $this->dontUnderstand();
                $this->run();
            }
		});
	}
    
    /*public function askTopping()
	{
		$this->ask('What kind of topping do you want?', function($answer) {
			$this->topping = $answer->getText();
			$this->askAddress();
		});
	}
	public function askAddress()
	{
		$this->ask('Where can we deliver your tasty pizza?', function($answer) {
			$this->address = $answer->getText();
			$this->say('Okay. That is all I need.');
			$this->say('Size: '.$this->size);
			$this->say('Topping: '.$this->topping);
			$this->say('Delivery address: '.$this->address);
		});
    }*/
    
    public function dontUnderstand() {
        $this->say('Desculpe, não entendi o que você quis dizer.');
    }
}