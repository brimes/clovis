<?php
namespace App\GraphQL\Client;

use EUAutomation\GraphQL\Client;
use EUAutomation\GraphQL\Exceptions\GraphQLInvalidResponse;
use EUAutomation\GraphQL\Exceptions\GraphQLMissingData;

class GraphQLClient extends Client
{
    /**
     * Make a GraphQL Request and get the response body in JSON form.
     *
     * @param string $query
     * @param array $variables
     * @param array $headers
     * @param bool $assoc
     *
     * @return mixed
     *
     * @throws GraphQLInvalidResponse
     * @throws GraphQLMissingData
     *
     * @SuppressWarnings("BooleanArgumentFlag")
     */
    public function json($query, $variables = [], $headers = [], $assoc = false)
    {
        $response = $this->raw($query, $variables, $headers);
        $contents = (string) $response->getBody()->getContents();

        $responseJson = json_decode($contents, $assoc);

        if ($responseJson === null) {
            throw new GraphQLInvalidResponse(
                'GraphQL did not provide a valid JSON response. Please make sure you are pointing at the correct URL.'
            );
        }

        if (!isset($responseJson->data)) {
            throw new GraphQLMissingData(
                'There was an error with the GraphQL response from "' . $this->url
                . '", no data key was found: "' . $contents . '".'
            );
        }

        return $responseJson;
    }
}
