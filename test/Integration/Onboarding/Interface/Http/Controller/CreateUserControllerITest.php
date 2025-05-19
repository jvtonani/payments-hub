<?php

namespace App\Tests\Integration\Onboarding\Interface\Http\Controller;

use Hyperf\Testing\Client;
use PHPUnit\Framework\TestCase;
use function Hyperf\Support\make;

class CreateUserControllerITest extends TestCase
{
    /**
     * @var Client
     */
    protected Client $client;

    public function __construct($name = null, array $data = [], $dataName ='')
    {
        parent::__construct($name, $data, $dataName);
        $this->client = make(Client::class);
    }

    public function testCreateUserController(): void
    {
        try{
            $res = $this->client->post('/user');
        } catch (\Exception $e){
            var_dump($e->getMessage());
        }
    }
}