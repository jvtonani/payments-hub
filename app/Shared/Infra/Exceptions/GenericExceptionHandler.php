<?php

namespace App\Shared\Infra\Exceptions;

use Hyperf\ExceptionHandler\ExceptionHandler;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;

class GenericExceptionHandler extends ExceptionHandler
{
    protected HttpResponse $response;

    public function __construct(HttpResponse $response)
    {
        $this->response = $response;
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        return $this->response->json([
            'error' => 'Algum erro aconteceu',
        ])->withStatus(500);
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}