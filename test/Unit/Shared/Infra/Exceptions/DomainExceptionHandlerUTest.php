<?php

namespace App\Tests\Unit\Shared\Infra\Exceptions;

use App\Shared\Infra\Exceptions\DomainExceptionHandler;
use DomainException;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class DomainExceptionHandlerUTest extends TestCase
{
    public function testHandleReturnsJsonWith422(): void
    {
        $expectedPayload = ['error' => 'Erro de domínio'];
        $exception = new DomainException('Erro de domínio');

        $httpResponseMock = Mockery::mock(HttpResponse::class);

        $psrResponseMock = Mockery::mock(ResponseInterface::class);

        $httpResponseMock->shouldReceive('json')
            ->with($expectedPayload)
            ->once()
            ->andReturn($psrResponseMock);

        $psrResponseMock->shouldReceive('withStatus')
            ->with(422)
            ->once()
            ->andReturnSelf();

        $handler = new DomainExceptionHandler($httpResponseMock);

        $response = $handler->handle($exception, Mockery::mock(ResponseInterface::class));

        $this->assertSame($psrResponseMock, $response);
    }
    protected function tearDown(): void
    {
        Mockery::close();
    }
}
