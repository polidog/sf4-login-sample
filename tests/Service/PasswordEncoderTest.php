<?php

namespace App\Tests\Service;


use App\Entity\LoginUser;
use App\Service\PasswordEncoder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class PasswordEncoderTest extends TestCase
{
    private $factory;

    protected function setUp()
    {
        $this->factory = $this->prophesize(EncoderFactoryInterface::class);
    }

    public function testEncode()
    {
        $plainPassword = 'test';
        $encodedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);

        $encoder = $this->getEncoderMock();
        $encoder->encodePassword($plainPassword, '')
            ->willReturn($encodedPassword);

        $this->factory->getEncoder(LoginUser::class)
            ->willReturn($encoder->reveal());

        $service = $this->getPasswordEncoder();
        $service->encode($plainPassword);

        $this->factory->getEncoder(LoginUser::class)
            ->shouldHaveBeenCalled();

        $encoder->encodePassword($plainPassword, '')
            ->shouldHaveBeenCalled();

    }

    public function testValid()
    {
        $plainPassword = 'test';
        $encodedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);

        $encoder = $this->getEncoderMock();
        $encoder->isPasswordValid($encodedPassword, $plainPassword, null)
            ->willReturn(true);

        $this->factory->getEncoder(LoginUser::class)
            ->willReturn($encoder->reveal());

        $service = $this->getPasswordEncoder();
        $service->valid($plainPassword, $encodedPassword);

        $encoder->isPasswordValid($encodedPassword, $plainPassword, null)
            ->shouldHaveBeenCalled();

        $this->factory->getEncoder(LoginUser::class)
            ->shouldHaveBeenCalled();
    }


    private function getPasswordEncoder() : PasswordEncoder
    {
        return new PasswordEncoder($this->factory->reveal());
    }

    private function getEncoderMock()
    {
        $encoder = $this->prophesize(PasswordEncoderInterface::class);
        return $encoder;
    }
}