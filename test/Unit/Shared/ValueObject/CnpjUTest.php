<?php

namespace App\Tests\Unit\Shared\ValueObject;

use App\Shared\Domain\ValueObject\Cnpj;
use PHPUnit\Framework\TestCase;

class CnpjUTest extends TestCase
{
    public function testCreateValidCnpj(): void
    {
        $number = '56987564123498';
        $cnpj = new Cnpj($number);

        $this->assertSame($number, (string) $cnpj);
    }

    public function testCreateValidCnpjWithSubstring(): void
    {
        $number = '56.987.564/1234-98';
        $cnpj = new Cnpj($number);

        $this->assertSame('56987564123498', (string) $cnpj);
    }

    public function testCreateInvalidCnpj(): void
    {
        $this->expectException(\DomainException::class);
        $number = '123456781231231290123123';
        new Cnpj($number);
    }
}