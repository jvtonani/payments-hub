<?php

namespace App\Authorization\Application;

use App\Authorization\Domain\Interfaces\AuthorizerInterface;
use App\Shared\Helper\RetryHelper;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;

class ExternalAuthorizer implements AuthorizerInterface
{

    private const MAX_RETRIES = 1;
    private const AUTHORIZER_SERVICE_URL = 'https://util.devi.tools/api/v2/authorize';
    public function __construct(private Client $client, private LoggerInterface $logger)
    {
    }

    public function isAuthorized(): bool
    {
        try {
            RetryHelper::run(self::MAX_RETRIES, function () {
                $this->client->get(self::AUTHORIZER_SERVICE_URL);
            });

            return true;
        } catch (\Throwable $e) {
            $this->logger->info("Falha na autorização de transferência" . $e->getMessage());
            return false;
        }
    }
}