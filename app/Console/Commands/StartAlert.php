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
//        $waService = new WhatsappBootstrapService();
//        $waService->sendInitialMessages();



        // Pull messages (for push messages please go to settings of the number)
        $my_apikey = env('TOKEN_WHATSAPP');
        //$number = "";
        $type = "IN";
        $markaspulled = "0";
        $getnotpulledonly = "0";
        $api_url  = "http://panel.apiwha.com/get_messages.php";
        $api_url .= "?apikey=". urlencode ($my_apikey);
        //$api_url .=	"&number=". urlencode ($number);
        $api_url .= "&type=". urlencode ($type);
        $api_url .= "&markaspulled=". urlencode ($markaspulled);
        $api_url .= "&getnotpulledonly=". urlencode ($getnotpulledonly);
        $my_json_result = file_get_contents($api_url, false);
        $my_php_arr = json_decode($my_json_result);
        $numbers = [];
        foreach($my_php_arr as $item)
        {
            $numbers[$item->from] = 1;
        }
        print_r($numbers);

    }
}
