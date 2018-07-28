<?php

declare(strict_types=1);

namespace Polidog\LoginSample\Account\UseCase;

use Polidog\LoginSample\Account\Entity\Account;
use Polidog\LoginSample\Account\Exception\DuplicateEmailException;
use Polidog\LoginSample\Account\Repository\AccountRepositoryInterface;
use Polidog\LoginSample\Account\Service\PasswordEncoderInterface;
use Polidog\LoginSample\Account\Specification\DuplicateEmailSpec;
use Polidog\LoginSample\TransactionManagerInterface;

class RegisterAccount
{
    /**
     * @var DuplicateEmailSpec
     */
    private $specification;

    /**
     * @var AccountRepositoryInterface
     */
    private $repository;

    /**
     * @var TransactionManagerInterface
     */
    private $transactionManager;

    /**
     * @var PasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * RegisterAccount constructor.
     *
     * @param DuplicateEmailSpec          $specification
     * @param AccountRepositoryInterface  $repository
     * @param TransactionManagerInterface $transactionManager
     * @param PasswordEncoderInterface    $passwordEncoder
     */
    public function __construct(DuplicateEmailSpec $specification, AccountRepositoryInterface $repository, TransactionManagerInterface $transactionManager, PasswordEncoderInterface $passwordEncoder)
    {
        $this->specification = $specification;
        $this->repository = $repository;
        $this->transactionManager = $transactionManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param Account $account
     */
    public function run(Account $account): void
    {
        if (false === $this->specification->isSatisfiedBy($account)) {
            throw DuplicateEmailException::newException($account->getEmail());
        }
        $account->encode($this->passwordEncoder);

        $this->transactionManager->transactional(function () use ($account): void {
            $this->repository->add($account);
        });
    }
}
