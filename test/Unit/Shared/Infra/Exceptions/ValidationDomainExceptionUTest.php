<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Infra\Exceptions;

use App\Shared\Infra\Exceptions\ValidationExceptionHandler;
use Hyperf\Validation\ValidationException;
use PHPUnit\Framework\TestCase;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Psr\Http\Message\ResponseInterface;
use Hyperf\Contract\ValidatorInterface;
class ValidationDomainExceptionUTest extends TestCase
{
    public function testIsValidReturnsTrueForValidationException()
    {
        $responseMock = $this->createMock(HttpResponse::class);
        $handler = new ValidationExceptionHandler($responseMock);

        $throwable = $this->createMock(ValidationException::class);
        $this->assertTrue($handler->isValid($throwable));
    }

    public function testHandleReturns422WithErrors()
    {
        $validatorMock = $this->createMock(ValidatorInterface::class);

        $exceptionMock = $this->createMock(ValidationException::class);
        $exceptionMock->validator = $validatorMock;

        $httpResponseMock = $this->createMock(HttpResponse::class);
        $finalResponseMock = $this->createMock(ResponseInterface::class);

        $handler = new ValidationExceptionHandler($httpResponseMock);

        $response = $handler->handle($exceptionMock, $finalResponseMock);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
