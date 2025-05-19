<?php

namespace App\Authorization\Domain\Interfaces;

interface AuthorizerInterface
{
    public function isAuthorized(int $retries): bool;

}