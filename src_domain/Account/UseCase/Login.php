<?php

declare(strict_types=1);

namespace Polidog\LoginSample\Account\UseCase;

use Polidog\LoginSample\Account\Entity\Account;
use Polidog\LoginSample\Account\Exception\AccountNotFoundException;
use Polidog\LoginSample\Account\Repository\AccountRepositoryInterface;

class Login
{
    /**
     * @var AccountRepositoryInterface
     */
    private $repository;

    /**
     * Login constructor.
     *
     * @param AccountRepositoryInterface $repository
     */
    public function __construct(AccountRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $email
     *
     * @return Account
     *
     * @throws AccountNotFoundException
     */
    public function run(string $email): Account
    {
        $account = $this->repository->findEmail($email);
        if (false === $account instanceof Account) {
            throw new AccountNotFoundException(sprintf('user %s not found', $email));
        }

        return $account;
    }
}
