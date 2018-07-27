<?php

declare(strict_types=1);

namespace App\Security\User;

use App\Entity\LoginUser;
use Polidog\LoginSample\Account\Entity\Account;
use Polidog\LoginSample\Account\Exception\AccountNotFoundException;
use Polidog\LoginSample\Account\UseCase\GetAccount;
use Polidog\LoginSample\Account\UseCase\Login;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class AccountProvider implements UserProviderInterface
{
    /**
     * @var GetAccount
     */
    private $getAccount;

    /**
     * @var Login
     */
    private $login;

    /**
     * AccountProvider constructor.
     *
     * @param GetAccount $getAccount
     */
    public function __construct(GetAccount $getAccount)
    {
        $this->getAccount = $getAccount;
    }

    public function loadUserByUsername($username)
    {
        try {
            $account = $this->getAccount->email($username);
        } catch (AccountNotFoundException $e) {
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username), $e);
        }

        if (false === $account instanceof Account) {
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
        }

        return new LoginUser($account);
    }

    public function refreshUser(UserInterface $user)
    {
        assert($user instanceof LoginUser);
        try {
            $account = $this->getAccount->email($user->getUsername());
        } catch (AccountNotFoundException $e) {
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $user->getUsername()), $e);
        }

        if (false === $account instanceof Account) {
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $user->getUsername()));
        }

        return new LoginUser($account);
    }

    public function supportsClass($class)
    {
        return $class instanceof LoginUser;
    }
}
