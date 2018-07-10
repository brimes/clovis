<?php

namespace App\Conversation;

use App\Services\WhatsappBootstrapService;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use Log;

class AdminConversation extends Conversation
{
    protected $password;
    protected $response;

    public function run()
    {
        $this->ask(
            'Oi Admin. Qual é a senha?',
            function (Answer $question) {
                try {
                    $this->password = $question->getText();
                    if ($this->password != 'clovis the best') {
                        $this->dontUnderstand("Acho que você digitou a senha errada.");
                        return true;
                    }
                    $this->ask('Show, senha correta. Deseja enviar a mensagem para todos os contatos?.',
                        function (Answer $question) {
                            $this->response = $question->getText();
                            if (strtolower($this->response) == 'sim') {
                                $this->ask('Deseja enviar a mensagem padrão?', function (Answer $question) {
                                    $this->response = $question->getText();
                                    if (strtolower($this->response) == 'sim') {
                                        $this->say('Ok, enviado');
                                        $waService = new WhatsappBootstrapService();
                                        $waService->sendInitialMessages();
                                        return true;
                                    }
                                    $this->ask('Qual mensagem voce deseja enviar?', function (Answer $question) {
                                        $this->response = $question->getText();
                                        $this->say('Ok, enviada a mensagem: ' . $this->response);
                                        $waService = new WhatsappBootstrapService();
                                        $waService->sendInitialMessages($this->response);
                                    });

                                });

                            } else {
                                $this->say('Ok, não enviei');
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
