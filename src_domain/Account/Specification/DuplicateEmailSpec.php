<?php

declare(strict_types=1);

namespace Polidog\LoginSample\Account\Specification;

use Polidog\LoginSample\Account\Entity\Account;
use Polidog\LoginSample\Account\Repository\AccountRepositoryInterface;

class DuplicateEmailSpec implements AccountSpec
{
    /**
     * @var AccountRepositoryInterface
     */
    private $repository;

    /**
     * DuplicateEmailSpec constructor.
     *
     * @param AccountRepositoryInterface $repository
     */
    public function __construct(AccountRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function isSatisfiedBy(Account $account): bool
    {
        $duplicatedAccount = $this->repository->findEmail($account->getEmail());
        if ($duplicatedAccount instanceof Account) {
            return false;
        }

        return true;
    }
}
