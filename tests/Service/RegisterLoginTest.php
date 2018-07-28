<?php

namespace App\Tests\Service;


use App\Entity\LoginUser;
use App\Service\RegisterLogin;
use PHPUnit\Framework\TestCase;
use Polidog\LoginSample\Account\Entity\Account;
use Prophecy\Argument;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class RegisterLoginTest extends TestCase
{
    private $tokenStorage;

    protected function setUp()
    {
        $this->tokenStorage = $this->prophesize(TokenStorageInterface::class);
    }

    public function testExecute()
    {
        $loginUser = $this->prophesize(LoginUser::class);
        $service = new RegisterLogin($this->tokenStorage->reveal());

        $loginUser->getRoles()
            ->willReturn(['ROLE_TEST']);

        $service->execute($loginUser->reveal());

        $this->tokenStorage->setToken(Argument::type(UsernamePasswordToken::class))
            ->shouldHaveBeenCalled();

        $loginUser->getRoles()
            ->shouldHaveBeenCalled();
    }

    public function testAccountExecute()
    {
        $service = new RegisterLogin($this->tokenStorage->reveal());
        $service->accountExecute(new Account("test","test@test",'test'));
        $this->tokenStorage->setToken(Argument::type(UsernamePasswordToken::class))
            ->shouldHaveBeenCalled();
    }


}