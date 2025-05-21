<?php

namespace App\Tests\Unit\Onboarding\Infra\Repositories;

use App\Onboarding\Domain\Entity\User;
use App\Onboarding\Infra\Models\UserModel;
use App\Onboarding\Infra\Repositories\UserRepository;
use PHPUnit\Framework\TestCase;

class UserRepositoryUTest extends TestCase
{
    private function makeUserData(): array
    {
        return [
            'id' => '1',
            'document_number' => '12345678900',
            'name' => 'John Doe',
            'email' => 'email@example.com',
            'user_type' => 'common',
            'password' => 'hashed-password',
            'cellphone' => '55999999999'
        ];
    }

    public function testFindByIdReturnsUser()
    {
        $userModel = $this->createMock(UserModel::class);
        $userData = $this->makeUserData();

        $userModel->expects($this->once())
            ->method('query')
            ->willReturn([$userData]);

        $repository = new UserRepository($userModel);

        $user = $repository->findById('1');
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('email@example.com', $user->toArray()['email']);
    }

    public function testFindByIdReturnsNullWhenNotFound()
    {
        $userModel = $this->createMock(UserModel::class);
        $userModel->expects($this->once())
            ->method('query')
            ->willReturn([]);

        $repository = new UserRepository($userModel);
        $user = $repository->findById('99');

        $this->assertNull($user);
    }

    public function testSaveUser()
    {
        $userData = $this->makeUserData();
        $user = User::createUser(
            $userData['document_number'],
            $userData['name'],
            $userData['email'],
            $userData['user_type'],
            $userData['password'],
            $userData['cellphone'],
            $userData['id']
        );

        $userModel = $this->createMock(UserModel::class);
        $userModel->expects($this->once())
            ->method('save')
            ->with($user->toArray())
            ->willReturn(1);

        $repository = new UserRepository($userModel);

        $this->assertEquals(1, $repository->save($user));
    }

    public function testExistsByDocumentReturnsTrue()
    {
        $userModel = $this->createMock(UserModel::class);
        $userModel->expects($this->once())
            ->method('query')
            ->willReturn([['document_number' => '12345678900']]);

        $repository = new UserRepository($userModel);
        $this->assertTrue($repository->existsByDocument('12345678900'));
    }

    public function testExistsByDocumentReturnsFalse()
    {
        $userModel = $this->createMock(UserModel::class);
        $userModel->expects($this->once())
            ->method('query')
            ->willReturn([]);

        $repository = new UserRepository($userModel);
        $this->assertFalse($repository->existsByDocument('00000000000'));
    }

    public function testExistsByEmailReturnsTrue()
    {
        $userModel = $this->createMock(UserModel::class);
        $userModel->expects($this->once())
            ->method('query')
            ->willReturn([['email' => 'email@example.com']]);

        $repository = new UserRepository($userModel);
        $this->assertTrue($repository->existsByEmail('email@example.com'));
    }

    public function testExistsByEmailReturnsFalse()
    {
        $userModel = $this->createMock(UserModel::class);
        $userModel->expects($this->once())
            ->method('query')
            ->willReturn([]);

        $repository = new UserRepository($userModel);
        $this->assertFalse($repository->existsByEmail('notfound@example.com'));
    }
}
