<?php

namespace App\Shared\Helper;

use Closure;
use Hyperf\Coroutine\Coroutine;
use Throwable;

class RetryHelper
{
    public static function run(
        int $maxRetries,
        Closure $callback,
        int $delaySeconds = 1
    ): void {
        $attempt = 0;

        while ($attempt < $maxRetries) {
            try {
                $callback();
                return;
            } catch (Throwable $e) {
                $attempt++;
                if ($attempt >= $maxRetries) {
                    throw $e;
                }

                Coroutine::sleep($delaySeconds);
            }
        }
    }
}