<?php
namespace App\GraphQL\Client\Auth;

use JWTAuth;
use JWTFactory;

class TradeToolsClientAuthentication extends AbstractClientAuth
{
    public function getToken() : string
    {
        $payload = JWTFactory::make([
            'sub' => 1,
            'id' => 1,
            'services' => "{$this->industry->name},MS-BIT",
        ]);
        return JWTAuth::encode($payload);
    }
}
