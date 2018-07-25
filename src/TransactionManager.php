<?php

declare(strict_types=1);

namespace App;

use Doctrine\ORM\EntityManagerInterface;
use Polidog\LoginSample\TransactionManagerInterface;

class TransactionManager implements TransactionManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * TransactionManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function begin(): void
    {
        $this->entityManager->beginTransaction();
    }

    public function commit(): void
    {
        $this->entityManager->commit();
    }

    public function rollback(): void
    {
        $this->entityManager->rollback();
    }

    public function transactional(callable $func): void
    {
        $this->begin();

        try {
            call_user_func($func, $this);
            $this->commit();
        } catch (\Throwable $e) {
            if ($this->entityManager->isOpen()) {
                $this->entityManager->close();
            }
            $this->rollback();

            throw $e;
        }
    }
}
