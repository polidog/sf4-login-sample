<?php

declare(strict_types=1);

namespace Polidog\LoginSample\Account\Exception;

class DuplicateEmailException extends \InvalidArgumentException implements AccountException
{
    /**
     * @var string
     */
    private $email;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    public static function newException(string $email): self
    {
        $self = new self(\sprintf('duplicate email %s', $email));
        $self->email = $email;

        return $self;
    }
}
