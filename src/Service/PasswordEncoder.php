<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\LoginUser;
use Polidog\LoginSample\Account\Service\PasswordEncoderInterface;
use Polidog\LoginSample\Account\Service\PasswordValidInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class PasswordEncoder implements PasswordEncoderInterface, PasswordValidInterface
{
    /**
     * @var EncoderFactoryInterface
     */
    private $factory;

    /**
     * PasswordEncoder constructor.
     *
     * @param EncoderFactoryInterface $factory
     */
    public function __construct(EncoderFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function encode(string $plainPassword): string
    {
        $encoder = $this->getEncoder();

        return $encoder->encodePassword($plainPassword, '');
    }

    public function valid($raw, $encoded): bool
    {
        $encoder = $this->getEncoder();

        return $encoder->isPasswordValid($encoded, $raw, null);
    }

    private function getEncoder()
    {
        return $this->factory->getEncoder(LoginUser::class);
    }
}
