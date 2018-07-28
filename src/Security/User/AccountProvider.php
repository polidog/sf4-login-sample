<?php

declare(strict_types=1);

namespace App\Security\User;

use App\Entity\LoginUser;
use Polidog\LoginSample\Account\Exception\AccountNotFoundException;
use Polidog\LoginSample\Account\UseCase\GetLoginAccount;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class AccountProvider implements UserProviderInterface
{
    /**
     * @var GetLoginAccount
     */
    private $getAccount;

    /**
     * AccountProvider constructor.
     *
     * @param GetLoginAccount $getAccount
     */
    public function __construct(GetLoginAccount $getAccount)
    {
        $this->getAccount = $getAccount;
    }

    public function loadUserByUsername($username)
    {
        return $this->getUserByEmail($username);
    }

    public function refreshUser(UserInterface $user)
    {
        return $this->getUserByEmail($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class instanceof LoginUser;
    }

    private function getUserByEmail(string $username): LoginUser
    {
        try {
            $account = $this->getAccount->email($username);

            return new LoginUser($account);
        } catch (AccountNotFoundException $e) {
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username), 0, $e);
        }
    }
}
