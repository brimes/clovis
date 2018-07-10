<?php

namespace App\Conversation;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use Log;

class SuggestConversation extends Conversation
{
    protected $cnpj;

    public function run()
    {
        $this->ask(
            'Que pena! Voce sabia que bla...?',
            function (Answer $question) {
                try {
                    $this->response = $question->getText();
                    $this->ask($this->response . '? Olha, não deixe seu tratamento: Temos uma oferta com 50% de desconto em 3 dias. Basta responder sim que disponibilizo para você.',
                        function (Answer $question) {
                            $this->response = $question->getText();
                            if (strtolower($this->response) == 'sim') {
                                $this->say('Basta ir em uma farmácia e usar o seu CPF');
                            } else {
                                $this->say('Que pena!');
                            }
                        }
                    );
                } catch (\Exception $e) {
                    $this->dontUnderstand("não entendi");
                }
                return false;
            }
        );
    }

    public function dontUnderstand($erro = "")
    {
        $this->say('Desculpe, ' . $erro);
        return false;
    }
}
