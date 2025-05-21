<?php

namespace App\Tests\Unit\Authorization\Application;

use App\Authorization\Application\ExternalAuthorizer;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\RequestInterface;

class ExternalAuthorizerUTest extends TestCase
{
    public function testIsAuthorizedReturnsTrueOnSuccess()
    {
        $mockClient = $this->createMock(Client::class);
        $mockClient->expects($this->once())
            ->method('get')
            ->with('https://util.devi.tools/api/v2/authorize');

        $logger = $this->createMock(LoggerInterface::class);
        $authorizer = new ExternalAuthorizer($mockClient, $logger);

        $this->assertTrue($authorizer->isAuthorized());
    }

    public function testIsAuthorizedReturnsFalseOnFailure()
    {
        $mockClient = $this->createMock(Client::class);
        $mockClient->expects($this->once())
            ->method('get')
            ->willThrowException(new RequestException("Erro na requisição", $this->createMock(RequestInterface::class)));

        $logger = $this->createMock(LoggerInterface::class);

        $authorizer = new ExternalAuthorizer($mockClient, $logger);

        $this->assertFalse($authorizer->isAuthorized());
    }
}
