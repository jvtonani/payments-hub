<?php

namespace App\Tests\Unit\Shared\Infra\Exceptions;

use App\Shared\Infra\Exceptions\GenericExceptionHandler;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

class GenericDomainExceptionUTest extends TestCase
{
    public function testHandleReturnsGenericJsonWith500(): void
    {
        $exception = new RuntimeException('Erro inesperado');

        $httpResponseMock = Mockery::mock(HttpResponse::class);
        $psrResponseMock = Mockery::mock(ResponseInterface::class);

        $httpResponseMock->shouldReceive('json')
            ->with([
                'error' => 'Algum erro aconteceu',
                'reason' => 'Erro inesperado'
            ])
            ->once()
            ->andReturn($psrResponseMock);

        $psrResponseMock->shouldReceive('withStatus')
            ->with(500)
            ->once()
            ->andReturnSelf();

        $handler = new GenericExceptionHandler($httpResponseMock);
        $response = $handler->handle($exception, Mockery::mock(ResponseInterface::class));

        $this->assertSame($psrResponseMock, $response);
    }
    protected function tearDown(): void
    {
        Mockery::close();
    }
}
