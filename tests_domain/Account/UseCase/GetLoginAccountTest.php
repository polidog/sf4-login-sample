<?php

namespace Polidog\LoginSample\Tests\Account\UseCase;

use PHPUnit\Framework\TestCase;
use Polidog\LoginSample\Account\Entity\Account;
use Polidog\LoginSample\Account\Repository\AccountRepositoryInterface;
use Polidog\LoginSample\Account\UseCase\GetLoginAccount;

class GetLoginAccountTest extends TestCase
{
    private $repository;

    public function setUp()
    {
        parent::setUp();
        $this->repository = $this->prophesize(AccountRepositoryInterface::class);
    }

    public function testGetLoginAccountFromEmail()
    {
        $emailAddress = 'test@test.com';

        $this->repository->findEmail($emailAddress)
            ->willReturn(new Account('test','test@test.com', 'test'));

        $useCase = new GetLoginAccount($this->repository->reveal());
        $useCase->email($emailAddress);

        $this->repository->findEmail($emailAddress)
            ->shouldHaveBeenCalled();
    }
}