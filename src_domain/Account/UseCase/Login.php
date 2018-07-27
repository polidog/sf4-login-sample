<?php

declare(strict_types=1);

namespace Polidog\LoginSample\Account\UseCase;

use Polidog\LoginSample\Account\Entity\Account;
use Polidog\LoginSample\Account\Service\PasswordValidInterface;

class Login
{
    /**
     * @var PasswordValidInterface
     */
    private $passwordValid;

    /**
     * Login constructor.
     *
     * @param PasswordValidInterface $passwordValid
     */
    public function __construct(PasswordValidInterface $passwordValid)
    {
        $this->passwordValid = $passwordValid;
    }

    /**
     * @param Account $account
     * @param string  $password
     *
     * @return bool
     */
    public function password(Account $account, string $password): bool
    {
        return $account->authentication($this->passwordValid, $password);
    }
}
