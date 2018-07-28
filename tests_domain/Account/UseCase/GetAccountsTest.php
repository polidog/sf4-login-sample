<?php

namespace Polidog\LoginSample\Tests\Account\UseCase;


use PHPUnit\Framework\TestCase;
use Polidog\LoginSample\Account\Repository\AccountRepositoryInterface;
use Polidog\LoginSample\Account\UseCase\GetAccounts;

class GetAccountsTest extends TestCase
{
    private $repository;

    public function setUp()
    {
        parent::setUp();
        $this->repository = $this->prophesize(AccountRepositoryInterface::class);
    }

    public function testAll()
    {
        $this->repository->all()
            ->willReturn([]);

        $useCase = new GetAccounts($this->repository->reveal());
        $useCase->all();

        $this->repository->all()
            ->shouldHaveBeenCalled();
    }
}