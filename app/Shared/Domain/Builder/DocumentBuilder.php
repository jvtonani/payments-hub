<?php

namespace App\Shared\Domain\Builder;

use App\Shared\Domain\ValueObject\Cnpj;
use App\Shared\Domain\ValueObject\Cpf;
use App\Shared\Domain\ValueObject\Document;

class DocumentBuilder
{
    private $documentNumber;
    //TODO melhorar lógica de criação do objeto
    public function __construct(string $documentNumber)
    {
        $this->documentNumber = $documentNumber;
    }

    public function getDocument(): Document
    {
        try{
            $document = new Cpf($this->documentNumber);
        } catch (\Exception $exception)
        {
            $document = new Cnpj($this->documentNumber);
        }

        return $document;
    }
}