<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\LoginUser;
use Polidog\LoginSample\Account\Entity\Account;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class RegisterLogin
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * RegisterLogin constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function execute(LoginUser $user): void
    {
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->tokenStorage->setToken($token);
    }

    public function accountExecute(Account $account): void
    {
        $this->execute(new LoginUser($account));
    }
}
