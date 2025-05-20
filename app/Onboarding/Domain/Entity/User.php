<?php

namespace App\Onboarding\Domain\Entity;

use App\Onboarding\Domain\ValueObject\UserType;
use App\Shared\Domain\Builder\DocumentBuilder;
use App\Shared\Domain\ValueObject\Document;
use App\Shared\Domain\ValueObject\Email;

class User
{
    private ?string $id;
    private Document $document;
    private string $name;
    private Email $email;
    private string $cellphone;
    private UserType $userType;
    private string $personType;

    private string $documentType;
    private string $password;

    public static function createUser(string $documentNumber, string $name, string $email, string $userType, string $password, string $cellphone, ?string $id): self
    {
        $documentBuilder = new DocumentBuilder($documentNumber);
        return new User($documentBuilder->getDocument(), $name, new Email($email), new UserType($userType), $password, $cellphone, $id,);
    }
    public function __construct(Document $document, string $name, Email $email, UserType $userType, string $password, string $cellphone, ?int $id,)
    {
        $this->document = $document;
        $this->name = $name;
        $this->email = $email;
        $this->userType = $userType;
        $this->password = $password;
        $this->cellphone = $cellphone;
        $this->id = $id;

        if($document instanceof Cpf) {
            $this->personType = 'pf';
            $this->documentType = 'cpf';
        } else {
            $this->personType = 'pj';
            $this->documentType = 'cnpj';
        }
    }

    public function getUserType(): UserType
    {
        return $this->userType;
    }

    public function toArray(): array
    {
        return [
            'id' =>  (int) $this->id,
            'name' =>  $this->name,
            'email' =>  $this->email,
            'document_number' => (string) $this->document,
            'user_type' => (string) $this->userType,
            'password' => $this->password,
            'person_type' => $this->personType,
            'document_type' => $this->documentType,
            'cellphone' =>  $this->cellphone,
        ];
    }

    public function getUserDocument(): Document
    {
        return $this->document;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function setId(mixed $id): void
    {
        $this->id = (int) $id;
    }
}