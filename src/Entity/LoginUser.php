<?php

declare(strict_types=1);

namespace App\Entity;

use Polidog\LoginSample\Account\Entity\Account;
use Symfony\Component\Security\Core\User\UserInterface;

class LoginUser implements UserInterface
{
    /**
     * @var Account
     */
    private $account;

    /**
     * LoginUser constructor.
     *
     * @param Account $account
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    public function getId(): int
    {
        return $this->account->getId();
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getPassword(): string
    {
        return $this->account->getPassword();
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername(): string
    {
        return $this->account->getEmail();
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return Account
     */
    public function getAccount(): Account
    {
        return $this->account;
    }
}
