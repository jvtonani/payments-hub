<?php

namespace App\Shared\Infra\Exceptions;

use Hyperf\ExceptionHandler\ExceptionHandler;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use DomainException;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;

class DomainExceptionHandler extends ExceptionHandler
{
    protected HttpResponse $response;

    public function __construct(HttpResponse $response)
    {
        $this->response = $response;
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        return $this->response->json([
            'error' => $throwable->getMessage(),
        ])->withStatus(422);
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof DomainException;
    }
}