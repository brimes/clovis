<?php
namespace App\GraphQL\Client;

use App\Models\Industry;
use Exception;
use Illuminate\Support\Facades\Log;

abstract class AbstractClient
{
    protected $industry;
    protected $graphQLClient;

    abstract protected function getUrl() : string;

    abstract protected function getToken() : string;

    public function __construct()
    {
        $this->industry = new Industry();//$industry;
        $this->graphQLClient = new GraphQLClient($this->getUrl());
    }

    public function setGraphQLClient(GraphQLClient $graphQLClient)
    {
        $this->graphQLClient = $graphQLClient;
    }

    protected function query($query, $variables = [])
    {
        //$token = $this->getToken();
        //$headers = ['Authorization' => 'Bearer ' . $token];
        $headers = [];
        try {
            return $this->graphQLClient->response(
                $query,
                $variables,
                $headers
            );
        } catch (Exception $exception) {
            Log::error(
                $exception->getMessage(),
                [
                    'token' => $token,
                    'query' => $query,
                    'variables' => $variables,
                ]
            );
            throw $exception;
        }
    }
}
