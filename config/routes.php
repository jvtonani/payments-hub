<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use App\Onboarding\Interface\Http\Controllers\CreateUserController;
use App\Payment\Interface\Http\Controllers\CreateTransferController;
use App\Payment\Interface\Http\Controllers\GetTransferController;
use Hyperf\HttpServer\Router\Router;

Router::post( '/user', [CreateUserController::class, 'perform']);
Router::post( '/transfer', [CreateTransferController::class, 'perform']);
Router::get('/transfer/{id:\d+}', [GetTransferController::class, 'perform']);
