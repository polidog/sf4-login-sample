<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Polidog\LoginSample\Account\Entity\Account;
use Polidog\LoginSample\Account\Repository\AccountRepositoryInterface;

class AccountRepository extends ServiceEntityRepository implements AccountRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

    public function add(Account $account): void
    {
        $this->_em->persist($account);
        $this->_em->flush($account);
    }

    public function findEmail(string $email): ?Account
    {
        return $this->findOneBy(['email' => $email]);
    }
}
