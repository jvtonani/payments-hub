<?php

namespace App\Authorization\Infra;

use App\Authorization\Domain\Interfaces\AuthorizerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
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
        $retries = 0;
        while($retries < self::MAX_RETRIES){
            try {
                $this->client->get(self::AUTHORIZER_SERVICE_URL);
                $this->logger->info('AUTORIZADO');
                return true;
            } catch (ClientException $e) {
                $this->logger->info('Não autorizado (Nova Tentativa)');
                sleep(1);
                $retries++;
            } catch (\Exception $e) {
                $this->logger->info('Não autorizado (Fim Tentativas)');

                return false;
            }
        }

        return false;
    }
}