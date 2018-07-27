<?php

declare(strict_types=1);

namespace Polidog\LoginSample\Account\UseCase;

use Polidog\LoginSample\Account\Entity\Account;
use Polidog\LoginSample\Account\Repository\AccountRepositoryInterface;

class GetAccounts
{
    /**
     * @var AccountRepositoryInterface
     */
    private $repository;

    /**
     * GetAccounts constructor.
     *
     * @param AccountRepositoryInterface $repository
     */
    public function __construct(AccountRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Account[]
     */
    public function run(): array
    {
        return $this->repository->all();
    }
}
