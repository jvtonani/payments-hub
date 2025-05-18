<?php

namespace App\Onboarding\Domain\Entity;

use App\Onboarding\Domain\ValueObject\UserType;
use App\Shared\Domain\ValueObject\ValueObject\Cpf;
use App\Shared\Domain\ValueObject\ValueObject\Email;

class User
{
    private Cpf $cpf;
    private string $name;
    private Email $email;
    private UserType $userType;
    private string $password;

    public static function createUser(string $cpfNumber, string $name, string $email, string $userType, string $password): self
    {
        return new User(new Cpf($cpfNumber), $name, new Email($email), new UserType($userType), $password);
    }
    public function __construct(Cpf $cpf, string $name, Email $email, UserType $userType, string $password)
    {
        $this->cpf = $cpf;
        $this->name = $name;
        $this->email = $email;
        $this->userType = $userType;
        $this->password = $password;
    }

    public function getUserType(): UserType
    {
        return $this->userType;
    }
}