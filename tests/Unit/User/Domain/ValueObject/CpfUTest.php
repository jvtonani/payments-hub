<?php

namespace App\Tests\Unit\User\Domain\ValueObject;

use PHPUnit\Framework\TestCase;
use App\User\Domain\ValueObject\Cpf;
class CpfUTest extends TestCase
{
    public function testCreateValidCpf(): void
    {
        $number = '12345678900';
        $cpf = new Cpf($number);

        $this->assertSame($number, (string) $cpf);
    }

    public function testCreateValidCpfWithSubstring(): void
    {
        $number = '1234--56789-00';
        $cpf = new Cpf($number);

        $this->assertSame('12345678900', (string) $cpf);
    }

    public function testCreateInvalidCpf(): void
    {
        $this->expectException(\DomainException::class);
        $number = '1234567890123123';
        new Cpf($number);
    }
}