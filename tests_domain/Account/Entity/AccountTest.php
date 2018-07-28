<?php

namespace Polidog\LoginSample\Tests\Account\Entity;


use PHPUnit\Framework\TestCase;
use Polidog\LoginSample\Account\Entity\Account;
use Polidog\LoginSample\Account\Service\PasswordEncoderInterface;
use Polidog\LoginSample\Account\Service\PasswordValidInterface;
use Prophecy\Argument;

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
        $encoder = new TestEncoder();

        $passwordValid = $this->prophesize(PasswordValidInterface::class);

        $passwordValid->valid($inputPassword, Argument::any())
            ->willReturn(false);

        $account = new Account('test', 'test@test.com', 'test');
        $account->encode($encoder);

        $account->authentication($passwordValid->reveal(), $inputPassword);

        $passwordValid->valid($inputPassword, $encoder->getEncodedPassword())
            ->shouldHaveBeenCalled();

    }
}

class TestEncoder implements PasswordEncoderInterface
{
    private $encodedPassword;

    public function encode(string $plainPassword): string
    {
        return $this->encodedPassword = md5($plainPassword).time();
    }

    /**
     * @return mixed
     */
    public function getEncodedPassword()
    {
        return $this->encodedPassword;
    }

}