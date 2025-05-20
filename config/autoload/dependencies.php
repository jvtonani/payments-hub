<?php

declare(strict_types=1);

use App\Authorization\Application\ExternalAuthorizer;
use App\Authorization\Domain\Interfaces\AuthorizerInterface;
use App\Onboarding\Domain\Repositories\UserRepositoryInterface;
use App\Onboarding\Infra\Repositories\UserRepository;
use App\Payment\Domain\Repositories\TransferRepositoryInterface;
use App\Payment\Infra\Repositories\TransferRepository;
use App\Wallet\Domain\Repositories\WalletRepositoryInterface;
use App\Wallet\Infra\Repositories\WalletRepository;

return [
    UserRepositoryInterface::class => UserRepository::class,
    WalletRepositoryInterface::class => WalletRepository::class,
    TransferRepositoryInterface::class => TransferRepository::class,
    AuthorizerInterface::class => ExternalAuthorizer::class
];