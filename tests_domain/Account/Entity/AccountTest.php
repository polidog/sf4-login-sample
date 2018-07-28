<?php

namespace Polidog\LoginSample\Tests\Account\Entity;


use PHPUnit\Framework\TestCase;
use Polidog\LoginSample\Account\Entity\Account;
use Polidog\LoginSample\Account\Service\PasswordEncoderInterface;
use Polidog\LoginSample\Account\Service\PasswordValidInterface;

class AccountTest extends TestCase
{
    public function testEncode()
    {
        $rawPassword = 'test-test';
        $hashedPassword = 'hashed password';
        $encoder = $this->prophesize(PasswordEncoderInterface::class);
        $encoder->encode($rawPassword)
            ->willReturn($hashedPassword);


        $account = new Account('test', 'test@test.com', $rawPassword);
        $account->encode($encoder->reveal());

        $encoder->encode($rawPassword)
            ->shouldHaveBeenCalled();

        $this->assertSame($hashedPassword, $account->getPassword());
    }

    public function testAuthentication()
    {
        $inputPassword = 'test-test';

        $passwordValid = $this->prophesize(PasswordValidInterface::class);

        $passwordValid->valid($inputPassword, null)
            ->willReturn(false);


        $account = new Account('test', 'test@test.com', 'test');
        $account->authentication($passwordValid->reveal(), $inputPassword);

        $passwordValid->valid($inputPassword, null)
            ->shouldHaveBeenCalled();

    }
}