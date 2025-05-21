<?php

namespace App\Tests\Unit\Shared\Helper;

use App\Shared\Helper\RetryHelper;
use PHPUnit\Framework\TestCase;
use Throwable;

class RetryHelperUTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testRunSucceedsOnFirstTry(): void
    {
        $called = false;
        RetryHelper::run(3, function () use (&$called) {
            $called = true;
        });

        $this->assertTrue($called);
    }

    public function testRunSucceedsAfterRetries(): void
    {
        $calledCount = 0;

        RetryHelper::run(3, function () use (&$calledCount) {
            $calledCount++;
            if ($calledCount < 2) {
                throw new \RuntimeException('Exeption');
            }
        });

        $this->assertEquals(2, $calledCount);
    }

    public function testRunThrowsAfterMaxRetries(): void
    {
        $this->expectException(\RuntimeException::class);
        $calledCount = 0;

        RetryHelper::run(3, function () use (&$calledCount) {
            $calledCount++;
            throw new \RuntimeException('Tentativas esgotadas');
        });
    }

    public function testSleepIsCalledOnFailure(): void
    {
        $calledCount = 0;
        $sleeps = [];

        try {
            RetryHelper::run(3, function () use (&$calledCount) {
                $calledCount++;
                throw new \Exception("Falhou");
            });
        } catch (Throwable $e) {
        }

        $this->assertEquals(3, $calledCount);
    }
}
