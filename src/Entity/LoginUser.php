<?php

declare(strict_types=1);

namespace App\Entity;

use Polidog\LoginSample\Account\Entity\Account;

class LoginUser
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

    public function getId()
    {
        return $this->account->getId();
    }

    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function getPassword()
    {
        return $this->account->getPassword();
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
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
