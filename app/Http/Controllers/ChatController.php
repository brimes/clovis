<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TelegramBootstrapService;

class ChatController extends Controller
{
    public function chatweb()
    {
    }

    public function chattelegram(Request $request)
    {
        $telegram = new TelegramBootstrapService();
        $telegram->run();
    }
}
