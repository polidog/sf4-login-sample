<?php

namespace Polidog\LoginSample\Tests\Account\UseCase;


use PHPUnit\Framework\TestCase;
use Polidog\LoginSample\Account\Entity\Account;
use Polidog\LoginSample\Account\Service\PasswordValidInterface;
use Polidog\LoginSample\Account\UseCase\Login;

class LoginTest extends TestCase
{
    private $passwordValid;

    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        $this->passwordValid = $this->prophesize(PasswordValidInterface::class);
    }


    public function testPassword()
    {
        $account = $this->prophesize(Account::class);
        $password = 'pass-word';

        $account->authentication($this->passwordValid, $password)
            ->willReturn(true);

        $useCase = new Login($this->passwordValid->reveal());
        $useCase->password($account->reveal(), $password);


        $account->authentication($this->passwordValid, $password)
            ->shouldHaveBeenCalled();

    }
}