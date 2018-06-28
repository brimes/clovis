<?php
namespace App\GraphQL\Client;

use App\GraphQL\Client\Auth\TradeToolsClientAuthentication;

class TradeToolsClient extends AbstractClient
{
    private $cachedToken;

    protected function getUrl() : string
    {
        //return $this->industry->url . '/index.php?r=api/graphql/index';
        //return 'http://10.0.5.44/tradetools/index.php?r=api/graphql/index';
        return env('URL_TRADETOOLS') . 'index.php?r=api/graphql/index';
    }

    public function getToken() : string
    {
        if (null === $this->cachedToken) {
            $auth = new TradeToolsClientAuthentication($this->industry);
            $this->cachedToken = $auth->getToken();
        }
        return $this->cachedToken;
    }

    public function getStatusOrder($cnpj = "") : Object
    {

        $statusOrder = $this->query('{
            orderStatus (cnpj: "' .$cnpj. '") {
                status_order,
                date,
                id
            }
        }')->orderStatus;
        
        
        return $statusOrder;
    }

}
