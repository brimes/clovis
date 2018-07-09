<?php
/**
 * Created by PhpStorm.
 * User: bruno
 * Date: 08/07/18
 * Time: 21:25
 */

namespace App\BotDriver;

use BotMan\BotMan\Drivers\HttpDriver;
use BotMan\BotMan\Interfaces\DriverInterface;
use BotMan\BotMan\Interfaces\UserInterface;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WhatsappDriver extends HttpDriver
{

    const DRIVER_NAME = 'Whatsapp';
    const API_URL = 'http://panel.apiwha.com/';



    /**
     * Determine if the request is for this driver.
     *
     * @return bool
     */
    public function matchesRequest()
    {
        return $this->event->event == "INBOX";
    }

    /**
     * Retrieve the chat message(s).
     *
     * @return array
     */
    public function getMessages()
    {
        if (empty($this->messages)) {
            $message = $this->event->text;
            $userId = $this->event->from;
            $this->messages = [new IncomingMessage($message, $userId, $userId, $this->payload)];
        }
        return $this->messages;
    }

    /**
     * @return bool
     */
    public function isConfigured()
    {
        return !empty($this->config->get('token'));
    }

    /**
     * Retrieve User information.
     * @param IncomingMessage $matchingMessage
     * @return User
     */
    public function getUser(IncomingMessage $matchingMessage)
    {
        return new User($matchingMessage->getSender());
    }

    /**
     * @param IncomingMessage $message
     * @return \BotMan\BotMan\Messages\Incoming\Answer
     */
    public function getConversationAnswer(IncomingMessage $message)
    {
        return Answer::create($message->getText())->setMessage($message);
    }

    /**
     * @param string|\BotMan\BotMan\Messages\Outgoing\Question $message
     * @param IncomingMessage $matchingMessage
     * @param array $additionalParameters
     * @return $this
     */
    public function buildServicePayload($message, $matchingMessage, $additionalParameters = [])
    {
        if (! $message instanceof WebAccess && ! $message instanceof OutgoingMessage) {
            $this->errorMessage = 'Unsupported message type.';
            $this->replyStatusCode = 500;
        }
        return [
            'message' => $message,
            'additionalParameters' => $additionalParameters
        ];
    }

    /**
     * @param mixed $payload
     * @return Response
     */
    public function sendPayload($payload)
    {
        $my_apikey = $this->config->get('token');
        $destination = $this->event->from;
        $message = $payload['message']->getText();
        $api_url = self::API_URL .  "send_message.php";
        $api_url .= "?apikey=". urlencode ($my_apikey);
        $api_url .= "&number=". urlencode ($destination);
        $api_url .= "&text=". urlencode ($message);

        $response = $this->http->get($api_url, [], [
            'Content-Type: application/json',
            'Accept: application/json',
        ], true);

        return $response;
    }

    /**
     * @param Request $request
     * @return void
     */
    public function buildPayload(Request $request)
    {
        $data = json_decode($request->request->get('data'));
        $this->payload = new ParameterBag((array) json_decode($request->getContent(), true));
        $this->event = $data;
        $this->signature = $request->headers->get('X_HUB_SIGNATURE', '');
        $this->content = $request->getContent();
        $this->config = Collection::make($this->config->get('whatsapp', []));
    }

    /**
     * Low-level method to perform driver specific API requests.
     *
     * @param string $endpoint
     * @param array $parameters
     * @param \BotMan\BotMan\Messages\Incoming\IncomingMessage $matchingMessage
     * @return void
     */
    public function sendRequest($endpoint, array $parameters, IncomingMessage $matchingMessage)
    {
        return true;
    }
}