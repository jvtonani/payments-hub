<?php

namespace App\Shared\Infra\Exceptions;

use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;


class ValidationExceptionHandler extends ExceptionHandler
{
    protected HttpResponse $response;

    public function __construct(HttpResponse $response)
    {
        $this->response = $response;
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $this->stopPropagation();

        return $this->response->json([
            'error' => 'Dados do payload inválidos',
            'params' => $throwable->validator->errors(),
        ])->withStatus(422);
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof ValidationException;
    }
}