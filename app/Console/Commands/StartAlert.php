<?php

namespace App\Console\Commands;

use App\Services\WhatsappBootstrapService;
use Illuminate\Console\Command;

class StartAlert extends Command
{
    /**
     * @var string
     */
    protected $signature = 'bot:start-alert';

    /**
     * @var string
     */
    protected $description = 'Send a notification to all clients';

    /**
     * @return mixed
     */
    public function handle()
    {
        $waService = new WhatsappBootstrapService();
        $waService->sendInitialMessages();




    }
}
