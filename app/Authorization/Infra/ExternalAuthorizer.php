<?php

namespace App\Authorization\Infra;

use App\Authorization\Domain\Interfaces\AuthorizerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

class ExternalAuthorizer implements AuthorizerInterface
{

    private const AUTHORIZER_SERVICE_URL = 'https://util.devi.tools/api/v2/authorize';
    public function __construct(private Client $client)
    {
    }

    public function isAuthorized(int $retries = 0): bool
    {
        try {
            $this->client->get(self::AUTHORIZER_SERVICE_URL);

            return true;
        } catch (ClientException $e) {
            return false;
        }
    }
}