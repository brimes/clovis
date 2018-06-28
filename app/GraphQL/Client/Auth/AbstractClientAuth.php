<?php
namespace App\GraphQL\Client\Auth;

use App\Models\Industry;

abstract class AbstractClientAuth
{
    protected $industry;

    public function __construct(Industry $industry)
    {
        $this->industry = $industry;
    }

    abstract public function getToken() : string;
}
