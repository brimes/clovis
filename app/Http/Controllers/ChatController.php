<?php

namespace App\Http\Controllers;

use App\Services\WhatsappBootstrapService;
use Illuminate\Http\Request;
use App\Services\TelegramBootstrapService;
use stdClass;

class ChatController extends Controller
{
    public function chatwhatsapp(Request $request)
    {
        $whatsapp = new WhatsappBootstrapService();
        $whatsapp->run();

//        $data = json_decode($request->post('data'));
//
//        if ($data->event=="INBOX")
//        {
//            // Default answer
//            $my_answer = "This is an autoreply from APIWHA!. You (". $data->from .") wrote: ". $data->text;
//
//            // You can evaluate the received message and prepare your new answer.
//            if(!(strpos(strtoupper($data->text), "PRICING")===false)){
//                $my_answer = "Sing up in our platform and you will get our pricing list!";
//
//            }else if(!(strpos(strtoupper($data->text), "INFORMATION")===false)){
//                $my_answer = "Of course! For more information you can access to our website http://www.apiwha.com!";
//
//            }
//
//            $response = new StdClass();
//            $response->apiwha_autoreply = $my_answer; // Attribute "apiwha_autoreply" is very important!
//
//            echo json_encode($response); // Don't forget to reply in JSON format
//
//            /* You don't need any APIKEY to answer to your customer from a webhook */
//
//        }
    }

    public function chatweb()
    {

    }

    public function chattelegram(Request $request)
    {
        $telegram = new TelegramBootstrapService();
        $telegram->run();
    }
}
