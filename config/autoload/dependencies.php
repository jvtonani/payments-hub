<?php

declare(strict_types=1);

use App\Onboarding\Domain\Repositories\UserRepositoryInterface;
use App\Onboarding\Infra\Repositories\UserRepository;

return [
    UserRepositoryInterface::class => UserRepository::class,
];