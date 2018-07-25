<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\LoginUser;
use Polidog\LoginSample\Account\Service\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class PasswordEncoder implements PasswordEncoderInterface
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
        $encoder = $this->factory->getEncoder(LoginUser::class);

        return $encoder->encodePassword($plainPassword, '');
    }
}
