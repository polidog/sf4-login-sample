<?php

namespace App\Tests;


use App\TransactionManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class TransactionManagerTest extends TestCase
{
    private $entityManager;

    protected function setUp()
    {
        $this->entityManager = $this->prophesize(EntityManagerInterface::class);
    }


    public function testBegin()
    {
        $tm = $this->getTransactionManager();
        $tm->begin();

        $this->entityManager->beginTransaction()
            ->shouldHaveBeenCalled();
    }

    public function testCommit()
    {
        $tm = $this->getTransactionManager();
        $tm->commit();

        $this->entityManager->commit()
            ->shouldHaveBeenCalled();
    }

    public function testRollback()
    {
        $tm = $this->getTransactionManager();
        $tm->rollback();

        $this->entityManager->rollback()
            ->shouldHaveBeenCalled();
    }

    public function testTransactional()
    {
        $tm = $this->getTransactionManager();
        $tm->transactional(function(){});

        $this->entityManager->beginTransaction()
            ->shouldHaveBeenCalled();

        $this->entityManager->commit()
            ->shouldHaveBeenCalled();
    }

    private function getTransactionManager()
    {
        return new TransactionManager($this->entityManager->reveal());
    }
}